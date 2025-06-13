<?php

namespace App\Notifications;

use App\Models\House;
use App\Models\Message as InquiryMessageModel; // Alias to avoid conflict if Message model is used directly
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str; // Import the Str facade

class NewInquiryMessage extends Notification implements ShouldQueue
{
    use Queueable;

    public InquiryMessageModel $inquiryMessage;
    public House $house;

    /**
     * Create a new notification instance.
     *
     * @param \App\Models\Message $inquiryMessage The message model instance
     * @param \App\Models\House $house The house related to the inquiry
     */
    public function __construct(InquiryMessageModel $inquiryMessage, House $house)
    {
        $this->inquiryMessage = $inquiryMessage;
        $this->house = $house;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail']; // Add 'mail' if you want email notifications
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $senderName = $this->inquiryMessage->sender->user_name ?? 'A user';
        $subject = "New Inquiry Message for " . $this->house->title;
        $greeting = "Hello " . ($notifiable->user_name ?? 'User') . ",";
        $line = $senderName . " has sent you a message regarding the property '" . $this->house->title . "'.";
        $actionText = "View Message";
        $actionUrl = route('houses.inquiry.show', $this->house); // Link to the inquiry thread

        return (new MailMessage)
                    ->subject($subject)
                    ->greeting($greeting)
                    ->line($line)
                    ->line("Message: \"" . Str::limit($this->inquiryMessage->content, 100) . "\"")
                    ->action($actionText, $actionUrl)
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     * (For database storage)
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $senderName = $this->inquiryMessage->sender->user_name ?? 'A user';
        $messageContent = Str::limit($this->inquiryMessage->content, 100);
        
        // Determine if the notifiable is the landlord or the inquirer
        if ($notifiable->id === $this->house->landlord_id) {
            // Notification for the landlord
            $text = "New inquiry message from {$senderName} for your property '{$this->house->title}': \"{$messageContent}\"";
        } else {
            // Notification for the inquirer (reply from landlord)
            $text = "{$senderName} (Landlord) replied to your inquiry for '{$this->house->title}': \"{$messageContent}\"";
        }

        return [
            'house_id' => $this->house->id,
            'house_title' => $this->house->title,
            'message_id' => $this->inquiryMessage->id,
            'sender_id' => $this->inquiryMessage->sender_id,
            'sender_name' => $senderName,
            'text' => $text, // Dynamic text based on recipient
            'link' => route('houses.inquiry.show', $this->house->id), // Link to the inquiry thread
            'type' => 'inquiry_message',
        ];
    }
}
