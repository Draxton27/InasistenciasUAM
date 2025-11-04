<?php

namespace App\Presentation\Http\Controllers;

use App\Infrastructure\Persistence\Eloquent\Models\Profesor;
use App\Infrastructure\Persistence\Eloquent\Models\Justificacion;
use App\Presentation\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

/**
 * Controller: ProfesorDashboardController
 * Capa: Presentation
 * Maneja el dashboard del profesor
 */
class ProfesorDashboardController extends Controller
{
    //
    public function index(Request $request)
    {
        $profesor = Profesor::where('user_id', Auth::id())->firstOrFail();

        $justificaciones = Justificacion::whereHas('claseProfesor', function ($query) use ($profesor) {
            $query->where('profesor_id', $profesor->id);
        })->where('estado', 'aceptada')->latest()->get();

        return view('profesor.dashboard', compact('justificaciones'));
    }
}