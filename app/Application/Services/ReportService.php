<?php

namespace App\Application\Services;

use App\Domain\Repositories\JustificacionRepositoryInterface;
use App\Domain\Repositories\ClaseRepositoryInterface;
use Carbon\Carbon;

/**
 * Servicio de Aplicación: ReportService
 * Capa: Application
 * Orquesta la generación de reportes de justificaciones
 */
class ReportService
{
    public function __construct(
        private JustificacionRepositoryInterface $justificacionRepository,
        private ClaseRepositoryInterface $claseRepository
    ) {}

    /**
     * Genera datos de reporte basado en filtros
     * Nota: Para reportes complejos con agrupaciones, usamos modelos directamente
     * ya que las entidades de dominio no tienen timestamps ni relaciones
     */
    public function generarDatosReporte(array $filters = []): array
    {
        // Para reportes, es más eficiente usar modelos directamente
        $query = \App\Infrastructure\Persistence\Eloquent\Models\Justificacion::query();
        
        $claseNombre = null;
        if (isset($filters['clase_id'])) {
            $query->whereHas('claseProfesor.clase', function ($q) use ($filters) {
                $q->where('id', $filters['clase_id']);
            });
            
            $clase = $this->claseRepository->findById($filters['clase_id']);
            if ($clase) {
                $claseNombre = $clase->name;
            }
        }

        // Filtro por rango de fechas
        if (isset($filters['fecha_inicio']) && isset($filters['fecha_fin'])) {
            $fechaInicio = Carbon::parse($filters['fecha_inicio'])->startOfDay();
            $fechaFin = Carbon::parse($filters['fecha_fin'])->endOfDay();
            $query->whereBetween('created_at', [$fechaInicio, $fechaFin]);
        }

        $justificaciones = $query->get();

        $total = $justificaciones->count();
        $aprobadas = $justificaciones->filter(fn($j) => strtolower($j->estado) === 'aceptada')->count();
        $rechazadas = $justificaciones->filter(fn($j) => strtolower($j->estado) === 'rechazada')->count();

        $porcentajeAprobadas = $total > 0 ? ($aprobadas / $total) * 100 : 0;
        $porcentajeRechazadas = $total > 0 ? ($rechazadas / $total) * 100 : 0;

        $porTipo = $justificaciones->groupBy('tipo_constancia')->map->count();

        return [
            'total' => $total,
            'aprobadas' => $aprobadas,
            'rechazadas' => $rechazadas,
            'porcentajeAprobadas' => round($porcentajeAprobadas, 2),
            'porcentajeRechazadas' => round($porcentajeRechazadas, 2),
            'porTipo' => $porTipo,
            'claseNombre' => $claseNombre,
            'filtros' => [
                'clase_id' => $filters['clase_id'] ?? null,
                'fecha_inicio' => $filters['fecha_inicio'] ?? null,
                'fecha_fin' => $filters['fecha_fin'] ?? null,
            ],
        ];
    }
}

