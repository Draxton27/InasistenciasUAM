<?php

namespace App\Infrastructure\Notifications\Services;

use App\Domain\Justificacion\Observer\Services\NotificationGateway as INotificationGateway;
use App\Domain\Entities\Justificacion;
use App\Domain\Entities\User;
use App\Infrastructure\Persistence\Eloquent\Models\User as UserModel;
use App\Infrastructure\Notifications\JustificationStatusChangedNotification;

/**
 * Implementación Concreta: NotificationGateway
 * Capa: Infrastructure
 * Implementa el gateway de notificaciones que centraliza la gestión
 * de múltiples canales (database, broadcast, email, SMS, etc.)
 */
class NotificationGateway implements INotificationGateway
{
    /**
     * Envía una notificación a través de los canales configurados
     */
    public function send(
        Justificacion $justificacion,
        string $estado,
        ?User $actor = null,
        ?string $motivo = null,
        array $channels = ['database', 'broadcast']
    ): void {
        if (!$justificacion->userId) {
            return;
        }

        $userModel = UserModel::find($justificacion->userId);
        if (!$userModel) {
            return;
        }

        // Crear la notificación con los canales especificados
        $notification = new JustificationStatusChangedNotification(
            status: $estado,
            justificationId: $justificacion->id ?? 0,
            actorId: $actor?->id,
            actorName: $actor?->name,
            reason: $motivo,
            channels: $channels
        );

        // Enviar a través de los canales especificados
        // Laravel automáticamente usa el método via() de la Notification
        $userModel->notify($notification);
    }
}

