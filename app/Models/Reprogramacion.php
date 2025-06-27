<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
