<?php

namespace App\Notifications;

use App\Models\Agreement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class TenantKeyCollectionReminder extends Notification
{
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

        return [
            'agreement_id' => $this->agreement->id,
            'house_title' => $houseTitle,
            'message' => "Good news! The keys for '{$houseTitle}' (Agreement #{$this->agreement->id}) are now ready for collection. Please come to the office to pick them up.",
            'link' => route('dashboard'), // Or a link to agreement details or contact page
        ];
    }
}
