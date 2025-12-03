<?php

namespace App\Domain\Justificacion\Services;

use App\Models\Justificacion;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class JustificationStatusNotifier
{
    public function enviar(
        Justificacion $justificacion,
        string $estado,
        ?User $actor = null,
        ?string $motivo = null
    ): void {
        match ($estado) {
            'aceptada' => \App\Events\JustificationApproved::dispatch($justificacion, $actor),
            'rechazada' => \App\Events\JustificationRejected::dispatch($justificacion, $actor, $motivo),
            default => Log::info("Estado no manejado por notificador: {$estado}"),
        };
    }
}
