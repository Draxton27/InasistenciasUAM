<?php

namespace App\Infrastructure\Persistence\Eloquent\Mappers;

use App\Domain\Entities\User as UserEntity;
use App\Infrastructure\Persistence\Eloquent\Models\User as UserModel;

/**
 * Mapper: UserMapper
 * Capa: Infrastructure
 * Convierte entre modelos Eloquent y entidades de dominio para usuarios
 */
class UserMapper
{
    public static function toEntity(UserModel $model): UserEntity
    {
        return new UserEntity(
            id: $model->id,
            name: $model->name,
            email: $model->email,
            role: $model->role,
        );
    }

    public static function toModel(UserEntity $entity, ?UserModel $model = null): UserModel
    {
        $model = $model ?? new UserModel();
        
        if ($entity->id) {
            $model->id = $entity->id;
        }
        
        $model->name = $entity->name;
        $model->email = $entity->email;
        $model->role = $entity->role;
        
        return $model;
    }
}

