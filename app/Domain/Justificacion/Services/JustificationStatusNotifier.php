<?php

namespace App\Domain\Justificacion\Services;

use App\Events\UserNotified;
use App\Models\Justificacion;
use App\Models\User;
use App\Notifications\JustificationStatusChangedNotification;

class JustificationStatusNotifier
{
    public function enviar(
        Justificacion $just,
        string $estado,          // 'aceptada' | 'rechazada'
        ?User $actor = null,
        ?string $motivo = null
    ): void {
        $user = $just->user; // Estudiante propietario
        if (! $user) {
            return;
        }

        // Notificación por database y broadcast
        $user->notify(new JustificationStatusChangedNotification(
            status: $estado,
            justificationId: $just->id,
            actorId: $actor?->id,
            actorName: $actor?->name,
            reason: $motivo
        ));

        // Difusión inmediata como refuerzo para alerta tipo toast
        event(new UserNotified($user->id, [
            'title' => $estado === 'aceptada' ? 'Justificación aprobada' : 'Justificación rechazada',
            'body' => $estado === 'aceptada'
                ? 'Tu justificación ha sido aprobada.'
                : 'Tu justificación ha sido rechazada.'.($motivo ? ' Motivo: '.$motivo : ''),
            'reason' => $motivo,
            'status' => $estado,
            'justification_id' => $just->id,
            'actor_id' => $actor?->id,
            'actor_name' => $actor?->name,
            'url' => route('justificaciones.index'),
        ]));
    }
}
