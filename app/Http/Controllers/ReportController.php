<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Justificacion;
use App\Models\Clase;
use App\Models\Rechazo;
use Carbon\Carbon;
use PDF;

class ReportController extends Controller
{
    public function generarReporte(Request $request)
{
    $query = Justificacion::query();
    $claseNombre = null;

    // Filtro por clase (solo clase_id)
    if ($request->filled('clase_id')) {
        $query->whereHas('claseProfesor.clase', function ($q) use ($request) {
            $q->where('id', $request->clase_id);
        });

        // Obtener nombre de la clase para mostrar en el reporte
        $clase = Clase::find($request->clase_id);
        if ($clase) {
            $claseNombre = $clase->name;
        }
    }

    // Filtro por rango de fechas
    if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
        $fechaInicio = Carbon::parse($request->fecha_inicio)->startOfDay();
        $fechaFin = Carbon::parse($request->fecha_fin)->endOfDay();

        $query->whereBetween('created_at', [$fechaInicio, $fechaFin]);
    }

    $justificaciones = $query->get();

    $total = $justificaciones->count();
    $aprobadas = $justificaciones->filter(fn($j) => strtolower($j->estado) === 'aceptada')->count();
    $rechazadas = $justificaciones->filter(fn($j) => strtolower($j->estado) === 'rechazada')->count();

    $porcentajeAprobadas = $total > 0 ? ($aprobadas / $total) * 100 : 0;
    $porcentajeRechazadas = $total > 0 ? ($rechazadas / $total) * 100 : 0;

    $porTipo = $justificaciones->groupBy('tipo_constancia')->map->count();

    $data = [
        'total' => $total,
        'aprobadas' => $aprobadas,
        'rechazadas' => $rechazadas,
        'porcentajeAprobadas' => round($porcentajeAprobadas, 2),
        'porcentajeRechazadas' => round($porcentajeRechazadas, 2),
        'porTipo' => $porTipo,
        'claseNombre' => $claseNombre,
        'filtros' => [
            'clase_id' => $request->clase_id,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
        ],
    ];

    $pdf = PDF::loadView('admin.justificaciones.reporte', $data);
    return $pdf->download('reporte_justificaciones.pdf');
}
}
