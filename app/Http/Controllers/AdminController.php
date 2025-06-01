<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Justificacion;

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

        return redirect()->route('admin.dashboard')->with('success', 'Justificación aprobada.');
    }

    public function rechazar($id)
    {
        $just = Justificacion::findOrFail($id);
        $just->estado = 'rechazada';
        $just->save();

        return redirect()->route('admin.dashboard')->with('success', 'Justificación rechazada.');
    }
}
