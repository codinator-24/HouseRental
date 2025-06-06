<style>
    @keyframes bubble {
        0% {
            transform: scale(0);
            opacity: 0.8;
        }

        50% {
            transform: scale(1.2);
            opacity: 0.4;
        }

        100% {
            transform: scale(1.6);
            opacity: 0;
        }
    }

    .bubble-effect.animate {
        animation: bubble 0.6s ease-out;
    }
</style>

<x-layout>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif
    @guest
        {{-- Hero Section --}}
        <section class="relative h-[500px] bg-cover bg-center flex items-center justify-center text-white"
            style="background-image: url('https://images.pexels.com/photos/731082/pexels-photo-731082.jpeg');">
            {{-- Overlay --}}
            <div class="absolute inset-0 bg-black opacity-50"></div>
            {{-- Content --}}
            <div class="relative z-10 px-4 text-center">
                <h1 class="mb-4 text-4xl font-bold md:text-5xl">Find Your Dream Home</h1>
                <p class="mb-8 text-lg md:text-xl">Discover the perfect property for your needs</p>
                <a href="{{ route('login') }}"
                    class="px-6 py-3 text-lg font-bold text-white transition duration-300 bg-blue-600 rounded-md hover:bg-blue-700">
                    Please login to rent a house
                </a>
            </div>
        </section>
    @endguest

    @auth
        {{-- Hero Section --}}
        <section class="relative h-[500px] bg-cover bg-center flex items-center justify-center text-white"
            style="background-image: url('https://images.pexels.com/photos/731082/pexels-photo-731082.jpeg');">
            {{-- Overlay --}}
            <div class="absolute inset-0 bg-black opacity-50"></div>
            {{-- Content --}}
            <div class="relative z-10 px-4 text-center">
                <h1 class="mb-4 text-4xl font-bold md:text-5xl">Find Your Dream Home</h1>
                <p class="mb-8 text-lg md:text-xl">Discover the perfect property for your needs</p>
                @if (auth()->user()->status === 'Not Verified')
                    <p class="px-6 py-3 text-lg font-bold text-white bg-orange-500 rounded-md">
                        Wait until your account is verified.
                    </p>
                @else
                    <a href="{{ route('Show.house.add') }}"
                        class="px-6 py-3 text-lg font-bold text-white transition duration-300 bg-blue-600 rounded-md hover:bg-blue-700">
                        Click here to place your property
                    </a>
                @endif
            </div>
        </section>
    @endauth

    {{-- Search Bar Section --}}

    {{-- Search Bar Section --}}
    <section id="search"
        class="container relative z-20 max-w-5xl px-5 py-6 mx-auto -mt-16 bg-white rounded-lg shadow-lg">
        {{-- Submit to the current URL to re-load the page with query parameters --}}
        <form action="{{ route('home') }}" method="GET" class="grid items-end grid-cols-1 gap-4 md:grid-cols-11">
            {{-- City Dropdown --}}
            <div class="md:col-span-2">
                <label for="city" class="block mb-1 text-sm font-medium text-gray-700">City</label>
                <select id="city" name="city"
                    class="w-full px-4 py-2 bg-white border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    <option value="" {{ request('city') == '' ? 'selected' : '' }}>All Cities</option>
                    <option value="Sulaymaniyah" {{ request('city') == 'Sulaymaniyah' ? 'selected' : '' }}>Sulaymaniyah
                    </option>
                    <option value="Hawler" {{ request('city') == 'Hawler' ? 'selected' : '' }}>Hawler</option>
                    <option value="Karkuk" {{ request('city') == 'Karkuk' ? 'selected' : '' }}>Karkuk</option>
                    <option value="Dhok" {{ request('city') == 'Dhok' ? 'selected' : '' }}>Dhok</option>
                    <option value="Halabja" {{ request('city') == 'Halabja' ? 'selected' : '' }}>Halabja</option>
                </select>
            </div>

            {{-- Neighborhood Dropdown --}}
            <div class="md:col-span-3">
                <label for="neighborhood" class="block mb-1 text-sm font-medium text-gray-700">Neighborhood</label>
                <select id="neighborhood" name="neighborhood"
                    class="w-full px-4 py-2 bg-white border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Neighborhoods</option>
                    {{-- Options will be populated by JavaScript --}}
                </select>
            </div>

            {{-- Price --}}
            <div class="md:col-span-2">
                <label for="price" class="block mb-1 text-sm font-medium text-gray-700">Price</label>
                <select id="price" name="price"
                    class="w-full px-4 py-2 bg-white border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    <option value="" {{ request('price') == '' ? 'selected' : '' }}>Price Range</option>
                    <option value="0-1000" {{ request('price') == '0-1000' ? 'selected' : '' }}>$0 - $1000</option>
                    <option value="1000-2000" {{ request('price') == '1000-2000' ? 'selected' : '' }}>$1000 - $2000
                    </option>
                    <option value="2000-3000" {{ request('price') == '2000-3000' ? 'selected' : '' }}>$2000 - $3000
                    </option>
                    <option value="3000+" {{ request('price') == '3000+' ? 'selected' : '' }}>$3000+</option>
                </select>
            </div>

            {{-- Property Type --}}
            <div class="md:col-span-2">
                <label for="property_type" class="block mb-1 text-sm font-medium text-gray-700">Property Type</label>
                <select id="property_type" name="property_type"
                    class="w-full px-4 py-2 bg-white border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    <option value="" {{ request('property_type') == '' ? 'selected' : '' }}>Property Type
                    </option>
                    <option value="apartment" {{ request('property_type') == 'apartment' ? 'selected' : '' }}>Apartment
                    </option>
                    <option value="house" {{ request('property_type') == 'house' ? 'selected' : '' }}>House</option>
                    <option value="condo" {{ request('property_type') == 'condo' ? 'selected' : '' }}>Condo</option>
                    <option value="studio" {{ request('property_type') == 'studio' ? 'selected' : '' }}>Studio</option>
                </select>
            </div>

            {{-- Search Button --}}
            <button type="submit"
                class="md:col-span-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md w-full h-[42px] flex items-center justify-center">
                Search
                <i class="fas fa-search fa-flip-horizontal ml-2"></i>
            </button>
        </form>
    </section>


    <div class="px-10 bg-gray-50">

        {{-- Featured Properties Section --}}
        <section class="py-5">
            <div class="container px-6 mx-auto">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-3xl font-bold text-gray-800">Featured Properties</h2>
                    <a href="{{ route('home') }}" class="font-medium text-blue-600 hover:text-blue-800">View All
                        Properties</a>
                </div>

                {{-- Property Grid --}}
                <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                    @forelse ($houses as $house)
                        {{-- Property Card --}}
                        <div
                            class="relative flex flex-col overflow-hidden bg-white border border-gray-200 rounded-xl shadow-lg">
                            {{-- Favorite Button - Positioned in upper right corner --}}
                            @auth
                                <button
                                    class="absolute top-3 right-3 z-10 w-10 h-10 bg-white/90 backdrop-blur-sm rounded-full shadow-md favorite-btn hover:bg-white focus:outline-none flex items-center justify-center cursor-pointer transition-all duration-200 active:scale-110 active:bg-red-100"
                                    data-house-id="{{ $house->id }}" title="Favorite">
                                    @if (auth()->user()->hasFavorited($house))
                                        <i class="text-lg text-red-500 fas fa-heart"></i>
                                    @else
                                        <i class="text-lg text-gray-600 far fa-heart"></i>
                                    @endif
                                    {{-- Click bubble effect --}}
                                    <span
                                        class="absolute inset-0 rounded-full bg-red-300/30 scale-0 opacity-0 pointer-events-none bubble-effect"></span>
                                </button>
                            @else
                                <button
                                    class="absolute top-3 right-3 z-10 w-10 h-10 bg-white/90 backdrop-blur-sm rounded-full shadow-md favorite-btn-guest hover:bg-white focus:outline-none flex items-center justify-center cursor-pointer transition-all duration-200 active:scale-110 active:bg-red-100"
                                    title="Favorite">
                                    <i class="text-lg text-gray-600 far fa-heart"></i>
                                    {{-- Click bubble effect --}}
                                    <span
                                        class="absolute inset-0 rounded-full bg-red-300/30 scale-0 opacity-0 pointer-events-none bubble-effect"></span>
                                </button>
                            @endguest

                            {{-- Use first picture if available, otherwise a placeholder --}}
                            @php
                                $imageUrl = $house->pictures->first()?->image_url
                                    ? asset($house->pictures->first()->image_url)
                                    : 'https://images.pexels.com/photos/731082/pexels-photo-731082.jpeg';
                            @endphp
                            <img src="{{ $imageUrl }}" alt="{{ $house->title }}" class="object-cover w-full h-60">
                            <div class="flex flex-col flex-grow p-6">
                                <h3 class="mb-2 text-2xl font-semibold">{{ $house->title }}</h3>
                                <p class="flex-grow mb-4 text-sm text-gray-600">
                                    <i class="mr-1 fas fa-map-marker-alt text-slate-500"></i>
                                    {{ $house->city }}, {{ $house->first_address }}
                                    {{-- In index, 'first_address' is now 'neighborhood' --}}
                                    {{-- Displaying city and neighborhood --}}
                                    {{ $house->neighborhood }}
                                    {{ $house->second_address ? ', ' . $house->second_address : '' }}
                                </p>
                                <div class="flex items-baseline mb-4 text-center justify-center">
                                    <span class="text-2xl font-bold text-blue-600 convertible-price"
                                        data-base-price-usd="{{ $house->rent_amount }}">
                                        {{-- Initial display, will be updated by JS --}}
                                        ${{ number_format($house->rent_amount, 2) }}
                                    </span>
                                    <span class="ml-1 text-xs text-gray-500">/month</span>
                                </div>
                                <div class="grid grid-cols-3 gap-x-4 py-3 my-3 border-t border-b">
                                    <div class="flex flex-col items-center text-center">
                                        <i class="mb-1 fas fa-bed text-gray-500"></i>
                                        <span class="block text-lg font-semibold text-gray-900">{{ $house->num_room ?? 0 }}</span>
                                        <span class="block text-xs text-gray-500">{{ Str::plural('Room', $house->num_room ?? 0) }}</span>
                                    </div>
                                    <div class="flex flex-col items-center text-center">
                                        <i class="mb-1 fas fa-layer-group text-gray-500"></i>
                                        <span class="block text-lg font-semibold text-gray-900">{{ $house->num_floor ?? 0 }}</span>
                                        <span class="block text-xs text-gray-500">{{ Str::plural('Floor', $house->num_floor ?? 0) }}</span>
                                    </div>
                                    <div class="flex flex-col items-center text-center">
                                        <i class="mb-1 fas fa-ruler-combined text-gray-500"></i>
                                        <span class="block text-lg font-semibold text-gray-900">{{ $house->square_footage ?? 0 }}</span>
                                        <span class="block text-xs text-gray-500">m<sup>2</sup></span>
                                    </div>
                                </div>
                                <div class="mt-auto">
                                    <a href="{{ route('house.details', $house) }}"
                                        class="block w-full px-5 py-3 text-base font-semibold text-center text-white transition duration-300 bg-blue-600 rounded-md hover:bg-blue-700">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-600 md:col-span-2 lg:col-span-3">No properties found at the
                            moment.</p>
                    @endforelse
                </div>

            </div>
        </section>
    </div>
    {{-- Why Choose Us Section --}}
    <section class="py-16 bg-blue-50">
        <div class="container px-6 mx-auto text-center">
            <h2 class="mb-4 text-3xl font-bold text-gray-800">Why Choose HouseRental?</h2>
            <p class="max-w-2xl mx-auto mb-12 text-gray-600">We make finding your perfect home simple and stress-free.
            </p>

            <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                {{-- Feature 1: Ideal Locations --}}
                <div class="p-8 bg-white border border-gray-200 rounded-lg shadow-md">
                    <div class="flex justify-center mb-4">
                        <div class="inline-flex p-4 text-blue-600 bg-blue-100 rounded-full">
                            {{-- Placeholder for Location Icon --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="mb-2 text-xl font-semibold">Ideal Locations</h3>
                    <p class="text-sm text-gray-600">Explore properties in prime locations across the city.</p>
                </div>

                {{-- Feature 2: Transparent Pricing --}}
                <div class="p-8 bg-white border border-gray-200 rounded-lg shadow-md">
                    <div class="flex justify-center mb-4">
                        <div class="inline-flex p-4 text-blue-600 bg-blue-100 rounded-full">
                            {{-- Placeholder for Pricing/Tag Icon --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-5 5a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="mb-2 text-xl font-semibold">Transparent Pricing</h3>
                    <p class="text-sm text-gray-600">No hidden fees or surprise costs, ever.</p>
                </div>

                {{-- Feature 3: Quality Verified --}}
                <div class="p-8 bg-white border border-gray-200 rounded-lg shadow-md">
                    <div class="flex justify-center mb-4">
                        <div class="inline-flex p-4 text-blue-600 bg-blue-100 rounded-full">
                            {{-- Placeholder for Check/Shield Icon --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="mb-2 text-xl font-semibold">Quality Verified</h3>
                    <p class="text-sm text-gray-600">All our listings are verified for quality and accuracy.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Add Font Awesome for icons if not already included --}}
    @push('head')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
            integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
    @endpush


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // City and Neighborhood Dropdown Logic for Search
            const cityDropdown = document.getElementById('city');
            const neighborhoodDropdown = document.getElementById('neighborhood');

            if (cityDropdown && neighborhoodDropdown) {
                // Define neighborhoods for each city (same as add property page)
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
                };

                // Get current request values
                const currentCity = "{{ request('city') }}";
                const currentNeighborhood = "{{ request('neighborhood') }}";

                function updateNeighborhoodOptions() {
                    const selectedCity = cityDropdown.value;

                    // Clear previous neighborhood options and add the default
                    neighborhoodDropdown.innerHTML = '<option value="">All Neighborhoods</option>';

                    if (selectedCity && neighborhoodsByCity[selectedCity] && neighborhoodsByCity[selectedCity]
                        .length > 0) {
                        neighborhoodsByCity[selectedCity].forEach(function(neighborhood) {
                            const option = document.createElement('option');
                            option.value = neighborhood;
                            option.textContent = neighborhood;

                            // Preserve selected neighborhood if it matches current request
                            if (neighborhood === currentNeighborhood && selectedCity === currentCity) {
                                option.selected = true;
                            }

                            neighborhoodDropdown.appendChild(option);
                        });

                        neighborhoodDropdown.disabled = false;
                    } else {
                        // If "All Cities" is selected, enable neighborhood but keep it as "All Neighborhoods"
                        neighborhoodDropdown.disabled = false;
                    }
                }

                // Add event listener for changes on the city dropdown
                cityDropdown.addEventListener('change', function() {
                    updateNeighborhoodOptions();
                    // Clear neighborhood selection when city changes (except on initial load)
                    if (cityDropdown.value === '') {
                        neighborhoodDropdown.value = '';
                    }
                });

                // Initial population of neighborhoods when the page loads
                updateNeighborhoodOptions();
            }
        });


        // FOR FAVORITES BUTTON
        document.addEventListener('DOMContentLoaded', function() {
    
            // Add bubble effect to all favorite buttons
            const favoriteButtons = document.querySelectorAll('.favorite-btn, .favorite-btn-guest');

            favoriteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    const bubbleEffect = this.querySelector('.bubble-effect');

                    // Remove existing animation
                    bubbleEffect.classList.remove('animate');

                    // Trigger reflow to restart animation
                    void bubbleEffect.offsetWidth;

                    // Add animation class
                    bubbleEffect.classList.add('animate');

                    // Remove animation class after animation completes
                    setTimeout(() => {
                        bubbleEffect.classList.remove('animate');
                    }, 600);
                });
            });

            // --- Guest Favorite Login Modal Script for Index Page ---
            const guestFavoriteBtnsIndex = document.querySelectorAll('.favorite-btn-guest');
            const guestFavoriteLoginModalIndex = document.getElementById('guestFavoriteLoginModal'); // Assuming one modal for the page
            const closeGuestFavoriteLoginModalBtnTopIndex = document.getElementById('closeGuestFavoriteLoginModalBtnTop');
            const closeGuestFavoriteLoginModalBtnBottomIndex = document.getElementById('closeGuestFavoriteLoginModalBtnBottom');

            if (guestFavoriteLoginModalIndex && closeGuestFavoriteLoginModalBtnTopIndex && closeGuestFavoriteLoginModalBtnBottomIndex) {
                function openGuestFavoriteModal() {
                    guestFavoriteLoginModalIndex.classList.remove('hidden');
                    guestFavoriteLoginModalIndex.classList.add('flex');
                }

                function closeGuestFavoriteModal() {
                    guestFavoriteLoginModalIndex.classList.add('hidden');
                    guestFavoriteLoginModalIndex.classList.remove('flex');
                }

                guestFavoriteBtnsIndex.forEach(button => {
                    button.addEventListener('click', function(event) {
                        event.preventDefault(); // Prevent default action if any
                        openGuestFavoriteModal();
                    });
                });

                closeGuestFavoriteLoginModalBtnTopIndex.addEventListener('click', closeGuestFavoriteModal);
                closeGuestFavoriteLoginModalBtnBottomIndex.addEventListener('click', closeGuestFavoriteModal);

                guestFavoriteLoginModalIndex.addEventListener('click', function(event) {
                    if (event.target === guestFavoriteLoginModalIndex) { // Click on overlay
                        closeGuestFavoriteModal();
                    }
                });

                document.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape' && !guestFavoriteLoginModalIndex.classList.contains('hidden')) {
                        closeGuestFavoriteModal();
                    }
                });
            }
        });
    </script>

<!-- Guest Favorite Login Modal (for index page) -->
<div id="guestFavoriteLoginModal" class="fixed inset-0 z-[80] flex items-center justify-center  bg-opacity-60 backdrop-blur-sm hidden" role="dialog" aria-modal="true" aria-labelledby="guestFavoriteLoginModalTitle">
    <div class="w-full max-w-md mx-4 overflow-hidden bg-white rounded-lg shadow-xl">
        <div class="flex items-center justify-between px-6 py-4 bg-gray-100 border-b border-gray-200">
            <h1 id="guestFavoriteLoginModalTitle" class="text-xl font-semibold text-gray-700">Login Required</h1>
            <button id="closeGuestFavoriteLoginModalBtnTop" aria-label="Close login required modal" class="text-2xl text-gray-500 hover:text-gray-700">&times;</button>
        </div>
        <div class="px-6 py-6">
            <p class="text-gray-700">
                Please <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:underline">Login</a> or <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:underline">Register</a> to add properties to your favorites.
            </p>
            <div class="flex justify-end mt-6">
                <button id="closeGuestFavoriteLoginModalBtnBottom" type="button" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
<!-- End Guest Favorite Login Modal -->

</x-layout>
