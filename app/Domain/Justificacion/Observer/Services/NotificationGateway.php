<?php

namespace App\Domain\Justificacion\Observer\Services;

use App\Domain\Entities\Justificacion;
use App\Domain\Entities\User;

/**
 * Interface: NotificationGateway
 * Capa: Domain
 * Define el contrato para un gateway de notificaciones que centraliza
 * la gestión de múltiples canales de notificación
 */
interface NotificationGateway
{
    /**
     * Envía una notificación a través de los canales configurados
     * 
     * @param Justificacion $justificacion Entidad de dominio de justificación
     * @param string $estado Estado de la justificación
     * @param User|null $actor Usuario que realizó la acción
     * @param string|null $motivo Motivo del cambio (ej: comentario de rechazo)
     * @param array $channels Canales por los que enviar (default: ['database', 'broadcast'])
     */
    public function send(
        Justificacion $justificacion,
        string $estado,
        ?User $actor = null,
        ?string $motivo = null,
        array $channels = ['database', 'broadcast']
    ): void;
}

