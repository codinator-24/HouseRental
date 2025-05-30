<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use App\Models\Agreement;
use App\Models\Payment;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class StripeController extends Controller
{
    public function pay()
    {
        return view('payment/pay');
    }

    public function checkout(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'rent_amount_checkout' => 'required|numeric|min:0.50',
            'booking_id' => 'required|exists:bookings,id',
        ]);

        Stripe::setApiKey(config('stripe.sk'));

        $rentAmount = floatval($validatedData['rent_amount_checkout']);
        $bookingId = $validatedData['booking_id'];

        // Convert the rent amount to cents, as Stripe expects the amount in the smallest currency unit
        $unitAmountInCents = (int) ($rentAmount * 100);

        $session = Session::create([
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Rental Agreement Payment - Booking #' . $bookingId,
                        ],
                        'unit_amount' => $unitAmountInCents,
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => route('success') . '?session_id={CHECKOUT_SESSION_ID}&booking_id=' . $bookingId,
            'cancel_url' => route('agreement.create', ['booking' => $bookingId, 'status' => 'payment_cancelled']),
            'metadata' => [
                'booking_id' => $bookingId,
                'rent_amount' => $rentAmount,
            ]
        ]);

        return redirect()->away($session->url);
    }

    public function success(Request $request)
    {
        try {
            // Get parameters from the URL
            $sessionId = $request->get('session_id');
            $bookingId = $request->get('booking_id');

            if (!$sessionId || !$bookingId) {
                return redirect()->route('home')->with('error', 'Invalid payment session.');
            }

            // Initialize Stripe
            Stripe::setApiKey(config('stripe.sk'));

            // Retrieve the checkout session from Stripe
            $session = Session::retrieve($sessionId);

            // Verify the payment was successful
            if ($session->payment_status !== 'paid') {
                return redirect()->route('agreement.create', ['booking' => $bookingId])
                    ->with('error', 'Payment was not successful.');
            }

            // Get the booking to extract necessary data
            $booking = Booking::with(['house', 'tenant'])->findOrFail($bookingId);

            // Check if agreement exists for this booking
            $agreement = Agreement::where('booking_id', $bookingId)->first();
            if (!$agreement) {
                return redirect()->route('agreement.create', ['booking' => $bookingId])
                    ->with('error', 'No agreement found for this booking.');
            }

            $expiresDate = Carbon::now()->copy()->addMonths($booking->month_duration ?? 1); // Default to 1 month if duration not set
            // Update the Agreement status to agreed/active
            $agreement->update([
                'status' => 'agreed', // or 'active' depending on your preference
                'signed_at' => Carbon::now(), // Update signed date when agreement is finalized
                'expires_at' => $expiresDate,
            ]);

            // Create the Payment record
            $rentAmount = $session->metadata->rent_amount ?? ($session->amount_total / 100);

            Payment::create([
                'agreement_id' => $agreement->id,
                'amount' => $rentAmount,
                'payment_method' => 'Credit', // Since it's Stripe payment
                'status' => 'completed',
                'paid_at' => Carbon::now(),
                'notes' => 'Initial rental agreement payment via Stripe. Session ID: ' . $sessionId,
            ]);

            // Optionally update booking status
            $booking->update(['status' => 'agreement_signed']); // Adjust field name as per your booking table

            // Redirect back to agreement creation page with success message
            return redirect()->route('agreement.create', ['booking' => $bookingId])
                ->with('success', 'Agreement signed successfully and payment processed!');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Payment success processing failed: ' . $e->getMessage());

            return redirect()->route('agreement.create', ['booking' => $bookingId ?? ''])
                ->with('error', 'An error occurred while processing your payment. Please contact support.');
        }
    }
}
