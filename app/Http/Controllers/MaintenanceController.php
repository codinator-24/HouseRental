<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\MaintenancePayment;
use App\Notifications\NewMaintenanceRequestForLandlord; // Added
use App\Notifications\MaintenanceRequestResponseForTenant; // Added
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session; // Added
use Illuminate\Support\Facades\Log; // Added for finalizePaidMaintenanceAcceptance


class MaintenanceController extends Controller
{
    public function InsertMaintenance(Request $request)
    {
      $validator = Validator::make($request->all(), [
            'house_id' => 'required|exists:houses,id',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'area_of_house' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'refund_amount' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator, 'newMaintenanceRequestErrors')
                        ->withInput()
                        ->with('error_modal_open', 'newMaintenanceRequestModal'); // To re-open modal
        }

        $data = $validator->validated();

        $data['tenant_id'] = Auth::id();
        $data['status'] = 'pending'; // Default status

        if ($request->hasFile('picture')) {
            $path = $request->file('picture')->store('maintenance_pictures', 'public');
            $data['picture'] = $path;
        }

        $maintenance = Maintenance::create($data);

        // Notify Landlord
        if ($maintenance->house && $maintenance->house->landlord) {
            $maintenance->house->landlord->notify(new NewMaintenanceRequestForLandlord($maintenance));
        }

        return redirect()->route('dashboard')
                         ->with('success', 'Maintenance request submitted successfully!')
                         ->with('active_tab', 'maintenance'); // To switch to the maintenance tab
    }

    public function tenantUpdate(Request $request, Maintenance $maintenance)
    {
        // Authorization: Check if the authenticated user is the tenant who created the request
        if (Auth::id() !== $maintenance->tenant_id) {
            return redirect()->route('dashboard')
                             ->with('error', 'You are not authorized to update this request.')
                             ->with('active_tab', 'maintenance');
        }

        // Check if the request is in an updatable state
        $updatableStatuses = ['pending', 'in_progress', 'needs_tenant_input']; // Define which statuses allow updates
        if (!in_array($maintenance->status, $updatableStatuses)) {
            return redirect()->route('dashboard')
                             ->with('error', 'This request can no longer be updated.')
                             ->with('active_tab', 'maintenance');
        }

        $validator = Validator::make($request->all(), [
            'description' => 'required|string|max:5000',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        
        // Define a unique error bag for this modal
        $errorBagName = 'updateSentMaintenanceRequestErrors_' . $maintenance->id;

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator, $errorBagName)
                        ->withInput()
                        ->with('error_modal_open', 'updateSentMaintenanceRequestModal')
                        ->with('open_modal_request_id_tenant_update', $maintenance->id);
        }

        $validatedData = $validator->validated();

        $maintenance->description = $validatedData['description'];

        if ($request->hasFile('picture')) {
            // Delete old picture if it exists
            if ($maintenance->picture) {
                Storage::disk('public')->delete($maintenance->picture);
            }
            $path = $request->file('picture')->store('maintenance_pictures', 'public');
            $maintenance->picture = $path;
        }

        $maintenance->save();

        return redirect()->route('dashboard')
                         ->with('success', 'Maintenance request updated successfully!')
                         ->with('active_tab', 'maintenance');
    }

    public function tenantCancel(Maintenance $maintenance)
    {
        // Authorization: Check if the authenticated user is the tenant
        if (Auth::id() !== $maintenance->tenant_id) {
            return redirect()->route('dashboard')
                             ->with('error', 'You are not authorized to cancel this request.')
                             ->with('active_tab', 'maintenance');
        }

        // Check if the request is in a cancellable state (e.g., only 'pending')
        if ($maintenance->status !== 'pending') {
            return redirect()->route('dashboard')
                             ->with('error', 'This request cannot be cancelled at its current status.')
                             ->with('active_tab', 'maintenance');
        }

        $maintenance->status = 'cancelled';
        $maintenance->save();

        return redirect()->route('dashboard')
                         ->with('success', 'Maintenance request cancelled successfully.')
                         ->with('active_tab', 'maintenance');
    }

    public function processLandlordResponse(Request $request, Maintenance $maintenance)
    {
        // Authorization: Check if the authenticated user is the landlord of the house
        // The Maintenance model has a 'house' relationship,
        // and the House model has a 'landlord_id' attribute.
        if (Auth::id() !== $maintenance->house->landlord_id) {
            return redirect()->route('dashboard')
                             ->with('error', 'You are not authorized to respond to this request.')
                             ->with('active_tab', 'maintenance');
        }

        $validator = Validator::make($request->all(), [
            'landlord_response' => 'required|string|max:5000',
            'action' => 'required|in:accept,reject', // Expecting 'accept' or 'reject'
        ]);

        // Define a unique error bag for this modal/form if needed, similar to other functions
        // For simplicity, using a generic error handling for now.
        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput()
                        // Potentially add a way to re-open the specific modal, e.g., by passing maintenance ID
                        ->with('error_modal_open', 'receivedMaintenanceRequestDetailModal_' . $maintenance->id) // Example
                        ->with('active_tab', 'maintenance');
        }

        $validatedData = $validator->validated();

        if ($validatedData['action'] === 'accept') {
            $maintenance->status = 'accepted';
        } elseif ($validatedData['action'] === 'reject') {
            $maintenance->status = 'rejected';
        }

        $maintenance->landlord_response = $validatedData['landlord_response'];
        $maintenance->save();

