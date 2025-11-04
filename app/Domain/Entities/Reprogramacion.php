<?php

namespace App\Domain\Entities;

/**
 * Entidad de Dominio: Reprogramacion
 * Capa: Domain
 * Representa la entidad de negocio sin dependencias de infraestructura (Eloquent)
 */
class Reprogramacion
{
    public function __construct(
        public ?int $id = null,
        public ?int $justificacionId = null,
        public ?string $fechaReprogramada = null,
        public ?string $aula = null,
    ) {}

    /**
     * Valida que la fecha reprogramada sea futura
     */
    public function esFechaFutura(): bool
    {
        if (!$this->fechaReprogramada) {
            return false;
        }
        
        return \Carbon\Carbon::parse($this->fechaReprogramada)->isFuture();
    }
}

