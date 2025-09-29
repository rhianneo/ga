<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Application;
use App\Notifications\ExpiryReminder;

class SendExpiryNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-expiry-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email reminders for applications expiring in 90 or 100 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get applications that are 90 or 100 days from expiry
        $applications = Application::where('days_before_expiry', 90)
                                    ->orWhere('days_before_expiry', 100)
                                    ->get();

        // Check if any applications were found
        if ($applications->isEmpty()) {
            $this->info('No applications found that are expiring in 90 or 100 days.');
            return;
        }

        // Loop through applications and send notifications
        foreach ($applications as $application) {
            $reminderType = $application->days_before_expiry == 90 ? '90' : '100';
            $application->notify(new ExpiryReminder($application, $reminderType));
        }

        // Output info to console
        $this->info('Expiry notifications sent successfully!');
    }
}
