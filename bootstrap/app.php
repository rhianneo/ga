<?php

use Illuminate\Foundation\Application;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\Exceptions;

// Create the application instance
return Application::configure(basePath: dirname(__DIR__))

    // Routing configuration
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )

    // Global middleware (applied to all routes)
    ->withMiddleware(function (Middleware $middleware): void {
        // Register route-specific middleware aliases
        $middleware->alias([
            'role' => RoleMiddleware::class, // Register 'role' alias for the custom RoleMiddleware
        ]);
    })

    // Exception handling
    ->withExceptions(function (Exceptions $exceptions): void {
        // Customize exception handling if needed
    })

    ->create();
