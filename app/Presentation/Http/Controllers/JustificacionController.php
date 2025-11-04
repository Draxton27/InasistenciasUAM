<?php

namespace App\Presentation\Http\Controllers;

use App\Application\Services\JustificacionService;
use App\Application\DTOs\JustificacionDTO;
use App\Domain\Justificacion\Observer\Contracts\JustificationSubject;
use App\Infrastructure\Persistence\Eloquent\Models\Justificacion as JustificacionModel;
use App\Infrastructure\Persistence\Eloquent\Models\Profesor as ProfesorModel;
use App\Presentation\Http\Controllers\Controller;
use App\Presentation\Http\Requests\JustificacionStoreRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * Controller: JustificacionController
 * Capa: Presentation
 * Controlador delgado que delega la lógica de negocio a los servicios de aplicación
 */
class JustificacionController extends Controller
{
    public function __construct(
        private JustificacionService $justificacionService,
        private JustificationSubject $subject
    ) {}

    /**
     * Sirve el archivo adjunto respetando autorización.
     */
    public function file(JustificacionModel $justificacion)
    {
        $user = Auth::user();
        if (!$user) {
            abort(401);
        }

        $puedeVer = $user->id === $justificacion->user_id || in_array($user->role, ['admin', 'profesor']);
        if (!$puedeVer) {
            abort(403);
        }

        if (!$justificacion->archivo || !Storage::disk('public')->exists($justificacion->archivo)) {
            abort(404);
        }

        $path = storage_path('app/public/'.$justificacion->archivo);
        return response()->file($path);
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $estado = $request->filled('estado') ? $request->estado : null;
        
        $justificacionesDTOs = $this->justificacionService->findByUserId($user->id, $estado);
        
        // Convertir DTOs a modelos para compatibilidad con las vistas (temporal)
        $justificaciones = $justificacionesDTOs->map(function ($dto) {
            return JustificacionModel::find($dto->id);
        })->filter();

        return view('justificaciones.index', compact('justificaciones'));
    }

    public function create()
    {
        $profesores = ProfesorModel::with('clases')->get();
        return view('justificaciones.create', compact('profesores'));
    }

    public function store(JustificacionStoreRequest $request)
    {
        $validated = $request->validated();
        $archivoPath = null;

        if ($request->hasFile('archivo')) {
            $file = $request->file('archivo');
            if ($file->isValid()) {
                $filename = uniqid().'_'.$file->getClientOriginalName();
                $destination = storage_path('app/public/justificaciones');
                if (!file_exists($destination)) {
                    mkdir($destination, 0755, true);
                }
                $file->move($destination, $filename);
                $archivoPath = 'justificaciones/'.$filename;
            } else {
                return back()->withErrors(['archivo' => 'El archivo no es válido.'])->withInput()->with('error', 'El archivo no es válido.');
            }
        }

        foreach ($validated['justificaciones'] as $entry) {
            $dto = new JustificacionDTO(
                userId: Auth::id(),
                claseProfesorId: $entry['clase_profesor_id'],
                fecha: $entry['fecha'],
                tipoConstancia: $validated['tipo_constancia'],
                notasAdicionales: $validated['notas_adicionales'] ?? null,
                archivo: $archivoPath,
                estado: 'registrada'
            );
            
            $this->justificacionService->create($dto);
        }

        return redirect()->route('justificaciones.index')->with('success', 'Justificación(es) enviadas correctamente.');
    }

    public function destroy(JustificacionModel $justificacion)
    {
        if ($justificacion->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para eliminar esta justificación.');
        }

        $this->justificacionService->eliminarArchivo($justificacion->archivo);
        $this->justificacionService->delete($justificacion->id);

        return redirect()->route('justificaciones.index')->with('success', 'Justificación eliminada correctamente.');
    }

    public function destroyAndCreate(JustificacionModel $justificacion)
    {
        if ($justificacion->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para eliminar esta justificación.');
        }

        $this->justificacionService->eliminarArchivo($justificacion->archivo);
        $this->justificacionService->delete($justificacion->id);

        return redirect()
            ->route('justificaciones.create')
            ->with('info', 'Justificación eliminada. Ahora puedes crear una nueva.');
    }

    public function edit($id)
    {
        $dto = $this->justificacionService->findById($id);
        if (!$dto || $dto->userId !== Auth::id()) {
            abort(403);
        }

        $justificacion = JustificacionModel::findOrFail($id);
        $profesores = ProfesorModel::with('clases')->get();
        $clases = \App\Infrastructure\Persistence\Eloquent\Models\ClaseProfesor::with('clase')->get();

        return view('justificaciones.edit', compact('justificacion', 'profesores', 'clases'));
    }

    public function update(Request $request, $id)
    {
        $dto = $this->justificacionService->findById($id);
        if (!$dto || $dto->userId !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'justificaciones.0.profesor_id' => 'required|exists:profesores,id',
            'justificaciones.0.clase_profesor_id' => 'required|exists:clase_profesor,id',
            'justificaciones.0.fecha' => 'required|date',
            'tipo_constancia' => 'required|in:trabajo,enfermedad,otro',
            'notas_adicionales' => 'nullable|string',
            'archivo' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Manejo de archivo
        if ($request->hasFile('archivo')) {
            $file = $request->file('archivo');
            if ($file->isValid()) {
                $this->justificacionService->eliminarArchivo($dto->archivo);
                $filename = uniqid().'_'.$file->getClientOriginalName();
                $destination = storage_path('app/public/justificaciones');
                if (!file_exists($destination)) {
                    mkdir($destination, 0755, true);
                }
                $file->move($destination, $filename);
                $dto->archivo = 'justificaciones/'.$filename;
            } else {
                return back()->withErrors(['archivo' => 'El archivo no es válido.'])->withInput()->with('error', 'El archivo no es válido.');
            }
        }

        $dto->claseProfesorId = $request->input('justificaciones.0.clase_profesor_id');
        $dto->fecha = $request->input('justificaciones.0.fecha');
        $dto->tipoConstancia = $request->input('tipo_constancia');
        $dto->notasAdicionales = $request->input('notas_adicionales');
        
        $this->justificacionService->update($dto);

        return redirect()->route('justificaciones.index')->with('success', 'Justificación actualizada correctamente.');
    }
}
