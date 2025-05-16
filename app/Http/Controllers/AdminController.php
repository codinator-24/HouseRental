<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\House;
use App\models\User;
use App\models\HousePicture;

class AdminController extends Controller
{
    
    public function viewaprove(){

        $data=House::all();
        return view('admin/aprove',compact('data'));
    }

    public function viewusers(){

        $data=User::all();
        return view('admin/users',compact('data'));
    }


}
