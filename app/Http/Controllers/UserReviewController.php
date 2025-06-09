<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Review;
use App\Http\Requests\StoreReviewRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserReviewController extends Controller
{
    /**
     * Display a listing of the bookings eligible for review by the user.
     */
    public function index()
    {
        $user = Auth::user();
        // Eager load house and its review by the current user for the specific booking
        $bookings = Booking::where('tenant_id', $user->id)
            ->where('status', 'completed')
            ->with(['house', 'review']) // Eager load review related to this booking
            ->get()
            ->filter(function ($booking) {
                // Check if booking is completed and past its end date
                // And if there isn't already a review for this specific booking
                return $booking->isCompletedAndPast() && is_null($booking->review);
            });

        // Separate list for already submitted reviews by the user
        $submittedReviews = Review::where('user_id', $user->id)
                                ->with(['house', 'booking']) // Eager load house and booking
                                ->latest()
                                ->paginate(10); // Or any number you prefer

        return view('users.reviews.index', compact('bookings', 'submittedReviews'));
    }

    /**
     * Show the form for creating a new review for a specific booking.
     */
    public function create(Booking $booking)
    {
        // Authorization: Check if the authenticated user is the tenant of the booking
        // and if the house can be reviewed by this user for this booking.
        if (Auth::id() !== $booking->tenant_id || !$booking->house || !$booking->house->canBeReviewedBy(Auth::user(), $booking)) {
            abort(403, 'This action is unauthorized.');
        }

        return view('users.reviews.create', compact('booking'));
    }

    /**
     * Store a newly created review in storage.
     */
    public function store(StoreReviewRequest $request, Booking $booking)
    {
        // Authorization is handled by StoreReviewRequest's authorize method

        Review::create([
            'user_id' => Auth::id(),
            'house_id' => $booking->house_id,
            'booking_id' => $booking->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => false, // Default to not approved, admin will approve
        ]);

        return redirect()->route('reviews.my')->with('success', 'Review submitted successfully and is pending approval.');
    }
}
