<?php

namespace App\Presentation\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ProfesorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->role === 'profesor') {
            return $next($request);
        }

        abort(403); // No autorizado
    }
}
