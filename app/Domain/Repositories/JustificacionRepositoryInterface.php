<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Justificacion;
use Illuminate\Support\Collection;

/**
 * Interface: JustificacionRepositoryInterface
 * Capa: Domain
 * Define el contrato para el repositorio de justificaciones
 * La implementación concreta estará en Infrastructure
 */
interface JustificacionRepositoryInterface
{
    public function findById(int $id): ?Justificacion;
    public function findByUserId(int $userId, ?string $estado = null): Collection;
    public function save(Justificacion $justificacion): Justificacion;
    public function delete(int $id): bool;
    public function updateEstado(int $id, string $estado): bool;
    public function findAll(array $filters = []): Collection;
}

