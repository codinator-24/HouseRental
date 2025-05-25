<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; // Ensure Request is imported
use Stripe\Checkout\Session;
use Stripe\Stripe;
class StripeController extends Controller
{

    public function pay()
    {
        return view('payment/pay');
    }

    public function checkout(Request $request) // Modified to accept Request
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'rent_amount_checkout' => 'required|numeric|min:0.50', // Stripe typically has a minimum charge (e.g., $0.50)
            'booking_id' => 'required|exists:bookings,id', // Ensure booking_id is valid
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
                            // You can add more product details if needed
                            // 'description' => 'Payment for house rental agreement.',
                        ],
                        'unit_amount' => $unitAmountInCents, // Use the dynamic amount in cents
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            // It's good practice to pass identifiers to your success and cancel URLs
            // so you can handle post-payment logic appropriately.
            'success_url' => route('success') . '?session_id={CHECKOUT_SESSION_ID}&booking_id=' . $bookingId,
            'cancel_url' => route('agreement.create', ['booking' => $bookingId, 'status' => 'payment_cancelled']), // Or a generic cancel page
            'metadata' => [
                'booking_id' => $bookingId,
                // Add any other metadata you want to associate with the Stripe payment
            ]
        ]);
        return redirect()->away($session->url);
    }

    public function success() {}
}
