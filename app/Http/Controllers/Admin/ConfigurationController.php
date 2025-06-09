<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\App; // For checking environment

class ConfigurationController extends Controller
{
    /**
     * Display the admin configuration page.
     */
    public function index(Request $request)
    {
        // Ensure this tool is only accessible in local environment
        if (!App::environment('local')) {
            abort(404);
        }

        $query = Booking::with(['house', 'tenant'])
                        ->orderBy('created_at', 'desc');

        // Simple filter by booking status
        if ($request->filled('booking_status')) {
            $query->where('status', $request->booking_status);
        }

        // Simple filter by tenant email or name
        if ($request->filled('tenant_search')) {
            $searchTerm = $request->tenant_search;
            $query->whereHas('tenant', function ($q) use ($searchTerm) {
                $q->where('full_name', 'like', "%{$searchTerm}%")
                  ->orWhere('user_name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }
        
        // Simple filter by house title
        if ($request->filled('house_title_search')) {
            $searchTerm = $request->house_title_search;
            $query->whereHas('house', function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%");
            });
        }

        $bookings = $query->paginate(20)->withQueryString(); // Paginate results

        return view('admin.configuration', compact('bookings'));
    }

    /**
     * "Age" a booking by modifying its created_at timestamp.
     */
    public function ageBooking(Request $request, Booking $booking)
    {
        // Ensure this tool is only usable in local environment
        if (!App::environment('local')) {
            abort(404);
        }

        $monthsToAge = (int) $request->input('months', 1);
        $setCompleted = $request->boolean('set_completed');

        if ($monthsToAge <= 0) {
            return back()->with('error', 'Number of months to age must be positive.');
        }

        $newCreatedAt = $booking->created_at->copy()->subMonths($monthsToAge);
        
        // To prevent created_at from being later than updated_at if updated_at is not also shifted
        // or to ensure updated_at reflects this "logical" change.
        $newUpdatedAt = $booking->updated_at->copy()->subMonths($monthsToAge);
        if ($newUpdatedAt->lt($newCreatedAt)) {
             $newUpdatedAt = $newCreatedAt->copy();
        }


        $booking->created_at = $newCreatedAt;
        $booking->updated_at = $newUpdatedAt; // Also adjust updated_at

        if ($setCompleted && $booking->status !== 'completed') {
            $booking->status = 'completed';
        }

        $booking->save();

        return back()->with('success', "Booking ID {$booking->id} successfully aged by {$monthsToAge} months. New created_at: {$booking->created_at->toDateTimeString()}");
    }
}
