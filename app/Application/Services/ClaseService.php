<?php

namespace App\Application\Services;

use App\Application\DTOs\ClaseDTO;
use App\Domain\Entities\Clase as ClaseEntity;
use App\Domain\Repositories\ClaseRepositoryInterface;
use Illuminate\Support\Collection;

/**
 * Servicio de Aplicación: ClaseService
 * Capa: Application
 * Orquesta la lógica de negocio relacionada con clases
 */
class ClaseService
{
    public function __construct(
        private ClaseRepositoryInterface $repository
    ) {}

    private function dtoToEntity(ClaseDTO $dto): ClaseEntity
    {
        return new ClaseEntity(
            id: $dto->id,
            name: $dto->name,
            note: $dto->note,
        );
    }

    private function entityToDto(ClaseEntity $entity): ClaseDTO
    {
        return new ClaseDTO(
            id: $entity->id,
            name: $entity->name,
            note: $entity->note,
        );
    }

    public function findById(int $id): ?ClaseDTO
    {
        $entity = $this->repository->findById($id);

        return $entity ? $this->entityToDto($entity) : null;
    }

    public function findAll(): Collection
    {
        $entities = $this->repository->findAll();

        return $entities->map(fn ($entity) => $this->entityToDto($entity));
    }

    public function create(ClaseDTO $dto): ClaseDTO
    {
        // Validar grupos duplicados
        if ($dto->profesores) {
            $this->validarGrupos($dto->profesores);
        }

        // Crear clase
        $entity = $this->dtoToEntity($dto);
        $savedEntity = $this->repository->save($entity);
        $claseDto = $this->entityToDto($savedEntity);

        // Asignar profesores
        if ($dto->profesores) {
            foreach ($dto->profesores as $profesorGrupo) {
                if (! empty($profesorGrupo['profesor_id'])) {
                    $this->repository->attachProfesor(
                        $savedEntity->id,
                        $profesorGrupo['profesor_id'],
                        $profesorGrupo['grupo'] ?? null
                    );
                }
            }
        }

        return $claseDto;
    }

    public function update(ClaseDTO $dto): ClaseDTO
    {
        // Validar grupos duplicados
        if ($dto->profesores) {
            $this->validarGrupos($dto->profesores);
        }

        // Actualizar clase
        $entity = $this->dtoToEntity($dto);
        $savedEntity = $this->repository->save($entity);
        $claseDto = $this->entityToDto($savedEntity);

        // Actualizar profesores
        if ($dto->profesores !== null) {
            $this->repository->detachAllProfesores($savedEntity->id);
            foreach ($dto->profesores as $profesorGrupo) {
                if (! empty($profesorGrupo['profesor_id'])) {
                    $this->repository->attachProfesor(
                        $savedEntity->id,
                        $profesorGrupo['profesor_id'],
                        $profesorGrupo['grupo'] ?? null
                    );
                }
            }
        }

        return $claseDto;
    }

    public function delete(int $id): bool
    {
        $clase = $this->repository->findById($id);
        if (! $clase) {
            return false;
        }

        // Eliminar relaciones
        $this->repository->detachAllProfesores($id);

        // Eliminar clase
        return $this->repository->delete($id);
    }

    /**
     * Valida que no haya grupos duplicados
     */
    private function validarGrupos(array $profesoresGrupos): void
    {
        $gruposUsados = [];
        foreach ($profesoresGrupos as $entry) {
            $grupo = $entry['grupo'] ?? null;
            if (! empty($grupo)) {
                if (in_array($grupo, $gruposUsados)) {
                    throw new \Exception('Este grupo ya ha sido asignado a otro profesor.');
                }
                $gruposUsados[] = $grupo;
            }
        }
    }
}
