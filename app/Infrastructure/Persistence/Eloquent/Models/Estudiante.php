<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Infrastructure\Persistence\Eloquent\Models\User;

/**
 * Modelo Eloquent: Estudiante
 * Capa: Infrastructure
 * Representa la persistencia de estudiantes usando Eloquent ORM
 */
class Estudiante extends Model
{
    use HasFactory;

    protected $table = 'estudiantes';

    protected $fillable = [
        'user_id',
        'cif',
        'nombre',
        'apellido',
        'email',
        'foto',
    ];

    // Relación con User (autenticación)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

