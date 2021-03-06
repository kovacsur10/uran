<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
        	\App\Http\Middleware\BeforeMiddleware::class,
        ],

        'api' => [
            'throttle:60,1',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth.logged' => \App\Http\Middleware\AuthenticatedMiddleware::class,
		'auth.notlogged' => \App\Http\Middleware\NonAuthenticatedMiddleware::class,
		'modules.rooms' => \App\Http\Middleware\Modules\RoomsMiddleware::class,
		'modules.ecnet' => \App\Http\Middleware\Modules\EcnetMiddleware::class,
		'modules.tasks' => \App\Http\Middleware\Modules\TasksMiddleware::class,
    	'modules.ecouncil' => \App\Http\Middleware\Modules\EcouncilMiddleware::class,
    ];
}
