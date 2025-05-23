<?php

namespace App\Http\Controllers;

use App\Models\House;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(){
           $query = House::with('pictures');

        // If the user is authenticated, exclude their own houses
        if (Auth::check()) {
            $query->where('landlord_id', '=', Auth::id());
        }
        $houses = $query->get();
        return view('users.dashboard', ['houses' => $houses]);
    }
}
