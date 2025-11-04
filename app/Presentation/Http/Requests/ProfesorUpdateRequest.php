<?php

namespace App\Presentation\Http\Requests;

use App\Infrastructure\Persistence\Eloquent\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Form Request: ProfesorUpdateRequest
 * Capa: Presentation
 * Valida los datos de actualización de profesores
 */
class ProfesorUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $profesor = $this->route('profesor');
        $userId = $profesor->user_id ?? null;

        return [
            'nombre' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'clase_grupo.*.grupo' => 'nullable|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'clase_grupo.*.grupo.integer' => 'El grupo debe ser un número válido.',
            'clase_grupo.*.grupo.min' => 'El grupo debe ser mayor a 0.',
        ];
    }
}

