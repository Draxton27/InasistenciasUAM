<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

/**
 * Define los canales de entrega y el payload que reciben
 * tanto la base de datos como el cliente por websockets.
 */
class JustificationStatusChangedNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected string $status,
        protected int $justificationId,
        protected ?int $actorId = null,
        protected ?string $actorName = null,
        protected ?string $reason = null,
    ) {}

    /** Canales de entrega: base de datos + broadcast (en tiempo real). */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /** Estructura persistida en la tabla notifications. */
    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => $this->status === 'aceptada' ? 'Justificación aprobada' : 'Justificación rechazada',
            'body' => $this->status === 'aceptada'
                ? 'Tu justificación ha sido aprobada.'
                : 'Tu justificación ha sido rechazada.'.($this->reason ? ' Motivo: '.$this->reason : ''),
            'status' => $this->status,
            'justification_id' => $this->justificationId,
            'actor_id' => $this->actorId,
            'actor_name' => $this->actorName,
            'url' => route('justificaciones.index'),
        ];
    }

    /** Payload emitido por websockets hacia el cliente. */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toDatabase($notifiable));
    }
}
