<?php

namespace App\Infrastructure\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

/**
 * Notificación: JustificationStatusChangedNotification
 * Capa: Infrastructure
 * Notificación de Laravel para cambios de estado de justificaciones
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
        protected array $channels = ['database', 'broadcast'],
    ) {}

    /** Canales de entrega: configurados dinámicamente o por defecto database + broadcast. */
    public function via(object $notifiable): array
    {
        return $this->channels;
    }

    /** Estructura persistida en la tabla notifications. */
    public function toDatabase($notifiable): array
    {
        return $this->payload($notifiable);
    }

    /** Payload emitido por websockets hacia el cliente. */
    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->payload($notifiable));
    }

    private function payload($notifiable): array
    {
        $title = $this->status === 'aceptada'
            ? 'Justificación aprobada'
            : ($this->status === 'rechazada'
                ? 'Justificación rechazada'
                : 'Actualización de justificación');

        $body = $this->status === 'aceptada'
            ? 'Tu justificación ha sido aprobada.'
            : 'Tu justificación ha sido rechazada.' . ($this->reason ? ' Motivo: ' . $this->reason : '');

        return [
            'title' => $title,
            'body' => $body,
            'status' => $this->status,
            'reason' => $this->reason,
            'justification_id' => $this->justificationId,
            'actor_id' => $this->actorId,
            'actor_name' => $this->actorName,
            'url' => route('justificaciones.index'),
        ];
    }
}

