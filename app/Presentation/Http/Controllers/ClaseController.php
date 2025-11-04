<?php

namespace App\Presentation\Http\Controllers;

use App\Application\Services\ClaseService;
use App\Application\DTOs\ClaseDTO;
use App\Infrastructure\Persistence\Eloquent\Models\Clase as ClaseModel;
use App\Infrastructure\Persistence\Eloquent\Models\Profesor as ProfesorModel;
use App\Presentation\Http\Controllers\Controller;
use App\Presentation\Http\Requests\ClaseStoreRequest;
use App\Presentation\Http\Requests\ClaseUpdateRequest;

/**
 * Controller: ClaseController
 * Capa: Presentation
 * Maneja las operaciones CRUD de clases
 */
class ClaseController extends Controller
{
    public function __construct(
        private ClaseService $claseService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Temporal: usar modelos directamente para compatibilidad con vistas
        $clases = ClaseModel::with('profesores')->get();
        return view('clases.index', compact('clases'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $profesores = ProfesorModel::orderBy('nombre')->get();
        return view('clases.create', compact('profesores'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClaseStoreRequest $request)
    {
        try {
            $dto = ClaseDTO::fromArray([
                'name' => $request->validated()['name'],
                'note' => $request->validated()['note'] ?? null,
                'profesor_grupo' => $request->validated()['profesor_grupo'] ?? [],
            ]);

            $this->claseService->create($dto);

            return redirect()->route('clases.index')->with('success', 'Clase creada y profesores asignados correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    /*public function show(ClaseModel $clase)
    {
        return view('clases.show', compact('clase'));
    }*/

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClaseModel $clase)
    {
        $profesores = ProfesorModel::orderBy('nombre')->get();
        return view('clases.edit', compact('clase', 'profesores'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClaseUpdateRequest $request, ClaseModel $clase)
    {
        try {
            $dto = ClaseDTO::fromArray([
                'id' => $clase->id,
                'name' => $request->validated()['name'],
                'note' => $request->validated()['note'] ?? null,
                'profesor_grupo' => $request->validated()['profesor_grupo'] ?? [],
            ]);

            $this->claseService->update($dto);

            return redirect()->route('clases.index')->with('success', 'Clase actualizada correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $this->claseService->delete($id);
            return redirect()->route('clases.index')->with('success', 'Clase eliminada correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
