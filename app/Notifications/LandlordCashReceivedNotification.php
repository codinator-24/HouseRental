<?php

namespace App\Notifications;

use App\Models\Agreement;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class LandlordCashReceivedNotification extends Notification
{
    use Queueable;

    public Payment $payment;
    public Agreement $agreement;

    /**
     * Create a new notification instance.
     */
    public function __construct(Payment $payment, Agreement $agreement)
    {
        $this->payment = $payment;
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
        
        $paymentAmount = $this->payment->amount;

        return [
            'payment_id' => $this->payment->id,
            'agreement_id' => $this->agreement->id,
            'house_title' => $houseTitle,
            'message' => "Good news! The cash payment of \${$paymentAmount} for '{$houseTitle}' (Agreement #{$this->agreement->id}) has been received at the office. We will process it to your bank account soon.",
            'link' => route('dashboard'), // Or a link to payment details/agreement
        ];
    }
}
