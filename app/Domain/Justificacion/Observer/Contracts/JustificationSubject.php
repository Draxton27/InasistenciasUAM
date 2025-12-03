<?php

namespace App\Domain\Justificacion\Observer\Contracts;

use App\Models\Justificacion;
use App\Models\User;

interface JustificationSubject
{
    public function attach(JustificationObserver $observer): void;
    public function detach(JustificationObserver $observer): void;
    public function notify(Justificacion $justificacion, string $estado, ?User $actor = null, ?string $motivo = null): void;
}
