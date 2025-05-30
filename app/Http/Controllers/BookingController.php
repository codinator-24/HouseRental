<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Models\Booking;
use App\Models\House;
use App\Models\Payment;
use App\Notifications\BookingStatusUpdated;
use App\Notifications\NewBookingRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function sendBooking(Request $request, House $house): RedirectResponse
    {
        // The 'auth' middleware (which should be on your route) handles unauthenticated users.
        // If Auth::id() is used, an authenticated user is assumed.
        // Validate the request data.
        // If validation fails, Laravel automatically redirects back to the previous page.
        // Errors will be in the 'bookingMessageErrors' bag, and old input will be flashed.
        // Your JavaScript in detailsHouse.blade.php should then reopen the modal.
        $validatedData = $request->validateWithBag('sendBookingFormErrors', [ // Changed error bag name
            'month_duration' => 'required|integer|min:1',
            'booking_message' => 'nullable|string|max:2000', // Message is optional as per your modal
        ], [
            // Optional: Custom validation messages
            'month_duration.required' => 'Please specify the duration in months.',
            'month_duration.integer' => 'The duration must be a whole number of months.',
            'month_duration.min' => 'The minimum duration is 1 month.',
            'booking_message.max' => 'Your message is too long (maximum 2000 characters).',
        ]);
        try {
            $booking =  Booking::create([
                'house_id' => $house->id,
                'tenant_id' => Auth::id(), // Gets the ID of the currently authenticated user
                'message' => $validatedData['booking_message'] ?? null, // Use validated data, default to null if empty
                'month_duration' => $validatedData['month_duration'],
                'status' => 'pending', // Set a default status like 'inquiry' or 'pending_confirmation'
            ]);

            // Get the landlord of the house.
            // This assumes your Booking model has a 'house' relationship,
            // and your House model has a 'landlord' relationship (e.g., belongsTo User via landlord_id).
            $landlord = $booking->house->landlord;

            if ($landlord) {
                $landlord->notify(new NewBookingRequest($booking));
            }

            return redirect()->back()->with('success', 'Your Booking has been sent successfully to the landlord!');
        } catch (\Exception $e) {
            // Log the detailed error for debugging purposes
            Log::error("Booking Submission Failed for House ID {$house->id} by User ID " . Auth::id() . ": " . $e->getMessage());
            // Redirect back with a general error message, using the 'bookingMessageErrors' bag.
            // This ensures the modal reopens and displays the error.
            return redirect()->back()
                ->withInput() // Flash the submitted input (the message) back to the form
                ->withErrors(['_form' => 'We couldn\'t send your Booking at this moment. Please try again later.'], 'sendBookingFormErrors');
        }
    }

    public function MyBookings(): View
    {
        // Get the ID of the currently authenticated landlord
        $landlordId = Auth::id();
        // Fetch bookings for houses owned by this landlord
        // Eager load the 'house' and 'tenant' relationships for efficiency
        $bookings = Booking::with(['house', 'tenant'])
            ->whereHas('house', function ($query) use ($landlordId) {
                $query->where('landlord_id', $landlordId);
            })
            ->orderBy('created_at', 'desc') // Optional: Order by newest first
            ->get();
        return view('bookings.MyHousesBookings', ['bookings' => $bookings]);
    }

    public function showBooking(Booking $booking): View
    {
        // Eager load relationships to prevent N+1 queries and for easy access in the view
        $booking->load(['tenant', 'house']);
        // Authorization: Ensure the authenticated user is the landlord of the house for this booking.
        if (Auth::id() !== $booking->house->landlord_id) {
            abort(403, 'Unauthorized action. You do not own the house associated with this booking.');
        }
        return view('bookings.showBooking', compact('booking'));
    }

    public function showSentBookings(): View
    {
        $user = Auth::user();
        // Fetch bookings where the authenticated user is the 'tenant_id' (the sender of the booking)
        // Eager load the 'house' relationship and then the 'landlord' relationship on the 'house'
        // Assumes Booking model has 'tenant_id' and a 'house' relationship.
        // Assumes House model has an 'landlord' relationship to the User model.
        $sentBookings = Booking::where('tenant_id', $user->id)
            ->with(['house.landlord']) // Eager load house and its landlord
            ->latest() // Order by the most recent bookings first
            ->paginate(10); // Paginate the results, 10 per page
        return view('bookings.sentBookings', compact('sentBookings'));
    }

    public function showDetailSentBooking(Booking $booking): View
    {
        // Authorization: Ensure the logged-in user is either the tenant or the property landlord
        if (Auth::id() !== $booking->tenant_id && (! $booking->house || Auth::id() !== $booking->house->landlord_id)) {
            // If you have an AuthorizationException imported:
            // throw new \Illuminate\Auth\Access\AuthorizationException('You are not authorized to view this booking.');
            // Otherwise, a simple abort:
            abort(403, 'You are not authorized to view this booking.');
        }

        // Eager load relationships for efficiency if not already globally eager-loaded in Booking model
        $booking->load(['house.landlord', 'tenant']);
        return view('bookings.ShowSentBookings', compact('booking'));
    }

    public function updateSentBooking(Request $request, Booking $booking)
    {
        // Authorization: Ensure the user is the tenant and booking is not rejected
        if (Auth::id() !== $booking->tenant_id || $booking->status === 'rejected') {
            abort(403, 'Unauthorized action.');
        }

        $validatedData = $request->validate([
            'month_duration' => 'required|integer|min:1',
            'message' => 'nullable|string|max:1000', // Adjust max length as needed
        ]);

        $booking->month_duration = $validatedData['month_duration'];
        $booking->message = $validatedData['message'];
        $booking->save();

        return redirect()->route('bookings.details.show', $booking->id)->with('success', 'Booking details updated successfully!');
    }

    public function destroySentBooking(Request $request, Booking $booking): RedirectResponse
    {
        // Authorization: Ensure the authenticated user is the one who sent the booking
        if ($booking->tenant_id !== Auth::id()) {
            return back()->with('error', 'You are not authorized to delete this booking.');
        }

        // Optional: Add logic here if there are conditions under which a booking cannot be deleted
        // (e.g., if it's already accepted and past a certain point)

        $booking->delete();

        $house = $booking->house;
        if ($house) {
            $house->status = 'available'; // Assuming 'booked' is a valid status in your House model
            $house->save();
        }

        return redirect()->route('bookings.sent')->with('success', 'Booking request deleted successfully.');
    }

    public function acceptBooking(Booking $booking): RedirectResponse
    {
        $booking->load('house', 'tenant'); // Ensure house and tenant relations are loaded

        if (!$booking->house) {
            // This case should ideally be prevented by foreign key constraints or soft deletes
            // or if the house was deleted after booking was made.
            return redirect()->back()->with('error', 'Associated property not found for this booking.');
        }

        // Authorization: Ensure the authenticated user is the landlord of the house for this booking.
        if (Auth::id() !== $booking->house->landlord_id) {
            abort(403, 'Unauthorized action. You do not own the house associated with this booking.');
        }

        // Prevent re-processing if already accepted or rejected
        if ($booking->status !== 'pending') {
            return redirect()->route('bookings.show', $booking)->with('info', 'This booking has already been processed.');
        }

        // Start a database transaction to ensure atomicity
        DB::beginTransaction();

        try {
            $newStatus = 'accepted';
            $booking->status = $newStatus;
            $booking->save();

            // Update the house status to 'booked'
            $house = $booking->house;
            if ($house) {
                $house->status = 'booked'; // Assuming 'booked' is a valid status in your House model
                $house->save();
            }

            // --- START: Logic for creating Agreement and Payment ---
            $agreementCreated = false;
            $agreementMessageSegment = '';

            // Check if an agreement already exists for this booking to prevent duplicates
            $existingAgreement = Agreement::where('booking_id', $booking->id)->first();

            if (!$existingAgreement) {
                // Create the Agreement
                $signedDate = Carbon::now();
                $expiresDate = $signedDate->copy()->addMonths($booking->month_duration ?? 1); // Default to 1 month if duration not set
                $rentAmount = $house->rent_amount;

                $agreement = Agreement::create([
                    'booking_id' => $booking->id,
                    'signed_at' => $signedDate,
                    'expires_at' => $expiresDate,
                    'rent_amount' => $rentAmount,
                    'rent_frequency' => 'monthly', // Default, adjust if necessary
                    'status' => 'pending', // Agreement is active upon booking acceptance
                ]);

                // Create the initial Payment record (status: pending)
                $agreementCreated = true;
                $agreementMessageSegment = ' Agreement record created.';
            } else {
                Log::info("Agreement already exists for Booking ID {$booking->id}. Skipping new agreement/payment creation.");
                $agreementMessageSegment = ' An agreement for this booking already exists.';
            }
            // --- END: Logic for creating Agreement and Payment ---

            DB::commit(); // Commit transaction if all successful

            // Notify the tenant
            $tenant = $booking->tenant;
            if ($tenant) {
                $tenant->notify(new BookingStatusUpdated($booking, $newStatus));
            }

            return redirect()->route('bookings.show', $booking)->with('success', 'Booking accepted successfully.' . $agreementMessageSegment);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction on error
            Log::error("Error accepting booking or creating agreement/payment for Booking ID {$booking->id}: " . $e->getMessage());
            return redirect()->route('bookings.show', $booking)->with('error', 'An error occurred while processing the booking. Please try again.');
        }
    }

    public function rejectBooking(Booking $booking): RedirectResponse
    {
        $booking->load('house'); // Ensure house relation is loaded

        if (!$booking->house) {
            return redirect()->back()->with('error', 'Associated property not found for this booking.');
        }

        // Authorization: Ensure the authenticated user is the landlord of the house for this booking.
        if (Auth::id() !== $booking->house->landlord_id) {
            abort(403, 'Unauthorized action. You do not own the house associated with this booking.');
        }

        // Prevent re-processing if already accepted or rejected
        if ($booking->status !== 'pending') {
            return redirect()->route('bookings.show', $booking)->with('info', 'This booking has already been processed.');
        }

        $newStatus = 'rejected';
        $booking->status = $newStatus;
        $booking->save();

        $tenant = $booking->tenant;

        if ($tenant) {
            $tenant->notify(new BookingStatusUpdated($booking, $newStatus));
        }

        return redirect()->route('bookings.show', $booking)->with('success', 'Booking rejected successfully.');
    }

    public function scheduleCashAppointment(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'date' => 'required|date',
            'time' => 'required',
        ]);

        $booking = Booking::findOrFail($request->booking_id);
        $booking->appointment_date = $request->date;
        $booking->appointment_time = $request->time;
        $booking->save();

        return redirect()->back()->with('success', 'Appointment scheduled successfully!');
    }
}
