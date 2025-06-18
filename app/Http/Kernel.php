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
        \App\Http\Middleware\StartSession::class,      // Nuevo
        \App\Http\Middleware\ShareErrorsFromSession::class, // Nuevo
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ],

    'api' => [
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ],
];

protected $routeMiddleware = [
    'admin' => \App\Http\Middleware\AdminMiddleware::class,
    'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
];
}
