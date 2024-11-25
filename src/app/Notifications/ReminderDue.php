<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Mail;

class ReminderDue extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
     protected $reminder;

     /**
      * Create a new notification instance.
      */
     public function __construct($reminder)
     {
         $this->reminder = $reminder;
     }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
      return (new MailMessage)
                ->greeting('Hello ' . $notifiable->name)
                ->line('Reminding you: ' . $this->reminder->title)
                ->line('Description: ' . $this->reminder->description)
                ->line('Event at: ' . $this->reminder->event_at)
                ->action('View Reminder', url('http://localhost:3000'))
                ->line('Thank you for using our application!');
    }


    public function send($notifiable)
        {
            // Send the email
            $result = Mail::to($notifiable->email)->send($this->toMail($notifiable));

            // Perform your action if successful
            if ($result) {
                \Log::info('Reminder email sent!', ['reminder_id' => $this->reminder->id]);

                // Example: Update reminder status in database
                $this->reminder->update(['status' => 2]);
            }
        }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
     public function toArray(object $notifiable): array
     {
         return [
             'reminder_id' => $this->reminder->id,
             'title' => $this->reminder->title,
             'description' => $this->reminder->description,
             'due_at' => $this->reminder->remind_at,
         ];
     }
}
