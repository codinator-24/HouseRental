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
