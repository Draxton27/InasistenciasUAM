<?php

namespace App\Presentation\Http\Controllers;

use App\Application\Services\ReprogramacionService;
use App\Application\DTOs\ReprogramacionDTO;
use App\Domain\Repositories\JustificacionRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Models\Reprogramacion;
use App\Infrastructure\Persistence\Eloquent\Models\Justificacion;
use App\Presentation\Http\Controllers\Controller;
use App\Presentation\Http\Requests\ReprogramacionStoreRequest;
use App\Presentation\Http\Requests\ReprogramacionUpdateRequest;
use Carbon\Carbon;

/**
 * Controller: ReprogramacionController
 * Capa: Presentation
 * Maneja las reprogramaciones de justificaciones aceptadas
 */
class ReprogramacionController extends Controller
{
    public function __construct(
        private ReprogramacionService $reprogramacionService,
        private JustificacionRepositoryInterface $justificacionRepository
    ) {}

    public function create(Justificacion $justificacion)
    {
        // Solo permitir si la justificación está aceptada y no tiene reprogramación
        if ($justificacion->estado !== 'aceptada' || $justificacion->reprogramacion) {
            return redirect()->back()->withErrors('No se puede agregar reprogramación a esta justificación.')->with('error', 'No se puede agregar reprogramación a esta justificación.');
        }
        return view('reprogramaciones.create', compact('justificacion'));
    }

    public function store(ReprogramacionStoreRequest $request)
    {
        $validated = $request->validated();
        
        try {
            $dto = ReprogramacionDTO::fromArray([
                'justificacionId' => $validated['justificacion_id'],
                'fechaReprogramada' => $validated['fecha_reprogramada'],
                'aula' => $validated['aula'] ?? null,
            ]);

            $this->reprogramacionService->create($dto);

            return redirect()->route('profesor.dashboard')->with('success', 'Reprogramación registrada correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function edit(Reprogramacion $reprogramacion)
    {
        $justificacion = $reprogramacion->justificacion;
        return view('reprogramaciones.edit', compact('reprogramacion', 'justificacion'));
    }

    public function update(ReprogramacionUpdateRequest $request, Reprogramacion $reprogramacion)
    {
        $validated = $request->validated();
        
        try {
            $dto = ReprogramacionDTO::fromArray([
                'id' => $reprogramacion->id,
                'justificacionId' => $reprogramacion->justificacion_id,
                'fechaReprogramada' => $validated['fecha_reprogramada'],
                'aula' => $validated['aula'] ?? null,
            ]);

            $this->reprogramacionService->update($dto);

            return redirect()->route('profesor.dashboard')->with('success', 'Reprogramación actualizada correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
}
