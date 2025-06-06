<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\House;
use App\Models\HousePicture;
use App\Models\Floor; // Import the Floor model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;


class HouseController extends Controller
{
    /**
     * Helper method to mask phone number - hide last 5 digits
     */
    private function maskPhoneNumber($phoneNumber)
    {
        if (!$phoneNumber) {
            return null;
        }
        
        $phoneNumber = (string) $phoneNumber;
        $length = strlen($phoneNumber);
        
        if ($length <= 5) {
            // If phone number is 5 digits or less, mask all but first digit
            return substr($phoneNumber, 0, 1) . str_repeat('X', $length - 1);
        }
        
        // Hide last 5 digits
        return substr($phoneNumber, 0, $length - 5) . 'XXXXX';
    }

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
            'neighborhood' => 'required|string|max:255',
            'second_address' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'property_type' => 'required|string|max:100',
            'square_footage' => 'required|numeric|min:0',
            'rent_amount' => 'required|numeric|min:0',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'pictures' => 'nullable|array', // Ensure pictures is an array if present
            'pictures.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10048', // Validate each file in the array
            'floors' => 'required|array|min:1', // Ensure at least one floor is provided
            'floors.*.number_of_rooms' => 'required|integer|min:0',
            'floors.*.bathrooms' => 'required|boolean', // Or 'in:0,1' if values are strictly '0' or '1'

        ]);

        // Start Database Transaction
        DB::beginTransaction();

        try {
            // 2. Create the House
            $house = House::create([
                'landlord_id' => Auth::id(), // Get logged-in user's ID
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
                'neighborhood' => $validatedData['neighborhood'],
                'second_address' => $validatedData['second_address'],
                'city' => $validatedData['city'],
                'property_type' => $validatedData['property_type'],
                'square_footage' => $validatedData['square_footage'],
                'rent_amount' => $validatedData['rent_amount'],
                'status' => 'disagree', // Default status
                'latitude' => $validatedData['latitude'] ?? null,
                'longitude' => $validatedData['longitude'] ?? null,
            ]);

            // 3. Create Floor records
            if (isset($validatedData['floors']) && is_array($validatedData['floors'])) {
                foreach ($validatedData['floors'] as $floorData) {
                    $house->floors()->create([
                        'num_room' => $floorData['number_of_rooms'],
                        'bathroom' => $floorData['bathrooms'],
                        // house_id is automatically set by the relationship
                    ]);
                }
            }

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

            // 5. Redirect with Success Message
            return redirect()->route('home')->with('success', 'House listing added successfully!.. Please wait for admin approval.');
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

        public function editMyHouse(House $house)
    {
        // Authorization: Ensure the authenticated user is the landlord of this house.
        if (Auth::id() !== $house->landlord_id) {
            abort(403, 'Unauthorized action. You do not own this property.');
        }
        // Eager load pictures and floors
        $house->load('pictures', 'floors');
        return view('users.EditMyHouse', ['house' => $house]);
    }

    public function updateMyHouse(House $house, Request $request): RedirectResponse
    {
        // 1. Authorization: Ensure the authenticated user is the landlord of this house.
        if (Auth::id() !== $house->landlord_id) {
            abort(403, 'Unauthorized action. You do not own this property.');
        }

        // 2. Validation Rules
        // Similar to AddHouse, but pictures.* is not 'required' for updates.
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'neighborhood' => 'required|string|max:255',
            'second_address' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'property_type' => 'required|string|max:100',
            'square_footage' => 'required|numeric|min:0',
            'rent_amount' => 'required|numeric|min:0',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'pictures' => 'nullable|array', // Pictures array is optional
            'pictures.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10048', // Each file in array must be an image
            'floors' => 'required|array|min:1', // Ensure at least one floor is provided
            'floors.*.number_of_rooms' => 'required|integer|min:0',
            'floors.*.bathrooms' => 'required|boolean',
        ]);

        DB::beginTransaction();

        try {
            // 3. Update the House details
            // The update method will only update fields present in $validatedData
            $houseDataToUpdate = [
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
                'neighborhood' => $validatedData['neighborhood'],
                'second_address' => $validatedData['second_address'],
                'city' => $validatedData['city'],
                'property_type' => $validatedData['property_type'],
                'square_footage' => $validatedData['square_footage'],
                'rent_amount' => $validatedData['rent_amount'],
                'latitude' => $validatedData['latitude'] ?? null,
                'longitude' => $validatedData['longitude'] ?? null,
            ];
            $house->update($houseDataToUpdate);

            // 4. Update Floor records
            // Strategy: Delete existing floors and recreate them from the request.
            $house->floors()->delete(); // Delete all existing floors for this house

            if (isset($validatedData['floors']) && is_array($validatedData['floors'])) {
                foreach ($validatedData['floors'] as $floorData) {
                    $house->floors()->create([
                        'num_room' => $floorData['number_of_rooms'],
                        'bathroom' => $floorData['bathrooms'],
                    ]);
                }
            }
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

    public function houseDetails(House $house)
    {
        $house->load('pictures', 'floors', 'landlord'); // Eager load landlord, pictures, and floors

        // Mask phone numbers for privacy
        if ($house->landlord) {
            $house->landlord->masked_first_phone = $this->maskPhoneNumber($house->landlord->first_phoneNumber);
            $house->landlord->masked_second_phone = $this->maskPhoneNumber($house->landlord->second_phoneNumber);
        }

        $userBookingForThisHouse = null;
        if (Auth::check() && $house && $house->id) {
            $userBookingForThisHouse = Booking::where('tenant_id', Auth::id())
                ->where('house_id', $house->id)
                // Optional: Add conditions like ->where('status', '!=', 'cancelled')
                ->first();
        }
        return view('posts.detailsHouse', compact('house', 'userBookingForThisHouse'));
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

    public function deleteMyHouse(House $house)
    {
        // Authorization: Ensure the authenticated user owns this house
        if (Auth::check() && Auth::id() !== $house->landlord_id) {
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