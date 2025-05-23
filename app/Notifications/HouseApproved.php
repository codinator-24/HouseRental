<?php

namespace App\Notifications;

use App\Models\House;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class HouseApproved extends Notification // Optionally: implements ShouldQueue
{
    use Queueable;

    public House $house;

    /**
     * Create a new notification instance.
     */
    public function __construct(House $house)
    {
        $this->house = $house;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // We'll store it in the database for the bell icon
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'house_id' => $this->house->id,
            'house_title' => $this->house->title,
            'message' => "Great news! Your property '{$this->house->title}' has been approved and is now listed as available.",
            // Link to the specific house details page
            'link' => route('house.details', $this->house->id),
        ];
    }

    /**
     * Get the mail representation of the notification. (Optional)
     * You can uncomment and customize this if you want to send emails as well.
     */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     $url = route('house.details', $this->house->id);

    //     return (new MailMessage)
    //                 ->subject('Your Property Has Been Approved: ' . $this->house->title)
    //                 ->greeting('Hello ' . $notifiable->user_name . ',')
    //                 ->line("Great news! Your property '{$this->house->title}' has been reviewed and approved by our admin team.")
    //                 ->line('It is now listed as available on our platform.')
    //                 ->action('View Your Property', $url)
    //                 ->line('Thank you for listing your property with us!');
    // }
}
