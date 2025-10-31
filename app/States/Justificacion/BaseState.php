<?php

namespace App\States\Justificacion;

use App\Models\Justificacion;

abstract class BaseState
{
    public static string $name;

    // Método que se ejecuta al entrar en el estado
    public function onEnter(Justificacion $justificacion, ?array $data = null): void
    {
        $justificacion->estado = static::$name;
        $justificacion->save();
    }

    // Acciones permitidas
    public function aprobar(Justificacion $justificacion)
    {
        throw new \Exception("No se puede aprobar desde el estado " . static::$name);
    }

    public function rechazar(Justificacion $justificacion, array $data)
    {
        throw new \Exception("No se puede rechazar desde el estado " . static::$name);
    }

    // Métodos para mostrar en Blade
    public function label(): string
    {
        return ucfirst(static::$name);
    }

    public function color(): string
    {
        return match(static::$name) {
            'pendiente' => 'bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs',
            'aceptada'  => 'bg-green-100 text-green-800 px-2 py-1 rounded text-xs',
            'rechazada' => 'bg-red-100 text-red-800 px-2 py-1 rounded text-xs',
            'en_revision' => 'bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs',
            default => 'bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs',
        };
    }

    // Control de permisos para acciones en la vista
    public function canEdit(): bool
    {
        return static::$name === 'pendiente';
    }

    public function canDelete(): bool
    {
        return static::$name === 'pendiente';
    }

    public function showRechazo(): bool
    {
        return static::$name === 'rechazada';
    }
}
