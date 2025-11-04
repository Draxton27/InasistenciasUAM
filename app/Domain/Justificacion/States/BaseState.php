<?php

namespace App\Domain\Justificacion\States;

use App\Domain\Entities\Justificacion;

/**
 * Clase Base: BaseState
 * Capa: Domain
 * Patrón: State (GoF)
 * Implementa el comportamiento por defecto para estados no permitidos
 */
abstract class BaseState implements JustificacionState
{
    public function revisar(Justificacion $justificacion): void
    {
        throw new \Exception("No se puede pasar a revisión desde el estado actual.");
    }

    public function aceptar(Justificacion $justificacion): void
    {
        throw new \Exception("No se puede aceptar desde el estado actual.");
    }

    public function rechazar(Justificacion $justificacion, ?string $comentario = null): void
    {
        throw new \Exception("No se puede rechazar desde el estado actual.");
    }
}

