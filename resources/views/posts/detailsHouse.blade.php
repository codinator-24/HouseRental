<x-layout>
    <div class="px-25 bg-gray-50">
        <section class="py-12">
            <div class="container px-4 mx-auto sm:px-6 lg:px-8">

                {{-- Back Button --}}
                <div class="py-2 mb-6">
                    <a href="{{ url()->previous() }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-1" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                        Back to Listings
                    </a>
                </div>

                <div class="overflow-hidden bg-white rounded-lg shadow-lg">

                    @if (session('success'))
                        <div class="relative px-4 py-3 mb-4 text-green-700 bg-green-100 border border-green-400 rounded"
                            role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="relative px-4 py-3 mb-4 text-red-700 bg-red-100 border border-red-400 rounded"
                            role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    {{-- Image Gallery/Carousel Section --}}
                    @if ($house->pictures->isNotEmpty())
                        <div id="imageCarousel" class="relative w-full overflow-hidden" data-carousel="slide">
                            {{-- Carousel wrapper --}}
                            <div class="relative h-96 md:h-[500px] overflow-hidden rounded-lg">
                                @foreach ($house->pictures as $index => $picture)
                                    <div class="absolute inset-0 transition-opacity duration-700 ease-in-out opacity-0"
                                        data-carousel-item @if ($loop->first) data-active @endif>
                                        <img src="{{ asset($picture->image_url) }}"
                                            class="block object-cover w-full h-full"
                                            alt="{{ $picture->caption ?? $house->title . ' - Image ' . ($index + 1) }}">
                                    </div>
                                @endforeach
                            </div>
                            {{-- Slider indicators --}}
                            @if ($house->pictures->count() > 1)
                                <div class="absolute z-30 flex space-x-3 -translate-x-1/2 bottom-5 left-1/2">
                                    @foreach ($house->pictures as $index => $picture)
                                        <button type="button"
                                            class="w-3 h-3 rounded-full {{ $loop->first ? 'bg-white' : 'bg-white/30 hover:bg-white/50' }}"
                                            aria-current="{{ $loop->first ? 'true' : 'false' }}"
                                            aria-label="Slide {{ $index + 1 }}"
                                            data-carousel-slide-to="{{ $index }}"></button>
                                    @endforeach
                                </div>
                            @endif
                            {{-- Slider controls --}}
                            @if ($house->pictures->count() > 1)
                                <button type="button"
                                    class="absolute top-0 left-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                                    data-carousel-prev>
                                    <span
                                        class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 group-hover:bg-white/50 group-focus:ring-4 group-focus:ring-white group-focus:outline-none">
                                        <svg class="w-4 h-4 text-white" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="M5 1 1 5l4 4" />
                                        </svg>
                                        <span class="sr-only">Previous</span>
                                    </span>
                                </button>
                                <button type="button"
                                    class="absolute top-0 right-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                                    data-carousel-next>
                                    <span
                                        class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 group-hover:bg-white/50 group-focus:ring-4 group-focus:ring-white group-focus:outline-none">
                                        <svg class="w-4 h-4 text-white" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="m1 9 4-4-4-4" />
                                        </svg>
                                        <span class="sr-only">Next</span>
                                    </span>
                                </button>
                            @endif
                        </div>
                    @else
                        <div class="flex items-center justify-center bg-gray-200 rounded-lg aspect-w-16 aspect-h-9">
                            <img src="https://via.placeholder.com/800x450.png?text=No+Image+Available"
                                alt="No Image Available" class="object-cover w-full h-full rounded-lg">
                        </div>
                    @endif

                    {{-- Details Section --}}
                    <div class="p-6 md:p-8">

                        {{-- Title and Price --}}
                        <div class="flex flex-col justify-between mb-4 md:flex-row md:items-center">
                            <h1 class="mb-2 text-3xl font-bold text-gray-800 md:text-4xl md:mb-0">{{ $house->title }}
                            </h1>
                            <span class="text-3xl font-bold text-blue-600">${{ number_format($house->rent_amount) }} /
                                month</span>
                        </div>

                        {{-- Address --}}
                        <div class="mb-6 text-lg text-gray-600">
                            <i class="mr-2 fas fa-map-marker-alt text-slate-500"></i>
                            {{ $house->first_address }},
                            {{ $house->second_address ? $house->second_address . ',' : '' }}
                            {{ $house->city }}
                        </div>

                        {{-- Key Features (Total Rooms, Total Floors, Size, etc.) --}}
                        @php
                            $totalRooms = $house->floors->sum('num_room');
                            $totalFloors = $house->floors->count();
                        @endphp
                        <div
                            class="grid grid-cols-2 gap-4 py-4 mb-6 border-t border-b border-gray-200 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
                            <div class="text-center">
                                <i class="mb-1 text-2xl text-blue-500 fas fa-door-open"></i>
                                <p class="text-sm text-gray-600">{{ $totalRooms }}
                                    {{ Str::plural('Room', $totalRooms) }} (Total)</p>
                            </div>
                            <div class="text-center">
                                <i class="mb-1 text-2xl text-blue-500 fas fa-layer-group"></i>
                                <p class="text-sm text-gray-600">{{ $totalFloors }}
                                    {{ Str::plural('Floor', $totalFloors) }}</p>
                            </div>
                            <div class="text-center">
                                <i class="mb-1 text-2xl text-blue-500 fas fa-ruler-combined"></i>
                                <p class="text-sm text-gray-600">{{ $house->square_footage }} m<sup>2</sup></p>
                            </div>
                            <div class="text-center">
                                <i class="mb-1 text-2xl text-blue-500 fas fa-building"></i> {{-- Changed icon for property type --}}
                                <p class="text-sm text-gray-600 capitalize">{{ $house->property_type ?? 'N/A' }}</p>
                            </div>
                            <div class="text-center">
                                {{-- Status: 'agree' or 'disagree' (approval status) --}}
                                {{-- Consider if this status is relevant for tenants or how to present it --}}
                                <i
                                    class="mb-1 text-2xl text-blue-500 fas {{ $house->status === 'available' ? 'fa-check-circle text-green-500' : 'fa-times-circle text-red-500' }}"></i>
                                <p class="text-sm text-gray-600 capitalize">{{ $house->status ?? 'N/A' }}</p>
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="mb-6">
                            <h2 class="mb-3 text-2xl font-semibold text-gray-800">Description</h2>
                            <p class="leading-relaxed text-gray-700 whitespace-pre-line">{{ $house->description }}</p>
                        </div>

                        {{-- Floor by Floor Details --}}
                        @if ($house->floors->isNotEmpty())
                            <div class="mb-6">
                                <h2 class="mb-3 text-2xl font-semibold text-gray-800">Floor Details</h2>
                                <div class="space-y-4">
                                    @foreach ($house->floors as $index => $floor)
                                        <div class="p-4 border rounded-md bg-gray-50">
                                            <h3 class="text-lg font-semibold text-gray-700">Floor {{ $index + 1 }}:
                                            </h3>
                                            <p class="text-sm text-gray-600">
                                                {{ $floor->num_room }} Rooms and Bathroom is
                                                {{ $floor->bathroom ? 'Exists' : 'not Exists' }}.
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Landlord Information --}}
                        @if ($house->landlord)
                            <div class="pt-6 mb-6 border-t">
                                <h2 class="mb-3 text-2xl font-semibold text-gray-800">Contact Landlord</h2>
                                <div class="space-y-2 text-gray-700">
                                    <p>
                                        <i class="mr-2 fas fa-user text-slate-500"></i>
                                        <strong>Name:</strong> {{ $house->landlord->full_name ?? 'N/A' }}
                                    </p>
                                    <p>
                                        <i class="mr-2 fas fa-phone text-slate-500"></i>
                                        <strong>Phone Numbers:</strong>
                                        {{ $house->landlord->first_phoneNumber ?? 'N/A' }}
                                        @if ($house->landlord->second_phoneNumber)
                                            / {{ $house->landlord->second_phoneNumber }}
                                        @endif
                                    </p>
                                    <div style="display: flex; align-items: center; gap: 6px;">
                                        <i class="fas fa-envelope mr-2 fas fa-phone text-slate-500"
                                            style="margin-bottom:8px;"></i>
                                        <a href="{{ route('contact') }}">
                                            <h2 class="mb-3 font-bold text-gray-800 color color-primary"
                                                style="color:#007bff;">For Feedback</h2>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Location URL / Map Placeholder --}}
                        @if ($house->location_url)
                            <div class="mb-6">
                                <h2 class="mb-3 text-2xl font-semibold text-gray-800">Location</h2>
                                <div
                                    class="overflow-hidden border border-gray-300 rounded-md shadow-sm aspect-w-16 aspect-h-9">
                                    <iframe src="{{ $house->location_url }}" width="100%" height="100%"
                                        style="border:0;" allowfullscreen="" loading="lazy"
                                        referrerpolicy="no-referrer-when-downgrade">
                                    </iframe>
                                </div>
                                <a href="{{ $house->location_url }}" target="_blank" rel="noopener noreferrer"
                                    class="inline-flex items-center mt-2 text-sm text-blue-600 hover:underline">
                                    View Full Map <i class="ml-1 text-xs fas fa-external-link-alt"></i>
                                </a>
                            </div>
                        @endif

                        {{-- Booking Buttons Logic --}}
                        @auth
                            @if (auth()->user()->status === 'Not Verified')
                                <div class="pt-6 mt-6 mb-8 border-t">
                                    <p class="text-lg text-orange-600">Wait until your account is verified.</p>
                                </div>
                            @else
                                @if ($house->landlord) {{-- Ensure landlord exists for this house --}}
                                    @if (auth()->id() === $house->landlord->id)
                                        {{-- User IS the landlord --}}
                                        <div class="pt-6 mt-6 mb-8 border-t">
                                            <p class="text-lg text-gray-700">This is your property. You cannot book it.</p>
                                            {{-- Optionally, add a link to manage this property or view its bookings --}}
                                        </div>
                                    @else
                                        {{-- User is authenticated and is NOT the landlord --}}
                                        {{-- Assumes $userBookingForThisHouse is passed from controller --}}
                                        {{-- $userBookingForThisHouse = Booking::where('tenant_id', auth()->id())->where('house_id', $house->id)->first(); --}}
                                        @if (isset($userBookingForThisHouse) && $userBookingForThisHouse)
                                            {{-- User has already booked this house --}}
                                            <div class="pt-6 mt-6 mb-8 border-t">
                                                <p class="mb-3 text-lg text-gray-700">You have already sent a booking
                                                    request for this property.</p>
                                                <a href="{{ route('bookings.details.show', $userBookingForThisHouse->id) }}"
                                                    class="inline-block px-6 py-3 text-lg font-bold text-white transition duration-300 ease-in-out bg-indigo-600 rounded-md hover:bg-indigo-700">
                                                    View Your Booking Details
                                                </a>
                                                {{-- <a href="{{ route('bookings.show.sent', $userBookingForThisHouse->id) }}"
                           class="inline-block px-6 py-3 text-lg font-bold text-white transition duration-300 ease-in-out bg-indigo-600 rounded-md hover:bg-indigo-700">
                            View Your Booking Details
                        </a> --}}
                                            </div>
                                        @else
                                            {{-- User has not booked this house yet: Show "Book This Property Now" button --}}
                                            <div class="pt-6 mt-6 mb-8 border-t">
                                                <button type="button" id="openBookingMessageModalBtn"
                                                    class="inline-block px-8 py-3 text-lg font-bold text-white transition duration-300 bg-green-600 rounded-md hover:bg-green-700">
                                                    Book This Property Now
                                                </button>
                                            </div>
                                        @endif
                                    @endif
                                @else
                                @endif
                            @endif
                        @endauth

                        @guest
                            {{-- Login to Book Button --}}
                            <div class="pt-6 mt-6 mb-8 border-t">
                                <a href="{{ route('login') }}"
                                    class="inline-block px-8 py-3 text-lg font-bold text-white transition duration-300 bg-blue-600 rounded-md hover:bg-blue-700">
                                    Login Now To Book
                                </a>
                            </div>
                        @endguest
                    </div>
                </div>
            </div>
        </section>
    </div>

    {{-- Booking Modal --}}
    @auth
        @if (
            $house->landlord &&
                auth()->id() !== $house->landlord->id &&
                (!isset($userBookingForThisHouse) || !$userBookingForThisHouse))
            <div id="bookingMessageModal"
                class="fixed inset-0 z-[60] flex items-center justify-center bg-opacity-50 backdrop-blur-sm"
                style="display: none;" role="dialog" aria-modal="true" aria-labelledby="bookingMessageModalTitle">
                <div class="w-full max-w-md mx-4 overflow-hidden bg-white rounded-lg shadow-xl">
                    <div class="flex items-center justify-between px-6 py-4 bg-gray-100 border-b border-gray-200">
                        <h1 id="bookingMessageModalTitle" class="text-xl font-semibold text-gray-700">Send Booking Request
                        </h1>
                        <button id="closeBookingMessageModalBtn" aria-label="Close booking modal"
                            class="text-2xl text-gray-500 hover:text-gray-700">×</button>
                    </div>

                    <form method="POST" action="{{ route('send.booking', ['house' => $house->id]) }}"
                        class="px-6 py-6">
                        @csrf
                        <div class="mb-4">
                            <label for="month_duration" class="block mb-1 text-sm font-medium text-gray-700">Your Required
                                Duration</label>
                            <input type="number" name="month_duration" id="month_duration"
                                placeholder="Enter Your Month Duration"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('month_duration', 'sendBookingFormErrors') border-red-500 @enderror"{{ old('month_duration') }}>
                        </div>
                        <div class="mb-4">
                            <label for="booking_message" class="block mb-1 text-sm font-medium text-gray-700">Your Message
                                (Optional)</label>
                            <textarea name="booking_message" id="booking_message" rows="5"
                                placeholder="E.g., I'm interested in viewing this property. What are the next steps?"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('booking_message', 'sendBookingFormErrors') border-red-500 @enderror">{{ old('booking_message') }}</textarea>
                        </div>

                        @if ($errors->sendBookingFormErrors->any())
                            <div class="p-3 mb-4 text-red-700 bg-red-100 border border-red-400 rounded" role="alert">
                                <p class="font-bold">Please correct the following error(s):</p>
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->sendBookingFormErrors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif


                        <div class="flex justify-end">
                            <button type="submit"
                                class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Send Booking Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    @endauth

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // --- Carousel Script (Existing) ---
                const carouselElement = document.getElementById('imageCarousel');
                if (carouselElement) { // Check if carousel exists on the page
                    const items = Array.from(carouselElement.querySelectorAll('[data-carousel-item]'));
                    const prevButton = carouselElement.querySelector('[data-carousel-prev]');
                    const nextButton = carouselElement.querySelector('[data-carousel-next]');
                    const indicators = Array.from(carouselElement.querySelectorAll('[data-carousel-slide-to]'));

                    if (items.length > 0) {
                        let activeIndex = items.findIndex(item => item.hasAttribute('data-active'));
                        if (activeIndex === -1) {
                            activeIndex = 0;
                        }

                        function showItem(indexToShow) {
                            if (indexToShow < 0 || indexToShow >= items.length) {
                                return;
                            }
                            items.forEach((item, idx) => {
                                if (idx === indexToShow) {
                                    item.classList.remove('opacity-0');
                                    item.classList.add('opacity-100');
                                    item.setAttribute('data-active', '');
                                } else {
                                    item.classList.remove('opacity-100');
                                    item.classList.add('opacity-0');
                                    item.removeAttribute('data-active');
                                }
                            });
                            indicators.forEach((indicator, idx) => {
                                indicator.setAttribute('aria-current', idx === indexToShow ? 'true' : 'false');
                                if (idx === indexToShow) {
                                    indicator.classList.add('bg-white');
                                    indicator.classList.remove('bg-white/30', 'hover:bg-white/50');
                                } else {
                                    indicator.classList.remove('bg-white');
                                    indicator.classList.add('bg-white/30', 'hover:bg-white/50');
                                }
                            });
                            activeIndex = indexToShow;
                        }

                        if (prevButton) {
                            prevButton.addEventListener('click', () => {
                                let newIndex = activeIndex - 1;
                                if (newIndex < 0) {
                                    newIndex = items.length - 1;
                                }
                                showItem(newIndex);
                            });
                        }

                        if (nextButton) {
                            nextButton.addEventListener('click', () => {
                                let newIndex = activeIndex + 1;
                                if (newIndex >= items.length) {
                                    newIndex = 0;
                                }
                                showItem(newIndex);
                            });
                        }

                        indicators.forEach(indicator => {
                            indicator.addEventListener('click', () => {
                                const slideToIndex = parseInt(indicator.getAttribute(
                                    'data-carousel-slide-to'));
                                showItem(slideToIndex);
                            });
                        });
                        showItem(activeIndex); // Initialize
                    }
                }

                // --- Booking Message Modal Script (MODIFIED) ---
                // Only attach listeners if the modal and its trigger button exist
                // (i.e., user hasn't booked yet, is not the landlord, and the modal HTML is rendered)
                const openBookingBtn = document.getElementById('openBookingMessageModalBtn');
                const closeBookingBtn = document.getElementById('closeBookingMessageModalBtn');
                const bookingModal = document.getElementById('bookingMessageModal');

                if (openBookingBtn && closeBookingBtn && bookingModal) {
                    openBookingBtn.addEventListener('click', function() {
                        bookingModal.style.display = 'flex';
                    });

                    closeBookingBtn.addEventListener('click', function() {
                        bookingModal.style.display = 'none';
                    });

                    bookingModal.addEventListener('click', function(event) {
                        if (event.target === bookingModal) { // Click on overlay
                            bookingModal.style.display = 'none';
                        }
                    });

                    // Close with Escape key
                    document.addEventListener('keydown', function(event) {
                        if (event.key === 'Escape' && bookingModal.style.display === 'flex') {
                            bookingModal.style.display = 'none';
                        }
                    });
                }

                // Keep booking modal open if there are validation errors for it
                // Ensure your controller redirects back with errors in the 'bookingMessageErrors' bag
                @if ($errors->hasBag('sendBookingFormErrors') && $errors->sendBookingFormErrors->any())
                    if (bookingModal) { // Check if modal exists on the page (it should if errors are present for it)
                        bookingModal.style.display = 'flex';
                    }
                @endif

            });
        </script>
    @endpush
</x-layout>
