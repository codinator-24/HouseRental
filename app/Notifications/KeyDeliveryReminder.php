<?php

namespace App\Notifications;

use App\Models\Agreement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon; // Import Carbon

class KeyDeliveryReminder extends Notification
{
    // use Queueable; // Queueable can be kept if you might queue it later, or removed.
    // For consistency with HouseApproved, let's keep Queueable trait but not implement ShouldQueue.
    use Queueable; 

    public Agreement $agreement;

    /**
     * Create a new notification instance.
     */
    public function __construct(Agreement $agreement)
    {
        $this->agreement = $agreement;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        $houseTitle = 'the property'; // Default
        if ($this->agreement->booking && $this->agreement->booking->house) {
            $houseTitle = $this->agreement->booking->house->title;
        }

        $deadline = 'the specified deadline'; // Default
        if ($this->agreement->key_delivery_deadline) {
            try {
                $deadline = Carbon::parse($this->agreement->key_delivery_deadline)->format('F j, Y');
            } catch (\Exception $e) {
                // Log error or handle invalid date format if necessary
                // For now, keeps the default
            }
        }

        return [
            'agreement_id' => $this->agreement->id,
            'house_title' => $houseTitle,
            'message' => "Now you have Agreement #{$this->agreement->id}, Please deliver the keys for '{$houseTitle}' to the office by {$deadline}.",
            'link' => route('dashboard'), // Link to the landlord's dashboard
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //                 ->line('The introduction to the notification.')
    //                 ->action('Notification Action', url('/'))
    //                 ->line('Thank you for using our application!');
    // }
}
