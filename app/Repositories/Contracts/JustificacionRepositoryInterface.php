<?php

namespace App\Repositories\Contracts;

use App\Models\Justificacion;
use Illuminate\Database\Eloquent\Collection;

interface JustificacionRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?Justificacion;
    public function create(array $data): Justificacion;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function getByUser(int $userId): Collection;
    public function getByUserAndState(int $userId, ?string $estado): Collection;
    public function findForUser(int $id, int $userId): ?Justificacion;
}
