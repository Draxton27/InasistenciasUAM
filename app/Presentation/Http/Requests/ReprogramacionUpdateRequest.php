<?php

namespace App\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

/**
 * Form Request: ReprogramacionUpdateRequest
 * Capa: Presentation
 * Valida los datos de actualización de reprogramaciones
 */
class ReprogramacionUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fecha_reprogramada' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $fecha = Carbon::parse($value, config('app.timezone'));
                    if ($fecha->lte(Carbon::now(config('app.timezone')))) {
                        $fail('La fecha y hora deben ser futuras.');
                    }
                },
            ],
            'aula' => 'nullable|string|max:100',
        ];
    }
}

