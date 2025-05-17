<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\House;
use App\models\User;
use App\models\HousePicture;
use App\models\Booking;

class AdminController extends Controller
{
    
    public function view_aprove(){

        $data=House::all();
        return view('admin/aprove',compact('data'));
    }

    public function view_users(){

        $data=User::all();
        return view('admin/users',compact('data'));
    }

    public function delete_aprove($id){
        
        $data= House::find($id);
        $data->delete();
        return redirect()->back();
    }

    public function delete_user($id){
        
        $data= User::find($id);
        $data->delete();
        return redirect()->back();
    }

//      public function approve_house($id)
// {
//     $house = Booking::findOrFail($id);
//     $house->status='accepted';
//     $house->save();
//     return redirect('/aprove');
// }

public function view_counts(){
        $users=User::count();
        $houses=House::count();
        return view('admin.dashboard',compact('users','houses'));
    }

}
