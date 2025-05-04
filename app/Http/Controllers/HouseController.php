<?php

namespace App\Http\Controllers;

use App\Models\House;
use App\Models\HousePicture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class HouseController extends Controller
{
public function ShowAddHouse(){
    return view('posts.AddHouse');
}

/**
     * Store a newly created house listing in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function AddHouse(Request $request): RedirectResponse
    {
        // 1. Validation Rules
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'first_address' => 'required|string|max:255',
            'second_address' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'location_url' => 'nullable|url|max:500',
            'property_type' => 'required|string|max:100',
            'num_room' => 'required|integer|min:0',
            'num_floor' => 'required|integer|min:0',
            'square_footage' => 'required|numeric|min:0',
            'rent_amount' => 'required|numeric|min:0',
            'pictures' => 'nullable|array', // Ensure pictures is an array if present
            'pictures.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10048' // Validate each file in the array
        ]);

        // Start Database Transaction
        DB::beginTransaction();

        try {
            // 2. Create the House
            $house = House::create([
                'landlord_id' => Auth::id(), // Get logged-in user's ID
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
                'first_address' => $validatedData['first_address'],
                'second_address' => $validatedData['second_address'],
                'city' => $validatedData['city'],
                'location_url' => $validatedData['location_url'],
                'property_type' => $validatedData['property_type'],
                'num_room' => $validatedData['num_room'],
                'num_floor' => $validatedData['num_floor'],
                'square_footage' => $validatedData['square_footage'],
                'rent_amount' => $validatedData['rent_amount'],
                'status' => 'available', // Default status
            ]);

            // 3. Handle Image Uploads
            if ($request->hasFile('pictures')) {
                foreach ($request->file('pictures') as $pictureFile) {
                    // Store the image in 'public/house_images' and get the path
                    $path = $pictureFile->store('house_images', 'public');

                    // Create HousePicture record
                    $house->pictures()->create([
                        'image_url' => Storage::url($path), // Get the public URL
                        // 'caption' => null, // Add caption handling if needed
                    ]);
                }
            }

            // Commit Transaction
            DB::commit();

            // 4. Redirect with Success Message
            return redirect()->route('home')->with('success', 'House listing added successfully!');

        } catch (\Exception $e) {
            // Rollback Transaction on error
            DB::rollBack();

            // Log the error (optional but recommended)
            Log::error('Error adding house: ' . $e->getMessage());

            // Redirect back with error message
            return back()->withInput()->with('error', 'Failed to add house listing. Please try again. Error: ' . $e->getMessage()); // Show detailed error in development
            // return back()->withInput()->with('error', 'Failed to add house listing. Please try again.'); // Generic error for production
        }
    }
}
