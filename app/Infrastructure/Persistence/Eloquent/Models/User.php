<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Infrastructure\Persistence\Eloquent\Models\Justificacion;
use App\Infrastructure\Persistence\Eloquent\Models\Profesor;
use App\Infrastructure\Persistence\Eloquent\Models\Estudiante;

/**
 * Modelo Eloquent: User
 * Capa: Infrastructure
 * Representa la persistencia de usuarios usando Eloquent ORM
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function justificaciones()
    {
        return $this->hasMany(Justificacion::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function profesor()
    {
        return $this->hasOne(Profesor::class);
    }

    public function estudiante()
    {
        return $this->hasOne(Estudiante::class);
    }
}

