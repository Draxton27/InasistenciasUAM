<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Clase;
use Illuminate\Support\Collection;

/**
 * Interface: ClaseRepositoryInterface
 * Capa: Domain
 * Define el contrato para el repositorio de clases
 */
interface ClaseRepositoryInterface
{
    public function findById(int $id): ?Clase;
    public function findAll(): Collection;
    public function save(Clase $clase): Clase;
    public function delete(int $id): bool;
    public function attachProfesor(int $claseId, int $profesorId, ?int $grupo = null): bool;
    public function detachAllProfesores(int $claseId): bool;
    public function verificarGrupoDisponible(int $claseId, int $grupo, ?int $excludeProfesorId = null): bool;
}

