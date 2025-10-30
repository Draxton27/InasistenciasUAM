<?php

namespace App\States\Justificacion;

use App\Models\Justificacion;

abstract class BaseState implements JustificacionState
{
    public function revisar(Justificacion $justificacion)
    {
        throw new \Exception("No se puede pasar a revisión desde el estado actual.");
    }

    public function aceptar(Justificacion $justificacion)
    {
        throw new \Exception("No se puede aceptar desde el estado actual.");
    }

    public function rechazar(Justificacion $justificacion, $comentario = null)
    {
        throw new \Exception("No se puede rechazar desde el estado actual.");
    }
}
