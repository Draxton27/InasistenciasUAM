<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Infrastructure\Persistence\Eloquent\Models\User;
use App\Infrastructure\Persistence\Eloquent\Models\Clase;

/**
 * Modelo Eloquent: Profesor
 * Capa: Infrastructure
 * Representa la persistencia de profesores usando Eloquent ORM
 */
class Profesor extends Model
{
    use HasFactory;

    protected $table = 'profesores';

    protected $fillable = [
        'user_id',
        'nombre',
        'email',
        'foto',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function clases()
    {
        return $this->belongsToMany(Clase::class, 'clase_profesor')
                    ->withPivot('grupo')
                    ->withTimestamps();
    }
}

