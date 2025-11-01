<?php

namespace App\Domain\Justificacion\Observer\Contracts;

use App\Models\Justificacion;
use App\Models\User;

interface JustificationObserver
{
    /**
     * Reacciona a un cambio en la justificación.
     * $estado: 'aceptada' o 'rechazada'.
     */
    public function update(
        Justificacion $justificacion,
        string $estado,
        ?User $actor = null,
        ?string $motivo = null
    ): void;
}
