<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

use Illuminate\Http\Request;
use App\Models\Justificacion;
use App\Models\Reprogramacion;
use Carbon\Carbon;

class ProfesorDashboardController extends Controller
{
    //
    public function index(Request $request)
    {
        $profesor = \App\Models\Profesor::where('user_id', Auth::id())->firstOrFail();

        $justificaciones = Justificacion::whereHas('claseProfesor', function ($query) use ($profesor) {
            $query->where('profesor_id', $profesor->id);
        })->where('estado', 'aceptada')->latest()->get();

        return view('profesor.dashboard', compact('justificaciones'));
    }
}