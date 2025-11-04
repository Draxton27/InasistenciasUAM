<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Reprogramacion;
use Illuminate\Support\Collection;

/**
 * Interface: ReprogramacionRepositoryInterface
 * Capa: Domain
 * Define el contrato para el repositorio de reprogramaciones
 * La implementación concreta estará en Infrastructure
 */
interface ReprogramacionRepositoryInterface
{
    public function findById(int $id): ?Reprogramacion;
    public function findByJustificacionId(int $justificacionId): ?Reprogramacion;
    public function save(Reprogramacion $reprogramacion): Reprogramacion;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function deleteByJustificacionId(int $justificacionId): bool;
    public function findAll(array $filters = []): Collection;
}

