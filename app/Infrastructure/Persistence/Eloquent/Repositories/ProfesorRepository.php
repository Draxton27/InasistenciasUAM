<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Repositories\ProfesorRepositoryInterface;
use App\Domain\Entities\Profesor as ProfesorEntity;
use App\Infrastructure\Persistence\Eloquent\Models\Profesor as ProfesorModel;
use App\Infrastructure\Persistence\Eloquent\Mappers\ProfesorMapper;
use Illuminate\Support\Collection;

/**
 * Repositorio Concreto: ProfesorRepository
 * Capa: Infrastructure
 * Implementación concreta del repositorio de profesores usando Eloquent
 */
class ProfesorRepository implements ProfesorRepositoryInterface
{
    public function findById(int $id): ?ProfesorEntity
    {
        $model = ProfesorModel::find($id);
        return $model ? ProfesorMapper::toEntity($model) : null;
    }

    public function findByUserId(int $userId): ?ProfesorEntity
    {
        $model = ProfesorModel::where('user_id', $userId)->first();
        return $model ? ProfesorMapper::toEntity($model) : null;
    }

    public function findAll(): Collection
    {
        return ProfesorModel::with('clases')->get()->map(fn($model) => ProfesorMapper::toEntity($model));
    }

    public function save(ProfesorEntity $profesor): ProfesorEntity
    {
        $model = $profesor->id 
            ? ProfesorModel::findOrFail($profesor->id)
            : new ProfesorModel();
            
        $model = ProfesorMapper::toModel($profesor, $model);
        $model->save();
        
        return ProfesorMapper::toEntity($model);
    }

    public function delete(int $id): bool
    {
        return ProfesorModel::destroy($id) > 0;
    }

    public function attachClase(int $profesorId, int $claseId, ?int $grupo = null): bool
    {
        $profesor = ProfesorModel::findOrFail($profesorId);
        $profesor->clases()->attach($claseId, ['grupo' => $grupo]);
        return true;
    }

    public function detachAllClases(int $profesorId): bool
    {
        $profesor = ProfesorModel::findOrFail($profesorId);
        $profesor->clases()->detach();
        return true;
    }
}

