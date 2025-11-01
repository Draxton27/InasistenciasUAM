<?php

namespace App\Events;

use App\Models\Justificacion;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;

/**
 * Se emite cuando una justificación es rechazada.
 */
class JustificationRejected
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Justificacion $justification,
        public ?User $actor = null,
        public ?string $reason = null // Motivo opcional del rechazo
    ) {}
}
