<?php

namespace App\Listeners;

use App\Events\JustificationApproved;
use App\Events\JustificationRejected;
use App\Events\UserNotified;
use App\Notifications\JustificationStatusChangedNotification;
use Illuminate\Support\Facades\Log;

/**
 * Observador que escucha aprobaciones/rechazos de justificaciones
 * y envía una notificación (db + broadcast). Además dispara un evento
 * de broadcast inmediato (UserNotified) como refuerzo visual en tiempo real.
 */
class SendJustificationStatusNotification
{
    /**
     * Punto de entrada del observador
     */
    public function handle(object $event): void
    {
        Log::info('Observer: received event', ['event' => get_class($event)]);

        // Determinar tipo de cambio
        $status = null;
        $reason = null;

        if ($event instanceof JustificationApproved) {
            $status = 'aceptada';
        } elseif ($event instanceof JustificationRejected) {
            $status = 'rechazada';
            $reason = $event->reason;
        } else {
            return; // Evento no manejado por este observador
        }

        $just = $event->justification;
        $user = $just->user; // Estudiante propietario

        if (!$user) {
            Log::warning('Observer: justification has no user relation');
            return;
        }

        try {
            // 1) Notificación (db + broadcast)
            $notification = new JustificationStatusChangedNotification(
                status: $status,
                justificationId: $just->id,
                actorId: $event->actor?->id,
                actorName: $event->actor?->name,
                reason: $reason,
            );
            $user->notify($notification); // Envia por canales definidos en via()

            // 2) Broadcast inmediato como refuerzo (toast en tiempo real)
            event(new UserNotified($user->id, [
                'title' => $status === 'aceptada' ? 'Justificación aprobada' : 'Justificación rechazada',
                'body'  => $status === 'aceptada'
                    ? 'Tu justificación ha sido aprobada.'
                    : 'Tu justificación ha sido rechazada.' . ($reason ? ' Motivo: ' . $reason : ''),
                'status' => $status,
                'justification_id' => $just->id,
                'actor_id'  => $event->actor?->id,
                'actor_name'=> $event->actor?->name,
                'url' => route('justificaciones.index'),
            ]));

            Log::info('Observer: notification sent', [
                'user_id' => $user->id,
                'justificacion_id' => $just->id,
                'status' => $status,
            ]);
        } catch (\Throwable $e) {
            Log::error('Observer: notification failed', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
