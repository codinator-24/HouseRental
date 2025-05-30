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

        // Calculate default signed_at and expires_at dates
        $signedDate = Carbon::now();
        $expiresDate = Carbon::now()->addMonths($booking->month_duration);

        return view('agreements.CreateAgreement', [
            'booking' => $booking,
            'signedDate' => $signedDate,
            'expiresDate' => $expiresDate,
        ]);
    }
}
