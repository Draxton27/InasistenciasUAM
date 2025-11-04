<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use App\Infrastructure\Persistence\Eloquent\Models\Clase;
use App\Infrastructure\Persistence\Eloquent\Models\Profesor;
use App\Infrastructure\Persistence\Eloquent\Models\Justificacion;

/**
 * Modelo Eloquent: ClaseProfesor
 * Capa: Infrastructure
 * Representa la persistencia de la relación clase-profesor usando Eloquent ORM
 */
class ClaseProfesor extends Model
{
    protected $table = 'clase_profesor';

    protected $fillable = [
        'clase_id',
        'profesor_id',
        'grupo',
    ];

    public function clase()
    {
        return $this->belongsTo(Clase::class);
    }

    public function profesor()
    {
        return $this->belongsTo(Profesor::class);
    }

    public function justificaciones()
    {
        return $this->hasMany(Justificacion::class);
    }
}

