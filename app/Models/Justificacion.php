<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\States\Justificacion\PendienteState;
use App\States\Justificacion\AprobadaState;
use App\States\Justificacion\RechazadaState;

class Justificacion extends Model
{
    use HasFactory;

    protected $table = 'justificaciones';

    protected $fillable = [
        'user_id',
        'clase_afectada',
        'clase_profesor_id',
        'profesor_id',
        'fecha',
        'tipo_constancia',
        'notas_adicionales',
        'archivo',
        'estado',
    ];

    //relaciones con otros modelos
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function claseProfesor()
    {
        return $this->belongsTo(ClaseProfesor::class, 'clase_profesor_id');
    }

    public function reprogramacion()
    {
        return $this->hasOne(Reprogramacion::class);
    }

    public function rechazos()
{
    return $this->hasMany(\App\Models\Rechazo::class)
                ->latest(); 
}

    //Gestión de estados
    public function setEstado($estado)
    {
        $this->estado = $estado;
        $this->save();
    }

    

    //Retorna la clase de estado correspondiente según el valor actual. 
    public function state()
    {
        return match ($this->estado) {
            'registrada' => new \App\States\Justificacion\RegistradaState(),
            'en_revision' => new \App\States\Justificacion\EnRevisionState(),
            'aceptada' => new \App\States\Justificacion\AceptadaState(),
            'rechazada' => new \App\States\Justificacion\RechazadaState(),
            default => new \App\States\Justificacion\RegistradaState(),
        };
    }
}
