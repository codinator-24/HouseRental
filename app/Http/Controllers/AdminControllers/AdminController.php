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
}
