<?php

namespace App\Events;

use App\Models\Justificacion;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;

/**
 * Se emite cuando una justificación es aprobada.
 * Transporta el contexto necesario para que los observadores reaccionen.
 */
class JustificationApproved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Justificacion $justification,  // Justificación afectada
        public ?User $actor = null            // Usuario que realizó la acción (admin, coordinador, etc.)
    ) {}
}
