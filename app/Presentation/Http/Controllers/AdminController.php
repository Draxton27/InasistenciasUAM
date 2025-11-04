<?php

namespace App\Presentation\Http\Controllers;

use App\Application\Services\JustificacionService;
use App\Application\DTOs\JustificacionDTO;
use App\Domain\Justificacion\Observer\Contracts\JustificationSubject;
use App\Infrastructure\Persistence\Eloquent\Models\Justificacion as JustificacionModel;
use App\Infrastructure\Persistence\Eloquent\Models\Rechazo as RechazoModel;
use App\Infrastructure\Persistence\Eloquent\Models\Clase as ClaseModel;
use App\Infrastructure\Persistence\Eloquent\Mappers\UserMapper;
use App\Presentation\Http\Controllers\Controller;
use App\Presentation\Http\Requests\AdminRejectRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller: AdminController
 * Capa: Presentation
 * Controlador delgado para administradores que delega la lógica a servicios
 */
class AdminController extends Controller
{
    public function __construct(
        private JustificacionService $justificacionService,
        private JustificationSubject $subject
    ) {}

    public function index(Request $request)
    {
        // Obtener conteos desde los modelos (temporal - podría moverse a un servicio)
        $conteo = [
            'pendiente' => JustificacionModel::where('estado', 'pendiente')->count(),
            'aceptada' => JustificacionModel::where('estado', 'aceptada')->count(),
            'rechazada' => JustificacionModel::where('estado', 'rechazada')->count(),
            'total' => JustificacionModel::count(),
        ];

        // Obtener justificaciones usando el servicio
        $filters = [];
        if ($request->filled('estado')) {
            $filters['estado'] = $request->estado;
        }

        $justificacionesDTOs = $this->justificacionService->findAll($filters);
        
        // Convertir DTOs a modelos para compatibilidad con las vistas (temporal)
        $justificaciones = $justificacionesDTOs->map(function ($dto) {
            return JustificacionModel::with(['user', 'claseProfesor.clase'])->find($dto->id);
        })->filter();

        $clases = ClaseModel::all();

        return view('admin.dashboard', compact('justificaciones', 'conteo', 'clases'));
    }

    public function approve($id)
    {
        $dto = $this->justificacionService->findById($id);
        if (!$dto) {
            abort(404);
        }

        // Usar el servicio para cambiar el estado (retorna entidad actualizada)
        $justificacionEntity = $this->justificacionService->aceptar($id);

        // Eliminar rechazo si existe (temporal - debería estar en el servicio)
        $justificacionModel = JustificacionModel::findOrFail($id);
        $justificacionModel->rechazo()->delete();

        // Notificar mediante Observer
        // Nota: UserMapper se usa aquí como caso especial para convertir Auth::user() 
        // (modelo Eloquent) a entidad de dominio necesaria para el Observer
        $userEntity = UserMapper::toEntity(Auth::user());
        $this->subject->notify($justificacionEntity, 'aceptada', $userEntity, null);

        return redirect()->route('admin.dashboard')->with('success', 'Justificación aprobada.');
    }

    public function reject($id, AdminRejectRequest $request)
    {
        $validated = $request->validated();
        $dto = $this->justificacionService->findById($id);
        if (!$dto) {
            abort(404);
        }

        // Usar el servicio para rechazar (retorna entidad actualizada)
        $justificacionEntity = $this->justificacionService->rechazar($id, $validated['comentario']);

        // Notificar mediante Observer
        // Nota: UserMapper se usa aquí como caso especial para convertir Auth::user() 
        // (modelo Eloquent) a entidad de dominio necesaria para el Observer
        $userEntity = UserMapper::toEntity(Auth::user());
        $this->subject->notify($justificacionEntity, 'rechazada', $userEntity, $validated['comentario']);

        return redirect()->route('admin.dashboard')->with('success', 'Justificación rechazada y rechazo registrado.');
    }

    public function showReject($id)
    {
        $justificacion = JustificacionModel::with(['user', 'claseProfesor.clase'])->findOrFail($id);
        return view('admin.justificaciones.rechazar', compact('justificacion'));
    }
}
