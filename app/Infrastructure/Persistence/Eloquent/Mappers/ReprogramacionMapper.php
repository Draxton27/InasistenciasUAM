<?php

namespace App\Infrastructure\Persistence\Eloquent\Mappers;

use App\Domain\Entities\Reprogramacion as ReprogramacionEntity;
use App\Infrastructure\Persistence\Eloquent\Models\Reprogramacion as ReprogramacionModel;

/**
 * Mapper: ReprogramacionMapper
 * Capa: Infrastructure
 * Convierte entre modelos Eloquent y entidades de dominio para reprogramaciones
 */
class ReprogramacionMapper
{
    public static function toEntity(ReprogramacionModel $model): ReprogramacionEntity
    {
        return new ReprogramacionEntity(
            id: $model->id,
            justificacionId: $model->justificacion_id,
            fechaReprogramada: $model->fecha_reprogramada,
            aula: $model->aula,
        );
    }

    public static function toModel(ReprogramacionEntity $entity, ?ReprogramacionModel $model = null): ReprogramacionModel
    {
        $model = $model ?? new ReprogramacionModel();
        
        if ($entity->id) {
            $model->id = $entity->id;
        }
        
        $model->justificacion_id = $entity->justificacionId;
        $model->fecha_reprogramada = $entity->fechaReprogramada;
        $model->aula = $entity->aula;
        
        return $model;
    }
}

