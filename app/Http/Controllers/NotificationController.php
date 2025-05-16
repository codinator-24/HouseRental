<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['notifications' => [], 'unread_count' => 0], 401);
        }

        // Fetch latest, e.g., 10 unread notifications. Adjust as needed.
        // Laravel stores notification data in a 'data' JSON column.
        $notifications = $user->unreadNotifications()->latest()->take(10)->get()->map(function ($notification) {
            return [
                'id' => $notification->id,
                'message' => $notification->data['message'] ?? 'Notification message missing.',
                'link' => $notification->data['link'] ?? '#',
                'created_at_human' => $notification->created_at->diffForHumans(), // For display
                'created_at' => $notification->created_at, // For Alpine timeAgo
            ];
        });

        $unreadCount = $user->unreadNotifications()->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    public function markAsRead(Request $request, $notificationId)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $notification = $user->notifications()->find($notificationId);

        if ($notification) {
            $notification->markAsRead();
            // After marking as read, you might want to redirect to the notification's link if it exists
            // For an API, just returning success is often enough, and JS handles redirection.
            return response()->json(['message' => 'Notification marked as read.']);
        }

        return response()->json(['message' => 'Notification not found.'], 404);
    }

    public function markAllAsRead(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }
        $user->unreadNotifications->markAsRead();
        return response()->json(['message' => 'All notifications marked as read.']);
    }
}
