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
                    {{-- Property Card --}}
                    <div
                        class="relative flex flex-col overflow-hidden bg-white border border-gray-200 rounded-xl shadow-lg">
                        {{-- Favorite Button - Positioned in upper right corner --}}
                        {{-- On favorites page, it's always favorited, so shows filled heart and acts as "remove" --}}
                        <button
                            class="absolute top-3 right-3 z-10 w-10 h-10 bg-white/90 backdrop-blur-sm rounded-full shadow-md favorite-btn hover:bg-white focus:outline-none flex items-center justify-center cursor-pointer transition-all duration-200 active:scale-110 active:bg-red-100"
                            data-house-id="{{ $house->id }}" title="Remove from Favorites">
                            <i class="text-lg text-red-500 fas fa-heart"></i>
                            {{-- Click bubble effect --}}
                            <span
                                class="absolute inset-0 rounded-full bg-red-300/30 scale-0 opacity-0 pointer-events-none bubble-effect"></span>
                        </button>

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
                                {{ $house->city }}, {{ $house->neighborhood }}
                                {{ $house->second_address ? ', ' . $house->second_address : '' }}
                            </p>
                            <div class="flex items-baseline mb-4 text-center justify-center">
                                <span class="text-2xl font-bold text-blue-600 convertible-price"
                                    data-base-price-usd="{{ $house->rent_amount }}">
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
                @endforeach
            </div>
        @endif
    </div>

    @push('head')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
            integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
    @endpush

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add bubble effect to all favorite buttons
        const favoriteButtons = document.querySelectorAll('.favorite-btn'); // Only target .favorite-btn

        favoriteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                const bubbleEffect = this.querySelector('.bubble-effect');

                if (bubbleEffect) { // Check if bubbleEffect element exists
                    // Remove existing animation
                    bubbleEffect.classList.remove('animate');

                    // Trigger reflow to restart animation
                    void bubbleEffect.offsetWidth;

                    // Add animation class
                    bubbleEffect.classList.add('animate');

                    // Remove animation class after animation completes
                    setTimeout(() => {
                        bubbleEffect.classList.remove('animate');
                    }, 600); // Match CSS animation duration
                }
            });
        });
    });
</script>
</x-layout>
