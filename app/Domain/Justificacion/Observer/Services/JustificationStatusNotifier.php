<?php

namespace App\Domain\Justificacion\Observer\Services;

use App\Domain\Entities\Justificacion;
use App\Domain\Entities\User;

/**
 * Interface: JustificationStatusNotifier
 * Capa: Domain
 * Define el contrato para notificar cambios de estado
 * La implementación concreta estará en Infrastructure
 */
interface JustificationStatusNotifier
{
    /**
     * Envía una notificación sobre el cambio de estado de una justificación
     */
    public function enviar(
        Justificacion $justificacion,
        string $estado,
        ?User $actor = null,
        ?string $motivo = null
    ): void;
}

