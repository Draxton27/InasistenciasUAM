<?php

namespace App\Infrastructure\Persistence\Eloquent\Mappers;

use App\Domain\Entities\Justificacion as JustificacionEntity;
use App\Infrastructure\Persistence\Eloquent\Models\Justificacion as JustificacionModel;

/**
 * Mapper: JustificacionMapper
 * Capa: Infrastructure
 * Convierte entre modelos Eloquent y entidades de dominio
 */
class JustificacionMapper
{
    public static function toEntity(JustificacionModel $model): JustificacionEntity
    {
        return new JustificacionEntity(
            id: $model->id,
            userId: $model->user_id,
            claseAfectada: $model->clase_afectada,
            claseProfesorId: $model->clase_profesor_id,
            profesorId: $model->profesor_id,
            fecha: $model->fecha,
            tipoConstancia: $model->tipo_constancia,
            notasAdicionales: $model->notas_adicionales,
            archivo: $model->archivo,
            estado: $model->estado,
        );
    }

    public static function toModel(JustificacionEntity $entity, ?JustificacionModel $model = null): JustificacionModel
    {
        $model = $model ?? new JustificacionModel();
        
        if ($entity->id) {
            $model->id = $entity->id;
        }
        
        $model->user_id = $entity->userId;
        $model->clase_afectada = $entity->claseAfectada;
        $model->clase_profesor_id = $entity->claseProfesorId;
        $model->profesor_id = $entity->profesorId;
        $model->fecha = $entity->fecha;
        $model->tipo_constancia = $entity->tipoConstancia;
        $model->notas_adicionales = $entity->notasAdicionales;
        $model->archivo = $entity->archivo;
        $model->estado = $entity->estado;
        
        return $model;
    }
}

