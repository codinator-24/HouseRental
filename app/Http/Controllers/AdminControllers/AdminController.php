<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Agreement;
use Illuminate\Http\Request;
use App\Models\House;
use App\Models\User;
use App\Models\HousePicture;
use App\Notifications\AccountVerified;
use App\Notifications\HouseApproved;
use App\Models\Floor;
use App\Models\Feedback;
use App\Models\Payment;
use App\Models\Report; // Added Report model
use Carbon\Carbon;
use App\Notifications\TenantCashDeadlineUpdated; // Added for notification
use App\Notifications\LandlordKeyDeadlineUpdated; // Added for notification
use App\Notifications\TenantKeyCollectionReminder; // Added for key collection notification
use App\Notifications\LandlordCashReceivedNotification; // Added for cash received notification
use Illuminate\Support\Facades\Log; // Added for logging notification errors

class AdminController extends  Controller
{

    public function dashboard()
    {
        //this is to count all tables data
        $users = User::count();
        $houses = House::where('status', 'available')->count();
        $feedbacks = Feedback::count();
        $aproves = House::where('status', 'disagree')->count();
        $landlords = User::where('role', 'lordland')->count();
        $tenants = User::where('role', 'Tenant')->count();
        $verify = User::where('status', 'Not Verified')->count();
        $bothes = User::where('role', 'both')->count();
        return view('admin.dashboard', compact('users', 'houses', 'landlords', 'tenants', 'aproves', 'verify', 'bothes', 'feedbacks'));
    }

    public function viewaprove()
    {
        $houses = House::all();
        $images = HousePicture::all();
        $floors = Floor::all();
        return view('admin/aprove', compact('houses', 'images', 'floors'));
    }

    public function view_house()
    {
        $houses = House::all();
        $images = HousePicture::all();
        $floors = Floor::all();
        return view('admin/houses', compact('houses', 'images', 'floors'));
    }

    public function view_allhouse()
    {
        $houses = House::all();
        $images = HousePicture::all();
        $floors = Floor::all();
        return view('admin/houses', compact('houses', 'images', 'floors'));
    }

    public function viewusers()
    {
        $data = User::all();
        return view('admin/users', compact('data'));
    }
    public function viewfeedback()
    {
        $feedbacks = Feedback::latest()->get();
        $reports = Report::with(['reporter', 'house', 'reportedUser'])->latest()->get();
        return view('admin/feedback', compact('feedbacks', 'reports'));
    }

    public function updateReportStatus(Request $request, Report $report)
    {
        $request->validate([
            'status' => 'required|string|in:pending,under_review,resolved,dismissed',
        ]);

        $report->status = $request->status;
        $report->save();

        return redirect()->route('feedback')->with('success', 'Report status updated successfully.');
    }

    // Potentially for a dedicated report view page or AJAX data source for modal
    public function showReportDetails(Report $report)
    {
        // For now, if accessed directly, redirect back or show a simple view.
        // Or, return as JSON if we decide to fetch modal content via AJAX.
        // For simplicity with current plan, modal will be populated by data already on page.
        return response()->json($report->load(['reporter', 'house', 'reportedUser']));
    }

    public function view_aprove_user()
    {
        $data = User::all();
        return view('admin/aprove-user', compact('data'));
    }

    public function approve_house($id)
    {
        $house = House::findOrFail($id);
        $house->status = 'available';
        $house->save();

        // Notify the landlord
        if ($house->landlord) {
            $house->landlord->notify(new HouseApproved($house));
        }

        return redirect('/approve');
    }


    public function delete_aprove($id)
    {

        $data = House::find($id);
        $data->delete();
        return redirect()->back();
    }

    public function delete_user($id)
    {

        $data = User::find($id);
        $data->delete();
        return redirect()->back();
    }

    public function delete_feedback($id)
    {

        $data = Feedback::find($id);
        $data->delete();
        return redirect()->back();
    }

    public function approve_user($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'Verified';
        $user->save();

        // Notify the user
        $user->notify(new AccountVerified($user));

        return redirect('/approve-user');
    }

