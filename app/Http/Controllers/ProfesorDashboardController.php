<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Justificacion;

class ProfesorDashboardController extends Controller
{
    //
     public function index()
    {
        $profesor = Auth::user();

        $justificaciones = Justificacion::where('profesor_id', $profesor->id)->latest()->get();

        return view('profesor.dashboard', compact('justificaciones'));

    }
}
