<?php

namespace App\Notifications;

use App\Models\Booking; // Assuming you have a Booking model
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewBookingRequest extends Notification // Optionally: implements ShouldQueue
{
    use Queueable;

    public Booking $booking;

    /**
     * Create a new notification instance.
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // We'll store it in the database for the bell icon
        // You could add 'mail' here too if you want email notifications: return ['database', 'mail'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        // Ensure your Booking model has relationships to 'user' (tenant) and 'property'
        // And your Property model has a relationship to its 'owner' (landlord User model)
        $tenant = $this->booking->tenant; // User who made the booking
        $house = $this->booking->house; // The booked house

        return [
            'booking_id' => $this->booking->id,
            'tenant_name' => $tenant->user_name,
            'house_title' => $house->title, // Assuming house has a 'title'
            'message' => "{$tenant->user_name} has requested to book your house: '{$house->title}'.",
            // Link to the specific booking details page for the landlord
            'link' => route('bookings.show', $this->booking->id), 
        ];
    }

    /**
     * Get the mail representation of the notification. (Optional)
     */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     $tenant = $this->booking->user;
    //     $house = $this->booking->house;
    //     $url = route('landlord.bookings.show', $this->booking->id);

    //     return (new MailMessage)
    //                 ->subject('New Booking Request for ' . $house->title)
    //                 ->greeting('Hello ' . $notifiable->user_name . ',')
    //                 ->line("{$tenant->user_name} has requested to book your house: '{$property->title}'.")
    //                 ->action('View Booking Request', $url)
    //                 ->line('Thank you for using our application!');
    // }
}
