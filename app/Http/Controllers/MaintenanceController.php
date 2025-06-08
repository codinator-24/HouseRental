<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


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

        Maintenance::create($data);

        return redirect()->route('dashboard')
                         ->with('success', 'Maintenance request submitted successfully!')
                         ->with('active_tab', 'maintenance'); // To switch to the maintenance tab
    }
}
