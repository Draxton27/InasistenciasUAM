<?php

namespace App\Domain\Entities;

/**
 * Entidad de Dominio: Profesor
 * Capa: Domain
 * Representa la entidad de negocio sin dependencias de infraestructura (Eloquent)
 */
class Profesor
{
    public function __construct(
        public ?int $id = null,
        public ?int $userId = null,
        public ?string $nombre = null,
        public ?string $email = null,
        public ?string $foto = null,
    ) {}
}

