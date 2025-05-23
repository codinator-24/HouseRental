<?php

namespace App\Http\Controllers;

use App\Models\House;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Feedback;

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

    public function show_contact()
    {
        return view('contact.contact');
    }

   public function insert_contact(Request $request)
{
    $data = new Feedback;
    $data->name = $request->name;
    $data->email = $request->email;
    $data->title = $request->title;
    $data->description = $request->description;

    $data->save();
    return redirect()->back();
}
}
