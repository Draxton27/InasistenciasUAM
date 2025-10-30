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


class JustificacionController extends Controller
{
    public function rechazar(Request $request, Justificacion $justificacion)
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

    return redirect()->route('admin.dashboard')->with('success', 'Justificación rechazada correctamente.');
}

    public function index(Request $request)
    {
         $query = Auth::user()->justificaciones()->latest();
        
         //filtro
         if ($request->filled('estado')) {
             $query->where('estado', $request->estado);
         }
         $justificaciones = $query->get();
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
            return back()->withErrors(['archivo' => 'El archivo no es válido.'])->withInput();
        }
    }

    foreach ($request->justificaciones as $entry) {
        Justificacion::create([
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

    $justificacion->delete();

    return redirect()->route('justificaciones.index')->with('success', 'Justificación eliminada correctamente.');
}

public function destroyAndCreate(Justificacion $justificacion)
{
    if ($justificacion->user_id !== Auth::id()) {
        abort(403, 'No tienes permiso para eliminar esta justificación.');
    }

    if ($justificacion->archivo && \Storage::disk('public')->exists($justificacion->archivo)) {
        \Storage::disk('public')->delete($justificacion->archivo);
    }

    $justificacion->delete();

    return redirect()->route('justificaciones.create');
}

public function edit($id)
{
    $justificacion = Auth::user()->justificaciones()->findOrFail($id);
    $profesores = \App\Models\Profesor::with('clases')->get();
    $clases = \App\Models\ClaseProfesor::with('clase')->get();
    return view('justificaciones.edit', compact('justificacion', 'profesores', 'clases'));
}

public function update(Request $request, $id)
{
    $justificacion = Auth::user()->justificaciones()->findOrFail($id);
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
            if ($justificacion->archivo && \Storage::disk('public')->exists($justificacion->archivo)) {
                \Storage::disk('public')->delete($justificacion->archivo);
            }
            $filename = uniqid() . '_' . $file->getClientOriginalName();
            $destination = storage_path('app/public/justificaciones');
            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }
            $file->move($destination, $filename);
            $justificacion->archivo = 'justificaciones/' . $filename;
        } else {
            return back()->withErrors(['archivo' => 'El archivo no es válido.'])->withInput();
        }
    }

    $justificacion->clase_profesor_id = $request->input('justificaciones.0.clase_profesor_id');
    $justificacion->fecha = $request->input('justificaciones.0.fecha');
    $justificacion->tipo_constancia = $request->input('tipo_constancia');
    $justificacion->notas_adicionales = $request->input('notas_adicionales');
    $justificacion->save();

    return redirect()->route('justificaciones.index')->with('success', 'Justificación actualizada correctamente.');
}

}