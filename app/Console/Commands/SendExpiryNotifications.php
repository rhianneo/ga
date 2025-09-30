<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Application;
use App\Models\User;
use App\Notifications\ExpiryReminder;
use Carbon\Carbon;

class SendExpiryNotifications extends Command
{
    protected $signature = 'app:send-expiry-notifications';
    protected $description = 'Send reminders for applications approaching expiry (100 & 90 days)';

    public function handle()
    {
        $today = Carbon::today();

        // Get applications that are 100 or 90 days before expiry
        $applications = Application::whereNotNull('expiry_date')->get();

        foreach ($applications as $app) {
            $daysBeforeExpiry = $today->diffInDays($app->expiry_date, false); // false = allow negative

            if (in_array($daysBeforeExpiry, [100, 90])) {
                // Get GA Staff users
                $gaStaff = User::where('role', 'GA Staff')->get();

                if ($gaStaff->isNotEmpty()) {
                    foreach ($gaStaff as $staff) {
                        $staff->notify(new ExpiryReminder($app, $daysBeforeExpiry));
                    }
                    $this->info("Reminder sent for {$app->name} ({$daysBeforeExpiry} days before expiry).");
                }
            }
        }

        $this->info("Expiry notifications checked successfully.");
    }
}
