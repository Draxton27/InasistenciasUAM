<?php

namespace App\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request: AdminRejectRequest
 * Capa: Presentation
 * Valida los datos para rechazar una justificación
 */
class AdminRejectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'comentario' => 'required|string|max:1000',
        ];
    }
}

