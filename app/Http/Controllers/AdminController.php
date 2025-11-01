<?php

namespace App\Http\Controllers;

use App\Events\JustificationApproved;
use App\Events\JustificationRejected;
use App\Models\Justificacion;
use App\Models\Rechazo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $conteo = [
            'pendiente' => Justificacion::where('estado', 'pendiente')->count(),
            'aceptada' => Justificacion::where('estado', 'aceptada')->count(),
            'rechazada' => Justificacion::where('estado', 'rechazada')->count(),
            'total' => Justificacion::count(),
        ];

        $justificaciones = Justificacion::with(['user', 'claseProfesor.clase'])
            ->when($request->filled('estado'), fn ($query) => $query->where('estado', $request->estado))
            ->latest()
            ->get();
        $clases = \App\Models\Clase::all();

        return view('admin.dashboard', compact('justificaciones', 'conteo', 'clases'));
    }

    public function approve($id)
    {
        $just = Justificacion::findOrFail($id);
        $just->estado = 'aceptada';
        $just->save();

        // Eliminar rechazo si existe
        $just->rechazo()->delete();

        // Notify owner via event
        event(new JustificationApproved($just, Auth::user()));

        return redirect()->route('admin.dashboard')->with('success', 'Justificación aprobada.');
    }

    public function reject($id, Request $request)
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

        // Notify owner via event
        event(new JustificationRejected($just, Auth::user(), $request->comentario));

        // Si es una petición AJAX, devolver JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Justificación rechazada y rechazo registrado.',
            ]);
        }

        return redirect()->route('admin.dashboard')->with('success', 'Justificación rechazada y rechazo registrado.');
    }

    public function showReject($id)
    {
        $justificacion = Justificacion::with(['user', 'claseProfesor.clase'])->findOrFail($id);

        return view('admin.justificaciones.rechazar', compact('justificacion'));
    }
}
