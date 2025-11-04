<?php

namespace App\Application\DTOs;

/**
 * DTO: ReprogramacionDTO
 * Capa: Application
 * Objeto de transferencia de datos para reprogramaciones entre capas
 */
class ReprogramacionDTO
{
    public function __construct(
        public ?int $id = null,
        public ?int $justificacionId = null,
        public ?string $fechaReprogramada = null,
        public ?string $aula = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            justificacionId: $data['justificacion_id'] ?? $data['justificacionId'] ?? null,
            fechaReprogramada: $data['fecha_reprogramada'] ?? $data['fechaReprogramada'] ?? null,
            aula: $data['aula'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'justificacion_id' => $this->justificacionId,
            'fecha_reprogramada' => $this->fechaReprogramada,
            'aula' => $this->aula,
        ];
    }
}

