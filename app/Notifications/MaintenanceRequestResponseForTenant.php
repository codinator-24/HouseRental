<?php

namespace App\Notifications;

use App\Models\Maintenance;
// use Illuminate\Bus\Queueable; // Removed as Queueable trait is not used
// use Illuminate\Contracts\Queue\ShouldQueue; // Removed
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class MaintenanceRequestResponseForTenant extends Notification // Removed implements ShouldQueue
{
    // use Queueable; // Removed

    public Maintenance $maintenance;
    public string $actionStatus; // 'accepted' or 'rejected'

    /**
     * Create a new notification instance.
     */
    public function __construct(Maintenance $maintenance, string $actionStatus)
    {
        $this->maintenance = $maintenance;
        $this->actionStatus = $actionStatus;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Can add 'mail' later if needed
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        $houseTitle = $this->maintenance->house->title ?? 'your property';
        $areaOfHouse = $this->maintenance->area_of_house ?? 'the issue';
        $landlordResponse = Str::limit($this->maintenance->landlord_response ?? 'No specific response provided.', 150);
        
        $message = '';
        if ($this->actionStatus === 'accepted') {
            $message = "Your maintenance request for '{$houseTitle}' regarding '{$areaOfHouse}' has been accepted.";
        } elseif ($this->actionStatus === 'rejected') {
            $message = "Your maintenance request for '{$houseTitle}' regarding '{$areaOfHouse}' has been rejected.";
        } else {
            // Fallback for any other status, though we only expect accepted/rejected here
            $message = "Your maintenance request for '{$houseTitle}' regarding '{$areaOfHouse}' has been updated to '{$this->maintenance->status}'.";
        }
        $message .= " Landlord's response: \"{$landlordResponse}\"";

        // Link for the tenant to view their updated maintenance request
        // This might point to a modal or a specific section on their dashboard
        $link = route('dashboard') . '?active_tab=maintenance&view_request=' . $this->maintenance->id;

        return [
            'maintenance_id' => $this->maintenance->id,
            'house_title' => $houseTitle,
            'area_of_house' => $areaOfHouse,
            'new_status' => $this->maintenance->status, // The actual status set on the model
            'action_taken' => $this->actionStatus, // 'accepted' or 'rejected'
            'landlord_response_summary' => $landlordResponse,
            'message' => $message,
            'link' => $link,
            'type' => 'maintenance_response', // Custom type for easy filtering/display
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     $houseTitle = $this->maintenance->house->title ?? 'your property';
    //     $areaOfHouse = $this->maintenance->area_of_house ?? 'the issue';
    //     $url = route('dashboard') . '?active_tab=maintenance&view_request=' . $this->maintenance->id;
    //     $subject = '';
    //     $greeting = "Hello {$notifiable->user_name},";
    //     $line1 = '';

    //     if ($this->actionStatus === 'accepted') {
    //         $subject = "Maintenance Request Accepted: {$areaOfHouse} at {$houseTitle}";
    //         $line1 = "Your maintenance request for '{$houseTitle}' regarding '{$areaOfHouse}' has been accepted by the landlord.";
    //     } elseif ($this->actionStatus === 'rejected') {
    //         $subject = "Maintenance Request Rejected: {$areaOfHouse} at {$houseTitle}";
    //         $line1 = "Your maintenance request for '{$houseTitle}' regarding '{$areaOfHouse}' has been rejected by the landlord.";
    //     }

    //     $mailMessage = (new MailMessage)
    //                 ->subject($subject)
    //                 ->greeting($greeting)
    //                 ->line($line1);
        
    //     if (!empty($this->maintenance->landlord_response)) {
    //         $mailMessage->line("Landlord's response: " . $this->maintenance->landlord_response);
    //     }
        
    //     $mailMessage->action('View Request Details', $url)
    //                 ->line('Thank you for using our application!');
        
    //     return $mailMessage;
    // }
}
