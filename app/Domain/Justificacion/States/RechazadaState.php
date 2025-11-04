<?php

namespace App\Domain\Justificacion\States;

use App\Domain\Entities\Justificacion;

/**
 * Estado Concreto: RechazadaState
 * Capa: Domain
 * Patrón: State (GoF)
 * Representa el estado cuando una justificación ha sido rechazada
 */
class RechazadaState extends BaseState
{
    public static string $name = 'rechazada';

    /**
     * Método adicional para cuando se entra al estado rechazada
     * El comentario se manejará en la capa de aplicación/infraestructura
     */
    public function onEnter(Justificacion $justificacion, ?string $comentario = null): void
    {
        $justificacion->cambiarEstado(self::$name);
        // El comentario se persistirá mediante el repositorio en la capa de aplicación
    }
}

