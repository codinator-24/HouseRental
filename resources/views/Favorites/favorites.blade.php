<x-layout>
    <div class="container px-6 py-12 mx-auto">
        <h1 class="mb-10 text-4xl font-bold text-center text-gray-800 md:text-left">My Favorite Properties</h1>

        @if ($favoriteHouses->isEmpty())
            <div class="p-8 text-center bg-white border border-gray-200 rounded-lg shadow-lg">
                <svg class="w-16 h-16 mx-auto mb-6 text-blue-500" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                    </path>
                </svg>
                <p class="mb-4 text-2xl font-semibold text-gray-700">No Favorites Yet</p>
                <p class="mb-8 text-gray-600">You haven't added any properties to your favorites. Start exploring and
                    find your dream home!</p>
                <a href="{{ route('home') }}"
                    class="inline-block px-8 py-3 text-lg font-semibold text-white transition duration-300 bg-blue-600 rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                    Explore Properties
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3">
                @foreach ($favoriteHouses as $house)
                    <div
                        class="flex flex-col overflow-hidden transition-shadow duration-300 bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-xl">
                        @php
                            $imageUrl = $house->pictures->first()?->image_url
                                ? asset($house->pictures->first()->image_url) // Consistent with index.blade.php
                                : 'https://images.pexels.com/photos/731082/pexels-photo-731082.jpeg'; // Default placeholder
                        @endphp
                        <a href="{{ route('house.details', $house) }}" class="block">
                            <img src="{{ $imageUrl }}" alt="Image of {{ $house->title }}"
                                class="object-cover w-full h-56">
                        </a>

                        <div class="flex flex-col flex-grow p-5">
                            <h3 class="mb-2 text-xl font-semibold text-gray-800">
                                <a href="{{ route('house.details', $house) }}"
                                    class="hover:text-blue-600">{{ $house->title }}</a>
                            </h3>

                            <p class="flex-grow mb-3 text-sm text-gray-600">
                                <i class="mr-1 fas fa-map-marker-alt text-slate-500"></i>
                                {{ $house->city }}, {{ $house->neighborhood }}
                                {{ $house->second_address ? ', ' . $house->second_address : '' }}
                            </p>

                            <div class="flex items-center justify-between mb-4">
                                <span class="text-2xl font-bold text-blue-600 convertible-price"
                                    data-base-price-usd="{{ $house->rent_amount }}">
                                    ${{ number_format($house->rent_amount, 2) }}
                                </span>
                                <span class="text-sm text-gray-500">per month</span>
                            </div>

                            <div class="grid grid-cols-3 gap-2 pt-3 mb-4 text-xs text-gray-600 border-t">
                                <span class="truncate"><i class="mr-1 fas fa-bed"></i> {{ $house->num_room ?? 'N/A' }}
                                    {{ Str::plural('Room', $house->num_room ?? 0) }}</span>
                                <span class="truncate"><i class="mr-1 fas fa-layer-group"></i>
                                    {{ $house->num_floor ?? 'N/A' }}
                                    {{ Str::plural('Floor', $house->num_floor ?? 0) }}</span>
                                <span class="truncate"><i class="mr-1 fas fa-ruler-combined"></i>
                                    {{ $house->square_footage ?? 'N/A' }} m<sup>2</sup></span>
                            </div>

                            <div class="flex items-center mt-auto">
                                <a href="{{ route('house.details', $house) }}"
                                    class="block w-full px-4 py-2.5 font-semibold text-center text-white transition duration-300 bg-blue-600 rounded-l-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                                    View Details
                                </a>
                                {{-- User is authenticated and this house is favorited --}}
                                <button
                                    class="p-2.5 px-4 bg-blue-600 rounded-r-md favorite-btn hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
                                    data-house-id="{{ $house->id }}" title="Remove from Favorites">
                                    <i class="text-xl text-red-500 fas fa-heart"></i> {{-- Always filled heart --}}
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    @push('head')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
            integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
    @endpush
</x-layout>