<?php

namespace App\States\Justificacion;

use App\Models\Justificacion;

interface JustificacionState
{
    public function revisar(Justificacion $justificacion);
    public function aceptar(Justificacion $justificacion);
    public function rechazar(Justificacion $justificacion, $comentario = null);
}
