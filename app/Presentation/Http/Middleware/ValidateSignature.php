<?php

namespace App\Presentation\Http\Middleware;

use Illuminate\Routing\Middleware\ValidateSignature as Middleware;

/**
 * Middleware: ValidateSignature
 * Capa: Presentation
 * Valida firmas de URLs firmadas
 */
class ValidateSignature extends Middleware
{
    /**
     * Los nombres de los parámetros de consulta que deben ser ignorados.
     *
     * @var array<int, string>
     */
    protected $except = [
        // 'fbclid',
        // 'utm_campaign',
        // 'utm_content',
        // 'utm_medium',
        // 'utm_source',
        // 'utm_term',
    ];
}

