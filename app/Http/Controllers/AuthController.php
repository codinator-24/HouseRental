<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Password; // Needed for Password facade if using rules like Password::min(8)
use Illuminate\Validation\Rules\Password as PasswordRules; 
use Illuminate\Validation\ValidationException;

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
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Added max size
            'IdCard' => 'required|nullable|file|mimes:pdf,jpeg,png,jpg,gif,webp|max:8048', // Added IdCard validation
        ], [
            'first_phoneNumber' => 'Please enter the correct contact number format (e.g., 07xxxxxxxx).',
            'second_phoneNumber' => 'Please enter the correct contact number format (e.g., 07xxxxxxxx).',
            'email.regex'     => 'Please enter a valid email address format.',
            'email.unique'    => 'This email address is already registered.',
            'user_name.unique' => 'This username is already taken.',
            'IdCard.mimes' => 'The ID Card must be a file of type: pdf, jpeg, png, jpg, gif, webp.',
        ]);

        // Hash the password
        $fields['password'] = Hash::make($fields['password']);

        // Handle file upload
        if ($request->hasFile('picture')) {
            // Store the file in 'public/profile_pictures' and get the path
            $path = $request->file('picture')->store('profile_pictures', 'public');
            $fields['picture'] = $path; // Save the path to the database
        }

        // Handle IdCard upload
        if ($request->hasFile('IdCard')) {
            $idCardPath = $request->file('IdCard')->store('id_cards', 'public');
            $fields['IdCard'] = $idCardPath;
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
        return redirect(route('home'));
    }

    public function showProfile()
    {
        $user = Auth::user(); // Get the currently authenticated user
        return view('users.profile', compact('user')); // Pass user data to the view
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user(); // Get the currently authenticated user

        // Validate
        $fields = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            // Ensure username is unique, *except* for the current user's existing username
            'user_name' => ['required', 'string', 'max:100', 'unique:users,user_name,' . $user->id],
            'first_phoneNumber' => ['required', 'regex:/^07[0-9]{9}$/'],
            'second_phoneNumber' => ['nullable', 'regex:/^07[0-9]{9}$/'],
            // Ensure email is unique, *except* for the current user's existing email
            'email' => ['required', 'max:100', 'email', 'unique:users,email,' . $user->id, 'regex:/^.+@.+\..+$/'],
            // Note: Password and Role are typically not updated here for security/logic reasons
            'address' => ['required', 'max:255'],
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Added max size
            'IdCard' => 'nullable|file|mimes:pdf,jpeg,png,jpg,gif,webp|max:8048', // Added IdCard validation
        ], [
            'first_phoneNumber.regex' => 'Please enter the correct contact number format (e.g., 07xxxxxxxx).',
            'second_phoneNumber.regex' => 'Please enter the correct contact number format (e.g., 07xxxxxxxx).',
            'email.regex'     => 'Please enter a valid email address format.',
            'email.unique'    => 'This email address is already registered by another user.',
            'user_name.unique' => 'This username is already taken by another user.',
            'IdCard.mimes' => 'The ID Card must be a file of type: pdf, jpeg, png, jpg, gif, webp.',
        ]);

        // Handle file upload if a new picture is provided
        if ($request->hasFile('picture')) {
            // Delete the old picture if it exists
            if ($user->picture) {
                Storage::disk('public')->delete($user->picture);
            }
            // Store the new file in 'public/profile_pictures' and get the path
            $path = $request->file('picture')->store('profile_pictures', 'public');
            $fields['picture'] = $path; // Add the new path to the fields to be updated
        } else {
            // If 'picture' was in the form but no file uploaded, $request->validate
            // (with 'nullable') would put 'picture' => null in $fields.
            // Unset it to prevent overwriting the existing picture with null.
            unset($fields['picture']);
        }

        // Handle IdCard upload if a new one is provided
        if ($request->hasFile('IdCard')) {
            if ($user->IdCard) {
                Storage::disk('public')->delete($user->IdCard);
            }
            $idCardPath = $request->file('IdCard')->store('id_cards', 'public');
            $fields['IdCard'] = $idCardPath;
        } else {
            // Prevent overwriting existing IdCard with null if no new file uploaded
            unset($fields['IdCard']);
        }

        // Update the user record
        // We only pass $fields which contains validated data, potentially including the new picture path
            User::where('id', $user->id)->update($fields);

        // Redirect back to the profile page with a success message
        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        /** @var \App\Models\User $user */ // Optional: Add PHPDoc to help Intelephense
        $user = Auth::user();

        try {
            $validated = $request->validateWithBag('updatePassword', [
                // ... (keep your validation rules the same)
                'current_password' => ['required', 'string', function ($attribute, $value, $fail) use ($user) {
                    if (!$user || !Hash::check($value, $user->password)) { // Added check for $user existence just in case
                        $fail('The :attribute is incorrect.');
                    }
                }],
                'new_password' => [
                    'required',
                    'string',
                    // PasswordRules::min(4)->mixedCase()->numbers()->symbols(), // Keep your rules
                    PasswordRules::min(4),
                    'confirmed'
                 ],
                'new_password_confirmation' => ['required'],
            ]);

            // --- CHANGE HERE ---
            // Directly assign the hashed password
            $user->password = Hash::make($validated['new_password']);
            // Save the changes to the user model
            $user->save();
            // --- END CHANGE ---

            return back()->with('password_success', 'Password updated successfully!');

        } catch (ValidationException $e) {
             return back()->withErrors($e->errors(), 'updatePassword');
        }
    }
}
