<?php

namespace App\Http\Controllers;

use App\Models\Reprogramacion;
use App\Models\Justificacion;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReprogramacionController extends Controller
{
    public function create(Justificacion $justificacion)
    {
        // Solo permitir si la justificación está aceptada y no tiene reprogramación
        if ($justificacion->estado !== 'aceptada' || $justificacion->reprogramacion) {
            return redirect()->back()->withErrors('No se puede agregar reprogramación a esta justificación.');
        }
        return view('reprogramaciones.create', compact('justificacion'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'justificacion_id' => 'required|exists:justificaciones,id',
            'fecha_reprogramada' => ['required', 'date', function($attribute, $value, $fail) {
                $fecha = Carbon::parse($value, config('app.timezone'));
                if ($fecha->lte(Carbon::now(config('app.timezone')))) {
                    $fail('La fecha y hora deben ser futuras.');
                }
            }],
            'aula' => 'nullable|string|max:100',
        ]);

        $justificacion = Justificacion::findOrFail($request->justificacion_id);
        if ($justificacion->estado !== 'aceptada' || $justificacion->reprogramacion) {
            return redirect()->back()->withErrors('No se puede agregar reprogramación a esta justificación.');
        }

        Reprogramacion::create([
            'justificacion_id' => $justificacion->id,
            'fecha_reprogramada' => $request->fecha_reprogramada,
            'aula' => $request->aula,
        ]);

        return redirect()->route('profesor.dashboard')->with('success', 'Reprogramación registrada correctamente.');
    }

    public function edit(Reprogramacion $reprogramacion)
    {
        $justificacion = $reprogramacion->justificacion;
        return view('reprogramaciones.edit', compact('reprogramacion', 'justificacion'));
    }

    public function update(Request $request, Reprogramacion $reprogramacion)
    {
        $request->validate([
            'fecha_reprogramada' => ['required', 'date', function($attribute, $value, $fail) {
                $fecha = Carbon::parse($value, config('app.timezone'));
                if ($fecha->lte(Carbon::now(config('app.timezone')))) {
                    $fail('La fecha y hora deben ser futuras.');
                }
            }],
            'aula' => 'nullable|string|max:100',
        ]);

        $reprogramacion->update([
            'fecha_reprogramada' => $request->fecha_reprogramada,
            'aula' => $request->aula,
        ]);

        return redirect()->route('profesor.dashboard')->with('success', 'Reprogramación actualizada correctamente.');
    }
}
