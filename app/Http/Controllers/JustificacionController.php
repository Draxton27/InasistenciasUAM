<?php

namespace App\Http\Controllers;

use App\Models\Justificacion;
use App\Models\Profesor;
use App\Models\Rechazo;
use App\States\Justificacion\RechazadaState;
use App\Models\Reprogramacion;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


use App\Repositories\Contracts\JustificacionRepositoryInterface;
use App\Domain\Justificacion\Observer\Contracts\JustificationSubject;

class JustificacionController extends Controller
{
    public function __construct(
        private JustificationSubject $subject,
        private JustificacionRepositoryInterface $repository
    ) {}

    /**
     * Sirve el archivo adjunto respetando autorización.
     */
    public function file(Justificacion $justificacion)
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

        $path = storage_path('app/public/' . $justificacion->archivo);
        return response()->file($path);
    }
    public function reject(Request $request, Justificacion $justificacion)
    {
        $request->validate([
            'comentario' => 'required|string|max:5000',
        ]);

        // Cambiar el estado
        $justificacion->estado = 'rechazada';
        $justificacion->save();

        // Crear el registro en la tabla rechazos
        Rechazo::create([
            'justificacion_id' => $justificacion->id,
            'comentario' => $request->comentario,
        ]);

        // Ejecutar la lógica del estado (si usas el patrón State)
        (new RechazadaState())->onEnter($justificacion, $request->comentario);

        // Notificar mediante el Subject/Observer explícito
        $this->subject->notify($justificacion, 'rechazada', Auth::user(), $request->comentario);

        return redirect()->route('admin.dashboard')->with('success', 'Justificación rechazada correctamente.');
    }

    public function index(Request $request)
    {
        $estado = $request->input('estado');
        $justificaciones = $this->repository->getByUserAndState(Auth::id(), $estado);

        return view('justificaciones.index', compact('justificaciones'));
    }


    public function create()
    {
        $profesores = Profesor::with('clases')->get();
        return view('justificaciones.create', compact('profesores'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'justificaciones' => 'required|array|min:1',
            'justificaciones.*.clase_profesor_id' => 'required|exists:clase_profesor,id',
            'justificaciones.*.fecha' => 'required|date',
            'tipo_constancia' => 'required|in:trabajo,enfermedad,otro',
            'notas_adicionales' => 'nullable|string',
            'archivo' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $archivoPath = null;

        if ($request->hasFile('archivo')) {
            $file = $request->file('archivo');

            if ($file->isValid()) {
                $filename = uniqid() . '_' . $file->getClientOriginalName();
                $destination = storage_path('app/public/justificaciones');

                if (!file_exists($destination)) {
                    mkdir($destination, 0755, true);
                }

                $file->move($destination, $filename);
                $archivoPath = 'justificaciones/' . $filename;
            } else {
                return back()->withErrors(['archivo' => 'El archivo no es válido.'])->withInput()->with('error', 'El archivo no es válido.');
            }
        }

        foreach ($request->justificaciones as $entry) {
            $this->repository->create([
                'user_id' => Auth::id(),
                'clase_profesor_id' => $entry['clase_profesor_id'],
                'fecha' => $entry['fecha'],
                'tipo_constancia' => $request->input('tipo_constancia'),
                'notas_adicionales' => $request->input('notas_adicionales'),
                'archivo' => $archivoPath,
            ]);
        }

        return redirect()->route('justificaciones.index')->with('success', 'Justificación(es) enviadas correctamente.');
    }

    public function destroy(Justificacion $justificacion)
    {
        if ($justificacion->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para eliminar esta justificación.');
        }

        if ($justificacion->archivo && Storage::disk('public')->exists($justificacion->archivo)) {
            Storage::disk('public')->delete($justificacion->archivo);
        }

        $this->repository->delete($justificacion->id);

        return redirect()->route('justificaciones.index')->with('success', 'Justificación eliminada correctamente.');
    }

    public function destroyAndCreate(Justificacion $justificacion)
    {
        if ($justificacion->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para eliminar esta justificación.');
        }

        if ($justificacion->archivo && Storage::disk('public')->exists($justificacion->archivo)) {
            Storage::disk('public')->delete($justificacion->archivo);
        }

        $this->repository->delete($justificacion->id);

        return redirect()
            ->route('justificaciones.create')
            ->with('info', 'Justificación eliminada. Ahora puedes crear una nueva.');
    }

    public function edit($id)
    {
        $justificacion = $this->repository->findForUser($id, Auth::id());
        if (!$justificacion) {
            abort(404);
        }

        $profesores = \App\Models\Profesor::with('clases')->get();
        $clases = \App\Models\ClaseProfesor::with('clase')->get();
        return view('justificaciones.edit', compact('justificacion', 'profesores', 'clases'));
    }

    public function update(Request $request, $id)
    {
        $justificacion = $this->repository->findForUser($id, Auth::id());
        if (!$justificacion) {
            abort(404);
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
                // Eliminar archivo anterior si existe
                if ($justificacion->archivo && Storage::disk('public')->exists($justificacion->archivo)) {
                    Storage::disk('public')->delete($justificacion->archivo);
                }
                $filename = uniqid() . '_' . $file->getClientOriginalName();
                $destination = storage_path('app/public/justificaciones');
                if (!file_exists($destination)) {
                    mkdir($destination, 0755, true);
                }
                $file->move($destination, $filename);
                $justificacion->archivo = 'justificaciones/' . $filename;
            } else {
                return back()->withErrors(['archivo' => 'El archivo no es válido.'])->withInput()->with('error', 'El archivo no es válido.');
            }
        }

        $this->repository->update($id, [
            'clase_profesor_id' => $request->input('justificaciones.0.clase_profesor_id'),
            'fecha' => $request->input('justificaciones.0.fecha'),
            'tipo_constancia' => $request->input('tipo_constancia'),
            'notas_adicionales' => $request->input('notas_adicionales'),
            'archivo' => $justificacion->archivo, // Persist the potentially new file path
        ]);

        return redirect()->route('justificaciones.index')->with('success', 'Justificación actualizada correctamente.');
    }

}