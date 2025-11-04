<?php

namespace App\Domain\Justificacion\States;

use App\Domain\Entities\Justificacion;

/**
 * Estado Concreto: AceptadaState
 * Capa: Domain
 * Patrón: State (GoF)
 * Estado final - no se pueden realizar más transiciones
 */
class AceptadaState extends BaseState
{
    // Estado final - no se pueden realizar más transiciones
}

