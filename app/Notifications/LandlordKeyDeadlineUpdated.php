<?php

namespace App\Notifications;

use App\Models\Agreement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class LandlordKeyDeadlineUpdated extends Notification
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

        $newDeadline = 'the specified deadline'; // Default
        if ($this->agreement->key_delivery_deadline) {
            try {
                $newDeadline = Carbon::parse($this->agreement->key_delivery_deadline)->format('F j, Y');
            } catch (\Exception $e) {
                // Log error or handle invalid date format
            }
        }

        return [
            'agreement_id' => $this->agreement->id,
            'house_title' => $houseTitle,
            'message' => "Update: The key delivery deadline for '{$houseTitle}' (Agreement #{$this->agreement->id}) has been changed to {$newDeadline}. Please deliver the keys by this new date.",
            'link' => route('dashboard'), // Or a link to agreement details
        ];
    }
}
