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
            if ($request->filled('cash_payment_deadline')) {
                $payment->payment_deadline = $validated['cash_payment_deadline'];
            }

            // Payment status: 'completed' if checked, 'pending' if not.
            // The checkbox sends '1' (true) when checked, and is absent if not checked.
            // So, if 'cash_payment_received' is present and true, status is 'completed'. Otherwise, 'pending'.
            $payment->status = $request->boolean('cash_payment_received') ? 'completed' : 'pending';
            $payment->save();

            if ($payment->agreement) {
                if ($request->filled('key_delivery_deadline')) {
                    $payment->agreement->key_delivery_deadline = $validated['key_delivery_deadline'];
                }
                $payment->agreement->landlord_keys_delivered = $request->boolean('key_handed_over');
                $payment->agreement->save();
            }

            return response()->json(['success' => true, 'message' => 'Cash payment details updated successfully.']);
        } catch (\Exception $e) {
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

            if ($request->filled('credit_key_delivery_deadline')) {
                $payment->agreement->key_delivery_deadline = $validated['credit_key_delivery_deadline'];
            }
            $payment->agreement->landlord_keys_delivered = $request->boolean('credit_key_handed_over');
            $payment->agreement->save();

            return response()->json(['success' => true, 'message' => 'Landlord details updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error updating details: ' . $e->getMessage()], 500);
        }
    }
}
