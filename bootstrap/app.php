<?php

use Illuminate\Foundation\Application as LaravelApplication;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Console\Scheduling\Schedule;
use App\Notifications\ExpiryReminder;
use App\Models\Application;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

/*
 |------------------------------------------------------------------------
 | Build the application (ApplicationBuilder)
 |------------------------------------------------------------------------
 |
 | We get the builder returned by LaravelApplication::configure(...)
 | so we can call methods conditionally (to avoid calling non-existent APIs).
 |
 */
$builder = LaravelApplication::configure(basePath: dirname(__DIR__))

    // Routing configuration
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',         // Main web routes
        commands: __DIR__ . '/../routes/console.php',// Custom Artisan commands
        health: '/up'                                // Health check route
    )

    // Global middleware (applied to all routes)
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => RoleMiddleware::class, // 'role' alias for middleware
        ]);
    })

    // Exception handling
    ->withExceptions(function (Exceptions $exceptions): void {
        // Customize exception handling if needed
    });

/*
 |------------------------------------------------------------------------
 | Register scheduled tasks
 |------------------------------------------------------------------------
 |
 | This section defines tasks that should run periodically, like sending
 | expiry notifications for applications.
 |
 */
if (method_exists($builder, 'withScheduledTasks')) {
    $builder = $builder->withScheduledTasks(function (Schedule $schedule): void {
        // Schedule daily task to check for applications nearing expiry
        $schedule->call(function () {
            // Get applications with expiry dates set
            $applications = Application::whereNotNull('expiry_date')->get();

            foreach ($applications as $application) {
                $expiryDate = $application->expiry_date;
                $currentDate = now();

                // Skip applications with expiry dates in the past
                if ($expiryDate->isPast()) {
                    continue;
                }

                // Calculate days remaining before expiry
                $daysBeforeExpiry = $currentDate->diffInDays($expiryDate);

                // Send reminder if 100 or 90 days before expiry
                if ($daysBeforeExpiry === 100 || $daysBeforeExpiry === 90) {
                    // Get all GA Staff users
                    $gaStaff = User::where('role', 'GA Staff')->get();
                    if ($gaStaff->isNotEmpty()) {
                        // Send notifications to GA Staff
                        Notification::send($gaStaff, new ExpiryReminder($application, (string) $daysBeforeExpiry));
                    }
                }
            }
        })->daily();  // Run this task daily
    });
}

/*
 |------------------------------------------------------------------------
 | Finally, create the application instance and return it
 |------------------------------------------------------------------------
 |
 | Create the application instance that is configured and return it.
 |
 */
return $builder->create();
