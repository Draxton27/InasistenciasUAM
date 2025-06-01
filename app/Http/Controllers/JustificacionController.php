<?php

namespace App\Http\Controllers;

use App\Models\Justificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class JustificacionController extends Controller
{
    public function index()
    {
        $justificaciones = Auth::user()->justificaciones()->latest()->get();
        return view('justificaciones.index', compact('justificaciones'));
    }

    public function create()
    {
        return view('justificaciones.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'clase_afectada' => 'required|string|max:255',
            'docente' => 'required|string|max:255',
            'fecha' => 'required|date',
            'tipo_constancia' => 'required|in:trabajo,enfermedad,otro',
            'notas_adicionales' => 'nullable|string',
            'archivo' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('archivo') && $request->file('archivo')->isValid()) {
            $path = $request->file('archivo')->store('justificaciones', 'public');
            $validated['archivo'] = $path;
        }

        $validated['user_id'] = Auth::id();

        Justificacion::create($validated);

        return redirect()->route('justificaciones.index')->with('success', 'Justificación enviada correctamente.');
    }
}
