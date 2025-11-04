<?php

namespace App\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request: ProfesorStoreRequest
 * Capa: Presentation
 * Valida los datos de creación de profesores
 */
class ProfesorStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'clase_grupo.*.grupo' => 'nullable|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'clase_grupo.*.grupo.integer' => 'El grupo debe ser un número válido.',
            'clase_grupo.*.grupo.min' => 'El grupo debe ser mayor a 0.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        ];
    }
}

