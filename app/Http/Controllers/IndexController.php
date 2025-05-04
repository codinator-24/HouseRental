<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\House; // Import the House model

class IndexController extends Controller
{
    // Handle the homepage request
    public function index()
    {
        // Fetch houses with their first picture eagerly loaded
        // You might want to add pagination later: ->paginate(9)
        $houses = House::with('pictures') // Eager load pictures relationship
                       ->latest()        // Optional: Order by newest
                       ->get();
        return view('posts.index', ['houses' => $houses]); // Pass houses to the view
    }
}
