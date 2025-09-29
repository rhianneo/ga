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
    public $reminderType;

    /**
     * Create a new notification instance.
     *
     * @param Application $application
     * @param string $reminderType
     */
    public function __construct(Application $application, string $reminderType)
    {
        $this->application = $application;
        $this->reminderType = $reminderType;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param object $notifiable
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];  // Notification will be sent via email only
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param object $notifiable
     * @return MailMessage
     */
    public function toMail(object $notifiable): MailMessage
    {
        // Format the expiry date
        $expiryDate = $this->application->expiry_date->format('M d, Y');

        // Set the reminder message based on reminder type (90 or 100 days)
        $reminderMessage = $this->reminderType == '90'
            ? 'This is a reminder that the application expiry date is 90 days away.'
            : 'This is a reminder that the application expiry date is 100 days away.';

        // Compose the mail message
        return (new MailMessage)
                    ->greeting('Hello GA Staff,')
                    ->line('The application for ' . $this->application->full_name . ' (Position: ' . $this->application->position . ') is approaching expiry on ' . $expiryDate . '.')
                    ->line($reminderMessage)
                    ->action('View Application', url('/applications/' . $this->application->id))  // Link to the application details
                    ->line('Please take the necessary action to process this application before the expiry date.')
                    ->line('Thank you for your attention.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param object $notifiable
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'application_id' => $this->application->id,
            'full_name' => $this->application->full_name,
            'position' => $this->application->position,
            'expiry_date' => $this->application->expiry_date->toDateString(),
            'reminder_type' => $this->reminderType,
        ];
    }
}
