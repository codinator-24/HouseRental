<?php

namespace App\Notifications;

use App\Models\Maintenance;
// use Illuminate\Bus\Queueable; // Removed as Queueable trait is not used
// use Illuminate\Contracts\Queue\ShouldQueue; // Removed
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewMaintenanceRequestForLandlord extends Notification
{
    // use Queueable; // Removed

    public Maintenance $maintenance;

    /**
     * Create a new notification instance.
     */
    public function __construct(Maintenance $maintenance)
    {
        $this->maintenance = $maintenance;
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
        $tenantName = $this->maintenance->tenant->user_name ?? 'A tenant';
        $houseTitle = $this->maintenance->house->title ?? 'one of your properties';
        $message = "New maintenance request from {$tenantName} for property '{$houseTitle}'.";

        // The link should ideally take the landlord to the dashboard,
        // with the maintenance tab active and possibly highlighting the new request.
        // For now, a general link to the dashboard. Frontend JS can handle focusing.
        $link = route('dashboard') . '?active_tab=maintenance&highlight_maintenance=' . $this->maintenance->id;


        return [
            'maintenance_id' => $this->maintenance->id,
            'tenant_name' => $tenantName,
            'house_title' => $houseTitle,
            'message' => $message,
            'link' => $link, // URL to view the maintenance request on the dashboard
            'type' => 'new_maintenance_request', // Custom type for easy filtering/display
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     $tenantName = $this->maintenance->tenant->user_name ?? 'A tenant';
    //     $houseTitle = $this->maintenance->house->title ?? 'one of your properties';
    //     $url = route('dashboard') . '?active_tab=maintenance&highlight_maintenance=' . $this->maintenance->id;

    //     return (new MailMessage)
    //                 ->subject("New Maintenance Request: {$houseTitle}")
    //                 ->greeting("Hello {$notifiable->user_name},")
    //                 ->line("You have received a new maintenance request from {$tenantName} for your property '{$houseTitle}'.")
    //                 ->line("Issue reported in: " . $this->maintenance->area_of_house)
    //                 ->line("Description: " . Str::limit($this->maintenance->description, 100))
    //                 ->action('View Request Details', $url)
    //                 ->line('Please review and respond to the request at your earliest convenience.');
    // }
}
