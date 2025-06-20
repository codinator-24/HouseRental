<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use App\Models\Agreement;
use App\Models\Payment; // Original payments model
use App\Models\MaintenancePayment; // New model for maintenance payments
use App\Models\Booking;
use App\Models\Maintenance; // Added Maintenance model
use App\Http\Controllers\MaintenanceController; // Added MaintenanceController for callback
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth; // Added Auth Facade
use App\Notifications\KeyDeliveryReminder; // Added for notification

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
                'key_delivery_deadline' => Carbon::now()->addMonth(),
            ]);

            // Create the Payment record
            $rentAmount = $session->metadata->rent_amount ?? ($session->amount_total / 100);

            Payment::create([
                'agreement_id' => $agreement->id,
                'amount' => $rentAmount,
                'payment_method' => 'Credit', // Since it's Stripe payment
                'payment_deadline' => Carbon::now()->addMonth(),
                'status' => 'paid',
                'paid_at' => Carbon::now(),
                'notes' => 'Initial rental agreement payment via Stripe. Session ID: ' . $sessionId,
            ]);

            // Optionally update booking status
            $booking->update(['status' => 'agreement_signed']); // Adjust field name as per your booking table

            // Send notification to landlord for key delivery
            $landlord = $agreement->landlord; // Using the accessor from Agreement model
            if ($landlord && $agreement->key_delivery_deadline) {
                try {
                    $landlord->notify(new KeyDeliveryReminder($agreement));
                } catch (\Exception $e) {
                    Log::error("Failed to send KeyDeliveryReminder notification for agreement ID {$agreement->id}: " . $e->getMessage());
                    // Optionally, decide if this failure should affect the user's redirect or message
                }
            }

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

    public function checkoutForMaintenance(Request $request, Maintenance $maintenance)
    {
        Stripe::setApiKey(config('stripe.sk'));

        $sessionData = session('pending_maintenance_payment_data');

        if (!$sessionData || !isset($sessionData['maintenance_id']) || $sessionData['maintenance_id'] !== $maintenance->id) {
            return redirect()->route('dashboard')->with('error', 'Invalid maintenance payment session. Please try again.')->with('active_tab', 'maintenance');
        }

        $amountToPay = (float) $sessionData['amount'];
        $landlordResponse = $sessionData['response']; // Will be passed in metadata

        if ($amountToPay <= 0) {
             // This case should ideally be handled by MaintenanceController before redirecting here.
             // If it reaches here, it means no payment is actually required.
             // We could redirect to a direct acceptance or show an error.
             // For now, let's assume MaintenanceController handles the $0 case.
            return redirect()->route('dashboard')->with('error', 'No payment amount specified for maintenance acceptance.')->with('active_tab', 'maintenance');
        }

        $unitAmountInCents = (int) ($amountToPay * 100);

        $stripeSession = Session::create([
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Maintenance Request Fee - Request #' . $maintenance->id,
                        ],
                        'unit_amount' => $unitAmountInCents,
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => route('maintenance.payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('maintenance.payment.cancel') . '?maintenance_id=' . $maintenance->id,
            'metadata' => [
                'maintenance_id' => $maintenance->id,
                'payment_type' => 'maintenance',
                'landlord_response' => $landlordResponse, // Pass response in metadata
                'amount_charged' => $amountToPay
            ]
        ]);

        return redirect()->away($stripeSession->url);
    }

    public function maintenancePaymentSuccess(Request $request)
    {
        $sessionId = $request->get('session_id');
        if (!$sessionId) {
            return redirect()->route('dashboard')->with('error', 'Invalid payment session.')->with('active_tab', 'maintenance');
        }

        try {
            Stripe::setApiKey(config('stripe.sk'));
            $stripeSession = Session::retrieve($sessionId);

            if ($stripeSession->payment_status !== 'paid') {
                $maintenanceId = $stripeSession->metadata->maintenance_id ?? null;
                return redirect()->route('dashboard')
                    ->with('error', 'Payment was not successful.')
                    ->with('active_tab', 'maintenance')
                    ->with('open_modal_request_id', $maintenanceId); // Attempt to reopen modal
            }

            $maintenanceId = $stripeSession->metadata->maintenance_id;
            $landlordResponse = $stripeSession->metadata->landlord_response;
            // $amountPaid = $stripeSession->metadata->amount_charged ?? ($stripeSession->amount_total / 100);


            if (!$maintenanceId || $landlordResponse === null) {
                 Log::error("Stripe Webhook/Success: Missing maintenance_id or landlord_response in metadata for session {$sessionId}");
                return redirect()->route('dashboard')->with('error', 'Critical payment data missing. Please contact support.')->with('active_tab', 'maintenance');
            }

            $maintenance = Maintenance::find($maintenanceId);
            if (!$maintenance) {
                Log::error("Stripe Webhook/Success: Maintenance record not found for ID {$maintenanceId} from session {$sessionId}");
                return redirect()->route('dashboard')->with('error', 'Maintenance record not found.')->with('active_tab', 'maintenance');
            }

            // Call the logic in MaintenanceController
            $maintenanceController = app(MaintenanceController::class);
            
            $paymentDetails = [
                'stripe_session_id' => $stripeSession->id,
                'amount' => $stripeSession->metadata->amount_charged ?? ($stripeSession->amount_total / 100), // Ensure this matches what MaintenancePayment expects
                'currency' => $stripeSession->currency ?? 'usd', // Stripe session object has currency
            ];

            // The finalizePaidMaintenanceAcceptance method now handles creating the MaintenancePayment record
            // and forgetting the session data.
            $acceptanceResult = $maintenanceController->finalizePaidMaintenanceAcceptance($maintenance, $landlordResponse, $paymentDetails);

            if ($acceptanceResult === true) {
                // finalizePaidMaintenanceAcceptance returns true on success, redirect is handled by it.
                // However, finalizePaidMaintenanceAcceptance is designed to return true, and this controller handles the redirect.
                return redirect()->route('dashboard')
                                 ->with('success', 'Payment successful and maintenance request accepted!')
                                 ->with('active_tab', 'maintenance');
            } else if (is_a($acceptanceResult, \Illuminate\Http\RedirectResponse::class)) {
                // If finalizePaidMaintenanceAcceptance itself returned a redirect (e.g. on auth error inside it)
                return $acceptanceResult;
            }
             else {
                // If executeAcceptanceLogic returned something else (e.g. false or an error indicator not a redirect)
                Log::error("Stripe Webhook/Success: finalizePaidMaintenanceAcceptance failed for maintenance ID {$maintenanceId} from session {$sessionId}");
                 return redirect()->route('dashboard')
                                 ->with('error', 'Failed to finalize maintenance acceptance after payment. Please contact support.')
                                 ->with('active_tab', 'maintenance');
            }

        } catch (\Stripe\Exception\ApiErrorException $e) {
            Log::error('Stripe API Error during maintenance payment success: ' . $e->getMessage() . " for session {$sessionId}");
            return redirect()->route('dashboard')->with('error', 'Stripe API error: ' . $e->getMessage())->with('active_tab', 'maintenance');
        } catch (\Exception $e) {
            Log::error('General Error during maintenance payment success: ' . $e->getMessage() . " for session {$sessionId}");
            return redirect()->route('dashboard')->with('error', 'An error occurred: ' . $e->getMessage())->with('active_tab', 'maintenance');
        }
    }

    public function maintenancePaymentCancel(Request $request)
    {
        $maintenanceId = $request->get('maintenance_id');
        // Clear session data if payment is cancelled
        if ($maintenanceId && session()->has('pending_maintenance_payment_data') && session('pending_maintenance_payment_data')['id'] == $maintenanceId) {
            session()->forget('pending_maintenance_payment_data');
        }

        return redirect()->route('dashboard')
                         ->with('error', 'Payment was cancelled.')
                         ->with('active_tab', 'maintenance')
                         ->with('open_modal_request_id', $maintenanceId); // Attempt to reopen modal
    }
}
