<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class AuthAdminController extends Controller
{

    public function showRegistrationForm()
    {
        return view('admin.AdminRegister');
    }

    public function register(Request $request)
    {
        $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'user_name' => ['required', 'string', 'max:255', 'unique:admins,user_name'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins,email'],
            'phoneNumber' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string','min:4','confirmed'],
            'picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:8048'],
        ]);

        $data = $request->only(['full_name', 'user_name', 'email', 'phoneNumber']);
        $data['password'] = Hash::make($request->password);

        if ($request->hasFile('picture')) {
            $path = $request->file('picture')->store('admin_pictures', 'public');
            $data['picture'] = $path;
        }

        $admin = Admin::create($data);

        Auth::guard('admin')->login($admin);

        return redirect()->route('AdminLogin.form')->with('success', 'Admin account created successfully!');
    }

    public function showLoginForm()
    {
        return view('admin.AdminLogin');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('AdminDashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('AdminLogin.form');
    }
}
