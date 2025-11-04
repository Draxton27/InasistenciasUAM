<?php

namespace App\Application\DTOs;

/**
 * DTO: EstudianteDTO
 * Capa: Application
 * Objeto de transferencia de datos para estudiantes entre capas
 */
class EstudianteDTO
{
    public function __construct(
        public ?int $id = null,
        public ?int $userId = null,
        public ?string $cif = null,
        public ?string $nombre = null,
        public ?string $apellido = null,
        public ?string $email = null,
        public ?string $foto = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            userId: $data['user_id'] ?? $data['userId'] ?? null,
            cif: $data['cif'] ?? null,
            nombre: $data['nombre'] ?? null,
            apellido: $data['apellido'] ?? null,
            email: $data['email'] ?? null,
            foto: $data['foto'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'cif' => $this->cif,
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'email' => $this->email,
            'foto' => $this->foto,
        ];
    }
}

