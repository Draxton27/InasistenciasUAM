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
    public function index()
    {
        $now = Carbon::now(config('app.timezone'))->format('Y-m-d H:i:s');
        Reprogramacion::where('fecha_reprogramada', '<', $now)->delete();

        $profesor = \App\Models\Profesor::where('user_id', Auth::id())->firstOrFail();

        $justificaciones = Justificacion::whereHas('claseProfesor', function ($query) use ($profesor) {
            $query->where('profesor_id', $profesor->id);
        })->latest()->get();

        return view('profesor.dashboard', compact('justificaciones'));
    }
}