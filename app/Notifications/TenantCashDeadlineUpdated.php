<?php

namespace App\Notifications;

use App\Models\Payment;
use App\Models\Agreement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class TenantCashDeadlineUpdated extends Notification
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

        $newDeadline = 'the specified deadline'; // Default
        if ($this->payment->payment_deadline) {
            try {
                $newDeadline = Carbon::parse($this->payment->payment_deadline)->format('F j, Y');
            } catch (\Exception $e) {
                // Log error or handle invalid date format
            }
        }
        
        $rentAmount = $this->payment->amount;

        return [
            'payment_id' => $this->payment->id,
            'agreement_id' => $this->agreement->id,
            'house_title' => $houseTitle,
            'message' => "Update: Your cash payment deadline for '{$houseTitle}' (Amount: \${$rentAmount}) has been changed to {$newDeadline}. Please bring the payment by this new date.",
            'link' => route('dashboard'), // Or a link to payment details/agreement
        ];
    }
}
