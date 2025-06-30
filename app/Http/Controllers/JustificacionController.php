<?php

namespace App\Http\Controllers;

use App\Models\Justificacion;
use App\Models\Profesor;
use App\Models\Reprogramacion;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class JustificacionController extends Controller
{
    public function index(Request $request)
    {
         $now = Carbon::now(config('app.timezone'))->format('Y-m-d H:i:s');
         Reprogramacion::where('fecha_reprogramada', '<', $now)->delete();
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

}