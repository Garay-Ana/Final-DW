<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FixAuthRequestMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Verificar si auth() recibe un objeto Request y corregirlo
        // Como no podemos modificar directamente auth(), interceptamos la llamada
        // y evitamos pasar el objeto Request como argumento.

        // No hacemos nada aquí, solo dejamos pasar la solicitud.
        // Este middleware es un placeholder para futuras correcciones si se detecta el problema.

        return $next($request);
    }
}
