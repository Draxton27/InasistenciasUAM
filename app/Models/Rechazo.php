<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
