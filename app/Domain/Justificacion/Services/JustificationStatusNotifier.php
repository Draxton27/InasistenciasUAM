<?php

namespace App\Domain\Justificacion\Services;

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
    }
}
