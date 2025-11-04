<?php

namespace App\Domain\Justificacion\Observer\Contracts;

use App\Domain\Entities\Justificacion;
use App\Domain\Entities\User;

/**
 * Interface: JustificationObserver
 * Capa: Domain
 * Patrón: Observer (GoF)
 * Define el contrato para observadores de cambios en justificaciones
 */
interface JustificationObserver
{
    /**
     * Reacciona a un cambio en la justificación.
     * @param Justificacion $justificacion Entidad de dominio de justificación
     * @param string $estado Estado al que cambió ('aceptada' o 'rechazada')
     * @param User|null $actor Usuario que realizó la acción
     * @param string|null $motivo Motivo del cambio (ej: comentario de rechazo)
     */
    public function update(
        Justificacion $justificacion,
        string $estado,
        ?User $actor = null,
        ?string $motivo = null
    ): void;
}

