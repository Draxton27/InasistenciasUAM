<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Profesor;
use Illuminate\Support\Collection;

/**
 * Interface: ProfesorRepositoryInterface
 * Capa: Domain
 * Define el contrato para el repositorio de profesores
 */
interface ProfesorRepositoryInterface
{
    public function findById(int $id): ?Profesor;
    public function findByUserId(int $userId): ?Profesor;
    public function findAll(): Collection;
    public function save(Profesor $profesor): Profesor;
    public function delete(int $id): bool;
    public function attachClase(int $profesorId, int $claseId, ?int $grupo = null): bool;
    public function detachAllClases(int $profesorId): bool;
}

