<?php

namespace App\Domain\Entities;

/**
 * Entidad de Dominio: Clase
 * Capa: Domain
 * Representa la entidad de negocio sin dependencias de infraestructura (Eloquent)
 */
class Clase
{
    public function __construct(
        public ?int $id = null,
        public ?string $name = null,
        public ?string $note = null,
    ) {}
}

