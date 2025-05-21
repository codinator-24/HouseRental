<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\House;
use App\Models\User;
use App\Models\HousePicture;

class AdminController extends  Controller
{

    public function dashboard()
    {
        return view('admin/dashboard');
    }

    public function viewaprove()
    {
        $houses = House::all();
        return view('admin.aprove', compact('houses'));
    }

    public function viewusers()
    {
        $data = User::all();
        return view('admin/users', compact('data'));
    }
    public function viewfeedback()
    {
        return view('admin/feedback');
    }

    //      public function approve_house($id)
    // {
    //     $house = Booking::findOrFail($id);
    //     $house->status='accepted';
    //     $house->save();
    //     return redirect('/aprove');
    // }


    public function delete_aprove($id)
    {

        $data = House::find($id);
        $data->delete();
        return redirect()->back();
    }

    public function delete_user($id)
    {

        $data = User::find($id);
        $data->delete();
        return redirect()->back();
    }

    public function view_counts()
    {
        $users = User::count();
        $houses = House::count();
        return view('admin.dashboard', compact('users', 'houses'));
    }
}
