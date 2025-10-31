<?php

namespace App\States\Justificacion;

use App\Models\Justificacion;

class AceptadaState extends BaseState
{
    public static string $name = 'aceptada';

    public function onEnter(Justificacion $justificacion, ?array $data = null): void
    {
        parent::onEnter($justificacion, $data);
        // Lógica adicional si se requiere
    }

    public function aprobar(Justificacion $justificacion)
    {
        // Ya está aprobada, puede no hacer nada o lanzar excepción
    }

    public function rechazar(Justificacion $justificacion, array $data)
    {
        $justificacion->state()->onEnter($justificacion, $data); // cambiar a rechazada si quieres
    }
}