        return redirect()->route('dashboard')
                         ->with('success', 'Response submitted successfully!')
                         ->with('active_tab', 'maintenance');
    }

    public function initiateAcceptancePayment(Request $request, Maintenance $maintenance)
    {
        // Authorization: Ensure the authenticated user is the landlord of the house
        if (Auth::id() !== $maintenance->house->landlord_id) {
            return redirect()->route('dashboard')
                             ->with('error', 'You are not authorized to perform this action.')
                             ->with('active_tab', 'maintenance');
        }

        // Validate landlord_response
        $validator = Validator::make($request->all(), [
            'landlord_response' => 'required|string|max:5000',
        ]);

        // Use a unique error bag name that includes the maintenance ID
        $errorBagName = 'landlordResponseErrors_' . $maintenance->id;

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator, $errorBagName)
                        ->withInput()
                        ->with('error_modal_open', 'receivedMaintenanceRequestDetailModal_' . $maintenance->id) // To reopen the modal
                        ->with('open_modal_request_id', $maintenance->id) // Pass ID to help JS identify which modal
                        ->with('active_tab', 'maintenance');
        }
        $landlordResponse = $request->input('landlord_response');

        // Check refund_amount to determine if payment is needed
        $paymentAmount = $maintenance->refund_amount ?? 0;

        if ($paymentAmount > 0) {
            // Payment is needed
            Session::put('pending_maintenance_payment_data', [
                'maintenance_id' => $maintenance->id, // Store id for verification in StripeController
                'response' => $landlordResponse,
                'amount' => $paymentAmount,
            ]);
            return redirect()->route('maintenance.checkout.stripe', ['maintenance' => $maintenance->id]);
        } else {
            // No payment needed, proceed to accept directly
            return $this->executeDirectAcceptance($maintenance, $landlordResponse);
        }
    }

    protected function executeDirectAcceptance(Maintenance $maintenance, string $landlordResponse)
    {
        // Authorization check (already done in initiateAcceptancePayment, but good for a protected method)
        if (Auth::id() !== $maintenance->house->landlord_id) {
             return redirect()->route('dashboard')->with('error', 'Unauthorized action.')->with('active_tab', 'maintenance');
        }

        $maintenance->status = 'accepted';
        $maintenance->landlord_response = $landlordResponse;
        $maintenance->save();

        // Notify Tenant
        if ($maintenance->tenant) {
            $maintenance->tenant->notify(new MaintenanceRequestResponseForTenant($maintenance, 'accepted'));
        }

        return redirect()->route('dashboard')
                         ->with('success', 'Maintenance request accepted successfully (no payment required).')
                         ->with('active_tab', 'maintenance');
    }

    public function finalizePaidMaintenanceAcceptance(Maintenance $maintenance, string $landlordResponse, array $paymentDetails)
    {
        // Authorization check (already done in initiateAcceptancePayment, but good for a public method called by another controller)
         if (Auth::id() !== $maintenance->house->landlord_id) {
            Log::error("Unauthorized attempt to finalize paid maintenance acceptance for maintenance ID: {$maintenance->id} by user ID: " . Auth::id());
            return redirect()->route('dashboard')
                             ->with('error', 'Authorization failed after payment. Please contact support.')
                             ->with('active_tab', 'maintenance');
        }

        $maintenance->status = 'accepted';
        $maintenance->landlord_response = $landlordResponse;
        $maintenance->save();

        MaintenancePayment::create([
            'maintenance_id' => $maintenance->id,
            'user_id' => Auth::id(), // Landlord who paid
            'stripe_session_id' => $paymentDetails['stripe_session_id'],
            'amount' => $paymentDetails['amount'],
            'currency' => $paymentDetails['currency'] ?? 'usd',
            'status' => 'succeeded', // Assuming this is only called on success
            'paid_at' => now(),
        ]);
        
        Session::forget('pending_maintenance_payment_data');

        // Notify Tenant
        if ($maintenance->tenant) {
            $maintenance->tenant->notify(new MaintenanceRequestResponseForTenant($maintenance, 'accepted'));
        }

        // This method returns true to indicate success to the calling StripeController.
        // The StripeController's success handler will manage the final user redirect.
        return true; 
    }

    public function rejectMaintenanceRequest(Request $request, Maintenance $maintenance)
    {
        // Authorization
        if (Auth::id() !== $maintenance->house->landlord_id) {
            return redirect()->route('dashboard')
                             ->with('error', 'You are not authorized to perform this action.')
                             ->with('active_tab', 'maintenance');
        }

        $validator = Validator::make($request->all(), [
            'landlord_response' => 'required|string|max:5000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator, 'rejectMaintenanceErrors_' . $maintenance->id)
                        ->withInput()
                        ->with('error_modal_open', 'receivedMaintenanceRequestDetailModal_' . $maintenance->id)
                        ->with('active_tab', 'maintenance');
        }

        $maintenance->status = 'rejected';
        $maintenance->landlord_response = $request->input('landlord_response');
        $maintenance->save();

        // Notify Tenant
        if ($maintenance->tenant) {
            $maintenance->tenant->notify(new MaintenanceRequestResponseForTenant($maintenance, 'rejected'));
        }

        return redirect()->route('dashboard')
                         ->with('success', 'Maintenance request rejected successfully!')
                         ->with('active_tab', 'maintenance');
    }
}
