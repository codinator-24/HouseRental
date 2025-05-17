<x-layout>

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
                <a href="{{ route('Show.house.add') }}"
                    class="px-6 py-3 text-lg font-bold text-white transition duration-300 bg-blue-600 rounded-md hover:bg-blue-700">
                    Click here to place your property
                </a>
            </div>
        </section>
    @endauth

    {{-- Search Bar Section --}}
    <section id="search"
        class="container relative z-20 max-w-4xl px-5 py-6 mx-auto -mt-16 bg-white rounded-lg shadow-lg">
        {{-- Submit to the current URL to re-load the page with query parameters --}}
        <form action="{{ request()->url() }}" method="GET" class="grid items-end grid-cols-1 gap-4 md:grid-cols-4">
            {{-- Location --}}
            <div>
                <label for="location" class="block mb-1 text-sm font-medium text-gray-700">Location</label>
                <input type="text" id="location" name="location" value="{{ request('location') }}"
                    placeholder="City, neighborhood..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
            </div>
            {{-- Price --}}
            <div>
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
            <div>
                <label for="property_type" class="block mb-1 text-sm font-medium text-gray-700">Property Type</label>
                <select id="property_type" name="property_type"
                    class="w-full px-4 py-2 bg-white border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    <option value="" {{ request('property_type') == '' ? 'selected' : '' }}>Property Type</option>
                    <option value="apartment" {{ request('property_type') == 'apartment' ? 'selected' : '' }}>Apartment
                    </option>
                    <option value="house" {{ request('property_type') == 'house' ? 'selected' : '' }}>House</option>
                    <option value="condo" {{ request('property_type') == 'condo' ? 'selected' : '' }}>Condo</option>
                    <option value="studio" {{ request('property_type') == 'studio' ? 'selected' : '' }}>Studio</option>
                </select>
            </div>
            {{-- Search Button --}}
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md w-full h-[42px]">
                {{-- Matched height of inputs --}}
                Search properties
            </button>
        </form>
    </section>


    <div class="px-10 bg-gray-50">

        {{-- Featured Properties Section --}}
        <section class="py-5">
            <div class="container px-6 mx-auto">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-3xl font-bold text-gray-800">Featured Properties</h2>
                    <a href="{{route('home')}}" class="font-medium text-blue-600 hover:text-blue-800">View All Properties</a>
                </div>

                {{-- Property Grid --}}
                <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                    @forelse ($houses as $house)
                        {{-- Property Card --}}
                        <div class="flex flex-col overflow-hidden bg-white border border-gray-200 rounded-lg shadow-md">
                            {{-- Use first picture if available, otherwise a placeholder --}}
                            @php
                                $imageUrl = $house->pictures->first()?->image_url
                                    ? asset($house->pictures->first()->image_url)
                                    : 'https://images.pexels.com/photos/731082/pexels-photo-731082.jpeg';
                            @endphp
                            <img src="{{ $imageUrl }}" alt="{{ $house->title }}" class="object-cover w-full h-48">
                            <div class="flex flex-col flex-grow p-6">
                                <h3 class="mb-2 text-xl font-semibold">{{ $house->title }}</h3>
                                <p class="flex-grow mb-4 text-sm text-gray-600">
                                    <i class="mr-1 fas fa-map-marker-alt text-slate-500"></i>
                                    {{ $house->city }}, {{ $house->first_address }}
                                    {{ $house->second_address ?? '' }}
                                </p>
                                <div class="flex items-center justify-between mb-4">
                                    <span
                                        class="text-2xl font-bold text-blue-600">${{ number_format($house->rent_amount) }}</span>
                                    <span class="text-sm text-gray-600">per month</span>
                                </div>
                                <div class="flex justify-between pt-4 mb-4 text-sm text-gray-600 border-t">
                                    <span><i class="mr-1 fas fa-bed"></i> {{ $house->num_room }}
                                        {{ Str::plural('Room', $house->num_room) }}</span>
                                    <span><i class="mr-1 fas fa-layer-group"></i> {{ $house->num_floor }}
                                        {{ Str::plural('Floor', $house->num_floor) }}</span>
                                    <span><i class="mr-1 fas fa-ruler-combined"></i> {{ $house->square_footage }}
                                        m<sup>2</sup></span>
                                </div>
                                <a href="{{ route('house.details', $house) }}"
                                    class="block w-full px-4 py-2 mt-auto font-bold text-center text-white transition duration-300 bg-blue-600 rounded hover:bg-blue-700">
                                    View Details
                                </a>
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
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
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

</x-layout>
