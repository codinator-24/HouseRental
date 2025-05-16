<?php

namespace App\Notifications;

use App\Models\Booking; // Assuming you have a Booking model
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingStatusUpdated extends Notification // Optionally: implements ShouldQueue
{
    use Queueable;

    public Booking $booking;
    public string $status; // 'accepted' or 'rejected'

    /**
     * Create a new notification instance.
     */
    public function __construct(Booking $booking, string $status)
    {
        $this->booking = $booking;
        $this->status = $status;
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
        $house = $this->booking->house;
        $message = "Your booking for '{$house->title}' has been {$this->status}.";

        if ($this->status === 'rejected' && !empty($this->booking->rejection_reason)) {
            // Assuming your Booking model might have a 'rejection_reason' field
            $message .= " Reason: " . $this->booking->rejection_reason;
        }

        return [
            'booking_id' => $this->booking->id,
            'house_title' => $house->title,
            'status' => $this->status,
            'message' => $message,
            // You'll need a route for tenants to view their bookings
            'link' => route('bookings.sent', $this->booking->id), // Example route
        ];
    }

    /**
     * Get the mail representation of the notification. (Optional)
     */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     $house = $this->booking->house;
    //     $url = route('tenant.bookings.show', $this->booking->id);
    //     $greeting = 'Hello ' . $notifiable->user_name . ',';

    //     $mailMessage = (new MailMessage)
    //                 ->subject('Booking Status Updated for ' . $house->title)
    //                 ->greeting($greeting)
    //                 ->line("Your booking for '{$house->title}' has been {$this->status}.");

    //     if ($this->status === 'rejected' && !empty($this->booking->rejection_reason)) {
    //         $mailMessage->line("Reason: " . $this->booking->rejection_reason);
    //     }

    //     $mailMessage->action('View Booking', $url)
    //                 ->line('Thank you for using our application!');
        
    //     return $mailMessage;
    // }
}
