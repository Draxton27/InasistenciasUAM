<?php

namespace App\Application\Services;

use App\Application\DTOs\EstudianteDTO;
use App\Domain\Repositories\EstudianteRepositoryInterface;
use App\Domain\Entities\Estudiante as EstudianteEntity;
use App\Infrastructure\Persistence\Eloquent\Models\User as UserModel;
use Illuminate\Support\Facades\Storage;

/**
 * Servicio de Aplicación: EstudianteService
 * Capa: Application
 * Orquesta la lógica de negocio relacionada con estudiantes
 */
class EstudianteService
{
    public function __construct(
        private EstudianteRepositoryInterface $repository
    ) {}

    private function dtoToEntity(EstudianteDTO $dto): EstudianteEntity
    {
        return new EstudianteEntity(
            id: $dto->id,
            userId: $dto->userId,
            cif: $dto->cif,
            nombre: $dto->nombre,
            apellido: $dto->apellido,
            email: $dto->email,
            foto: $dto->foto,
        );
    }

    private function entityToDto(EstudianteEntity $entity): EstudianteDTO
    {
        return new EstudianteDTO(
            id: $entity->id,
            userId: $entity->userId,
            cif: $entity->cif,
            nombre: $entity->nombre,
            apellido: $entity->apellido,
            email: $entity->email,
            foto: $entity->foto,
        );
    }

    public function findById(int $id): ?EstudianteDTO
    {
        $entity = $this->repository->findById($id);
        return $entity ? $this->entityToDto($entity) : null;
    }

    public function findByUserId(int $userId): ?EstudianteDTO
    {
        $entity = $this->repository->findByUserId($userId);
        return $entity ? $this->entityToDto($entity) : null;
    }

    public function update(EstudianteDTO $dto): EstudianteDTO
    {
        if (!$dto->id) {
            throw new \Exception('El ID del estudiante es requerido para actualizar.');
        }

        // Actualizar usuario si existe
        if ($dto->userId && $dto->email) {
            $user = UserModel::find($dto->userId);
            if ($user) {
                $user->update([
                    'name' => $dto->nombre,
                    'email' => $dto->email,
                ]);
            }
        }

        // Actualizar estudiante
        $data = [
            'nombre' => $dto->nombre,
            'apellido' => $dto->apellido,
            'cif' => $dto->cif,
            'email' => $dto->email,
            'foto' => $dto->foto,
        ];

        // Eliminar campos null para no sobrescribir valores existentes
        $data = array_filter($data, fn($value) => $value !== null);

        $this->repository->update($dto->id, $data);

        // Obtener entidad actualizada
        $updatedEntity = $this->repository->findById($dto->id);
        if (!$updatedEntity) {
            throw new \Exception('No se pudo encontrar el estudiante después de la actualización.');
        }

        return $this->entityToDto($updatedEntity);
    }

    public function deleteFoto(int $estudianteId): bool
    {
        $estudiante = $this->repository->findById($estudianteId);
        if (!$estudiante || !$estudiante->foto) {
            return false;
        }

        Storage::disk('public')->delete($estudiante->foto);

        $this->repository->update($estudianteId, ['foto' => null]);

        return true;
    }
}

