<x-layout>
<div class="container mx-auto px-4 py-8"> {{-- Basic container and padding --}}
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Edit Property</h1> {{-- Styled heading --}}

    {{-- Display validation errors if any --}}
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
            <strong class="font-bold">Oops!</strong>
            <span class="block sm:inline">Please fix the following errors:</span>
            <ul class="mt-3 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- The route('house.add') is assumed correct based on previous context --}}
    <form action="{{ route('Myhouse.update', $house) }}" method="post" enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        @csrf {{-- CSRF Protection --}}
        @method('PUT') {{-- Method Spoofing for PUT request --}}

        {{-- House Details Section --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6"> {{-- Grid layout for better alignment --}}

            {{-- Title --}}
            <div class="mb-4">
                <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title:</label>
                <input type="text" id="title" name="title" value="{{ old('title', $house->title) }}" required
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('title') border-red-500 @enderror">
                @error('title') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
            </div>

            {{-- Property Type --}}
            <div class="mb-4">
                <label for="property_type" class="block text-sm font-medium text-gray-700 mb-1">Property Type</label>
                <select id="property_type" name="property_type" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 bg-white @error('property_type') border-red-500 @enderror" required>
                    <option value="" {{ old('property_type', $house->property_type) == '' ? 'selected' : '' }}>Select Property Type</option>
                    <option value="apartment" {{ old('property_type', $house->property_type) == 'apartment' ? 'selected' : '' }}>Apartment</option>
                    <option value="house" {{ old('property_type', $house->property_type) == 'house' ? 'selected' : '' }}>House</option>
                    <option value="condo" {{ old('property_type', $house->property_type) == 'condo' ? 'selected' : '' }}>Condo</option>
                    <option value="studio" {{ old('property_type', $house->property_type) == 'studio' ? 'selected' : '' }}>Studio</option>
                </select>
                @error('property_type') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
            </div>

            {{-- First Address --}}
            <div class="mb-4 md:col-span-2"> {{-- Span across 2 columns on medium screens --}}
                <label for="first_address" class="block text-gray-700 text-sm font-bold mb-2">First Address Line:</label>
                <input type="text" id="first_address" name="first_address" value="{{ old('first_address', $house->first_address) }}" required
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('first_address') border-red-500 @enderror">
                @error('first_address') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
            </div>

            {{-- Second Address --}}
            <div class="mb-4">
                <label for="second_address" class="block text-gray-700 text-sm font-bold mb-2">Second Address Line <span class="text-gray-500 text-xs">(Optional)</span>:</label>
              <input type="text" id="second_address" name="second_address" value="{{ old('second_address', $house->second_address) }}"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('second_address') border-red-500 @enderror">
                @error('second_address') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
            </div>

            {{-- City --}}
            <div class="mb-4">
                <label for="city" class="block text-gray-700 text-sm font-bold mb-2">City:</label>
                <input type="text" id="city" name="city" value="{{ old('city', $house->city) }}" required
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('city') border-red-500 @enderror">
                @error('city') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
            </div>

            {{-- Location URL --}}
            <div class="mb-4 md:col-span-2">
                <label for="location_url" class="block text-gray-700 text-sm font-bold mb-2">Location URL <span class="text-gray-500 text-xs">(e.g., Google Maps, Optional)</span>:</label>
                <input type="url" id="location_url" name="location_url" value="{{ old('location_url', $house->location_url) }}"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('location_url') border-red-500 @enderror">
                @error('location_url') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
            </div>

            {{-- Number of Rooms --}}
            <div class="mb-4">
                <label for="num_room" class="block text-gray-700 text-sm font-bold mb-2">Number of Rooms:</label>
                <input type="number" id="num_room" name="num_room" value="{{ old('num_room', $house->num_room) }}" min="0" required
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('num_room') border-red-500 @enderror">
                @error('num_room') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
            </div>

            {{-- Number of Floors --}}
            <div class="mb-4">
                <label for="num_floor" class="block text-gray-700 text-sm font-bold mb-2">Number of Floors:</label>
                <input type="number" id="num_floor" name="num_floor" value="{{ old('num_floor', $house->num_floor) }}" min="0" required
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('num_floor') border-red-500 @enderror">
                @error('num_floor') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
            </div>

            {{-- Square Footage --}}
            <div class="mb-4">
                <label for="square_footage" class="block text-gray-700 text-sm font-bold mb-2">Square Footage (sq ft):</label>
                <input type="number" id="square_footage" name="square_footage" value="{{ old('square_footage', $house->square_footage) }}" min="0" required
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('square_footage') border-red-500 @enderror">
                @error('square_footage') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
            </div>

            {{-- Rent Amount --}}
            <div class="mb-4">
                <label for="rent_amount" class="block text-gray-700 text-sm font-bold mb-2">Rent Amount (per month):</label>
                <input type="number" id="rent_amount" name="rent_amount" value="{{ old('rent_amount', $house->rent_amount) }}" min="0" step="0.01" required
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('rent_amount') border-red-500 @enderror">
                @error('rent_amount') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
            </div>

            {{-- Description --}}
            <div class="mb-4 md:col-span-2">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
                <textarea id="description" name="description" rows="4" required
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror">
                {{ old('description', $house->description) }}</textarea>
                @error('description') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
            </div>

            {{-- House Pictures --}}
            <div class="mb-6 md:col-span-2">
                <label class="block text-gray-700 text-sm font-bold mb-2">Current Pictures:</label>
                @if($house->pictures && $house->pictures->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mb-4">
                        @foreach($house->pictures as $picture)
                            <div class="h-32"> {{-- Container for consistent image height --}}
                                <img src="{{ asset($picture->image_url) }}" alt="House picture {{ $loop->iteration }}" class="w-full h-full object-cover rounded-md shadow-md">
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm mb-4">No pictures currently uploaded for this property.</p>
                @endif

                <label for="pictures" class="block text-gray-700 text-sm font-bold mb-2 pt-4 border-t mt-4">
                    Upload New/Additional Pictures:
                    <span class="text-gray-500 text-xs">(Leave empty to keep current pictures)</span>
                </label>
                {{-- Use name="pictures[]" to handle multiple files as an array in the controller --}}
                <input type="file" id="pictures" name="pictures[]" multiple accept="image/*"
                       class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none @error('pictures.*') border-red-500 @enderror @error('pictures') border-red-500 @enderror" >
                       <p class="mt-1 text-sm text-gray-500" id="file_input_help">PNG, JPG, GIF, WEBP. You can select multiple images. Your controller's update logic will determine if these replace or add to existing pictures.</p>
                @error('pictures') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror {{-- Error for the array itself --}}
                @error('pictures.*') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror {{-- Error for individual files in the array --}}
            </div>

        </div> {{-- End Grid --}}

        {{-- Submit Button --}}
        <div class="flex items-center justify-end mt-6">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Update House
            </button>

            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 ml-6 rounded focus:outline-none focus:shadow-outline">
                Delete House
            </button>
        </div>

    </form>
</div>
</x-layout>