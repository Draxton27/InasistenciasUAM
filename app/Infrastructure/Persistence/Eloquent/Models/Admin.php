<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Infrastructure\Persistence\Eloquent\Models\User;

/**
 * Modelo Eloquent: Admin
 * Capa: Infrastructure
 * Representa la persistencia de administradores usando Eloquent ORM
 */
class Admin extends Model
{
    use HasFactory;

    protected $table = 'admins';

    protected $fillable = [
        'user_id',
        'nombre',
        'telefono',
        'foto',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

