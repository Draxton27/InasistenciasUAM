<?php

namespace App\Application\Services;

use App\Application\DTOs\ReprogramacionDTO;
use App\Domain\Repositories\ReprogramacionRepositoryInterface;
use App\Domain\Repositories\JustificacionRepositoryInterface;
use App\Domain\Entities\Reprogramacion as ReprogramacionEntity;
use Carbon\Carbon;

/**
 * Servicio de Aplicación: ReprogramacionService
 * Capa: Application
 * Orquesta la lógica de negocio relacionada con reprogramaciones
 */
class ReprogramacionService
{
    public function __construct(
        private ReprogramacionRepositoryInterface $repository,
        private JustificacionRepositoryInterface $justificacionRepository
    ) {}

    private function dtoToEntity(ReprogramacionDTO $dto): ReprogramacionEntity
    {
        return new ReprogramacionEntity(
            id: $dto->id,
            justificacionId: $dto->justificacionId,
            fechaReprogramada: $dto->fechaReprogramada,
            aula: $dto->aula,
        );
    }

    private function entityToDto(ReprogramacionEntity $entity): ReprogramacionDTO
    {
        return new ReprogramacionDTO(
            id: $entity->id,
            justificacionId: $entity->justificacionId,
            fechaReprogramada: $entity->fechaReprogramada,
            aula: $entity->aula,
        );
    }

    public function findById(int $id): ?ReprogramacionDTO
    {
        $entity = $this->repository->findById($id);
        return $entity ? $this->entityToDto($entity) : null;
    }

    public function findByJustificacionId(int $justificacionId): ?ReprogramacionDTO
    {
        $entity = $this->repository->findByJustificacionId($justificacionId);
        return $entity ? $this->entityToDto($entity) : null;
    }

    public function create(ReprogramacionDTO $dto): ReprogramacionDTO
    {
        // Validar que la justificación existe y está aceptada
        $justificacion = $this->justificacionRepository->findById($dto->justificacionId);
        if (!$justificacion) {
            throw new \Exception('La justificación no existe.');
        }

        if ($justificacion->estado !== 'aceptada') {
            throw new \Exception('Solo se pueden crear reprogramaciones para justificaciones aceptadas.');
        }

        // Validar que no existe otra reprogramación para esta justificación
        $existente = $this->repository->findByJustificacionId($dto->justificacionId);
        if ($existente) {
            throw new \Exception('Ya existe una reprogramación para esta justificación.');
        }

        // Validar que la fecha sea futura
        $entidad = $this->dtoToEntity($dto);
        if (!$entidad->esFechaFutura()) {
            throw new \Exception('La fecha y hora deben ser futuras.');
        }

        $savedEntity = $this->repository->save($entidad);
        return $this->entityToDto($savedEntity);
    }

    public function update(ReprogramacionDTO $dto): ReprogramacionDTO
    {
        if (!$dto->id) {
            throw new \Exception('El ID de la reprogramación es requerido para actualizar.');
        }

        // Validar que la fecha sea futura
        $entidad = $this->dtoToEntity($dto);
        if (!$entidad->esFechaFutura()) {
            throw new \Exception('La fecha y hora deben ser futuras.');
        }

        $data = [
            'fecha_reprogramada' => $dto->fechaReprogramada,
            'aula' => $dto->aula,
        ];

        $this->repository->update($dto->id, $data);

        $updatedEntity = $this->repository->findById($dto->id);
        if (!$updatedEntity) {
            throw new \Exception('No se pudo encontrar la reprogramación después de la actualización.');
        }

        return $this->entityToDto($updatedEntity);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }

    /**
     * Elimina reprogramaciones vencidas (usado por comando)
     */
    public function eliminarVencidas(): int
    {
        $reprogramaciones = $this->repository->findAll();
        $eliminadas = 0;

        foreach ($reprogramaciones as $reprogramacion) {
            if ($reprogramacion->fechaReprogramada) {
                $fecha = Carbon::parse($reprogramacion->fechaReprogramada);
                if ($fecha->isPast()) {
                    $this->repository->delete($reprogramacion->id);
                    $eliminadas++;
                }
            }
        }

        return $eliminadas;
    }
}

