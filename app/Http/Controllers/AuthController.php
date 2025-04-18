<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //Register
    public function register(Request $request)
    {
        //Validate
        $fields = $request->validate([
            'fullName' => ['required', 'max:120'],
            'email' => ['required', 'max:100', 'email', 'unique:tenants'],
            'password' => ['required', 'min:8', 'confirmed'],
            'address' => ['required', 'max:255'],
            'contactNo' => ['required', 'regex:/^07[0-9]{9}$/'],
            'userTitle' => ['required', 'max:100']
        ], [
            // Custom Messages
            'contactNo.regex' => 'Please enter the correct number',
        ]);

        //Hash the passowrd before registering
        $fields['password'] = Hash::make($fields['password']);
        //Register
        $user = Tenant::create($fields);

        //Login
        Auth::login($user);

        //Redirect
        return redirect()->route('home');
    }

    //Login User
    public function login(Request $request)
    {
        //Validate
        $fields = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Attempt Login
        if (Auth::attempt($fields, $request->remember)) {
            return redirect()->intended('dashboard');
        } else {
            return back()->withErrors([
                'failed' => 'The provided credentials do not match our records.'
            ]);
        }
    }

    //Logout User
    public function logout(Request $request)
    {
        //Logout
        Auth::logout();

        //Invalidate Session
        $request->session()->invalidate();

        //Regenerate CSRF Token
        $request->session()->regenerate();

        //return to home
        return redirect('/');
    }
}
