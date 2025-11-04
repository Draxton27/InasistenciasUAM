<?php

namespace App\Infrastructure\Persistence\Eloquent\Mappers;

use App\Domain\Entities\Clase as ClaseEntity;
use App\Infrastructure\Persistence\Eloquent\Models\Clase as ClaseModel;

/**
 * Mapper: ClaseMapper
 * Capa: Infrastructure
 * Convierte entre modelos Eloquent y entidades de dominio para clases
 */
class ClaseMapper
{
    public static function toEntity(ClaseModel $model): ClaseEntity
    {
        return new ClaseEntity(
            id: $model->id,
            name: $model->name,
            note: $model->note,
        );
    }

    public static function toModel(ClaseEntity $entity, ?ClaseModel $model = null): ClaseModel
    {
        $model = $model ?? new ClaseModel();
        
        if ($entity->id) {
            $model->id = $entity->id;
        }
        
        $model->name = $entity->name;
        $model->note = $entity->note;
        
        return $model;
    }
}

