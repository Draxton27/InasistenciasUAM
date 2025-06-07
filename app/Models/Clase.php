<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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