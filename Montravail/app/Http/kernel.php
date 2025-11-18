<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [
        // middleware globaux (optionnel)
    ];

    protected $middlewareGroups = [
        'web' => [
            \App\Http\middleware\EncryptCookies::class,
            \Illuminate\Cookie\middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\middleware\StartSession::class,
            \Illuminate\View\middleware\ShareErrorsFromSession::class,
            \App\Http\middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\middleware\SubstituteBindings::class,
        ],

        'api' => [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'role' => \App\Http\Middleware\RoleMiddleware::class,
    ];
}
