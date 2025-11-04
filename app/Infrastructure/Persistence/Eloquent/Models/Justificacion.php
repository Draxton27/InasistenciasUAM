<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Infrastructure\Persistence\Eloquent\Models\User;
use App\Infrastructure\Persistence\Eloquent\Models\ClaseProfesor;
use App\Infrastructure\Persistence\Eloquent\Models\Reprogramacion;
use App\Infrastructure\Persistence\Eloquent\Models\Rechazo;

/**
 * Modelo Eloquent: Justificacion
 * Capa: Infrastructure
 * Representa la persistencia de justificaciones usando Eloquent ORM
 */
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

    // Relaciones con otros modelos
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
        return $this->hasOne(Rechazo::class);
    }

    // Gestión de estados (mantenido por compatibilidad, pero la lógica de estado está en Domain)
    public function setEstado($estado)
    {
        $this->estado = $estado;
        $this->save();
    }

    /**
     * Retorna la clase de estado correspondiente según el valor actual.
     * Nota: Esta función ahora apunta a los estados en Domain
     */
    public function state()
    {
        return match ($this->estado) {
            'registrada' => new \App\Domain\Justificacion\States\RegistradaState(),
            'en_revision' => new \App\Domain\Justificacion\States\EnRevisionState(),
            'aceptada' => new \App\Domain\Justificacion\States\AceptadaState(),
            'rechazada' => new \App\Domain\Justificacion\States\RechazadaState(),
            default => new \App\Domain\Justificacion\States\RegistradaState(),
        };
    }
}

