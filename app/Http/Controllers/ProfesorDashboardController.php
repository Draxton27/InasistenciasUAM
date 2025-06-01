<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Justificacion;

class ProfesorDashboardController extends Controller
{
    //
     public function index()
    {
        $justificaciones = Justificacion::with('user')->latest()->get(); // Luego puedes filtrar por clase/docente
        return view('profesor.dashboard', compact('justificaciones'));
    }
}
