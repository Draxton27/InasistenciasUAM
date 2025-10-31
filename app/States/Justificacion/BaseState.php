<?php

namespace App\States\Justificacion;

use App\Models\Justificacion;

abstract class BaseState implements JustificacionState
{
    protected string $nombre;

    public function __construct()
    {
        $this->nombre = class_basename(static::class);
    }

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

    public function label(): string
    {
        return ucfirst(str_replace('State', '', $this->nombre));
    }

    public function color(): string
    {
        return match ($this->nombre) {
            'AprobadaState' => 'bg-green-100 text-green-800',
            'RechazadaState' => 'bg-red-100 text-red-800',
            default => 'bg-yellow-100 text-yellow-800',
        };
    }
}
