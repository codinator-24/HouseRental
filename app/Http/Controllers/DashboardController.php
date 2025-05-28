<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\House;
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

        return view('users.dashboard', compact('houses', 'hasMoreProperties', 'sentBookings', 'hasMoreSentBookings', 'receivedBookings', 'hasMoreReceivedBookings'));
    }

    public function show_contact()
    {
        return view('contact.contact');
    }

    public function insert_contact(Request $request)
    {
        $data = new Feedback;
        $data->name = $request->name;
        $data->email = $request->email;
        $data->title = $request->title;
        $data->description = $request->description;

        $data->save();
        return redirect()->back();
    }
}
