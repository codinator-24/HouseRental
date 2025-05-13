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
        $query = House::query()->with('pictures'); // Start a query and eager load pictures

        // 1. Filter by Location (as discussed before)
        if ($request->filled('location')) {
            $location = $request->input('location');
            $query->where(function ($q) use ($location) {
                $q->where('city', 'LIKE', "%{$location}%")
                  ->orWhere('first_address', 'LIKE', "%{$location}%")
                  ->orWhere('second_address', 'LIKE', "%{$location}%");
            });
        }

        // 2. Filter by Price (as discussed before)
        if ($request->filled('price')) {
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

        // 3. Filter by Property Type (as discussed before)
        if ($request->filled('property_type')) {
            $query->where('property_type', $request->input('property_type'));
        }

        // 4. NEW: Exclude properties listed by the authenticated user
        if (Auth::check()) {
            // Assuming your House model has a 'landlord_id' column
            // that stores the ID of the user who listed the house.
            // If it's 'user_id', change 'landlord_id' to 'user_id'.
            $query->where('landlord_id', '!=', Auth::id());
        }

        // Get the results (you might want to paginate)
        $houses = $query->latest()->paginate(9); // Example: Paginate with 9 items per page

        return view('posts.index', compact('houses'));
    }
}
