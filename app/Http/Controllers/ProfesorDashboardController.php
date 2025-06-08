<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

use Illuminate\Http\Request;
use App\Models\Justificacion;

class ProfesorDashboardController extends Controller
{
    //
    public function index()
    {
        $profesor = \App\Models\Profesor::where('user_id', Auth::id())->firstOrFail();

        $justificaciones = Justificacion::whereHas('claseProfesor', function ($query) use ($profesor) {
            $query->where('profesor_id', $profesor->id);
        })->latest()->get();

        return view('profesor.dashboard', compact('justificaciones'));
    }
}