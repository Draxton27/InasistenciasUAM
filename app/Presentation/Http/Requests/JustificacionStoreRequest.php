<?php

namespace App\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request: JustificacionStoreRequest
 * Capa: Presentation
 * Valida los datos de creación de justificaciones
 */
class JustificacionStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'justificaciones' => 'required|array|min:1',
            'justificaciones.*.clase_profesor_id' => 'required|exists:clase_profesor,id',
            'justificaciones.*.fecha' => 'required|date',
            'tipo_constancia' => 'required|in:trabajo,enfermedad,otro',
            'notas_adicionales' => 'nullable|string',
            'archivo' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];
    }
}

