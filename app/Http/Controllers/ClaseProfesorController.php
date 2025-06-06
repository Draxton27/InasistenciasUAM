<?php

namespace App\Http\Controllers;
use App\Models\Clase;
use App\Models\Profesor;
use Illuminate\Http\Request;
use App\Models\ClaseProfesor;


class ClaseProfesorController extends Controller
{
    //
    public function create()
    {
        $clases = Clase::orderBy('nombre')->get();
        $profesores = Profesor::orderBy('nombre')->get();

        return view('clases_profesor.create', compact('clases', 'profesores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'clase_id' => 'required|exists:clases,id',
            'profesor_id' => 'required|exists:profesores,id',
            'grupo' => 'required|string|max:255',
        ]);

        ClaseProfesor::create($request->all());

        return redirect()->route('clases_profesor.index')->with('success', 'Clase asignada correctamente.');
    }
}