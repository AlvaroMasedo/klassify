<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(fn() => route('home'));
        $middleware->alias([
            'teacher' => \App\Http\Middleware\EnsureUserIsTeacher::class,
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'teacher.verified' => \App\Http\Middleware\EnsureTeacherIsVerified::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
