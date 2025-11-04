<?php

namespace App\Domain\Justificacion\States;

use App\Domain\Entities\Justificacion as JustificacionEntity;

/**
 * Interface: JustificacionState
 * Capa: Domain
 * Patrón: State (GoF)
 * Define el contrato para los estados de una justificación
 */
interface JustificacionState
{
    public function revisar(JustificacionEntity $justificacion): void;
    public function aceptar(JustificacionEntity $justificacion): void;
    public function rechazar(JustificacionEntity $justificacion, ?string $comentario = null): void;
}

