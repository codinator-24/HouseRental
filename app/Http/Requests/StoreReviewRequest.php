<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\House;

class StoreReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $booking = $this->route('booking'); // Get the booking from the route model binding

        if (!$booking instanceof Booking) {
            // If not a Booking instance, perhaps it's just an ID, try to fetch it.
            // This case might not be strictly necessary if route model binding is always used.
            $booking = Booking::find($booking);
        }

        if (!$booking || !$booking->house) {
            return false; // Booking or associated house not found
        }
        
        // Check if the authenticated user is the tenant of the booking
        // and if the house allows review by this user for this booking.
        return Auth::check() && Auth::id() === $booking->tenant_id && $booking->house->canBeReviewedBy(Auth::user(), $booking);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ];
    }
}
