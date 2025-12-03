<?php

namespace App\Repositories;

use App\Models\Justificacion;
use App\Repositories\Contracts\JustificacionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class JustificacionRepository implements JustificacionRepositoryInterface
{
    public function all(): Collection
    {
        return Justificacion::all();
    }

    public function find(int $id): ?Justificacion
    {
        return Justificacion::find($id);
    }

    public function create(array $data): Justificacion
    {
        return Justificacion::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $justificacion = $this->find($id);
        if (!$justificacion) {
            return false;
        }
        return $justificacion->update($data);
    }

    public function delete(int $id): bool
    {
        $justificacion = $this->find($id);
        if (!$justificacion) {
            return false;
        }
        return $justificacion->delete();
    }

    public function getByUser(int $userId): Collection
    {
        return Justificacion::where('user_id', $userId)->latest()->get();
    }

    public function getByUserAndState(int $userId, ?string $estado): Collection
    {
        $query = Justificacion::where('user_id', $userId)->latest();

        if ($estado) {
            $query->where('estado', $estado);
        }

        return $query->get();
    }

    public function findForUser(int $id, int $userId): ?Justificacion
    {
        return Justificacion::where('id', $id)->where('user_id', $userId)->first();
    }
}
