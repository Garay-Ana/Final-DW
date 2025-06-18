<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\ViewErrorBag;

class ShareErrorsFromSession
{
    public function handle($request, Closure $next)
    {
        // Compartir errores con todas las vistas
        view()->share(
            'errors',
            $request->session()->get('errors') ?: new ViewErrorBag
        );

        return $next($request);
    }
}
