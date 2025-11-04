<?php

namespace App\Infrastructure\Persistence\Eloquent\Mappers;

use App\Domain\Entities\Estudiante as EstudianteEntity;
use App\Infrastructure\Persistence\Eloquent\Models\Estudiante as EstudianteModel;

/**
 * Mapper: EstudianteMapper
 * Capa: Infrastructure
 * Convierte entre modelos Eloquent y entidades de dominio para estudiantes
 */
class EstudianteMapper
{
    public static function toEntity(EstudianteModel $model): EstudianteEntity
    {
        return new EstudianteEntity(
            id: $model->id,
            userId: $model->user_id,
            cif: $model->cif,
            nombre: $model->nombre,
            apellido: $model->apellido,
            email: $model->email,
            foto: $model->foto,
        );
    }

    public static function toModel(EstudianteEntity $entity, ?EstudianteModel $model = null): EstudianteModel
    {
        $model = $model ?? new EstudianteModel();
        
        if ($entity->id) {
            $model->id = $entity->id;
        }
        
        $model->user_id = $entity->userId;
        $model->cif = $entity->cif;
        $model->nombre = $entity->nombre;
        $model->apellido = $entity->apellido;
        $model->email = $entity->email;
        $model->foto = $entity->foto;
        
        return $model;
    }
}

