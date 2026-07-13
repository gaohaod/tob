<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use App\Http\Middleware\CheckAdmin;
use App\Http\Middleware\BanMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(CheckAdmin::class);
        $middleware->append(BanMiddleware::class);

        $middleware->validateCsrfTokens(
            except: ['marketplace/*', 'persistence/*', '1.1/*', '1.0/*']
        );

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
