<?php

namespace App\Domain\Justificacion\Observer\Contracts;

use App\Models\Justificacion;
use App\Models\User;

interface JustificationObserver
{
    public function update(Justificacion $justificacion, string $estado, ?User $actor = null, ?string $motivo = null): void;
}
