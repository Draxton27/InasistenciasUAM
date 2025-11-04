<?php

namespace App\Domain\Justificacion\States;

use App\Domain\Entities\Justificacion;

/**
 * Estado Concreto: RegistradaState
 * Capa: Domain
 * Patrón: State (GoF)
 * Representa el estado inicial de una justificación registrada
 */
class RegistradaState extends BaseState
{
    public function revisar(Justificacion $justificacion): void
    {
        $justificacion->cambiarEstado('en_revision');
    }
}

