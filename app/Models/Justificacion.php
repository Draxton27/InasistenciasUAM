<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\ClaseProfesor;
use App\Models\Reprogramacion;
use App\Models\Rechazo;
use App\States\Justificacion\RegistradaState;
use App\States\Justificacion\EnRevisionState;
use App\States\Justificacion\AceptadaState;
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

    /* =========================
       RELACIONES
    ============================*/
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

    public function rechazo()
    {
        return $this->hasMany(Rechazo::class)->latest();
    }

    /* =========================
       GESTIÓN DE ESTADOS
    ============================*/
    public function setEstado(string $estado)
    {
        $this->estado = $estado;
        $this->save();
    }

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

public function aprobar()
{
    $this->state()->aprobar($this);
}

public function rechazar(array $data)
{
    $this->state()->rechazar($this, $data);
}

}
