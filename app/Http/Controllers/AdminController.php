<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Justificacion;
use App\Models\Rechazo;

class AdminController extends Controller
{
    public function index()
    {
        $justificaciones = Justificacion::with('user')->latest()->get();
        return view('admin.dashboard', compact('justificaciones'));
    }

    public function aprobar($id)
    {
        $just = Justificacion::findOrFail($id);
        $just->estado = 'aceptada';
        $just->save();

        // Eliminar rechazo si existe
        $just->rechazo()->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Justificación aprobada.');
    }

    public function rechazar($id, Request $request)
    {
        $request->validate([
            'comentario' => 'required|string|max:1000',
        ]);
        $just = Justificacion::findOrFail($id);
        $just->estado = 'rechazada';
        $just->save();

        Rechazo::create([
            'justificacion_id' => $just->id,
            'comentario' => $request->comentario,
        ]);

        // Si es una petición AJAX, devolver JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Justificación rechazada y rechazo registrado.'
            ]);
        }

        return redirect()->route('admin.dashboard')->with('success', 'Justificación rechazada y rechazo registrado.');
    }

    public function showRechazar($id)
    {
        $justificacion = Justificacion::with(['user', 'claseProfesor.clase'])->findOrFail($id);
        return view('admin.justificaciones.rechazar', compact('justificacion'));
    }
}
