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
            'full_name' => ['required', 'string', 'max:255'],
            'user_name' => ['required', 'string', 'max:100', 'unique:users,user_name'], // Added validation for user_name
            'first_phoneNumber' => ['required', 'regex:/^07[0-9]{9}$/'],
            'second_phoneNumber' => ['nullable', 'regex:/^07[0-9]{9}$/'],
            'email' => ['required', 'max:100', 'email', 'unique:users,email', 'regex:/^.+@.+\..+$/'],
            'password' => ['required', 'min:4', 'confirmed'],
            'role' => ['required', 'string', 'max:50'], // Added validation for role
            'address' => ['required', 'max:255'],
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
        ], [
            'first_phoneNumber' => 'Please enter the correct contact number format (e.g., 07xxxxxxxx).',
            'second_phoneNumber' => 'Please enter the correct contact number format (e.g., 07xxxxxxxx).',
            'email.regex'     => 'Please enter a valid email address format.',
            'email.unique'    => 'This email address is already registered.',
            'user_name.unique' => 'This username is already taken.',
         ]);
 
        // Hash the password
        $fields['password'] = Hash::make($fields['password']);

        // Handle file upload
        if ($request->hasFile('picture')) {
            // Store the file in 'public/profile_pictures' and get the path
            $path = $request->file('picture')->store('profile_pictures', 'public');
            $fields['picture'] = $path; // Save the path to the database
        }

        // Register using the User model
        $user = User::create($fields);

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