    public function deactivate_user($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'Not Verified';
        $user->save();

        // Notify the user
        $user->notify(new AccountVerified($user));

        return redirect()->back();
    }

    public function deactivate_house($id)
    {
        $house = House::findOrFail($id);
        $house->status = 'disagree';
        $house->save();
        return redirect()->back();
    }

   public function ViewProfit()
{
    // Your new profit data
    $profitData = [
        'labels' => ['Houses', 'Apartments', 'Commercials'],
        'data' => [25.3, 17.4, 12.0], // in thousands
    ];
    
    // Calculate totals
    $totalProfit = array_sum($profitData['data']);
    $fivePercentOfTotal = $totalProfit * 0.05;
    
    // Colors for the chart segments
    $colors = [
        'rgba(54, 162, 235, 0.7)',   // Blue for Houses
        'rgba(255, 99, 132, 0.7)',   // Red for Apartments
        'rgba(255, 206, 86, 0.7)',   // Yellow for Commercials
    ];
    
    $borderColors = [
        'rgba(54, 162, 235, 1)',
        'rgba(255, 99, 132, 1)',
        'rgba(255, 206, 86, 1)',
    ];
    
    $chartDataFromServer = [
        'labels' => $profitData['labels'],
        'datasets' => [
            [
                'label' => 'Profit Distribution',
                'data' => $profitData['data'],
                'backgroundColor' => array_slice($colors, 0, count($profitData['data'])),
                'borderColor' => array_slice($borderColors, 0, count($profitData['data'])),
                'borderWidth' => 2
            ]
        ]
    ];
    
    // Pass additional data for display
    $additionalData = [
        'totalProfit' => $totalProfit,
        'fivePercentOfTotal' => $fivePercentOfTotal
    ];
    
    return view('admin.profit', compact('chartDataFromServer', 'additionalData'));
}

    public function ViewAgreement()
    {
        // Fetch agreements with necessary relationships
        $agreements = Agreement::with([
            'booking.tenant', // To get tenant info
            'booking.house.landlord' // To get house info and landlord info
        ])->latest()->get();

        return view('admin.agreements', compact('agreements'));
    }

    public function ViewPayment()
    {
        // Fetch payments with necessary relationships
        $payments = Payment::with([
            'agreement.booking.tenant', // To get tenant info
            'agreement.booking.house.landlord', // To get house info and landlord info for the agreement accessor
            'agreement' // To ensure agreement fields like key_delivery_deadline are loaded
        ])->latest()->get();
        return view('admin.payments', compact('payments'));
    }

