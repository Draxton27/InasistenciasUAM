<?php

namespace App\Domain\Justificacion\Observer;

use App\Models\Justificacion;
use App\Models\User;
use App\Domain\Justificacion\Services\JustificationStatusNotifier;
use App\Domain\Justificacion\Observer\Contracts\JustificationObserver;

class NotificationObserver implements JustificationObserver
{
    public function __construct(private JustificationStatusNotifier $notifier) {}

    public function update(
        Justificacion $justificacion,
        string $estado,
        ?User $actor = null,
        ?string $motivo = null
    ): void {
        $this->notifier->enviar($justificacion, $estado, $actor, $motivo);
    }
}
