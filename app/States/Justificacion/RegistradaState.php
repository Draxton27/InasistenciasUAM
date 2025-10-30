<?php

namespace App\States\Justificacion;

use App\Models\Justificacion;

class RegistradaState extends BaseState
{
    public function revisar(Justificacion $justificacion)
    {
        $justificacion->setEstado('en_revision');
    }
}
