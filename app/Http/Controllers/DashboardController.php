<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Agreement;
use App\Models\House;
use App\Models\Maintenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Feedback;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $userId = Auth::id();

        // Fetch houses owned by the user (for "My Properties" section)
        // Fetch the latest 5 properties owned by the user
        $housesQuery = House::with('pictures')->where('landlord_id', $userId);
        $houses = $housesQuery->latest()->take(5)->get();

        // Check if there are more than 5 properties in total for this user
        $hasMoreProperties = false;
        if ($userId) {
            $hasMoreProperties = House::where('landlord_id', $userId)->count() > 5;
        }

        // Fetch the latest 5 bookings sent by the user
        $sentBookings = Booking::where('tenant_id', $userId)
            ->with(['house.landlord']) // Eager load house and its landlord
            ->latest() // Order by the most recent bookings first
            ->take(5)
            ->get();

        // Check if there are more than 5 sent bookings in total
        $hasMoreSentBookings = Booking::where('tenant_id', $userId)->count() > 5;

        // Fetch the latest 5 bookings received by the user (for their properties)
        $receivedBookingsQuery = Booking::with(['house', 'tenant'])
            ->whereHas('house', function ($query) use ($userId) { // $userId is landlord_id here
                $query->where('landlord_id', $userId);
            });
        $receivedBookings = $receivedBookingsQuery->latest()->take(5)->get();

        // Check if there are more than 5 received bookings
        $hasMoreReceivedBookings = Booking::whereHas('house', function ($query) use ($userId) {
            $query->where('landlord_id', $userId);
        })->count() > 5;

        // Fetch houses the tenant is currently associated with (e.g., for maintenance requests)
        $rentedHouses = collect(); // Default to an empty collection
        if (Auth::user()->role === 'tenant' || Auth::user()->role === 'both') {
            // Fetch houses for which the tenant has an 'agreed' rental agreement.
            $rentedHouses = Agreement::where('status', 'agreed') // Status set in StripeController on success
                ->whereHas('booking', function ($bookingQuery) use ($userId) {
                    $bookingQuery->where('tenant_id', $userId);
                })
                ->with([
                    'booking.house.pictures', // Eager load pictures for the house
                    'booking.house.landlord'  // Eager load landlord for the house
                ])
                ->get()
                ->map(function ($agreement) {
                    // Ensure booking and house exist to prevent errors
                    return $agreement->booking && $agreement->booking->house ? $agreement->booking->house : null;
                })
                ->filter() // Remove any null house objects
                ->unique('id'); // Get unique houses
        }

        // Maintenance Requests Logic
        $user = Auth::user();

        // For the "Sent Maintenance Requests" section (visible to tenant & both)
        // Passed as 'maintenanceRequests' and 'hasMoreMaintenance' for Blade compatibility.
        $maintenanceRequests = collect(); // Default for landlord-only role
        $hasMoreMaintenance = false;    // Default for landlord-only role

        if ($user->role === 'tenant' || $user->role === 'both') {
            $sentMaintenanceQuery = Maintenance::with(['house', 'tenant'])
                ->where('tenant_id', $userId);
            $totalSentCount = (clone $sentMaintenanceQuery)->count();
            $hasMoreMaintenance = $totalSentCount > 5;
            $maintenanceRequests = $sentMaintenanceQuery->latest()->take(5)->get();
        }

        // For the "Received Maintenance Requests" section (visible to landlord & both)
        $receivedMaintenanceRequests = collect();
        $hasMoreReceivedMaintenance = false;

        if ($user->role === 'landlord' || $user->role === 'both') {
            $receivedMaintenanceQuery = Maintenance::with(['house', 'tenant', 'house.landlord'])
                ->whereHas('house', function ($q) use ($userId) {
                    $q->where('landlord_id', $userId);
                });
            $totalReceivedCount = (clone $receivedMaintenanceQuery)->count();
            $hasMoreReceivedMaintenance = $totalReceivedCount > 5;
            $receivedMaintenanceRequests = $receivedMaintenanceQuery->latest()->take(5)->get();
        }

        // For the "Maintenance" tab badge count
        $totalMaintenanceItemsForBadge = 0;
        if ($user->role === 'tenant') {
            // Total sent requests
            $totalMaintenanceItemsForBadge = Maintenance::where('tenant_id', $userId)->count();
        } elseif ($user->role === 'landlord') {
            // Total received requests
            $totalMaintenanceItemsForBadge = Maintenance::whereHas('house', function ($q) use ($userId) {
                $q->where('landlord_id', $userId);
            })->count();
        } elseif ($user->role === 'both') {
            // Total unique requests (sent by them OR for their properties)
            $totalMaintenanceItemsForBadge = Maintenance::where(function ($query) use ($userId) {
                $query->where('tenant_id', $userId)
                      ->orWhereHas('house', function ($q) use ($userId) {
                          $q->where('landlord_id', $userId);
                      });
            })->count();
        }


        return view('users.dashboard', compact(
            'houses',
            'hasMoreProperties',
            'sentBookings',
            'hasMoreSentBookings',
            'receivedBookings',
            'hasMoreReceivedBookings',
            'rentedHouses',
            'maintenanceRequests', // Sent requests for tenant/both
            'hasMoreMaintenance',  // For sent requests
            'receivedMaintenanceRequests',
            'hasMoreReceivedMaintenance',
            'totalMaintenanceItemsForBadge'
        ));
    }

    public function show_contact()
    {
        return view('contact.contact');
    }

    public function insert_contact(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
        ]);

        $data = new Feedback;

        if (Auth::check()) {
            $user = Auth::user();
            $data->name = $user->full_name; // Assuming 'full_name' exists on User model
            $data->email = $user->email;
        } else {
            // This case should ideally not be reached if frontend hides form for guests.
            // If it is, name and email would be null, or you might want to redirect.
            // For now, we'll let it proceed, but validation for name/email would fail if they were required for guests.
            // However, since we are removing them for guests, this path is less likely.
            return redirect()->route('login')->with('error', 'Please login to submit feedback.');
        }

        $data->title = $request->title;
        $data->description = $request->description;

        $data->save();
        return redirect()->back()->with('success', 'Feedback submitted successfully!');
    }
}
