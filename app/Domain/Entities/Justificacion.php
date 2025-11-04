<?php

namespace App\Domain\Entities;

/**
 * Entidad de Dominio: Justificacion
 * Capa: Domain
 * Representa la entidad de negocio sin dependencias de infraestructura (Eloquent)
 */
class Justificacion
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
        public ?string $estado = 'registrada',
    ) {}

    public function cambiarEstado(string $estado): void
    {
        $this->estado = $estado;
    }

    public function estaEnEstado(string $estado): bool
    {
        return $this->estado === $estado;
    }
}

