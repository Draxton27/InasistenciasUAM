<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Repositories\ClaseRepositoryInterface;
use App\Domain\Entities\Clase as ClaseEntity;
use App\Infrastructure\Persistence\Eloquent\Models\Clase as ClaseModel;
use App\Infrastructure\Persistence\Eloquent\Mappers\ClaseMapper;
use Illuminate\Support\Collection;

/**
 * Repositorio Concreto: ClaseRepository
 * Capa: Infrastructure
 * Implementación concreta del repositorio de clases usando Eloquent
 */
class ClaseRepository implements ClaseRepositoryInterface
{
    public function findById(int $id): ?ClaseEntity
    {
        $model = ClaseModel::find($id);
        return $model ? ClaseMapper::toEntity($model) : null;
    }

    public function findAll(): Collection
    {
        return ClaseModel::with('profesores')->get()->map(fn($model) => ClaseMapper::toEntity($model));
    }

    public function save(ClaseEntity $clase): ClaseEntity
    {
        $model = $clase->id 
            ? ClaseModel::findOrFail($clase->id)
            : new ClaseModel();
            
        $model = ClaseMapper::toModel($clase, $model);
        $model->save();
        
        return ClaseMapper::toEntity($model);
    }

    public function delete(int $id): bool
    {
        return ClaseModel::destroy($id) > 0;
    }

    public function attachProfesor(int $claseId, int $profesorId, ?int $grupo = null): bool
    {
        $clase = ClaseModel::findOrFail($claseId);
        $clase->profesores()->attach($profesorId, ['grupo' => $grupo]);
        return true;
    }

    public function detachAllProfesores(int $claseId): bool
    {
        $clase = ClaseModel::findOrFail($claseId);
        $clase->profesores()->detach();
        return true;
    }

    public function verificarGrupoDisponible(int $claseId, int $grupo, ?int $excludeProfesorId = null): bool
    {
        $query = \App\Infrastructure\Persistence\Eloquent\Models\ClaseProfesor::where('clase_id', $claseId)
            ->where('grupo', $grupo);
            
        if ($excludeProfesorId) {
            $query->where('profesor_id', '!=', $excludeProfesorId);
        }
        
        return !$query->exists();
    }
}

