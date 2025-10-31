<?php

namespace App\States\Justificacion;

use App\Models\Justificacion;

class RechazadaState extends BaseState
{
    public static string $name = 'rechazada';

    public function onEnter(Justificacion $justificacion, ?array $data = null): void
    {
        // Cambiar estado en la base
        parent::onEnter($justificacion, $data);

        // Guardar comentario solo si se proporciona y no está vacío
        if (!empty($data['comentario'] ?? null)) {
            $justificacion->rechazo()->create([
                'comentario' => $data['comentario'],
            ]);
        }
    }
}
