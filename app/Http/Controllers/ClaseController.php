<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clase;
use App\Models\Profesor;

class ClaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clases = Clase::with('profesores')->get();
        return view('clases.index', compact('clases'));
    }

    /**
     * Show the form for creating a new resource.
     */

    public function create()
    {
        $profesores = Profesor::orderBy('nombre')->get();
        return view('clases.create', compact('profesores'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $clase = Clase::create([
            'name' => $request->name,
        ]);

        if ($request->has('profesor_grupo')) {
            foreach ($request->profesor_grupo as $entry) {
                if (!empty($entry['profesor_id'])) {
                    $clase->profesores()->attach($entry['profesor_id'], [
                        'grupo' => $entry['grupo'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('clases.index')->with('success', 'Clase creada y profesores asignados correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Clase $clase)
    {
        return view('clases.show', compact('clase'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Clase $clase)
{
    $profesores = Profesor::orderBy('nombre')->get(); // Asegúrate de importar el modelo

    return view('clases.edit', compact('clase', 'profesores'));
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Clase $clase)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $clase->update([
            'name' => $request->name,
        ]);

        $clase->profesores()->detach();

        if ($request->has('profesor_grupo')) {
            foreach ($request->profesor_grupo as $entry) {
                if (!empty($entry['profesor_id'])) {
                    $clase->profesores()->attach($entry['profesor_id'], [
                        'grupo' => $entry['grupo'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('clases.index')->with('success', 'Clase actualizada correctamente.');
    }


    /**
     * Remove the specified resource from storage.
     */
  public function destroy($id)
    {
        $clase = Clase::findOrFail($id);
        $clase->delete();

        return redirect()->route('clases.index')->with('success', 'Clase eliminada correctamente.');
    }
}