<?php

namespace App\Domain\Entities;

/**
 * Entidad de Dominio: User
 * Capa: Domain
 * Representa la entidad de negocio sin dependencias de infraestructura (Eloquent)
 */
class User
{
    public function __construct(
        public ?int $id = null,
        public ?string $name = null,
        public ?string $email = null,
        public ?string $role = null,
    ) {}

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isProfesor(): bool
    {
        return $this->role === 'profesor';
    }

    public function isEstudiante(): bool
    {
        return $this->role === 'estudiante';
    }
}

