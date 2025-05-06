<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\House; // Import the House model
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    // Handle the homepage request
    public function index()
    {
        $query = House::with('pictures');

        // If the user is authenticated, exclude their own houses
        if (Auth::check()) {
            $query->where('landlord_id', '!=', Auth::id());
        }
        $houses = $query->get();
        return view('posts.index', ['houses' => $houses]); // Pass houses to the view
    }
}
