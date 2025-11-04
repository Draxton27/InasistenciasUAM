<?php

namespace App\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request: ClaseStoreRequest
 * Capa: Presentation
 * Valida los datos de creación de clases
 */
class ClaseStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'note' => 'nullable|string',
            'profesor_grupo' => 'nullable|array',
            'profesor_grupo.*.profesor_id' => 'nullable|exists:profesores,id',
            'profesor_grupo.*.grupo' => 'nullable|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'profesor_grupo.*.grupo.integer' => 'El grupo debe ser un número válido.',
            'profesor_grupo.*.grupo.min' => 'El grupo debe ser mayor a 0.',
        ];
    }
}

