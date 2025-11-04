<?php

namespace App\Application\DTOs;

/**
 * DTO: ClaseDTO
 * Capa: Application
 * Objeto de transferencia de datos para clases entre capas
 */
class ClaseDTO
{
    public function __construct(
        public ?int $id = null,
        public ?string $name = null,
        public ?string $note = null,
        public ?array $profesores = null, // [['profesor_id' => int, 'grupo' => int]]
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            name: $data['name'] ?? null,
            note: $data['note'] ?? null,
            profesores: $data['profesores'] ?? $data['profesor_grupo'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'note' => $this->note,
        ];
    }
}

