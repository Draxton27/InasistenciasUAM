<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use App\Infrastructure\Persistence\Eloquent\Models\Justificacion;

/**
 * Modelo Eloquent: Rechazo
 * Capa: Infrastructure
 * Representa la persistencia de rechazos usando Eloquent ORM
 */
class Rechazo extends Model
{
    protected $table = 'rechazos';

    protected $fillable = [
        'justificacion_id',
        'comentario',
    ];

    public function justificacion()
    {
        return $this->belongsTo(Justificacion::class);
    }
}

