<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        // Tambahkan middleware ke grup web
        $middleware->web(append: [
            \App\Http\Middleware\UserActivity::class,
        ]);

        // Register alias middleware (WAJIB satu per satu)
        $middleware->alias([
            'redirect.auth.to.dashboard' => \App\Http\Middleware\RedirectAuthenticatedToDashboard::class,
            'admin' => \App\Http\Middleware\AdminOnly::class,
            'regular' => \App\Http\Middleware\RegularOnly::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
