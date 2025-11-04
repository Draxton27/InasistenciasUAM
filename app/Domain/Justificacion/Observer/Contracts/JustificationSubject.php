<?php

namespace App\Domain\Justificacion\Observer\Contracts;

use App\Domain\Entities\Justificacion;
use App\Domain\Entities\User;

/**
 * Interface: JustificationSubject
 * Capa: Domain
 * Patrón: Observer (GoF)
 * Define el contrato para el sujeto observable de justificaciones
 */
interface JustificationSubject
{
    public function attach(JustificationObserver $observer): void;
    public function detach(JustificationObserver $observer): void;

    /**
     * Notifica a los observadores un cambio de estado de la justificación.
     * @param Justificacion $justificacion Entidad de dominio de justificación
     * @param string $estado Nuevo estado
     * @param User|null $actor Usuario que realizó la acción
     * @param string|null $motivo Motivo del cambio
     */
    public function notify(
        Justificacion $justificacion,
        string $estado,
        ?User $actor = null,
        ?string $motivo = null
    ): void;
}

