<?php

namespace App\Presentation\Http\Controllers;

use App\Infrastructure\Persistence\Eloquent\Models\Clase;
use App\Infrastructure\Persistence\Eloquent\Models\Profesor;
use App\Infrastructure\Persistence\Eloquent\Models\ClaseProfesor;
use App\Presentation\Http\Controllers\Controller;
use App\Presentation\Http\Requests\ClaseProfesorStoreRequest;

/**
 * Controller: ClaseProfesorController
 * Capa: Presentation
 * Maneja la asignación de profesores a clases
 */
class ClaseProfesorController extends Controller
{
    //
    public function create()
    {
        $clases = Clase::orderBy('name')->get();
        $profesores = Profesor::orderBy('nombre')->get();

        return view('clases_profesor.create', compact('clases', 'profesores'));
    }

    public function store(ClaseProfesorStoreRequest $request)
    {
        ClaseProfesor::create($request->validated());

        return redirect()->route('clases.index')->with('success', 'Clase asignada correctamente.');
    }
}