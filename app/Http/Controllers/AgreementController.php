<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use function Ramsey\Uuid\v8;

class AgreementController extends Controller
{
  public function create(Booking $booking)
    {
        // Eager load necessary relationships for efficiency
        $booking->load([
            'tenant', // User model for tenant
            'house.landlord', // User model for landlord, via House
            'house.floors' // Floor models via House
        ]);

        // Attempt to find an existing agreement for this booking.
        // This will retrieve its status (e.g., 'pending'), amounts, dates, etc.
        $agreement = Agreement::where('booking_id', $booking->id)->first();

        // If an existing agreement is found, use its dates.
        // Otherwise, calculate default dates for a new agreement.
        if ($agreement) {
            // Ensure these are Carbon instances, falling back if they are somehow null in DB
            $signedDate = $agreement->signed_at ? Carbon::parse($agreement->signed_at) : Carbon::now();
            $expiresDate = $agreement->expires_at ? Carbon::parse($agreement->expires_at) : Carbon::now()->addMonths($booking->month_duration);
        } else {
            $signedDate = Carbon::now();
            $expiresDate = Carbon::now()->addMonths($booking->month_duration);
        }

        return view('agreements.CreateAgreement', [
            'booking' => $booking,
            'agreement' => $agreement, // Pass the existing agreement (or null if not found)
            'signedDate' => $signedDate, // Use determined signedDate
            'expiresDate' => $expiresDate, // Use determined expiresDate
        ]);
    }


        public function CashAppointment(Request $request)
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|exists:bookings,id',
            'rent_amount' => 'required|numeric|min:0.01', // Assuming rent_amount is passed
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $bookingId = $request->input('booking_id');
            $rentAmount = $request->input('rent_amount');

            $booking = Booking::with(['house', 'tenant'])->findOrFail($bookingId);
            $agreement = Agreement::where('booking_id', $bookingId)->first();

            if (!$agreement) {
                // Based on StripeController's success method, an agreement is expected.
                return redirect()->route('agreement.create', ['booking' => $bookingId])
                                 ->with('error', 'No agreement found for this booking. Please initiate the agreement first.');
            }

            // Update Agreement
            $expiresDate = Carbon::now()->copy()->addMonths($booking->month_duration ?? 1);
            $agreement->update([
                'status' => 'agreed', // User can change this to 'pending_cash_payment' if needed
                'signed_at' => Carbon::now(),
                'expires_at' => $expiresDate,
            ]);

            // Create Payment Record
            Payment::create([
                'agreement_id' => $agreement->id,
                'amount' => $rentAmount,
                'payment_method' => 'cash',
                'status' => 'paying',
                'payment_deadline' => Carbon::now()->addMonth(),
                'paid_at' => null,
                'notes' => 'Cash payment initiated for rental agreement. Booking ID: ' . $bookingId,
            ]);

            // Update Booking Status
            $booking->update(['status' => 'agreement_signed']); // User can change this to 'awaiting_cash_payment'

            return redirect()->route('agreement.create', ['booking' => $bookingId])
                             ->with('success', 'Cash appointment successfully recorded. Payment is pending.');

        } catch (\Exception $e) {
            Log::error('Cash Appointment processing failed: ' . $e->getMessage());
            $bookingIdForRedirect = $request->input('booking_id', ''); // Fallback if bookingId not set before error
            return redirect()->route('agreement.create', ['booking' => $bookingIdForRedirect])
                             ->with('error', 'An error occurred while processing the cash appointment. Please try again.');
        }
    }
}
