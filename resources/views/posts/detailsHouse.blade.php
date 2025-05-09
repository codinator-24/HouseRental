<x-layout>
    <div class="px-25 bg-gray-50">
        <section class="py-12">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">

                {{-- Back Button --}}
                <div class="mb-6 py-2">
                    <a href="{{ url()->previous() }}" class="text-blue-600 hover:text-blue-800 inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                        Back to Listings
                    </a>
                </div>

                <div class="bg-white shadow-lg rounded-lg overflow-hidden">

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
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
                                    <div class="opacity-0 transition-opacity duration-700 ease-in-out absolute inset-0"
                                        data-carousel-item @if ($loop->first) data-active @endif>
                                        <img src="{{ asset($picture->image_url) }}"
                                            class="block w-full h-full object-cover"
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
                        <div class="aspect-w-16 aspect-h-9 bg-gray-200 flex items-center justify-center rounded-lg">
                            <img src="https://via.placeholder.com/800x450.png?text=No+Image+Available"
                                alt="No Image Available" class="object-cover w-full h-full rounded-lg">
                        </div>
                    @endif

                    {{-- Details Section --}}
                    <div class="p-6 md:p-8">

                        {{-- Title and Price --}}
                        <div class="flex flex-col md:flex-row justify-between md:items-center mb-4">
                            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2 md:mb-0">{{ $house->title }}
                            </h1>
                            <span class="text-3xl font-bold text-blue-600">${{ number_format($house->rent_amount) }} /
                                month</span>
                        </div>

                        {{-- Address --}}
                        <div class="text-gray-600 mb-6 text-lg">
                            <i class="fas fa-map-marker-alt mr-2 text-slate-500"></i>
                            {{ $house->first_address }},
                            {{ $house->second_address ? $house->second_address . ',' : '' }}
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
                                <p class="text-sm text-gray-600">{{ $house->square_footage }} m<sup>2</sup></p>
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
                                        <strong>Phone Numbers:</strong>
                                        {{ $house->landlord->first_phoneNumber ?? 'N/A' }}
                                        @if ($house->landlord->second_phoneNumber)
                                            / {{ $house->landlord->second_phoneNumber }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @endif

                        {{-- Location URL / Map Placeholder --}}
                        @if ($house->location_url)
                            <div class="mb-6">
                                <h2 class="text-2xl font-semibold text-gray-800 mb-3">Location</h2>
                                <div
                                    class="aspect-w-16 aspect-h-9 rounded-md overflow-hidden border border-gray-300 shadow-sm">
                                    <iframe src="{{ $house->location_url }}" width="100%" height="100%"
                                        style="border:0;" allowfullscreen="" loading="lazy"
                                        referrerpolicy="no-referrer-when-downgrade">
                                    </iframe>
                                </div>
                                <a href="{{ $house->location_url }}" target="_blank" rel="noopener noreferrer"
                                    class="text-blue-600 hover:underline inline-flex items-center mt-2 text-sm">
                                    View Full Map <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                                </a>
                            </div>
                        @endif

                        @auth
                            {{-- Booking Button - MODIFIED --}}
                            {{-- Show booking button only if the authenticated user is NOT the landlord of this house --}}
                            @if ($house->landlord && auth()->id() !== $house->landlord->id)
                                <div class="mt-6 mb-8 border-t pt-6">
                                    <button type="button" id="openBookingMessageModalBtn"
                                        class="inline-block bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-md text-lg transition duration-300">
                                        Book This Property Now
                                    </button>
                                </div>
                            @endif
                        @endauth
                        @guest
                            {{-- Login to Book Button --}}
                            <div class="mt-6 mb-8 border-t pt-6">
                                <a href="{{ route('login') }}"
                                    class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-md text-lg transition duration-300">
                                    Login Now To Book
                                </a>
                            </div>
                        @endguest
                    </div>
                </div>
            </div>
        </section>
    </div>

    {{-- Booking Modal--}}
    <div id="bookingMessageModal" class="fixed inset-0 z-[60] flex items-center justify-center bg-opacity-50 backdrop-blur-sm"
        style="display: none;" role="dialog" aria-modal="true" aria-labelledby="bookingMessageModalTitle">
        <div class="bg-white rounded-lg shadow-xl overflow-hidden max-w-md w-full mx-4">
            <div class="px-6 py-4 bg-gray-100 border-b border-gray-200 flex justify-between items-center">
                <h1 id="bookingMessageModalTitle" class="text-xl font-semibold text-gray-700">Send Booking
                </h1>
                <button id="closeBookingMessageModalBtn" aria-label="Close booking modal"
                    class="text-gray-500 hover:text-gray-700 text-2xl">×</button>
            </div>

            {{-- Replace 'booking.sendMessage' with your actual route name --}}
            {{-- Make sure this route can handle $house parameter if needed, or add a hidden input for house_id --}}
            <form method="POST" action="{{ route('send.booking', ['house' => $house->id]) }}" class="px-6 py-6">
                @csrf

                {{-- Display Validation Errors for Booking Message --}}
                {{-- Ensure your controller uses 'bookingMessageErrors' as the named error bag if validation fails --}}
                @if ($errors->bookingMessageErrors->any())
                    <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded" role="alert">
                        <p class="font-bold">Please correct the following error(s):</p>
                        <ul class="list-disc list-inside">
                            @foreach ($errors->bookingMessageErrors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Message --}}
                <div class="mb-4">
                    <label for="booking_message" class="block text-sm font-medium text-gray-700 mb-1">Your Message
                        (Optional)</label>
                    <textarea name="booking_message" id="booking_message" rows="5"
                        placeholder="E.g., I'm interested in viewing this property. What are the next steps?"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('booking_message', 'bookingMessageErrors') border-red-500 @enderror">{{ old('booking_message') }}</textarea>
                    @error('booking_message', 'bookingMessageErrors')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit Button --}}
                <div class="flex justify-end">
                    <button type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Send Booking
                    </button>
                </div>
            </form>
        </div>
    </div>

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

                // --- Booking Message Modal Script (NEW) ---
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
                @if ($errors->hasBag('bookingMessageErrors') && $errors->bookingMessageErrors->any())
                    if (bookingModal) {
                        bookingModal.style.display = 'flex';
                    }
                @endif
            });
        </script>
    @endpush
</x-layout>
