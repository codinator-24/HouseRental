<?php

namespace App\Http\Controllers;

use App\Models\User;
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
            // *** THIS IS THE LINE TO FIX ***
            // Change 'unique:tenants,email' to 'unique:users,email'
            'email' => ['required', 'max:100', 'email', 'unique:users,email', 'regex:/^.+@.+\..+$/'],
            'password' => ['required', 'min:4', 'confirmed'],
            'address' => ['required', 'max:255'],
            'contactNo' => ['required', 'regex:/^07[0-9]{9}$/'],
            'userTitle' => ['required', 'max:100']
        ], [
            'contactNo.regex' => 'Please enter the correct contact number format (e.g., 07xxxxxxxx).',
            'email.regex'     => 'Please enter a valid email address format.',
            'email.unique'    => 'This email address is already registered.',
        ]);

        // Register using the User model
        $user = User::create($fields); // Ensure this uses User::create

        //Login
        Auth::login($user);

        //Redirect
        return redirect()->route('home'); // Or wherever you redirect after registration
    }

    public function login(Request $request)
    {
        //Validate
        $fields = $request->validate([

            // 'email' => ['required', 'email'],
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required'],
        ]);

        // Attempt Login
        if (Auth::attempt($fields, $request->remember)) {
            return redirect()->intended('/');
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
