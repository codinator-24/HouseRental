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
    public function ShowAddHouse()
    {
        return view('posts.AddHouse');
    }

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

    public function houseDetails(House $house)
    {
        // Eager load pictures and landlord information
        $house->load(['pictures', 'landlord']);
        return view('posts.detailsHouse', ['house' => $house]);
    }

    public function MyHouses()
    {
        $query = House::with('pictures');

        // If the user is authenticated, exclude their own houses
        if (Auth::check()) {
            $query->where('landlord_id', '=', Auth::id());
        }
        $houses = $query->get();
        return view('users.MyHouses', ['houses' => $houses]); // Pass houses to the view
    }

    public function editMyHouse(House $house)
    {
        // Authorization: Ensure the authenticated user is the landlord of this house.
        if (Auth::id() !== $house->landlord_id) {
            abort(403, 'Unauthorized action. You do not own this property.');
        }
        return view('users.EditMyHouse', ['house' => $house]);
    }

    public function updateMyHouse(House $house, Request $request): RedirectResponse
    {
        // 1. Authorization: Ensure the authenticated user is the landlord of this house.
        if (Auth::id() !== $house->landlord_id) {
            abort(403, 'Unauthorized action. You do not own this property.');
        }

        // 2. Validation Rules
        // These are similar to AddHouse, but pictures.* is not 'required'
        // as new pictures are optional during an update.
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
            'pictures' => 'nullable|array', // Pictures array is optional
            'pictures.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10048' // Each file in array must be an image
        ]);

        DB::beginTransaction();

        try {
            // 3. Update the House details
            // The update method will only update fields present in $validatedData
            // and defined as fillable in the House model.
            $house->update([
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
            ]);

            // 4. Handle New Image Uploads (adds to existing pictures)
            // As per the blade file, new pictures are additional.
            if ($request->hasFile('pictures')) {
                foreach ($request->file('pictures') as $pictureFile) {
                    $path = $pictureFile->store('house_images', 'public');
                    $house->pictures()->create([
                        'image_url' => Storage::url($path),
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('my.houses')->with('success', 'House listing updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating house (ID: ' . $house->id . '): ' . $e->getMessage());
            // Provide detailed error for development, generic for production
            return back()->withInput()->with('error', 'Failed to update house listing. Please try again. Details: ' . $e->getMessage());
            // return back()->withInput()->with('error', 'Failed to update house listing. Please try again.');
        }
    }

    public function deleteMyHouse(House $house)
    {
        // Authorization: Ensure the authenticated user owns this house
        if (Auth::id() !== $house->landlord_id) {
            // Or use Laravel Policies: $this->authorize('delete', $house);
            // return redirect()->route('Myhouse.index')->with('error', 'You are not authorized to delete this property.');
            return redirect()->route('Myhouse.index')->with('error', 'You are not authorized to delete this property.');
        }

        // Delete associated pictures first (files and DB records)
        foreach ($house->pictures as $picture) {
            if (Storage::disk('public')->exists($picture->image_path)) {
                Storage::disk('public')->delete($picture->image_path);
            }
            $picture->delete(); // Delete DB record
        }

        // Delete the house
        $house->delete();
        return redirect()->route('my.houses')->with('success', 'Property deleted successfully.');
    }

    public function destroyPicture(HousePicture $picture)
    {
        // IMPORTANT: Add authorization check here
        // Example:
        // if ($picture->house->user_id !== auth()->id()) {
        //     return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        // }

        try {
            $imagePath = $picture->image_url;
            $pictureId = $picture->id; // Get ID before deleting object

            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            } else {
                Log::warning("Picture file not found for deletion: '{$imagePath}' for picture ID: {$pictureId}. Record will still be deleted.");
            }

            $picture->delete();

            if (request()->ajax() || request()->wantsJson()) {
                // Pass back picture_id if needed by JS, though current JS derives it
                return response()->json(['success' => true, 'message' => 'Picture deleted successfully!', 'picture_id' => $pictureId]);
            }
            return back()->with('success', 'Picture deleted successfully!');
        } catch (\Exception $e) {
            Log::error("Error deleting picture" . $e->getMessage());
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to delete picture. Please try again or contact support.'], 500);
            }
            return back()->with('error', 'Failed to delete picture. Please try again or contact support.');
        }
    }
}
