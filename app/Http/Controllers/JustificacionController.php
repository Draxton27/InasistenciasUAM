<?php

namespace App\Http\Controllers;

use App\Models\Justificacion;
use App\Models\Profesor;

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
        $profesores = Profesor::orderBy('nombre')->get();
        return view('justificaciones.create', compact('profesores'), compact('profesores'));

    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'clase_afectada' => 'required|string|max:255',
            'profesor_id' => 'required|exists:users,id',
            'profesor_id' => 'required|exists:users,id',
            'fecha' => 'required|date',
            'tipo_constancia' => 'required|in:trabajo,enfermedad,otro',
            'notas_adicionales' => 'nullable|string',
            'archivo' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('archivo')) {
            $file = $request->file('archivo');

            if ($file->isValid()) {
                $filename = uniqid() . '_' . $file->getClientOriginalName();
                $destination = storage_path('app/public/justificaciones');

                if (!file_exists($destination)) {
                    mkdir($destination, 0755, true);
                }

                $file->move($destination, $filename);
                $validated['archivo'] = 'justificaciones/' . $filename;
            } else {
                return back()->withErrors(['archivo' => 'El archivo no es válido.'])->withInput();
            }
        }

        $validated['user_id'] = Auth::id();

        Justificacion::create($validated);

        return redirect()->route('justificaciones.index')->with('success', 'Justificación enviada correctamente.');
    }

}