    public function updateCashPaymentDetails(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'cash_payment_deadline' => 'nullable|date',
            'cash_payment_received' => 'nullable|boolean',
            'key_delivery_deadline' => 'nullable|date',
            'key_handed_over' => 'nullable|boolean',
        ]);

        try {
            // Eager load necessary relationships
            $payment->loadMissing('agreement.booking.tenant', 'agreement.booking.house.landlord');

            // Store original deadlines and status for comparison
            $originalPaymentDeadlineString = $payment->payment_deadline ? Carbon::parse($payment->payment_deadline)->toDateString() : null;
            $originalPaymentStatus = $payment->status; // Store original payment status
            $originalKeyDeliveryDeadlineString = null;
            $originalLandlordKeysDelivered = false;

            if ($payment->agreement) {
                if ($payment->agreement->key_delivery_deadline) {
                    $originalKeyDeliveryDeadlineString = Carbon::parse($payment->agreement->key_delivery_deadline)->toDateString();
                }
                $originalLandlordKeysDelivered = (bool) $payment->agreement->landlord_keys_delivered;
            }

            $paymentDeadlineChanged = false;
            $keyDeliveryDeadlineChanged = false;
            // Variable to track if keys_delivered status changed to true
            $keysNowDelivered = false;
            $paymentMarkedAsReceived = false; // Flag to track if cash_payment_received was true in this request

            // Handle payment deadline update
            if ($request->boolean('cash_payment_received')) {
                $payment->status = 'paid';
                $payment->paid_at = Carbon::now();
                $paymentMarkedAsReceived = true; // Set flag
            } else {
                // Only update payment_deadline if cash_payment_received is false and the field is provided
                if ($request->filled('cash_payment_deadline')) {
                    $newDeadline = Carbon::parse($validated['cash_payment_deadline'])->toDateString();
                    if ($newDeadline !== $originalPaymentDeadlineString) {
                        $payment->payment_deadline = $validated['cash_payment_deadline'];
                        $paymentDeadlineChanged = true;
                    }
                }
                $payment->status = 'paying';
                $payment->paid_at = null;
            }
            
            $payment->save();

            // Notify tenant if cash payment deadline changed and payment not marked as received
            if ($paymentDeadlineChanged && !$request->boolean('cash_payment_received')) {
                $agreementForTenant = $payment->agreement;
                if ($agreementForTenant && $agreementForTenant->booking && $agreementForTenant->booking->tenant) {
                    try {
                        $agreementForTenant->booking->tenant->notify(new TenantCashDeadlineUpdated($payment, $agreementForTenant));
                    } catch (\Exception $e) {
                        Log::error("Failed to send TenantCashDeadlineUpdated notification for payment ID {$payment->id}: " . $e->getMessage());
                    }
                }
            }
            
            // Notify Landlord if cash payment was marked as received and status changed to 'paid'
            if ($paymentMarkedAsReceived && $payment->status === 'paid' && $originalPaymentStatus !== 'paid') {
                $agreementForCashNotification = $payment->agreement;
                if ($agreementForCashNotification && $agreementForCashNotification->booking && $agreementForCashNotification->booking->house && $agreementForCashNotification->booking->house->landlord) {
                    try {
                        $agreementForCashNotification->booking->house->landlord->notify(new LandlordCashReceivedNotification($payment, $agreementForCashNotification));
                    } catch (\Exception $e) {
                        Log::error("Failed to send LandlordCashReceivedNotification for payment ID {$payment->id}: " . $e->getMessage());
                    }
                }
            }

            // Handle key delivery deadline update
            $agreementForLandlord = $payment->agreement;
            if ($agreementForLandlord) {
                if ($request->filled('key_delivery_deadline')) {
                    $newKeyDeadline = Carbon::parse($validated['key_delivery_deadline'])->toDateString();
                    if ($newKeyDeadline !== $originalKeyDeliveryDeadlineString) {
                        $agreementForLandlord->key_delivery_deadline = $validated['key_delivery_deadline'];
                        $keyDeliveryDeadlineChanged = true;
                    }
                }
                $agreementForLandlord->landlord_keys_delivered = $request->boolean('key_handed_over');
                
                // Save agreement only if something related to it changed
                if ($keyDeliveryDeadlineChanged || $agreementForLandlord->isDirty('landlord_keys_delivered') || $agreementForLandlord->isDirty('key_delivery_deadline')) {
                    $agreementForLandlord->save();
                }

                // Notify landlord if key delivery deadline changed
                if ($keyDeliveryDeadlineChanged) {
                    if ($agreementForLandlord->booking && $agreementForLandlord->booking->house && $agreementForLandlord->booking->house->landlord) {
                        try {
                            $agreementForLandlord->booking->house->landlord->notify(new LandlordKeyDeadlineUpdated($agreementForLandlord));
                        } catch (\Exception $e) {
                            Log::error("Failed to send LandlordKeyDeadlineUpdated notification for agreement ID {$agreementForLandlord->id}: " . $e->getMessage());
                        }
                    }
                }

                // Check if landlord_keys_delivered changed to true
                $newLandlordKeysDelivered = (bool) $agreementForLandlord->landlord_keys_delivered;
                if ($newLandlordKeysDelivered && !$originalLandlordKeysDelivered) {
                    $keysNowDelivered = true; // Set flag
                }

                // Notify Tenant if keys are now marked as delivered
                if ($keysNowDelivered) {
                    if ($agreementForLandlord->booking && $agreementForLandlord->booking->tenant) {
                        try {
                            $agreementForLandlord->booking->tenant->notify(new TenantKeyCollectionReminder($agreementForLandlord));
                        } catch (\Exception $e) {
                            Log::error("Failed to send TenantKeyCollectionReminder for agreement ID {$agreementForLandlord->id} from cash update: " . $e->getMessage());
                        }
                    }
                }
            }

            return response()->json(['success' => true, 'message' => 'Cash payment details updated successfully.']);
        } catch (\Exception $e) {
            Log::error('Error updating cash payment details: ' . $e->getMessage() . ' Stack: ' . $e->getTraceAsString());
            return response()->json(['success' => false, 'message' => 'Error updating details: ' . $e->getMessage()], 500);
        }
    }

    public function updateCreditLandlordDetails(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'credit_key_delivery_deadline' => 'nullable|date',
            'credit_key_handed_over' => 'nullable|boolean',
        ]);

        try {
            if (!$payment->agreement) {
                return response()->json(['success' => false, 'message' => 'Agreement not found for this payment.'], 404);
            }
            
            // Eager load necessary relationships for agreement
            $payment->agreement->loadMissing('booking.house.landlord', 'booking.tenant'); // Also load tenant for key collection notification

            $originalKeyDeliveryDeadlineString = $payment->agreement->key_delivery_deadline ? Carbon::parse($payment->agreement->key_delivery_deadline)->toDateString() : null;
            $originalLandlordKeysDeliveredCredit = (bool) $payment->agreement->landlord_keys_delivered; // Store original state for this function too
            $keyDeliveryDeadlineChanged = false;
            $keysNowDeliveredCredit = false; // Flag for this function

            if ($request->filled('credit_key_delivery_deadline')) {
                $newKeyDeadline = Carbon::parse($validated['credit_key_delivery_deadline'])->toDateString();
                 if ($newKeyDeadline !== $originalKeyDeliveryDeadlineString) {
                    $payment->agreement->key_delivery_deadline = $validated['credit_key_delivery_deadline'];
                    $keyDeliveryDeadlineChanged = true;
                }
            }
            $payment->agreement->landlord_keys_delivered = $request->boolean('credit_key_handed_over');
            
            if ($keyDeliveryDeadlineChanged || $payment->agreement->isDirty('landlord_keys_delivered')) {
                $payment->agreement->save();
            }

            // Notify landlord if key delivery deadline changed
            // This logic is duplicated from updateCashPaymentDetails, consider refactoring if it grows
            if ($keyDeliveryDeadlineChanged) {
                // Access landlord via booking and house relationships
                if ($payment->agreement && $payment->agreement->booking && $payment->agreement->booking->house && $payment->agreement->booking->house->landlord) {
                     try {
                        $payment->agreement->booking->house->landlord->notify(new LandlordKeyDeadlineUpdated($payment->agreement));
                    } catch (\Exception $e) {
                        Log::error("Failed to send LandlordKeyDeadlineUpdated from credit update for agreement ID {$payment->agreement->id}: " . $e->getMessage());
                    }
                }
            }

            // Check if landlord_keys_delivered changed to true in this function's context
            $newLandlordKeysDeliveredCredit = (bool) $payment->agreement->landlord_keys_delivered;
            if ($newLandlordKeysDeliveredCredit && !$originalLandlordKeysDeliveredCredit) {
                $keysNowDeliveredCredit = true;
            }

            // Notify Tenant if keys are now marked as delivered
            if ($keysNowDeliveredCredit) {
                if ($payment->agreement && $payment->agreement->booking && $payment->agreement->booking->tenant) {
                    try {
                        $payment->agreement->booking->tenant->notify(new TenantKeyCollectionReminder($payment->agreement));
                    } catch (\Exception $e) {
                        Log::error("Failed to send TenantKeyCollectionReminder for agreement ID {$payment->agreement->id} from credit update: " . $e->getMessage());
                    }
                }
            }

            return response()->json(['success' => true, 'message' => 'Landlord details updated successfully.']);
        } catch (\Exception $e) {
            Log::error('Error updating credit landlord details: ' . $e->getMessage() . ' Stack: ' . $e->getTraceAsString());
            return response()->json(['success' => false, 'message' => 'Error updating details: ' . $e->getMessage()], 500);
        }
    }
}
