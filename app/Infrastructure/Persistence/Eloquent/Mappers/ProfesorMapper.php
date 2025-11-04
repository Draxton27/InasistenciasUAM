<?php

namespace App\Infrastructure\Persistence\Eloquent\Mappers;

use App\Domain\Entities\Profesor as ProfesorEntity;
use App\Infrastructure\Persistence\Eloquent\Models\Profesor as ProfesorModel;

/**
 * Mapper: ProfesorMapper
 * Capa: Infrastructure
 * Convierte entre modelos Eloquent y entidades de dominio para profesores
 */
class ProfesorMapper
{
    public static function toEntity(ProfesorModel $model): ProfesorEntity
    {
        return new ProfesorEntity(
            id: $model->id,
            userId: $model->user_id,
            nombre: $model->nombre,
            email: $model->email,
            foto: $model->foto,
        );
    }

    public static function toModel(ProfesorEntity $entity, ?ProfesorModel $model = null): ProfesorModel
    {
        $model = $model ?? new ProfesorModel();
        
        if ($entity->id) {
            $model->id = $entity->id;
        }
        
        $model->user_id = $entity->userId;
        $model->nombre = $entity->nombre;
        $model->email = $entity->email;
        $model->foto = $entity->foto;
        
        return $model;
    }
}

