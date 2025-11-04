<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Estudiante;
use Illuminate\Support\Collection;

/**
 * Interface: EstudianteRepositoryInterface
 * Capa: Domain
 * Define el contrato para el repositorio de estudiantes
 * La implementación concreta estará en Infrastructure
 */
interface EstudianteRepositoryInterface
{
    public function findById(int $id): ?Estudiante;
    public function findByUserId(int $userId): ?Estudiante;
    public function save(Estudiante $estudiante): Estudiante;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}

