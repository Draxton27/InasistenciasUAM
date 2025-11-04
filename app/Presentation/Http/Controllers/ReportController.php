<?php

namespace App\Presentation\Http\Controllers;

use App\Application\Services\ReportService;
use App\Presentation\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;

/**
 * Controller: ReportController
 * Capa: Presentation
 * Genera reportes de justificaciones
 */
class ReportController extends Controller
{
    public function __construct(
        private ReportService $reportService
    ) {}

    public function generarReporte(Request $request)
    {
        $filters = [];
        if ($request->filled('clase_id')) {
            $filters['clase_id'] = $request->clase_id;
        }
        if ($request->filled('fecha_inicio')) {
            $filters['fecha_inicio'] = $request->fecha_inicio;
        }
        if ($request->filled('fecha_fin')) {
            $filters['fecha_fin'] = $request->fecha_fin;
        }

        $data = $this->reportService->generarDatosReporte($filters);

        $pdf = PDF::loadView('admin.justificaciones.reporte', $data);
        return $pdf->download('reporte_justificaciones.pdf');
    }
}
