<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\House; // Import the House model
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    // Handle the homepage request
    public function index(Request $request)
    {
        $query = House::query()->with(['pictures', 'floors']); // Eager load pictures and floors

        // 1. Filter by City (updated from general location search)
        if ($request->filled('city')) {
            $query->where('city', $request->input('city'));
        }

        // 2. NEW: Filter by Neighborhood
        if ($request->filled('neighborhood') && $request->input('neighborhood') !== '') {
            $query->where('neighborhood', $request->input('neighborhood'));
            // Note: Make sure your database table has a 'neighborhood' column
            // If your column name is different (like 'first_address'), change it accordingly:
            // $query->where('first_address', $request->input('neighborhood'));
        }

        // 3. Filter by Price (as discussed before)
        if ($request->filled('price') && $request->input('price') !== '') {
            $priceRange = $request->input('price');
            switch ($priceRange) {
                case '0-1000':
                    $query->whereBetween('rent_amount', [0, 1000]);
                    break;
                case '1000-2000':
                    $query->whereBetween('rent_amount', [1000, 2000]);
                    break;
                case '2000-3000':
                    $query->whereBetween('rent_amount', [2000, 3000]);
                    break;
                case '3000+':
                    $query->where('rent_amount', '>=', 3000);
                    break;
            }
        }

        // 4. Filter by Property Type (as discussed before)
        if ($request->filled('property_type') && $request->input('property_type') !== '') {
            $query->where('property_type', $request->input('property_type'));
        }

        // 5. NEW: Exclude properties listed by the authenticated user
        if (Auth::check()) {
            // Assuming your House model has a 'landlord_id' column
            // that stores the ID of the user who listed the house.
            // If it's 'user_id', change 'landlord_id' to 'user_id'.
            $query->where('landlord_id', '!=', Auth::id());
        }

        // 6. Filter by status: only show 'available' houses
        // Assuming 'available' is the status for publicly visible and rentable houses.
        // Adjust to 'agree' if that's your intended status.
        // The previous version had 'available', your HouseController AddHouse sets 'disagree' by default.
        $query->where('status', 'available');

        // Get the results (you might want to paginate)
        $houses = $query->latest()->paginate(9); // Example: Paginate with 9 items per page

        return view('posts.index', compact('houses'));
    }
}
