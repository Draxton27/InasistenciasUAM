<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Justificacion;
use App\Models\Rechazo;
use PDF;

class ReportController extends Controller
{
public function generarReporte()
{
    $justificaciones = Justificacion::all();

    $total = $justificaciones->count();
    $aprobadas = $justificaciones->where('estado', 'aceptada')->count();
    $rechazadas = $justificaciones->where('estado', 'rechazada')->count();

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
    ];

    $pdf = PDF::loadView('admin.justificaciones.reporte', $data);
    return $pdf->download('reporte_justificaciones.pdf');
}
}
