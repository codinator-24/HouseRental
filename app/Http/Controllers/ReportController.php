<?php

namespace App\Http\Controllers;

use App\Models\House;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class ReportController extends Controller
{
    /**
     * Store a newly created report in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\House  $house
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, House $house)
    {
        $validator = Validator::make($request->all(), [
            'reason_category' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator, 'reportFormErrors')
                        ->withInput();
        }

        if (!$house->landlord) {
            return redirect()->back()->with('error', 'Cannot report a house without an assigned landlord.');
        }

        Report::create([
            'user_id' => Auth::id(),
            'house_id' => $house->id,
            'reported_user_id' => $house->landlord->id,
            'reason_category' => $request->input('reason_category'),
            'description' => $request->input('description'),
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Report submitted successfully.');
    }
}
