<?php

namespace App\Infrastructure\Notifications\Services;

use App\Domain\Justificacion\Observer\Services\JustificationStatusNotifier as JustificationStatusNotifierInterface;
use App\Domain\Justificacion\Observer\Services\NotificationGateway as NotificationGatewayInterface;
use App\Domain\Entities\Justificacion;
use App\Domain\Entities\User;

/**
 * Implementación Concreta: JustificationStatusNotifier
 * Capa: Infrastructure
 * Implementa la notificación de cambios de estado usando el NotificationGateway
 */
class JustificationStatusNotifier implements JustificationStatusNotifierInterface
{
    public function __construct(
        private NotificationGatewayInterface $gateway
    ) {}

    public function enviar(
        Justificacion $justificacion,
        string $estado,
        ?User $actor = null,
        ?string $motivo = null
    ): void {
        // Delegar al Gateway para manejar los canales de notificación
        $this->gateway->send(
            justificacion: $justificacion,
            estado: $estado,
            actor: $actor,
            motivo: $motivo,
            channels: ['database', 'broadcast'] // Canales por defecto
        );
    }
}

