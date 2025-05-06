<x-layout>
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Back Button --}}
            <div class="mb-6">
                <a href="{{ url()->previous() }}" class="text-blue-600 hover:text-blue-800 inline-flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                    Back to Listings
                </a>
            </div>

            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                {{-- Image Gallery/Carousel Section --}}
                @if ($house->pictures->isNotEmpty())
                    {{-- Simple Grid for now, could be replaced with a carousel library --}}
                    <div
                        class="grid grid-cols-1 @if ($house->pictures->count() > 1) md:grid-cols-2 @endif @if ($house->pictures->count() > 2) lg:grid-cols-3 @endif gap-1">
                        @foreach ($house->pictures as $picture)
                            <div class="aspect-w-16 aspect-h-9">
                                <img src="{{ asset($picture->image_url) }}"
                                    alt="{{ $picture->caption ?? $house->title }}" class="object-cover w-full h-full">
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="aspect-w-16 aspect-h-9 bg-gray-200 flex items-center justify-center">
                        <img src="https://via.placeholder.com/800x450.png?text=No+Image+Available"
                            alt="No Image Available" class="object-cover w-full h-full">
                    </div>
                @endif

                {{-- Details Section --}}
                <div class="p-6 md:p-8">
                    {{-- Title and Price --}}
                    <div class="flex flex-col md:flex-row justify-between md:items-center mb-4">
                        <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2 md:mb-0">{{ $house->title }}</h1>
                        <span class="text-3xl font-bold text-blue-600">${{ number_format($house->rent_amount) }} /
                            month</span>
                    </div>

                    {{-- Address --}}
                    <div class="text-gray-600 mb-6 text-lg">
                        <i class="fas fa-map-marker-alt mr-2 text-slate-500"></i>
                        {{ $house->first_address }}, {{ $house->second_address ? $house->second_address . ',' : '' }}
                        {{ $house->city }}
                    </div>

                    {{-- Key Features (Rooms, Floors, Size) --}}
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 border-t border-b border-gray-200 py-4 mb-6">
                        <div class="text-center">
                            <i class="fas fa-bed text-blue-500 text-2xl mb-1"></i>
                            <p class="text-sm text-gray-600">{{ $house->num_room }}
                                {{ Str::plural('Room', $house->num_room) }}</p>
                        </div>
                        <div class="text-center">
                            <i class="fas fa-layer-group text-blue-500 text-2xl mb-1"></i>
                            <p class="text-sm text-gray-600">{{ $house->num_floor }}
                                {{ Str::plural('Floor', $house->num_floor) }}</p>
                        </div>
                        <div class="text-center">
                            <i class="fas fa-ruler-combined text-blue-500 text-2xl mb-1"></i>
                            <p class="text-sm text-gray-600">{{ $house->square_footage }} sq ft</p>
                        </div>
                        <div class="text-center">
                            <i class="fas fa-home text-blue-500 text-2xl mb-1"></i>
                            <p class="text-sm text-gray-600 capitalize">{{ $house->property_type ?? 'N/A' }}</p>
                        </div>
                        <div class="text-center">
                            <i class="fas fa-check-circle text-blue-500 text-2xl mb-1"></i>
                            <p class="text-sm text-gray-600 capitalize">{{ $house->status ?? 'N/A' }}</p>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="mb-6">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-3">Description</h2>
                        <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $house->description }}</p>
                    </div>

                    {{-- Landlord Information --}}
                    @if ($house->landlord)
                        <div class="mb-6 border-t pt-6">
                            <h2 class="text-2xl font-semibold text-gray-800 mb-3">Contact Landlord</h2>
                            <div class="text-gray-700 space-y-2">
                                <p>
                                    <i class="fas fa-user mr-2 text-slate-500"></i>
                                    <strong>Name:</strong> {{ $house->landlord->full_name ?? 'N/A' }}
                                </p>
                                <p>
                                    <i class="fas fa-phone mr-2 text-slate-500"></i>
                                    <strong>First Phone Number:</strong> {{ $house->landlord->first_phoneNumber ?? 'N/A'}} - {{$house->landlord->second_phoneNumber ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                    @endif

                    {{-- Location URL / Map Placeholder --}}
                    @if ($house->location_url)
                        <div class="mb-6">
                            <h2 class="text-2xl font-semibold text-gray-800 mb-3">Location</h2>
                            {{-- Embed Google Map --}}
                            {{-- NOTE: This requires $house->location_url to be a valid Google Maps *embed* URL. --}}
                            {{-- If it's a standard share URL, it might not display correctly. --}}
                            <div
                                class="aspect-w-16 aspect-h-9 rounded-md overflow-hidden border border-gray-300 shadow-sm">
                                {{-- Container for aspect ratio --}}
                                <iframe src="{{ $house->location_url }}" width="100%" {{-- Tailwind handles sizing via aspect ratio container --}}
                                    height="100%" {{-- Tailwind handles sizing via aspect ratio container --}} style="border:0;" allowfullscreen=""
                                    loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                                </iframe>
                            </div>
                            {{-- Optional: Keep the link to view on Google Maps itself --}}
                            <a href="{{ $house->location_url }}" target="_blank" rel="noopener noreferrer"
                                class="text-blue-600 hover:underline inline-flex items-center mt-2 text-sm">
                                View Full Map <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                            </a>
                        </div>
                    @endif

                    @auth
                        {{-- Booking Button --}}
                        <div class="mt-6 mb-8 border-t pt-6">
                            {{-- Link this to your actual booking route when ready --}}
                            <a href="#" {{-- href="{{ route('booking.create', $house) }}" --}}
                                class="inline-block bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-md text-lg transition duration-300">
                                Book This Property Now
                            </a>
                        </div>
                    @endauth

                    @guest
                        {{-- Booking Button --}}
                        <div class="mt-6 mb-8 border-t pt-6">
                            {{-- Link this to your actual booking route when ready --}}
                            <a href="{{ route('login') }}" {{-- href="{{ route('booking.create', $house) }}" --}}
                                class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-md text-lg transition duration-300">
                                Login Now To Book
                            </a>
                        </div>
                    @endguest

                </div>
            </div>
        </div>
    </section>

    {{-- Ensure Font Awesome is loaded if not globally available --}}
    @push('head')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
            integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
        {{-- Add Tailwind Aspect Ratio plugin if needed for image containers --}}
        {{-- <script src="https://cdn.tailwindcss.com?plugins=aspect-ratio"></script> --}}
    @endpush
</x-layout>
