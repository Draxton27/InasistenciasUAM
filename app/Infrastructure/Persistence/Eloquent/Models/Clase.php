<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Infrastructure\Persistence\Eloquent\Models\Profesor;

/**
 * Modelo Eloquent: Clase
 * Capa: Infrastructure
 * Representa la persistencia de clases usando Eloquent ORM
 */
class Clase extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'note'];

    public function profesores()
    {
        return $this->belongsToMany(Profesor::class, 'clase_profesor')
                    ->withPivot('grupo')
                    ->withTimestamps();
    }
}

