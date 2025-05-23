<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\House;
use App\Models\User;
use App\Models\HousePicture;
use App\Models\Floor;
use App\Models\Feedback;

class AdminController extends  Controller
{

    public function dashboard()
    {
        //this is to count all tables data
        $users = User::count();
        $houses = House::count();
        $feedbacks = Feedback::count();
        $aproves = User::where('status', 'unavailable')->count();
        $landlords = User::where('role', 'lordland')->count();
        $tenants = User::where('role', 'Tenant')->count();
        $verify = User::where('status', 'Not Verified')->count();
        $bosses = User::where('role', 'both')->count();
        return view('admin.dashboard', compact('users', 'houses','landlords','tenants','bosses','aproves','verify','feedbacks'));
    }

    public function viewaprove()
    {
        $houses = House::all();
        $images= HousePicture::all();
        $floors = Floor::all();
        return view('admin/aprove', compact('houses','images','floors'));
    }

    public function viewusers()
    {
        $data = User::all();
        return view('admin/users', compact('data'));
    }
    public function viewfeedback()
    {
        $data = Feedback::all();
        return view('admin/feedback',compact('data'));
    }
    public function view_aprove_user()
    {
        $data = User::all();
        return view('admin/aprove-user',compact('data'));
    }

    public function approve_house($id)
    {
         $house = House::findOrFail($id);
         $house->status='available';
         $house->save();
         return redirect('/approve');
    }


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

    public function delete_feedback($id)
    {

        $data = Feedback::find($id);
        $data->delete();
        return redirect()->back();
    }

    public function approve_user($id)
    {
         $user = User::findOrFail($id);
         $user->status='Verified';
         $user->save();
         return redirect('/approve-user');
    }

    
}
