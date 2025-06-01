<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profesor extends Model
{
    use HasFactory;

    protected $table = 'profesores';

    protected $fillable = [
        'user_id',
        'nombre',
        'email',
        'telefono',
        'especialidad',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'profesor_id');
    }
}
