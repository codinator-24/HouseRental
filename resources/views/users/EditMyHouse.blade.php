<x-layout>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Edit Property</h1>

        {{-- Display validation errors if any (from main form submission) --}}
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

        {{-- Session messages for picture deletion (or other actions returning to this page) --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <form action="{{ route('Myhouse.update', $house) }}" method="post" enctype="multipart/form-data"
            class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Title --}}
                <div class="mb-4">
                    <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title:</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $house->title) }}"
                        required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('title') border-red-500 @enderror">
                    @error('title')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Property Type --}}
                <div class="mb-4">
                    <label for="property_type" class="block text-sm font-medium text-gray-700 mb-1">Property Type</label>
                    <select id="property_type" name="property_type"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 bg-white @error('property_type') border-red-500 @enderror"
                        required>
                        <option value="" {{ old('property_type', $house->property_type) == '' ? 'selected' : '' }}>Select Property Type</option>
                        <option value="apartment" {{ old('property_type', $house->property_type) == 'apartment' ? 'selected' : '' }}>Apartment</option>
                        <option value="house" {{ old('property_type', $house->property_type) == 'house' ? 'selected' : '' }}>House</option>
                        <option value="condo" {{ old('property_type', $house->property_type) == 'condo' ? 'selected' : '' }}>Condo</option>
                        <option value="studio" {{ old('property_type', $house->property_type) == 'studio' ? 'selected' : '' }}>Studio</option>
                    </select>
                    @error('property_type')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                {{-- First Address --}}
                <div class="mb-4 md:col-span-2">
                    <label for="first_address" class="block text-gray-700 text-sm font-bold mb-2">First Address Line:</label>
                    <input type="text" id="first_address" name="first_address" value="{{ old('first_address', $house->first_address) }}"
                        required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('first_address') border-red-500 @enderror">
                    @error('first_address')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Second Address --}}
                <div class="mb-4">
                    <label for="second_address" class="block text-gray-700 text-sm font-bold mb-2">Second Address Line <span class="text-gray-500 text-xs">(Optional)</span>:</label>
                    <input type="text" id="second_address" name="second_address" value="{{ old('second_address', $house->second_address) }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('second_address') border-red-500 @enderror">
                    @error('second_address')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                {{-- City --}}
                <div class="mb-4">
                    <label for="city" class="block text-gray-700 text-sm font-bold mb-2">City:</label>
                    <input type="text" id="city" name="city" value="{{ old('city', $house->city) }}"
                        required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('city') border-red-500 @enderror">
                    @error('city')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Location URL --}}
                <div class="mb-4 md:col-span-2">
                    <label for="location_url" class="block text-gray-700 text-sm font-bold mb-2">Location URL <span class="text-gray-500 text-xs">(e.g., Google Maps, Optional)</span>:</label>
                    <input type="url" id="location_url" name="location_url" value="{{ old('location_url', $house->location_url) }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('location_url') border-red-500 @enderror">
                    @error('location_url')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Number of Rooms --}}
                <div class="mb-4">
                    <label for="num_room" class="block text-gray-700 text-sm font-bold mb-2">Number of Rooms:</label>
                    <input type="number" id="num_room" name="num_room" value="{{ old('num_room', $house->num_room) }}"
                        min="0" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('num_room') border-red-500 @enderror">
                    @error('num_room')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Number of Floors --}}
                <div class="mb-4">
                    <label for="num_floor" class="block text-gray-700 text-sm font-bold mb-2">Number of Floors:</label>
                    <input type="number" id="num_floor" name="num_floor" value="{{ old('num_floor', $house->num_floor) }}"
                        min="0" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('num_floor') border-red-500 @enderror">
                    @error('num_floor')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Square Footage --}}
                <div class="mb-4">
                    <label for="square_footage" class="block text-gray-700 text-sm font-bold mb-2">Square Meter (m<sup>2</sup>):</label>
                    <input type="number" id="square_footage" name="square_footage" value="{{ old('square_footage', $house->square_footage) }}"
                        min="0" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('square_footage') border-red-500 @enderror">
                    @error('square_footage')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Rent Amount --}}
                <div class="mb-4">
                    <label for="rent_amount" class="block text-gray-700 text-sm font-bold mb-2">Rent Amount (per month):</label>
                    <input type="number" id="rent_amount" name="rent_amount" value="{{ old('rent_amount', $house->rent_amount) }}"
                        min="0" step="0.01" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('rent_amount') border-red-500 @enderror">
                    @error('rent_amount')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="mb-4 md:col-span-2">
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
                    <textarea id="description" name="description" rows="4" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror">{{ old('description', $house->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                {{-- House Pictures --}}
                <div class="mb-6 md:col-span-2">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Current Pictures:</label>
                    @if ($house->pictures && $house->pictures->count() > 0)
                        {{-- Container for pictures, used by JS to check if it's empty --}}
                        <div id="pictures-container" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mb-4">
                            @foreach ($house->pictures as $picture)
                                {{-- Each picture item wrapper --}}
                                <div id="picture-{{ $picture->id }}" class="relative h-32 group picture-item">
                                    <img src="{{ asset($picture->image_url) }}"
                                        alt="House picture {{ $loop->iteration }}"
                                        class="w-full h-full object-cover rounded-md shadow-md">

                                    {{-- MODIFIED: Delete Button - Now type="button" and uses JavaScript --}}
                                    <button type="button"
                                            data-delete-url="{{ route('myhouse.picture.destroy', $picture->id) }}"
                                            onclick="confirmDeletePicture(this)"
                                            class="absolute top-1 right-1 z-10 bg-red-600 hover:bg-red-800 text-white text-xs font-semibold py-1 px-2 rounded-full shadow-md opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                                            title="Delete Picture">
                                        × {{-- This is an 'X' character --}}
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p id="no-pictures-message" class="text-gray-500 text-sm mb-4">No pictures currently uploaded for this property.</p>
                    @endif

                    <label for="pictures" class="block text-gray-700 text-sm font-bold mb-2 pt-4 border-t mt-4">
                        Upload New/Additional Pictures:
                        <span class="text-gray-500 text-xs">(Leave empty to keep current pictures)</span>
                    </label>
                    <input type="file" id="pictures" name="pictures[]" multiple accept="image/*"
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none @error('pictures.*') border-red-500 @enderror @error('pictures') border-red-500 @enderror">
                    <p class="mt-1 text-sm text-gray-500" id="file_input_help">PNG, JPG, GIF, WEBP. You can select multiple images.</p>
                    @error('pictures')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                    @error('pictures.*')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

            </div> {{-- End Grid --}}

            {{-- Submit Button for the main form (should now work correctly) --}}
            <div class="flex items-center justify-end mt-6">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Update House
                </button>
            </div>
        </form>
    </div>

    {{-- ADDED: JavaScript for handling picture deletion --}}
    @push('scripts')
    <script>
    function confirmDeletePicture(buttonElement) {
        if (confirm('Are you sure you want to delete this picture? This action cannot be undone.')) {
            const url = buttonElement.dataset.deleteUrl;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json', // Expect a JSON response from the server
                },
            })
            .then(response => {
                // Try to parse JSON, but if it fails, get text for better error reporting
                return response.json().catch(() => {
                    return response.text().then(text => {
                        throw new Error(text || `Server responded with status: ${response.status}`);
                    });
                });
            })
            .then(data => {
                if (data.success) {
                    // Visual feedback: Remove the picture element from the DOM
                    const pictureElementToRemove = document.getElementById(`picture-${data.picture_id || buttonElement.closest('.picture-item').id.split('-')[1]}`); // try to get id from response or element
                    if (pictureElementToRemove) {
                        pictureElementToRemove.remove();
                    }
                    
                    // Check if there are any pictures left
                    const picturesContainer = document.getElementById('pictures-container');
                    const noPicturesMessage = document.getElementById('no-pictures-message');

                    if (picturesContainer && picturesContainer.children.length === 0) {
                        if (noPicturesMessage) {
                            noPicturesMessage.style.display = 'block'; // Show the 'no pictures' message
                        } else {
                             // If the "no pictures" message element wasn't there initially (because there were pictures),
                             // we might need to create it or simply reload the page.
                             // Reloading is simpler to ensure UI consistency.
                             window.location.reload();
                        }
                    }
                    // You might want to display data.message using a more sophisticated notification system
                    // For simplicity, we can just rely on the visual removal or page reload.
                    // If you want to show a success message without reload, you'd add that here.
                    // Example: showFlashMessage(data.message, 'success');
                    // For now, the page will either have the item removed, or reload if all items are gone.
                } else {
                    alert(data.message || 'Failed to delete picture. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error deleting picture:', error);
                alert('An error occurred while deleting the picture: ' + error.message);
            });
        }
    }
    </script>
    @endpush
</x-layout>