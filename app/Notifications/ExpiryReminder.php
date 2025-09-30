<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Application;

class ExpiryReminder extends Notification implements ShouldQueue
{
    use Queueable;

    public $application;
    public $daysBeforeExpiry;

    public function __construct(Application $application, int $daysBeforeExpiry)
    {
        $this->application = $application;
        $this->daysBeforeExpiry = $daysBeforeExpiry;
    }

    public function via(object $notifiable): array
    {
        return ['mail']; // Send via email
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Reminder: AEP and PV Visa Application Expiry Approaching")
            ->greeting("Dear GA Staff,")
            ->line("The application for {$this->application->name} (Position: {$this->application->position}) is approaching its expiry date in {$this->daysBeforeExpiry} days. The expiry date is set for {$this->application->expiry_date->format('F j, Y')}.")
            ->action('Login to View Applications', 'http://172.16.98.200:8000/auth/login')
            ->line('Please take necessary action before the expiry date.')
            ->salutation('Regards, Expatriate Monitoring');
    }
}
