<?php

namespace App\Domain\Justificacion\States;

use App\Domain\Entities\Justificacion;

/**
 * Estado Concreto: EnRevisionState
 * Capa: Domain
 * Patrón: State (GoF)
 * Representa el estado cuando una justificación está en revisión
 */
class EnRevisionState extends BaseState
{
    public function aceptar(Justificacion $justificacion): void
    {
        $justificacion->cambiarEstado('aceptada');
    }

    public function rechazar(Justificacion $justificacion, ?string $comentario = null): void
    {
        $justificacion->cambiarEstado('rechazada');
    }
}

