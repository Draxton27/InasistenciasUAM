<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Difunde al instante (sin pasar por cola) un payload para el canal privado del usuario.
 * Útil para mostrar toasts en tiempo real.
 */
class UserNotified implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $userId,
        public array $payload
    ) {}

    /** Canales destino: canal privado del usuario autenticado. */
    public function broadcastOn(): array
    {
        return [new PrivateChannel('App.Models.User.' . $this->userId)];
    }

    /** Nombre del evento en el cliente (Echo). */
    public function broadcastAs(): string
    {
        return 'UserNotified';
    }
}
