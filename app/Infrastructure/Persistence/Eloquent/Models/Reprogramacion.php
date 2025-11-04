<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use App\Infrastructure\Persistence\Eloquent\Models\Justificacion;

/**
 * Modelo Eloquent: Reprogramacion
 * Capa: Infrastructure
 * Representa la persistencia de reprogramaciones usando Eloquent ORM
 */
class Reprogramacion extends Model
{
    protected $table = 'reprogramaciones';
    
    protected $fillable = [
        'justificacion_id',
        'fecha_reprogramada',
        'aula',
    ];

    public function justificacion()
    {
        return $this->belongsTo(Justificacion::class);
    }
}

