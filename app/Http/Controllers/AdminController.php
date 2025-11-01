<?php

namespace App\Http\Controllers;

use App\Models\Justificacion;
use App\Models\Rechazo;
use App\Domain\Justificacion\Observer\Contracts\JustificationSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct(private JustificationSubject $subject) {}
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

        // Notificar vía Subject/Observer explícito (sin listeners)
        $this->subject->notify($just, 'aceptada', Auth::user(), null);

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

        // Notificar vía Subject/Observer explícito (sin listeners)
        $this->subject->notify($just, 'rechazada', Auth::user(), $request->comentario);

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
