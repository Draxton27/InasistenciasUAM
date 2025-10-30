<?php

namespace App\States\Justificacion;

use App\Models\Justificacion;
use App\Models\Rechazo;

class RechazadaState extends BaseState
{
    public static string $name = 'rechazada';

    public function onEnter(Justificacion $justificacion, ?string $comentario = null): void
    {
        // Cambia el estado del modelo
        $justificacion->estado = self::$name;
        $justificacion->save();

        // Guarda el comentario si se proporcionó
        if ($comentario) {
            Rechazo::create([
                'justificacion_id' => $justificacion->id,
                'comentario' => $comentario,
            ]);
        }
    }
}
