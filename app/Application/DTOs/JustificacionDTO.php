<?php

namespace App\Application\DTOs;

/**
 * DTO: JustificacionDTO
 * Capa: Application
 * Objeto de transferencia de datos para justificaciones entre capas
 */
class JustificacionDTO
{
    public function __construct(
        public ?int $id = null,
        public ?int $userId = null,
        public ?string $claseAfectada = null,
        public ?int $claseProfesorId = null,
        public ?int $profesorId = null,
        public ?string $fecha = null,
        public ?string $tipoConstancia = null,
        public ?string $notasAdicionales = null,
        public ?string $archivo = null,
        public ?string $estado = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            userId: $data['user_id'] ?? $data['userId'] ?? null,
            claseAfectada: $data['clase_afectada'] ?? $data['claseAfectada'] ?? null,
            claseProfesorId: $data['clase_profesor_id'] ?? $data['claseProfesorId'] ?? null,
            profesorId: $data['profesor_id'] ?? $data['profesorId'] ?? null,
            fecha: $data['fecha'] ?? null,
            tipoConstancia: $data['tipo_constancia'] ?? $data['tipoConstancia'] ?? null,
            notasAdicionales: $data['notas_adicionales'] ?? $data['notasAdicionales'] ?? null,
            archivo: $data['archivo'] ?? null,
            estado: $data['estado'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'clase_afectada' => $this->claseAfectada,
            'clase_profesor_id' => $this->claseProfesorId,
            'profesor_id' => $this->profesorId,
            'fecha' => $this->fecha,
            'tipo_constancia' => $this->tipoConstancia,
            'notas_adicionales' => $this->notasAdicionales,
            'archivo' => $this->archivo,
            'estado' => $this->estado,
        ];
    }
}

