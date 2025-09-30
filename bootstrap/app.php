<?php

use Illuminate\Foundation\Application as LaravelApplication;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\SendExpiryNotifications;

/*
 |--------------------------------------------------------------------------
 | Build the application (ApplicationBuilder)
 |--------------------------------------------------------------------------
 |
 | The ApplicationBuilder allows us to configure routing, middleware,
 | exception handling, and scheduled tasks in a clean and centralized way.
 |
 */
$builder = LaravelApplication::configure(basePath: dirname(__DIR__))

    // Routing configuration
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',          // Main web routes
        commands: __DIR__ . '/../routes/console.php', // Custom Artisan commands
        health: '/up'                                 // Health check route
    )

    // Global middleware (applied to all routes)
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => RoleMiddleware::class, // 'role' alias for RoleMiddleware
        ]);
    })

    // Exception handling
    ->withExceptions(function (Exceptions $exceptions): void {
        // Customize exception handling if needed
    });

/*
 |--------------------------------------------------------------------------
 | Register scheduled tasks
 |--------------------------------------------------------------------------
 |
 | Define periodic tasks such as sending application expiry reminders.
 | Instead of embedding business logic here, we delegate to a console
 | command for better separation of concerns.
 |
 */
if (method_exists($builder, 'withScheduledTasks')) {
    $builder = $builder->withScheduledTasks(function (Schedule $schedule): void {
        // Run expiry notification command daily
        $schedule->command(SendExpiryNotifications::class)->daily();
    });
}

/*
 |--------------------------------------------------------------------------
 | Create and return the application instance
 |--------------------------------------------------------------------------
 */
return $builder->create();
