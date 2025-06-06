<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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