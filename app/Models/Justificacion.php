<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Justificacion extends Model
{
    use HasFactory;

    protected $table = 'justificaciones';

    protected $fillable = [
        'user_id',
        'clase_afectada',
        'profesor_id',
        'fecha',
        'tipo_constancia',
        'notas_adicionales',
        'archivo',
        'estado',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
