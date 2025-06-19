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
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \App\Http\Middleware\FixAuthRequestMiddleware::class,
    ],

    'api' => [
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ],
];

protected $routeMiddleware = [
    'admin' => \App\Http\Middleware\AdminMiddleware::class,
    'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
    'auth' => \App\Http\Middleware\Authenticate::class,
];
}
