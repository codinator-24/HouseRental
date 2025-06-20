<?php

namespace App\Notifications;

use App\Models\Agreement;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class TenantCashPaymentReminder extends Notification
{
    use Queueable;

    public Agreement $agreement;
    public Payment $payment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Agreement $agreement, Payment $payment)
    {
        $this->agreement = $agreement;
        $this->payment = $payment;
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

        $paymentDeadline = 'the specified deadline'; // Default
        if ($this->payment->payment_deadline) {
            try {
                $paymentDeadline = Carbon::parse($this->payment->payment_deadline)->format('F j, Y');
            } catch (\Exception $e) {
                // Log error or handle invalid date format if necessary
            }
        }

        $rentAmount = $this->payment->amount; // Assuming amount is correctly set in payment

        return [
            'agreement_id' => $this->agreement->id,
            'payment_id' => $this->payment->id,
            'house_title' => $houseTitle,
            'message' => "Please bring the cash payment of \${$rentAmount} for Agreement #{$this->agreement->id} '{$houseTitle}' to the office by {$paymentDeadline}.",
            'link' => route('dashboard'), // Link to the tenant's dashboard or agreement view
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
