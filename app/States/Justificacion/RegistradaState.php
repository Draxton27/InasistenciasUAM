<?php

namespace App\States\Justificacion;

use App\Models\Justificacion;

class RegistradaState extends BaseState
{
    public static string $name = 'registrada';

    // Pasar a revisión
    public function revisar(Justificacion $justificacion): void
    {
        // Cambia el estado usando onEnter para mantener consistencia
        $justificacion->estado = 'en_revision';
        $justificacion->save();
    }

    // No se puede aprobar desde este estado
    public function aprobar(Justificacion $justificacion)
    {
        throw new \Exception("No se puede aprobar una justificación que aún está registrada.");
    }

    // No se puede rechazar desde este estado directamente
    public function rechazar(Justificacion $justificacion, array $data)
    {
        throw new \Exception("No se puede rechazar una justificación que aún está registrada.");
    }
}
