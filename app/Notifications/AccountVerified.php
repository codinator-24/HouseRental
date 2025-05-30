<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountVerified extends Notification // Optionally: implements ShouldQueue
{
    use Queueable;

    public User $user;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
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
            'user_id' => $this->user->id,
            'message' => 'Congratulations! Your account has been verified.',
            // Link to the user's profile page
            'link' => route('profile.show'), 
        ];
    }

    /**
     * Get the mail representation of the notification. (Optional)
     * You can uncomment and customize this if you want to send emails as well.
     */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     $url = route('profile.show');

    //     return (new MailMessage)
    //                 ->subject('Your Account Has Been Verified')
    //                 ->greeting('Hello ' . $notifiable->user_name . ',')
    //                 ->line('Congratulations! Your account on ' . config('app.name') . ' has been verified by our admin team.')
    //                 ->line('You can now fully access all features available to verified users.')
    //                 ->action('View Your Profile', $url)
    //                 ->line('Thank you for using our application!');
    // }
}
