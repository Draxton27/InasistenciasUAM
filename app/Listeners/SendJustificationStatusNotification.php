<?php

namespace App\Listeners;

use App\Events\JustificationApproved;
use App\Events\JustificationRejected;
use App\Notifications\JustificationStatusChangedNotification;
use Illuminate\Support\Facades\Log;

/**
 * Observador que escucha aprobaciones/rechazos de justificaciones
 * y envía una notificación (db + broadcast).
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
            // Notificación (db + broadcast)
            $notification = new JustificationStatusChangedNotification(
                status: $status,
                justificationId: $just->id,
                actorId: $event->actor?->id,
                actorName: $event->actor?->name,
                reason: $reason,
            );
            $user->notify($notification); // Envia por canales definidos en via()

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
