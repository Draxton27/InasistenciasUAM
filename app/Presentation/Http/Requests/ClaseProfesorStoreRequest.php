<?php

namespace App\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request: ClaseProfesorStoreRequest
 * Capa: Presentation
 * Valida los datos de asignación de profesores a clases
 */
class ClaseProfesorStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'clase_id' => 'required|exists:clases,id',
            'profesor_id' => 'required|exists:profesores,id',
            'grupo' => 'required|integer|min:1',
        ];
    }
}

