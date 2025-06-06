<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;

use function Ramsey\Uuid\v8;

class AgreementController extends Controller
{
  public function create(Booking $booking)
    {
        // Eager load necessary relationships for efficiency
        $booking->load([
            'tenant', // User model for tenant
            'house.landlord', // User model for landlord, via House
            'house.floors' // Floor models via House
        ]);

        // Attempt to find an existing agreement for this booking.
        // This will retrieve its status (e.g., 'pending'), amounts, dates, etc.
        $agreement = Agreement::where('booking_id', $booking->id)->first();

        // If an existing agreement is found, use its dates.
        // Otherwise, calculate default dates for a new agreement.
        if ($agreement) {
            // Ensure these are Carbon instances, falling back if they are somehow null in DB
            $signedDate = $agreement->signed_at ? Carbon::parse($agreement->signed_at) : Carbon::now();
            $expiresDate = $agreement->expires_at ? Carbon::parse($agreement->expires_at) : Carbon::now()->addMonths($booking->month_duration);
        } else {
            $signedDate = Carbon::now();
            $expiresDate = Carbon::now()->addMonths($booking->month_duration);
        }

        return view('agreements.CreateAgreement', [
            'booking' => $booking,
            'agreement' => $agreement, // Pass the existing agreement (or null if not found)
            'signedDate' => $signedDate, // Use determined signedDate
            'expiresDate' => $expiresDate, // Use determined expiresDate
        ]);
    }
}
