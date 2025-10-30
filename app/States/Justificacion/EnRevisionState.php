<?php

namespace App\States\Justificacion;

use App\Models\Justificacion;

class EnRevisionState extends BaseState
{
    public function aceptar(Justificacion $justificacion)
    {
        $justificacion->setEstado('aceptada');
    }

    public function rechazar(Justificacion $justificacion, $comentario = null)
    {
        $justificacion->setEstado('rechazada');
        $justificacion->comentario_rechazo = $comentario;
        $justificacion->save();
    }
}
