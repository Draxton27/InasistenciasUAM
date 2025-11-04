<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Repositories\JustificacionRepositoryInterface;
use App\Domain\Entities\Justificacion as JustificacionEntity;
use App\Infrastructure\Persistence\Eloquent\Models\Justificacion as JustificacionModel;
use App\Infrastructure\Persistence\Eloquent\Mappers\JustificacionMapper;
use Illuminate\Support\Collection;

/**
 * Repositorio Concreto: JustificacionRepository
 * Capa: Infrastructure
 * Implementación concreta del repositorio de justificaciones usando Eloquent
 */
class JustificacionRepository implements JustificacionRepositoryInterface
{

    public function findById(int $id): ?JustificacionEntity
    {
        $model = JustificacionModel::find($id);
        return $model ? JustificacionMapper::toEntity($model) : null;
    }

    public function findByUserId(int $userId, ?string $estado = null): Collection
    {
        $query = JustificacionModel::where('user_id', $userId);
        
        if ($estado) {
            $query->where('estado', $estado);
        }
        
        return $query->latest()->get()->map(fn($model) => JustificacionMapper::toEntity($model));
    }

    public function save(JustificacionEntity $justificacion): JustificacionEntity
    {
        $model = $justificacion->id 
            ? JustificacionModel::findOrFail($justificacion->id)
            : new JustificacionModel();
            
        $model = JustificacionMapper::toModel($justificacion, $model);
        $model->save();
        
        return JustificacionMapper::toEntity($model);
    }

    public function delete(int $id): bool
    {
        return JustificacionModel::destroy($id) > 0;
    }

    public function updateEstado(int $id, string $estado): bool
    {
        $model = JustificacionModel::findOrFail($id);
        $model->estado = $estado;
        return $model->save();
    }

    public function findAll(array $filters = []): Collection
    {
        $query = JustificacionModel::query();
        
        if (isset($filters['estado'])) {
            $query->where('estado', $filters['estado']);
        }
        
        return $query->latest()->get()->map(fn($model) => JustificacionMapper::toEntity($model));
    }
}

