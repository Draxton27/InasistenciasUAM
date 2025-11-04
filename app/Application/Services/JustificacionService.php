<?php

namespace App\Application\Services;

use App\Application\DTOs\JustificacionDTO;
use App\Domain\Repositories\JustificacionRepositoryInterface;
use App\Domain\Entities\Justificacion as JustificacionEntity;
use App\Domain\Justificacion\States\JustificacionState;
use App\Domain\Justificacion\States\RegistradaState;
use App\Domain\Justificacion\States\EnRevisionState;
use App\Domain\Justificacion\States\AceptadaState;
use App\Domain\Justificacion\States\RechazadaState;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use App\Infrastructure\Persistence\Eloquent\Models\Rechazo as RechazoModel;

/**
 * Servicio de Aplicación: JustificacionService
 * Capa: Application
 * Orquesta la lógica de negocio y coordina entre Domain y Infrastructure
 */
class JustificacionService
{
    public function __construct(
        private JustificacionRepositoryInterface $repository
    ) {}

    /**
     * Obtiene el estado correspondiente para una justificación
     */
    private function getStateForEstado(string $estado): JustificacionState
    {
        return match ($estado) {
            'registrada' => new RegistradaState(),
            'en_revision' => new EnRevisionState(),
            'aceptada' => new AceptadaState(),
            'rechazada' => new RechazadaState(),
            default => new RegistradaState(),
        };
    }

    /**
     * Convierte DTO a entidad de dominio
     */
    private function dtoToEntity(JustificacionDTO $dto): JustificacionEntity
    {
        return new JustificacionEntity(
            id: $dto->id,
            userId: $dto->userId,
            claseAfectada: $dto->claseAfectada,
            claseProfesorId: $dto->claseProfesorId,
            profesorId: $dto->profesorId,
            fecha: $dto->fecha,
            tipoConstancia: $dto->tipoConstancia,
            notasAdicionales: $dto->notasAdicionales,
            archivo: $dto->archivo,
            estado: $dto->estado ?? 'registrada',
        );
    }

    /**
     * Convierte entidad de dominio a DTO
     */
    private function entityToDto(JustificacionEntity $entity): JustificacionDTO
    {
        return new JustificacionDTO(
            id: $entity->id,
            userId: $entity->userId,
            claseAfectada: $entity->claseAfectada,
            claseProfesorId: $entity->claseProfesorId,
            profesorId: $entity->profesorId,
            fecha: $entity->fecha,
            tipoConstancia: $entity->tipoConstancia,
            notasAdicionales: $entity->notasAdicionales,
            archivo: $entity->archivo,
            estado: $entity->estado,
        );
    }

    public function findById(int $id): ?JustificacionDTO
    {
        $entity = $this->repository->findById($id);
        return $entity ? $this->entityToDto($entity) : null;
    }

    public function findByUserId(int $userId, ?string $estado = null): Collection
    {
        $entities = $this->repository->findByUserId($userId, $estado);
        return $entities->map(fn($entity) => $this->entityToDto($entity));
    }

    public function findAll(array $filters = []): Collection
    {
        $entities = $this->repository->findAll($filters);
        return $entities->map(fn($entity) => $this->entityToDto($entity));
    }

    public function create(JustificacionDTO $dto): JustificacionDTO
    {
        $entity = $this->dtoToEntity($dto);
        $savedEntity = $this->repository->save($entity);
        return $this->entityToDto($savedEntity);
    }

    public function update(JustificacionDTO $dto): JustificacionDTO
    {
        $entity = $this->dtoToEntity($dto);
        $updatedEntity = $this->repository->save($entity);
        return $this->entityToDto($updatedEntity);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }

    public function cambiarEstado(int $id, string $nuevoEstado): void
    {
        $entity = $this->repository->findById($id);
        if (!$entity) {
            throw new \Exception("Justificación no encontrada");
        }

        $state = $this->getStateForEstado($entity->estado);
        
        match($nuevoEstado) {
            'en_revision' => $state->revisar($entity),
            'aceptada' => $state->aceptar($entity),
            'rechazada' => $state->rechazar($entity),
            default => throw new \Exception("Estado no válido: {$nuevoEstado}"),
        };

        $this->repository->save($entity);
    }

    public function aceptar(int $id): JustificacionEntity
    {
        $entity = $this->repository->findById($id);
        if (!$entity) {
            throw new \Exception("Justificación no encontrada");
        }

        $this->cambiarEstado($id, 'aceptada');
        
        // Retornar la entidad actualizada
        return $this->repository->findById($id);
    }

    public function rechazar(int $id, ?string $comentario = null): JustificacionEntity
    {
        $entity = $this->repository->findById($id);
        if (!$entity) {
            throw new \Exception("Justificación no encontrada");
        }

        $state = $this->getStateForEstado($entity->estado);
        $state->rechazar($entity, $comentario);
        
        $this->repository->save($entity);

        // Persistir el rechazo en la base de datos
        if ($comentario) {
            RechazoModel::create([
                'justificacion_id' => $id,
                'comentario' => $comentario,
            ]);
        }
        
        // Retornar la entidad actualizada
        return $this->repository->findById($id);
    }
    
    /**
     * Obtiene una entidad de dominio por ID (sin convertir a DTO)
     * Útil para casos especiales como el Observer pattern
     */
    public function findEntityById(int $id): ?JustificacionEntity
    {
        return $this->repository->findById($id);
    }

    public function eliminarArchivo(?string $archivo): bool
    {
        if ($archivo && Storage::disk('public')->exists($archivo)) {
            return Storage::disk('public')->delete($archivo);
        }
        return false;
    }
}

