<?php

namespace App\Presentation\Http\Requests;

use App\Infrastructure\Persistence\Eloquent\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * Form Request: EstudianteUpdateProfileRequest
 * Capa: Presentation
 * Valida los datos de actualización del perfil de estudiante
 */
class EstudianteUpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $estudiante = Auth::user()->estudiante;

        return [
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'cif' => [
                'required',
                'string',
                'max:20',
                Rule::unique('estudiantes', 'cif')->ignore($estudiante->id ?? null),
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore(Auth::id()),
            ],
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'eliminar_foto' => 'nullable|boolean',
        ];
    }
}

