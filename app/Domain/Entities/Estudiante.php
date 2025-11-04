<?php

namespace App\Domain\Entities;

/**
 * Entidad de Dominio: Estudiante
 * Capa: Domain
 * Representa la entidad de negocio sin dependencias de infraestructura (Eloquent)
 */
class Estudiante
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
}

