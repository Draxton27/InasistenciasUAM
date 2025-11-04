<?php

namespace App\Application\Services;

use App\Application\DTOs\ProfesorDTO;
use App\Domain\Repositories\ProfesorRepositoryInterface;
use App\Domain\Entities\Profesor as ProfesorEntity;
use App\Infrastructure\Persistence\Eloquent\Models\User as UserModel;
use App\Infrastructure\Persistence\Eloquent\Models\Clase as ClaseModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

/**
 * Servicio de Aplicación: ProfesorService
 * Capa: Application
 * Orquesta la lógica de negocio relacionada con profesores
 */
class ProfesorService
{
    public function __construct(
        private ProfesorRepositoryInterface $repository
    ) {}

    private function dtoToEntity(ProfesorDTO $dto): ProfesorEntity
    {
        return new ProfesorEntity(
            id: $dto->id,
            userId: $dto->userId,
            nombre: $dto->nombre,
            email: $dto->email,
            foto: $dto->foto,
        );
    }

    private function entityToDto(ProfesorEntity $entity): ProfesorDTO
    {
        return new ProfesorDTO(
            id: $entity->id,
            userId: $entity->userId,
            nombre: $entity->nombre,
            email: $entity->email,
            foto: $entity->foto,
        );
    }

    public function findById(int $id): ?ProfesorDTO
    {
        $entity = $this->repository->findById($id);
        return $entity ? $this->entityToDto($entity) : null;
    }

    public function findByUserId(int $userId): ?ProfesorDTO
    {
        $entity = $this->repository->findByUserId($userId);
        return $entity ? $this->entityToDto($entity) : null;
    }

    public function findAll(): Collection
    {
        $entities = $this->repository->findAll();
        return $entities->map(fn($entity) => $this->entityToDto($entity));
    }

    public function create(ProfesorDTO $dto): ProfesorDTO
    {
        // Validar que clase + grupo no estén duplicados
        if ($dto->clases) {
            $this->validarClasesGrupos($dto->clases);
        }

        // Crear usuario primero
        $user = UserModel::create([
            'name' => $dto->nombre,
            'email' => $dto->email,
            'password' => Hash::make($dto->password),
            'role' => 'profesor',
        ]);

        $dto->userId = $user->id;
        
        // Crear profesor
        $entity = $this->dtoToEntity($dto);
        $savedEntity = $this->repository->save($entity);
        $profesorDto = $this->entityToDto($savedEntity);

        // Asignar clases
        if ($dto->clases) {
            foreach ($dto->clases as $claseGrupo) {
                if (!empty($claseGrupo['clase_id'])) {
                    $this->repository->attachClase(
                        $savedEntity->id,
                        $claseGrupo['clase_id'],
                        $claseGrupo['grupo'] ?? null
                    );
                }
            }
        }

        return $profesorDto;
    }

    public function update(ProfesorDTO $dto): ProfesorDTO
    {
        if ($dto->clases) {
            $this->validarClasesGrupos($dto->clases, $dto->id);
        }

        // Actualizar usuario si existe
        if ($dto->userId) {
            $user = UserModel::find($dto->userId);
            if ($user) {
                $user->update([
                    'name' => $dto->nombre,
                    'email' => $dto->email,
                ]);
            }
        }

        // Actualizar profesor
        $entity = $this->dtoToEntity($dto);
        $savedEntity = $this->repository->save($entity);
        $profesorDto = $this->entityToDto($savedEntity);

        // Actualizar clases
        if ($dto->clases !== null) {
            $this->repository->detachAllClases($savedEntity->id);
            foreach ($dto->clases as $claseGrupo) {
                if (!empty($claseGrupo['clase_id'])) {
                    $this->repository->attachClase(
                        $savedEntity->id,
                        $claseGrupo['clase_id'],
                        $claseGrupo['grupo'] ?? null
                    );
                }
            }
        }

        return $profesorDto;
    }

    public function delete(int $id): bool
    {
        $profesor = $this->repository->findById($id);
        if (!$profesor) {
            return false;
        }

        // Eliminar relaciones
        $this->repository->detachAllClases($id);

        // Eliminar profesor
        $this->repository->delete($id);

        // Eliminar usuario si existe
        if ($profesor->userId) {
            UserModel::destroy($profesor->userId);
        }

        return true;
    }

    /**
     * Valida que no haya duplicados de clase+grupo
     */
    private function validarClasesGrupos(array $clasesGrupos, ?int $excludeProfesorId = null): void
    {
        $combinaciones = [];
        foreach ($clasesGrupos as $entry) {
            $clase = $entry['clase_id'] ?? null;
            $grupo = $entry['grupo'] ?? null;

            if ($clase && $grupo) {
                $clave = $clase . '-' . $grupo;
                if (in_array($clave, $combinaciones)) {
                    throw new \Exception("Ya se asignó este grupo a esta clase en el formulario.");
                }
                $combinaciones[] = $clave;

                // Verificar en base de datos
                $existe = DB::table('clase_profesor')
                    ->where('clase_id', $clase)
                    ->where('grupo', $grupo);
                    
                if ($excludeProfesorId) {
                    $existe->where('profesor_id', '!=', $excludeProfesorId);
                }
                
                if ($existe->exists()) {
                    throw new \Exception("Este grupo ya está asignado a otro profesor para esta clase.");
                }
            }
        }
    }
}

