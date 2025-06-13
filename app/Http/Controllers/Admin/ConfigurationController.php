<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\CurrencyRate; // Added
use Illuminate\Support\Facades\App; // For checking environment
use Illuminate\Support\Facades\Config; // Added
use Illuminate\Support\Facades\Validator; // Added

class ConfigurationController extends Controller
{
    /**
     * Display the admin configuration page.
     */
    public function index(Request $request)
    {
        // Ensure this tool is only accessible in local environment
        if (!App::environment('local')) {
            abort(404);
        }

        $query = Booking::with(['house', 'tenant'])
                        ->orderBy('created_at', 'desc');

        // Simple filter by booking status
        if ($request->filled('booking_status')) {
            $query->where('status', $request->booking_status);
        }

        // Simple filter by tenant email or name
        if ($request->filled('tenant_search')) {
            $searchTerm = $request->tenant_search;
            $query->whereHas('tenant', function ($q) use ($searchTerm) {
                $q->where('full_name', 'like', "%{$searchTerm}%")
                  ->orWhere('user_name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }
        
        // Simple filter by house title
        if ($request->filled('house_title_search')) {
            $searchTerm = $request->house_title_search;
            $query->whereHas('house', function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%");
            });
        }

        $bookings = $query->paginate(20)->withQueryString(); // Paginate results

        // Fetch current USD to IQD rate
        $usdToIqdRateSetting = CurrencyRate::where('rate_name', 'USD_TO_IQD')->first();
        $currentUsdToIqdRate = $usdToIqdRateSetting ? (float)$usdToIqdRateSetting->rate_value : Config::get('currency.exchange_rates.USD_TO_IQD', 1460);
        
        $currentIqdToUsdRate = 0;
        if ($currentUsdToIqdRate > 0) {
            $currentIqdToUsdRate = 1 / $currentUsdToIqdRate;
        } else {
            // Fallback for IQD to USD if USD to IQD is zero or invalid
            $defaultIqdToUsd = Config::get('currency.exchange_rates.IQD_TO_USD');
            $currentIqdToUsdRate = $defaultIqdToUsd ?: (1/1460);
        }


        return view('admin.configuration', compact('bookings', 'currentUsdToIqdRate', 'currentIqdToUsdRate'));
    }

    /**
     * Update the currency exchange rate.
     */
    public function updateCurrencyRate(Request $request)
    {
        if (!App::environment('local')) {
            abort(403, 'This feature is only available in the local environment.');
        }

        $validator = Validator::make($request->all(), [
            'usd_to_iqd_rate' => 'required|numeric|min:0.0001', // Ensure it's a positive number
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', 'Invalid exchange rate provided.');
        }

        $newRate = (float) $request->input('usd_to_iqd_rate');

        try {
            CurrencyRate::updateOrCreate(
                ['rate_name' => 'USD_TO_IQD'],
                ['rate_value' => $newRate]
            );
            return back()->with('success', 'Exchange rate updated successfully to 1 USD = ' . $newRate . ' IQD.');
        } catch (\Exception $e) {
            // Log error $e->getMessage()
            return back()->with('error', 'Failed to update exchange rate. Please try again.');
        }
    }

    /**
     * "Age" a booking by modifying its created_at timestamp.
     */
    public function ageBooking(Request $request, Booking $booking)
    {
        // Ensure this tool is only usable in local environment
        if (!App::environment('local')) {
            abort(404);
        }

        $monthsToAge = (int) $request->input('months', 1);
        $setCompleted = $request->boolean('set_completed');

        if ($monthsToAge <= 0) {
            return back()->with('error', 'Number of months to age must be positive.');
        }

        $newCreatedAt = $booking->created_at->copy()->subMonths($monthsToAge);
        
        // To prevent created_at from being later than updated_at if updated_at is not also shifted
        // or to ensure updated_at reflects this "logical" change.
        $newUpdatedAt = $booking->updated_at->copy()->subMonths($monthsToAge);
        if ($newUpdatedAt->lt($newCreatedAt)) {
             $newUpdatedAt = $newCreatedAt->copy();
        }


        $booking->created_at = $newCreatedAt;
        $booking->updated_at = $newUpdatedAt; // Also adjust updated_at

        if ($setCompleted && $booking->status !== 'completed') {
            $booking->status = 'completed';
        }

        $booking->save();

        return back()->with('success', "Booking ID {$booking->id} successfully aged by {$monthsToAge} months. New created_at: {$booking->created_at->toDateTimeString()}");
    }
}
