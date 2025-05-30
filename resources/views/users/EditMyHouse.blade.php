<x-layout>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8 text-center text-gray-800">Edit Property</h1> {{-- Centered and more margin --}}

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
            class="bg-white shadow-xl rounded-lg p-8 mx-auto max-w-6xl mb-4"> {{-- Updated class --}}
            @csrf
            @method('PUT')

            {{-- Section 1: Basic Property Information --}}
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">Basic Property Information</h2>
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
                        <label for="property_type" class="block text-sm font-medium text-gray-700 mb-2">Property Type:</label>
                        <select id="property_type" name="property_type"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 bg-white shadow appearance-none @error('property_type') border-red-500 @enderror"
                            required>
                            <option value="" {{ old('property_type', $house->property_type) === '' ? 'selected' : '' }}>Select Property Type</option>
                            <option value="apartment" {{ old('property_type', $house->property_type) == 'apartment' ? 'selected' : '' }}>Apartment</option>
                            <option value="house" {{ old('property_type', $house->property_type) == 'house' ? 'selected' : '' }}>House</option>
                            <option value="condo" {{ old('property_type', $house->property_type) == 'condo' ? 'selected' : '' }}>Condo</option>
                            <option value="studio" {{ old('property_type', $house->property_type) == 'studio' ? 'selected' : '' }}>Studio</option>
                        </select>
                        @error('property_type')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Section 2: Location Details --}}
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">Location Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                    {{-- City --}}
                    <div class="mb-4">
                        <label for="city" class="block text-gray-700 text-sm font-bold mb-2">City:</label>
                        <select id="city" name="city" required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-white @error('city') border-red-500 @enderror">
                            <option value="" {{ old('city', $house->city) == '' ? 'selected' : '' }}>Select City</option>
                            <option value="Sulaymaniyah" {{ old('city', $house->city) == 'Sulaymaniyah' ? 'selected' : '' }}>Sulaymaniyah</option>
                            <option value="Hawler" {{ old('city', $house->city) == 'Hawler' ? 'selected' : '' }}>Hawler</option>
                            <option value="Karkuk" {{ old('city', $house->city) == 'Karkuk' ? 'selected' : '' }}>Karkuk</option>
                            <option value="Dhok" {{ old('city', $house->city) == 'Dhok' ? 'selected' : '' }}>Dhok</option>
                            <option value="Halabja" {{ old('city', $house->city) == 'Halabja' ? 'selected' : '' }}>Halabja</option>
                        </select>
                        @error('city')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Neighborhood --}}
                    <div class="mb-4">
                        <label for="neighborhood" class="block text-gray-700 text-sm font-bold mb-2">Neighborhood:</label>
                        <select id="neighborhood" name="neighborhood" required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-white @error('neighborhood') border-red-500 @enderror">
                            <option value="">Select Neighborhood</option> {{-- Options populated by JS --}}
                        </select>
                        @error('neighborhood')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                {{-- Second Address --}}
                <div class="mb-6"> {{-- Increased bottom margin --}}
                    <label for="second_address" class="block text-gray-700 text-sm font-bold mb-2">Second Address Line <span class="text-gray-500 text-xs">(Optional)</span>:</label>
                    <input type="text" id="second_address" name="second_address" value="{{ old('second_address', $house->second_address) }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('second_address') border-red-500 @enderror">
                    @error('second_address')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Section 3: Property Specifications --}}
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">Property Specifications</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                </div>
            </div>

            {{-- Section 4: Floor Details --}}
            <div class="mb-8"> {{-- Replaced md:col-span-2, border-t, pt-6, mt-4 with mb-8 for consistency --}}
                <h2 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">Floor Details</h2> {{-- Consistent heading style --}}
                <div id="floors_container" class="space-y-6">
                    @php
                        // Prepare default floor data from existing house floors
                        $defaultFloorsData = $house->floors->map(function ($floor) {
                            return [
                                'number_of_rooms' => $floor->num_room,
                                'bathrooms' => (string)$floor->bathroom, // Ensure string for select comparison
                            ];
                        })->all();

                        // If the house has no floors, and it's the initial load (not a validation error redirect),
                        // provide one default empty floor structure.
                        if (empty($defaultFloorsData) && !old('floors') && request()->isMethod('get')) {
                            $defaultFloorsData = [['number_of_rooms' => '', 'bathrooms' => '0']];
                        }

                        // Use old input for floors if available (validation failed), otherwise use the prepared default data.
                        $floorEntries = old('floors', $defaultFloorsData);
                    @endphp

                    @foreach($floorEntries as $index => $floorData)
                        <div class="floor-section p-4 border rounded-md shadow-sm bg-gray-50" data-index="{{ $index }}">
                            <h3 class="text-xl font-semibold mb-3 text-gray-600">Floor {{ $index + 1 }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="floor_{{ $index }}_number_of_rooms" class="block text-gray-700 text-sm font-bold mb-1">Number of Rooms:</label>
                                    <input type="number" id="floor_{{ $index }}_number_of_rooms" name="floors[{{ $index }}][number_of_rooms]"
                                           value="{{ $floorData['number_of_rooms'] ?? '' }}" min="0" required
                                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('floors.'.$index.'.number_of_rooms') border-red-500 @enderror">
                                    @error('floors.'.$index.'.number_of_rooms') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="floor_{{ $index }}_bathrooms" class="block text-gray-700 text-sm font-bold mb-1">Bathrooms:</label>
                                    <select id="floor_{{ $index }}_bathrooms" name="floors[{{ $index }}][bathrooms]"
                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-white @error('floors.'.$index.'.bathrooms') border-red-500 @enderror" required>
                                        <option value="0" {{ (isset($floorData['bathrooms']) && $floorData['bathrooms'] == '0') ? 'selected' : '' }}>Not Exists</option>
                                        <option value="1" {{ (isset($floorData['bathrooms']) && $floorData['bathrooms'] == '1') ? 'selected' : '' }}>Exists</option>
                                    </select>
                                    @error('floors.'.$index.'.bathrooms') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            {{-- Always allow removing floors in edit mode, backend validation will check for min 1 floor --}}
                            <div class="mt-3 text-right">
                                <button type="button" class="remove-floor-btn text-red-500 hover:text-red-700 text-sm font-medium">Remove Floor</button>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-6">
                    <button type="button" id="addFloorButton" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Add Another Floor</button>
                </div>
            </div>

            {{-- Section 5: Description --}}
            <div class="mb-8"> {{-- Replaced md:col-span-2 with mb-8 --}}
                <h2 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">Description</h2>
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2 sr-only">Description:</label>
                <textarea id="description" name="description" rows="4" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror">{{ old('description', $house->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            {{-- Section 7: House Pictures (Moved from original position, will be Section 6 after map) --}}
            {{-- Note: The heading for this section will be added in the next step to match AddHouse.blade.php --}}
            <div class="mb-8"> {{-- Replaced md:col-span-2, border-t, pt-6, mt-4 with mb-8 --}}
                <h2 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">House Pictures</h2>
                <label class="block text-gray-700 text-sm font-bold mb-2 sr-only">Current Pictures:</label>
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
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
                @error('pictures.*')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Section 6: Property Location (Map) --}}
            <div class="mb-8"> {{-- Replaced mt-6, border-t, pt-6, md:col-span-2 with mb-8 --}}
                <h2 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">Property Location</h2> {{-- Consistent heading --}}
                <div id="map" style="height: 400px; width: 100%;" class="rounded-md border"></div>
                <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', $house->latitude) }}">
                <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', $house->longitude) }}">
                @error('latitude') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                @error('longitude') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Submit Button --}}
            <div class="flex items-center justify-end mt-8 pt-6 border-t"> {{-- Increased top margin & added border-t --}}
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
        // ... (existing picture deletion JS - no changes here)
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

    // JavaScript for adding/removing floors (similar to AddHouse.blade.php)
    document.addEventListener('DOMContentLoaded', function () {
        const addFloorButton = document.getElementById('addFloorButton');
        const floorsContainer = document.getElementById('floors_container');
        
        // Initialize floorCounter based on already rendered floors
        let floorCounter = {{ count($floorEntries) }};

        addFloorButton.addEventListener('click', function () {
            const newIndex = floorCounter; // Use current counter as the index for the new floor

            const floorSectionHtml = `
                <div class="floor-section p-4 border rounded-md shadow-sm bg-gray-50" data-index="${newIndex}">
                    <h3 class="text-xl font-semibold mb-3 text-gray-600">Floor ${newIndex + 1}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="floor_${newIndex}_number_of_rooms" class="block text-gray-700 text-sm font-bold mb-1">Number of Rooms:</label>
                            <input type="number" id="floor_${newIndex}_number_of_rooms" name="floors[${newIndex}][number_of_rooms]"
                                   value="" min="0" required
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div>
                            <label for="floor_${newIndex}_bathrooms" class="block text-gray-700 text-sm font-bold mb-1">Bathrooms:</label>
                            <select id="floor_${newIndex}_bathrooms" name="floors[${newIndex}][bathrooms]"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-white" required>
                                <option value="0" selected>Not Exists</option>
                                <option value="1">Exists</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-3 text-right">
                        <button type="button" class="remove-floor-btn text-red-500 hover:text-red-700 text-sm font-medium">Remove Floor</button>
                    </div>
                </div>
            `;
            
            floorsContainer.insertAdjacentHTML('beforeend', floorSectionHtml);
            floorCounter++; // Increment counter for the next floor
        });

        // Event delegation for remove buttons
        floorsContainer.addEventListener('click', function(event) {
            if (event.target && event.target.classList.contains('remove-floor-btn')) {
                const floorSectionToRemove = event.target.closest('.floor-section');
                if (floorSectionToRemove) {
                    floorSectionToRemove.remove();
                    updateFloorTitlesAndCounter();
                }
            }
        });

        function updateFloorTitlesAndCounter() {
            const allFloorSections = floorsContainer.querySelectorAll('.floor-section');
            allFloorSections.forEach((section, idx) => {
                const titleElement = section.querySelector('h3');
                if (titleElement) {
                    titleElement.textContent = `Floor ${idx + 1}`;
                }
                // Note: Input names (floors[index][field]) are not re-indexed here.
                // PHP handles non-sequential array keys fine.
            });
            floorCounter = allFloorSections.length; // Reset counter to current number of floors
        }

        // --- City and Neighborhood Dropdown Logic (Adapted for Edit Page) ---
        const cityDropdown = document.getElementById('city');
        const neighborhoodDropdown = document.getElementById('neighborhood');

        if (cityDropdown && neighborhoodDropdown) {
            const neighborhoodsByCity = {
                'Sulaymaniyah': [
                    'Salim', 'Raparin', 'New Sulaymaniyah', 'Bakhtiary', 'Tasfirate',
                    'German', 'Goizha', 'Kani Ashkan', 'Malkandi', 'Shaikh Maruf',
                    'Qelay Sherwana', 'Pismam', 'Nawbahar', 'Kanes', 'Razgah', 'Ashti'
                ],
                'Hawler': [
                    'Ankawa', 'Iskan', 'Naz City', 'Kushtaba', 'Majidi Mall',
                    'Erbil Citadel', 'Shadi', 'Mamostayan', 'Badawa', 'Prmam',
                    'Rozhalat', 'Brayati', 'Galawezh', 'Bakhtyari', 'Brusk', 'Haybat Sultan'
                ],
                'Karkuk': [
                    'Shorja', 'Arafa', 'Imam Qasim', 'Shorawiya', 'Tisseen Street',
                    'Baghlan', 'Azadi', 'Rahimawa', 'Domiz', 'New Kirkuk',
                    'Wasati', 'Laylan' // Removed duplicate Shorja, Iskan
                ],
                'Dhok': [
                    'Azadi', 'Baxtyari', 'Shexan', 'Qutabxana', 'Newroz', 'Center',
                    'Nali', 'Shorsh', 'Tasluja', 'Qoshtapa'
                ],
                'Halabja': [
                    'Center', 'New Halabja', 'Khurmal', 'Biara', 'Sayid Sadiq',
                    'Serkani', 'Anab', 'Biyare', 'Tuwela', 'Maidan', 'Shahidan'
                ]
            };

            const currentOldCity = "{{ old('city', $house->city) }}";
            const currentOldNeighborhood = "{{ old('neighborhood', $house->neighborhood) }}";

            function updateNeighborhoodOptions() {
                const selectedCity = cityDropdown.value;
                neighborhoodDropdown.innerHTML = '<option value="">Select Neighborhood</option>'; // Clear and add placeholder

                if (selectedCity && neighborhoodsByCity[selectedCity] && neighborhoodsByCity[selectedCity].length > 0) {
                    neighborhoodsByCity[selectedCity].forEach(function(neighborhood) {
                        const option = document.createElement('option');
                        option.value = neighborhood;
                        option.textContent = neighborhood;
                        // Pre-select logic: if the current neighborhood matches for the selected city
                        if (selectedCity === currentOldCity && neighborhood === currentOldNeighborhood) {
                            option.selected = true;
                        }
                        neighborhoodDropdown.appendChild(option);
                    });
                    neighborhoodDropdown.disabled = false;
                } else {
                    neighborhoodDropdown.disabled = true;
                }
            }

            // Add event listener for changes on the city dropdown
            cityDropdown.addEventListener('change', updateNeighborhoodOptions);

            // Initial population:
            // 1. Ensure the city dropdown itself has the correct initial value (from old input or model data).
            //    The Blade template already handles setting the 'selected' attribute on city options.
            //    So, cityDropdown.value should be correct on DOMContentLoaded.
            //
            // 2. Call updateNeighborhoodOptions to populate neighborhoods based on the initially selected city
            //    and to select the correct neighborhood.
            if (currentOldCity) { // Ensure cityDropdown.value is explicitly set if relying on JS for initial state
                 cityDropdown.value = currentOldCity;
            }
            updateNeighborhoodOptions();
        }
        // --- End of City and Neighborhood Dropdown Logic ---
    });
    </script>
    @endpush

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA550b4oUwPM5RoQAmGX3LIH_BkmJoPeFM&callback=initMap" async defer></script>
<script>
    let map;
    let marker;

    function initMap() {
        const initialLat = parseFloat(document.getElementById('latitude').value)  || 35.555744; // Default bo nawarasti slemani
        const initialLng = parseFloat(document.getElementById('longitude').value) || 45.435123; // Default bo nawarasti slemani

        map = new google.maps.Map(document.getElementById('map'), {
            center: { lat: initialLat, lng: initialLng },
            zoom: 12,
        });

        marker = new google.maps.Marker({
            position: { lat: initialLat, lng: initialLng },
            map: map,
            draggable: true, // Make the marker draggable
        });

        // Update hidden input fields when marker is dragged
        marker.addListener('dragend', function() {
            updateLatLngInputs(marker.getPosition());
        });

        // Allow placing marker by clicking on the map
        map.addListener('click', function(event) {
            placeMarker(event.latLng);
        });
    }

    function placeMarker(location) {
        if (marker) {
            marker.setPosition(location);
        } else {
            marker = new google.maps.Marker({
                position: location,
                map: map,
                draggable: true,
            });
             marker.addListener('dragend', function() {
                updateLatLngInputs(marker.getPosition());
            });
        }
        updateLatLngInputs(location);
    }

    function updateLatLngInputs(location) {
        document.getElementById('latitude').value = location.lat();
        document.getElementById('longitude').value = location.lng();
    }

    // Initialize map when the window loads
    // The `async defer` on the script tag handles the timing, but this is a fallback/alternative
    // window.onload = initMap; // This might conflict with the callback in the script tag
    // Better to rely on the `callback=initMap` in the script tag.
</script>
</x-layout>
