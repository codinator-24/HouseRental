<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\House;
use App\Models\User;
use App\Models\HousePicture;
use App\Notifications\AccountVerified;
use App\Notifications\HouseApproved;
use App\Models\Floor;
use App\Models\Feedback;
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
        $bosses = User::where('role', 'both')->count();
        return view('admin.dashboard', compact('users', 'houses', 'landlords', 'tenants', 'aproves', 'verify', 'bosses', 'feedbacks'));
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

    // Example in your AdminController.php (or relevant controller)
    public function ViewProfit()
    {
        // --- Fetch or calculate your data ---
        // Example:
        $profitData = [
            'labels' => ['Luxury Apartments', 'Standard Houses', 'Commissions'],
            'data' => [15000, 25000, 2250],
        ];

        // You might also want to generate colors dynamically or have a predefined set
        $colors = [
            'rgba(255, 99, 132, 0.7)',
            'rgba(54, 162, 235, 0.7)',
            'rgba(255, 206, 86, 0.7)',
            // Add more colors if needed
        ];
        $borderColors = [
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)',
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
                    'borderWidth' => 1
                ]
            ]
        ];

        return view('admin.profit', compact('chartDataFromServer'));
    }
}
