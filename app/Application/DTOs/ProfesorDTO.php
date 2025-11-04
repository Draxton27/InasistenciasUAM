<?php

namespace App\Application\DTOs;

/**
 * DTO: ProfesorDTO
 * Capa: Application
 * Objeto de transferencia de datos para profesores entre capas
 */
class ProfesorDTO
{
    public function __construct(
        public ?int $id = null,
        public ?int $userId = null,
        public ?string $nombre = null,
        public ?string $email = null,
        public ?string $foto = null,
        public ?string $password = null,
        public ?array $clases = null, // [['clase_id' => int, 'grupo' => int]]
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            userId: $data['user_id'] ?? $data['userId'] ?? null,
            nombre: $data['nombre'] ?? null,
            email: $data['email'] ?? null,
            foto: $data['foto'] ?? null,
            password: $data['password'] ?? null,
            clases: $data['clases'] ?? $data['clase_grupo'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'nombre' => $this->nombre,
            'email' => $this->email,
            'foto' => $this->foto,
        ];
    }
}

