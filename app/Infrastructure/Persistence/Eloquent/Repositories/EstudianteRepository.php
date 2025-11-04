<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Repositories\EstudianteRepositoryInterface;
use App\Domain\Entities\Estudiante as EstudianteEntity;
use App\Infrastructure\Persistence\Eloquent\Models\Estudiante as EstudianteModel;
use App\Infrastructure\Persistence\Eloquent\Mappers\EstudianteMapper;
use Illuminate\Support\Collection;

/**
 * Repositorio Concreto: EstudianteRepository
 * Capa: Infrastructure
 * Implementación concreta del repositorio de estudiantes usando Eloquent
 */
class EstudianteRepository implements EstudianteRepositoryInterface
{
    public function findById(int $id): ?EstudianteEntity
    {
        $model = EstudianteModel::find($id);
        return $model ? EstudianteMapper::toEntity($model) : null;
    }

    public function findByUserId(int $userId): ?EstudianteEntity
    {
        $model = EstudianteModel::where('user_id', $userId)->first();
        return $model ? EstudianteMapper::toEntity($model) : null;
    }

    public function save(EstudianteEntity $estudiante): EstudianteEntity
    {
        $model = $estudiante->id 
            ? EstudianteModel::findOrFail($estudiante->id)
            : new EstudianteModel();
            
        $model = EstudianteMapper::toModel($estudiante, $model);
        $model->save();
        return EstudianteMapper::toEntity($model);
    }

    public function update(int $id, array $data): bool
    {
        $model = EstudianteModel::find($id);
        if (!$model) {
            return false;
        }
        
        $model->fill($data);
        return $model->save();
    }

    public function delete(int $id): bool
    {
        $model = EstudianteModel::find($id);
        if (!$model) {
            return false;
        }
        
        return $model->delete();
    }
}

