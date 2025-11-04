<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Repositories\ReprogramacionRepositoryInterface;
use App\Domain\Entities\Reprogramacion as ReprogramacionEntity;
use App\Infrastructure\Persistence\Eloquent\Models\Reprogramacion as ReprogramacionModel;
use App\Infrastructure\Persistence\Eloquent\Mappers\ReprogramacionMapper;
use Illuminate\Support\Collection;

/**
 * Repositorio Concreto: ReprogramacionRepository
 * Capa: Infrastructure
 * Implementación concreta del repositorio de reprogramaciones usando Eloquent
 */
class ReprogramacionRepository implements ReprogramacionRepositoryInterface
{
    public function findById(int $id): ?ReprogramacionEntity
    {
        $model = ReprogramacionModel::find($id);
        return $model ? ReprogramacionMapper::toEntity($model) : null;
    }

    public function findByJustificacionId(int $justificacionId): ?ReprogramacionEntity
    {
        $model = ReprogramacionModel::where('justificacion_id', $justificacionId)->first();
        return $model ? ReprogramacionMapper::toEntity($model) : null;
    }

    public function save(ReprogramacionEntity $reprogramacion): ReprogramacionEntity
    {
        $model = $reprogramacion->id 
            ? ReprogramacionModel::findOrFail($reprogramacion->id)
            : new ReprogramacionModel();
            
        $model = ReprogramacionMapper::toModel($reprogramacion, $model);
        $model->save();
        return ReprogramacionMapper::toEntity($model);
    }

    public function update(int $id, array $data): bool
    {
        $model = ReprogramacionModel::find($id);
        if (!$model) {
            return false;
        }
        
        $model->fill($data);
        return $model->save();
    }

    public function delete(int $id): bool
    {
        $model = ReprogramacionModel::find($id);
        if (!$model) {
            return false;
        }
        
        return $model->delete();
    }

    public function deleteByJustificacionId(int $justificacionId): bool
    {
        return ReprogramacionModel::where('justificacion_id', $justificacionId)->delete();
    }

    public function findAll(array $filters = []): Collection
    {
        $query = ReprogramacionModel::query();
        
        if (isset($filters['justificacion_id'])) {
            $query->where('justificacion_id', $filters['justificacion_id']);
        }
        
        return $query->get()->map(function ($model) {
            return ReprogramacionMapper::toEntity($model);
        });
    }
}

