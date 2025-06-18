<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * Middleware global de la aplicación.
     */
    protected $middleware = [
        // Aquí van tus middleware globales si tienes alguno
    ];

    /**
     * Middleware por grupos (web y api).
     */
    protected $middlewareGroups = [
        'web' => [
            // Tus middleware actuales (puede incluir EncryptCookies, StartSession, etc.)
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,

            // Middleware necesario para que $errors funcione en las vistas
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,

            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            // Tus middleware de API si usas
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * Middleware individuales de rutas.
     */
    protected $routeMiddleware = [
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
    ];
}
