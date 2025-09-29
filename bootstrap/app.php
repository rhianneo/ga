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
            'role' => RoleMiddleware::class, // 'role' alias
        ]);
    })

    // Exception handling
    ->withExceptions(function (Exceptions $exceptions): void {
        // Customize exception handling if needed
    });

/*
 |------------------------------------------------------------------------
 | Register scheduled tasks only if the builder supports it.
 | This avoids fatal errors on installations where the builder API
 | doesn't include withScheduledTasks().
 |------------------------------------------------------------------------
 */
if (method_exists($builder, 'withScheduledTasks')) {
    $builder = $builder->withScheduledTasks(function (Schedule $schedule): void {
        // Run daily; change frequency if you want
        $schedule->call(function () {
            // Find applications that have expiry_date set
            $applications = Application::whereNotNull('expiry_date')->get();

            foreach ($applications as $application) {
                // Ensure expiry_date is a Carbon instance
                $expiryDate = $application->expiry_date;
                $currentDate = now();
                
                // Skip if expiry date is in the past
                if ($expiryDate->isPast()) {
                    continue;
                }

                $daysBeforeExpiry = $currentDate->diffInDays($expiryDate);

                // Send reminders at 100 and 90 days before expiry
                if ($daysBeforeExpiry === 100 || $daysBeforeExpiry === 90) {
                    $gaStaff = User::where('role', 'GA Staff')->get();
                    if ($gaStaff->isNotEmpty()) {
                        Notification::send($gaStaff, new ExpiryReminder($application, (string) $daysBeforeExpiry));
                    }
                }
            }
        })->daily();
    });
}

/*
 |------------------------------------------------------------------------
 | Finally create the application instance and return it
 |------------------------------------------------------------------------
 */
return $builder->create();
