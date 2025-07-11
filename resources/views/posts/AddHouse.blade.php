<x-layout>
    {{-- Ensure this page is accessed only by authenticated users,
         or add middleware to the route if not already done.
         The @auth directive here provides an additional layer of check at the view level. --}}
    @auth
        @if (auth()->user()->status === 'Not Verified')
            <div class="container mx-auto px-4 py-8">
                <div class="bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-6 rounded-md shadow-md"
                    role="alert">
                    <div class="flex">
                        <div class="py-1"><svg class="fill-current h-6 w-6 text-orange-500 mr-4"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path
                                    d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zM9 5v6h2V5H9zm0 8v2h2v-2H9z" />
                            </svg></div>
                        <div>
                            <p class="font-bold text-xl">@lang('words.add_house_verification_pending_title')</p>
                            <p class="text-md">@lang('words.add_house_verification_pending_text')</p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="container mx-auto px-4 py-8"> {{-- Basic container and padding --}}
                <h1 class="text-3xl font-bold mb-8 text-center text-gray-800">@lang('words.add_house_page_title')</h1>
                {{-- Styled heading, centered --}}

                {{-- Display validation errors if any --}}
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6"
                        role="alert">
                        <strong class="font-bold">@lang('words.error_oops')</strong>
                        <span class="block sm:inline">@lang('words.error_fix_following')</span>
                        <ul class="mt-3 list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- The route('house.add') is assumed correct based on previous context --}}
                <form action="{{ route('house.add') }}" method="POST" enctype="multipart/form-data"
                    class="bg-white shadow-xl rounded-lg p-8 mx-auto max-w-6xl mb-4">
                    @csrf {{-- CSRF protection token --}}

                    {{-- Section 1: Basic Property Information --}}
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">@lang('words.add_house_section_basic_info')</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Title --}}
                            <div class="mb-4">
                                <label for="title"
                                    class="block text-gray-700 text-sm font-bold mb-2">@lang('words.add_house_label_title')</label>
                                <input type="text" id="title" name="title" value="{{ old('title') }}"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('title') border-red-500 @enderror">
                                @error('title')
                                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Property Type --}}
                            <div class="mb-4">
                                <label for="property_type"
                                    class="block text-sm font-medium text-gray-700 mb-2">@lang('words.add_house_label_property_type')</label>
                                <select id="property_type" name="property_type"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 bg-white shadow appearance-none @error('property_type') border-red-500 @enderror">
                                    <option value="" {{ old('property_type') == '' ? 'selected' : '' }}>
                                        @lang('words.add_house_select_property_type')</option>
                                    <option value="apartment" {{ old('property_type') == 'apartment' ? 'selected' : '' }}>
                                        @lang('words.property_type_apartment')</option>
                                    <option value="house" {{ old('property_type') == 'house' ? 'selected' : '' }}>
                                        @lang('words.property_type_house')
                                    </option>
                                    <option value="commercial" {{ old('property_type') == 'commercial' ? 'selected' : '' }}>
                                        @lang('words.property_type_commercial')
                                    </option>
                                </select>
                                @error('property_type')
                                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Section 2: Location Details --}}
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">@lang('words.add_house_section_location_details')</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            {{-- City --}}
                            <div class="mb-4">
                                <label for="city"
                                    class="block text-gray-700 text-sm font-bold mb-2">@lang('words.add_house_label_city_colon')</label>
                                <select id="city" name="city"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-white @error('city') border-red-500 @enderror">
                                    <option value="" {{ old('city') == '' ? 'selected' : '' }}>@lang('words.add_house_select_city')
                                    </option>
                                    <option value="Sulaymaniyah" {{ old('city') == 'Sulaymaniyah' ? 'selected' : '' }}>
                                        @lang('words.Sulaymaniyah')</option>
                                    <option value="Hawler" {{ old('city') == 'Hawler' ? 'selected' : '' }}>
                                        @lang('words.Hawler')</option>
                                    <option value="Karkuk" {{ old('city') == 'Karkuk' ? 'selected' : '' }}>
                                        @lang('words.Karkuk')</option>
                                    <option value="Dhok" {{ old('city') == 'Dhok' ? 'selected' : '' }}>@lang('words.Dhok')
                                    </option>
                                    <option value="Halabja" {{ old('city') == 'Halabja' ? 'selected' : '' }}>
                                        @lang('words.Halabja')
                                    </option>
                                </select>
                                @error('city')
                                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Neighborhood --}}
                            <div class="mb-4">
                                <label for="neighborhood"
                                    class="block text-gray-700 text-sm font-bold mb-2">@lang('words.add_house_label_neighborhood_colon')</label>
                                <select id="neighborhood" name="neighborhood"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-white @error('neighborhood') border-red-500 @enderror">
                                    <option value="">@lang('words.add_house_select_neighborhood')</option> {{-- Default placeholder --}}
                                    {{-- Options will be populated by JavaScript --}}
                                </select>
                                @error('neighborhood')
                                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        {{-- Second Address --}}
                        <div class="mb-6"> {{-- Increased bottom margin --}}
                            <label for="second_address"
                                class="block text-gray-700 text-sm font-bold mb-2">@lang('words.add_house_label_second_address')</label>
                            <input type="text" id="second_address" name="second_address"
                                value="{{ old('second_address') }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('second_address') border-red-500 @enderror">
                            @error('second_address')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Section 3: Property Specifications --}}
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">@lang('words.add_house_section_specifications')</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Square Footage --}}
                            <div class="mb-4">
                                <label for="square_footage"
                                    class="block text-gray-700 text-sm font-bold mb-2">@lang('words.add_house_label_square_meter')</label>
                                <input type="number" id="square_footage" name="square_footage"
                                    value="{{ old('square_footage') }}" min="0"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('square_footage') border-red-500 @enderror">
                                @error('square_footage')
                                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Rent Amount --}}
                            <div class="mb-4">
                                <label for="rent_amount"
                                    class="block text-gray-700 text-sm font-bold mb-2">@lang('words.add_house_label_rent_amount')</label>
                                <input type="number" id="rent_amount" name="rent_amount" value="{{ old('rent_amount') }}"
                                    min="0" step="0.01"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('rent_amount') border-red-500 @enderror">
                                @error('rent_amount')
                                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Section 4: Floor Details --}}
                    <div class="mb-8"> {{-- Replaced md:col-span-2 and mb-2 with mb-8 for consistency --}}
                        <h2 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">@lang('words.add_house_section_floor_details')</h2>
                        {{-- Consistent heading style --}}
                        <div id="floors_container" class="space-y-6">
                            @php
                                // Ensure $floorEntries is an array; default to one empty floor structure for initial display or if old data is invalid
                                $floorEntries = old('floors');
                                if (!is_array($floorEntries) || (empty($floorEntries) && request()->isMethod('get'))) {
                                    $floorEntries = [['number_of_rooms' => '', 'bathrooms' => '0']]; // Default: 1 floor, no rooms, bathroom not exists
                                } elseif (empty($floorEntries) && !request()->isMethod('get')) {
                                    // If form was submitted with all floors removed, respect that.
                                    // Or, if you always want at least one floor, you can re-add the default here.
                                    // For now, allow zero floors if explicitly submitted as such.
                                    $floorEntries = [];
                                }
                            @endphp

                            @foreach ($floorEntries as $index => $floorData)
                                <div class="floor-section p-4 border rounded-md shadow-sm bg-gray-50"
                                    data-index="{{ $index }}">
                                    <h3 class="text-xl font-semibold mb-3 text-gray-600">@lang('words.add_house_floor_title_prefix')
                                        {{ $index + 1 }}
                                    </h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="floor_{{ $index }}_number_of_rooms"
                                                class="block text-gray-700 text-sm font-bold mb-1">@lang('words.add_house_label_number_of_rooms')</label>
                                            <input type="number" id="floor_{{ $index }}_number_of_rooms"
                                                name="floors[{{ $index }}][number_of_rooms]"
                                                value="{{ $floorData['number_of_rooms'] ?? '' }}" min="0"
                                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('floors.' . $index . '.number_of_rooms') border-red-500 @enderror">
                                            @error('floors.' . $index . '.number_of_rooms')
                                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="floor_{{ $index }}_bathrooms"
                                                class="block text-gray-700 text-sm font-bold mb-1">@lang('words.add_house_label_bathrooms')</label>
                                            <select id="floor_{{ $index }}_bathrooms"
                                                name="floors[{{ $index }}][bathrooms]"
                                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-white @error('floors.' . $index . '.bathrooms') border-red-500 @enderror">
                                                <option value="0"
                                                    {{ isset($floorData['bathrooms']) && $floorData['bathrooms'] == '0' ? 'selected' : '' }}>
                                                    @lang('words.add_house_bathroom_not_exists')</option>
                                                <option value="1"
                                                    {{ isset($floorData['bathrooms']) && $floorData['bathrooms'] == '1' ? 'selected' : '' }}>
                                                    @lang('words.add_house_bathroom_exists')</option>
                                            </select>
                                            @error('floors.' . $index . '.bathrooms')
                                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    @if ($index > 0)
                                        {{-- Allow removing floors, but not the very first one if it's the only one, or always allow removal --}}
                                        <div class="mt-3 text-right">
                                            <button type="button"
                                                class="remove-floor-btn text-red-500 hover:text-red-700 text-sm font-medium">@lang('words.add_house_remove_floor_button')</button>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            <button type="button" id="addFloorButton"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                @lang('words.add_house_add_floor_button')
                            </button>
                        </div>
                    </div>

                    {{-- Section 5: Description --}}
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">@lang('words.add_house_section_description')</h2>
                        <label for="description"
                            class="block text-gray-700 text-sm font-bold mb-2 sr-only">@lang('words.add_house_label_description_sr')</label>
                        {{-- Label is visually hidden but available for screen readers --}}
                        <textarea id="description" name="description" rows="4"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Section 6: Property Location (Map) --}}
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">@lang('words.add_house_section_property_location')</h2>
                        <div id="map" style="height: 400px; width: 100%;" class="rounded-md border"></div>
                        <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}">
                        <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}">
                        @error('latitude')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                        @error('longitude')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Section 7: House Pictures --}}
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">@lang('words.add_house_section_house_pictures')</h2>
                        <label for="pictures"
                            class="block text-gray-700 text-sm font-bold mb-2 sr-only">@lang('words.add_house_label_house_pictures_sr')</label>
                        {{-- Label is visually hidden --}}
                        <input type="file" id="pictures" name="pictures[]" multiple accept="image/*"
                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none @error('pictures.*') border-red-500 @enderror @error('pictures') border-red-500 @enderror">
                        <p class="mt-1 text-sm text-gray-500" id="file_input_help">@lang('words.add_house_pictures_help_text')</p>
                        @error('pictures')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                        @error('pictures.*')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <x-commission-info />

                    {{-- Submit Button --}}
                    <div class="flex items-center justify-end mt-8 pt-6 border-t"> {{-- Increased top margin --}}
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            @lang('words.Add House')
                        </button>
                    </div>

                </form>
            </div>
        @endif
    @else
        {{-- Optional: Message for guests trying to access this page directly,
             though ideally, route middleware should redirect them to login. --}}
        <div class="container mx-auto px-4 py-8 text-center">
            <p class="text-xl text-gray-700">@lang('words.add_house_guest_login_prompt', ['Login' => '<a href="' . route('login') . '" class="text-blue-600 hover:underline">' . __('words.Login') . '</a>'])</p>
        </div>
    @endauth

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- Existing Floor Management Script ---
            const addFloorButton = document.getElementById('addFloorButton');
            const floorsContainer = document.getElementById('floors_container');

            if (addFloorButton && floorsContainer) {
                let floorCounter = {{ isset($floorEntries) ? count($floorEntries) : 0 }};

                addFloorButton.addEventListener('click', function() {
                    const newIndex = floorCounter;
                    const floorSectionHtml = `
                    <div class="floor-section p-4 border rounded-md shadow-sm bg-gray-50" data-index="${newIndex}">
                        <h3 class="text-xl font-semibold mb-3 text-gray-600">@lang('words.add_house_floor_title_prefix') ${newIndex + 1}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="floor_${newIndex}_number_of_rooms" class="block text-gray-700 text-sm font-bold mb-1">@lang('words.add_house_label_number_of_rooms')</label>
                                <input type="number" id="floor_${newIndex}_number_of_rooms" name="floors[${newIndex}][number_of_rooms]"
                                       value="" min="0" 
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>
                            <div>
                                <label for="floor_${newIndex}_bathrooms" class="block text-gray-700 text-sm font-bold mb-1">@lang('words.add_house_label_bathrooms')</label>
                                <select id="floor_${newIndex}_bathrooms" name="floors[${newIndex}][bathrooms]"
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-white" >
                                    <option value="0" selected>@lang('words.add_house_bathroom_not_exists')</option>
                                    <option value="1">@lang('words.add_house_bathroom_exists')</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-3 text-right">
                            <button type="button" class="remove-floor-btn text-red-500 hover:text-red-700 text-sm font-medium">@lang('words.add_house_remove_floor_button')</button>
                        </div>
                    </div>
                `;
                    floorsContainer.insertAdjacentHTML('beforeend', floorSectionHtml);
                    floorCounter++;
                });

                floorsContainer.addEventListener('click', function(event) {
                    if (event.target && event.target.classList.contains('remove-floor-btn')) {
                        const floorSectionToRemove = event.target.closest('.floor-section');
                        if (floorSectionToRemove) {
                            floorSectionToRemove.remove();
                            updateFloorTitles();
                        }
                    }
                });

                function updateFloorTitles() {
                    const allFloorSections = floorsContainer.querySelectorAll('.floor-section');
                    allFloorSections.forEach((section, idx) => {
                        const titleElement = section.querySelector('h3');
                        if (titleElement) {
                            titleElement.textContent = `@lang('words.add_house_floor_title_prefix') ${idx + 1}`;
                        }
                    });
                    floorCounter = allFloorSections.length;
                }
            }
            // --- End of Existing Floor Management Script ---


            // --- NEW: City and Neighborhood Dropdown Logic ---
            const cityDropdown = document.getElementById('city');
            const neighborhoodDropdown = document.getElementById('neighborhood'); // The new select element

            if (cityDropdown && neighborhoodDropdown) { // Ensure these elements exist on the page
                // Define neighborhoods for each city.
                // In a real application, this data might come from the backend (e.g., passed as a JSON object or fetched via API)
                const neighborhoodsByCity = {
                    'Sulaymaniyah': [
                        'Salim',
                        'Raparin',
                        'Bakrajo',
                        'Xabat',
                        'New Sulaymaniyah',
                        'Bakhtiary',
                        'Tasfirate',
                        'German',
                        'Goizha',
                        'Kani Ashkan',
                        'Malkandi',
                        'Shaikh Maruf',
                        'Qelay Sherwana',
                        'Pismam',
                        'Nawbahar',
                        'Kanes',
                        'Razgah',
                        'Ashti'
                    ],
                    'Hawler': [
                        'Ankawa',
                        'Iskan',
                        'Naz City',
                        'Kushtaba',
                        'Majidi Mall',
                        'Erbil Citadel',
                        'Shadi',
                        'Mamostayan',
                        'Badawa',
                        'Prmam',
                        'Rozhalat',
                        'Brayati',
                        'Galawezh',
                        'Bakhtyari',
                        'Brusk',
                        'Haybat Sultan'
                    ],
                    'Karkuk': [
                        'Shorja',
                        'Arafa',
                        'Imam Qasim',
                        'Shorawiya',
                        'Tisseen Street',
                        'Baghlan',
                        'Azadi',
                        'Rahimawa',
                        'Domiz',
                        'New Kirkuk',
                        'Wasati',
                        'Shorja',
                        'Iskan',
                        'Laylan'
                    ],
                    'Dhok': [
                        'Azadi',
                        'Baxtyari',
                        'Shexan',
                        'Qutabxana',
                        'Newroz',
                        'Center',
                        'Nali',
                        'Shorsh',
                        'Tasluja',
                        'Qoshtapa'
                    ],
                    'Halabja': [
                        'Center',
                        'New Halabja',
                        'Khurmal',
                        'Biara',
                        'Sayid Sadiq',
                        'Serkani',
                        'Ababaile',
                        'Anab',
                        'Biyare',
                        'Tuwela',
                        'Maidan',
                        'Shahidan'
                    ]
                    // Add more cities and their neighborhoods as needed
                };

                const currentOldCity = "{{ old('city') }}";
                const currentOldNeighborhood = "{{ old('neighborhood') }}";

                function updateNeighborhoodOptions() {
                    const selectedCity = cityDropdown.value;

                    // Clear previous neighborhood options and add the default placeholder
                    neighborhoodDropdown.innerHTML = '<option value="">' +
                        "{{ __('words.add_house_select_neighborhood') }}" + '</option>';

                    if (selectedCity && neighborhoodsByCity[selectedCity] && neighborhoodsByCity[selectedCity]
                        .length > 0) {
                        neighborhoodsByCity[selectedCity].forEach(function(neighborhood) {
                            const option = document.createElement('option');
                            option.value = neighborhood;
                            option.textContent = neighborhood;
                            neighborhoodDropdown.appendChild(option);
                        });

                        // If there was an old city selected and it matches the current selected city,
                        // and there was an old neighborhood, try to re-select it.
                        if (selectedCity === currentOldCity && currentOldNeighborhood) {
                            // Ensure the old neighborhood is valid for the selected city before setting it
                            if (neighborhoodsByCity[selectedCity].includes(currentOldNeighborhood)) {
                                neighborhoodDropdown.value = currentOldNeighborhood;
                            }
                        }

                        neighborhoodDropdown.disabled = false;
                    } else {
                        neighborhoodDropdown.disabled =
                            true; // Disable if no city selected or no neighborhoods for the city
                    }
                }

                // Add event listener for changes on the city dropdown
                cityDropdown.addEventListener('change', updateNeighborhoodOptions);

                // Initial population of neighborhoods when the page loads.
                // This handles cases where `old('city')` pre-selects a city,
                // ensuring the neighborhood dropdown is populated and `old('neighborhood')` is respected.
                updateNeighborhoodOptions();
            }
            // --- End of City and Neighborhood Dropdown Logic ---
        });
    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAKWlf4wz8z4ZGYXYIk4n1nEs2MEwATrEw&callback=initMap" async
        defer></script>
    <script>
        let map;
        let marker;

        function initMap() {
            const initialLat = parseFloat(document.getElementById('latitude').value) ||
                35.555744; // Default bo nawarasti slemani
            const initialLng = parseFloat(document.getElementById('longitude').value) ||
                45.435123; // Default bo nawarasti slemani


            map = new google.maps.Map(document.getElementById('map'), {
                center: {
                    lat: initialLat,
                    lng: initialLng
                },
                zoom: 12,
            });

            marker = new google.maps.Marker({
                position: {
                    lat: initialLat,
                    lng: initialLng
                },
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
