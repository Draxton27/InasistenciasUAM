<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clase;
use App\Models\Profesor;
use Illuminate\Support\Facades\Validator;


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
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'profesor_grupo' => 'nullable|array',
        'profesor_grupo.*.profesor_id' => 'nullable|exists:profesores,id',
        'profesor_grupo.*.grupo' => 'nullable|integer|min:1',
    ], [
    'profesor_grupo.*.grupo.integer' => 'El grupo debe ser un número válido.',
    'profesor_grupo.*.grupo.min' => 'El grupo debe ser mayor a 0.',
]);

    // Validación personalizada
    $validator->after(function ($validator) use ($request) {
        $gruposUsados = [];

        foreach ($request->input('profesor_grupo', []) as $index => $entry) {
            $grupo = $entry['grupo'] ?? null;

            if (!empty($grupo)) {
                if (in_array($grupo, $gruposUsados)) {
                    $validator->errors()->add("profesor_grupo.$index.grupo", "Este grupo ya ha sido asignado a otro profesor.");
                } else {
                    $gruposUsados[] = $grupo;
                }
            }
        }
    });

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput()
            ->with('error', 'Corrige los errores del formulario.');
    }

    // Crear clase
    $clase = Clase::create([
        'name' => $request->name,
        'note' => $request->note ?? null,
    ]);

    // Asignar profesores
    foreach ($request->profesor_grupo ?? [] as $entry) {
        if (!empty($entry['profesor_id'])) {
            $clase->profesores()->attach($entry['profesor_id'], [
                'grupo' => $entry['grupo'] ?? null,
            ]);
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
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'profesor_grupo' => 'nullable|array',
        'profesor_grupo.*.profesor_id' => 'nullable|exists:profesores,id',
        'profesor_grupo.*.grupo' => 'nullable|integer|min:1',
    ], [
    'profesor_grupo.*.grupo.integer' => 'El grupo debe ser un número válido.',
    'profesor_grupo.*.grupo.min' => 'El grupo debe ser mayor a 0.',
]);

    // Validación personalizada: evitar grupos repetidos
    $validator->after(function ($validator) use ($request) {
        $gruposUsados = [];

        foreach ($request->input('profesor_grupo', []) as $index => $entry) {
            $grupo = $entry['grupo'] ?? null;

            if (!empty($grupo)) {
                if (in_array($grupo, $gruposUsados)) {
                    $validator->errors()->add("profesor_grupo.$index.grupo", "Este grupo ya ha sido asignado a otro profesor.");
                } else {
                    $gruposUsados[] = $grupo;
                }
            }
        }
    });

    // Si falla, redirigir con errores y datos anteriores
    if ($validator->fails()) {
        return redirect()->back()
                         ->withErrors($validator)
                         ->withInput()
                         ->with('error', 'Corrige los errores del formulario.');
    }

    // Actualizar clase
    $clase->update([
        'name' => $request->name,
        'note' => $request->note ?? null,
    ]);

    // Actualizar relación con profesores
    $clase->profesores()->detach();

    foreach ($request->profesor_grupo ?? [] as $entry) {
        if (!empty($entry['profesor_id'])) {
            $clase->profesores()->attach($entry['profesor_id'], [
                'grupo' => $entry['grupo'] ?? null,
            ]);
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