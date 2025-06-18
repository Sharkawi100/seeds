<?php
// File: bootstrap/app.php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckUserActive;
use App\Http\Middleware\IsAdmin;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);

        // Register middleware aliases
        $middleware->alias([
            'admin' => IsAdmin::class,
            'teacher' => \App\Http\Middleware\CanCreateQuizzes::class,
            'active' => CheckUserActive::class,
        ]);

        // Add CSRF exception for social callbacks
        $middleware->validateCsrfTokens(except: [
            'auth/*/callback',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();