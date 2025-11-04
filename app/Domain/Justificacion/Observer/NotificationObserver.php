<?php

namespace App\Domain\Justificacion\Observer;

use App\Domain\Entities\Justificacion;
use App\Domain\Entities\User;
use App\Domain\Justificacion\Observer\Contracts\JustificationObserver;
use App\Domain\Justificacion\Observer\Services\JustificationStatusNotifier;

/**
 * Clase Concreta: NotificationObserver
 * Capa: Domain
 * Patrón: Observer (GoF)
 * Observador que notifica cambios de estado de justificaciones
 */
class NotificationObserver implements JustificationObserver
{
    public function __construct(
        private JustificationStatusNotifier $notifier
    ) {}

    public function update(
        Justificacion $justificacion,
        string $estado,
        ?User $actor = null,
        ?string $motivo = null
    ): void {
        $this->notifier->enviar($justificacion, $estado, $actor, $motivo);
    }
}

