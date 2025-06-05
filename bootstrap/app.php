<?php
// File: bootstrap/app.php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register middleware aliases
        $middleware->alias([
            'admin' => \App\Http\Middleware\IsAdmin::class,
            'can.create.quizzes' => \App\Http\Middleware\CanCreateQuizzes::class, // Add this line
        ]);

        // Append to web middleware group
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);

        // Add CSRF exceptions
        $middleware->validateCsrfTokens(except: [
            'auth/*/callback',
            'logout',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();