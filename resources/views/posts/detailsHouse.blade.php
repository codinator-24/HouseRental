<x-layout>
    <div class="px-25 bg-gray-50">
    <section class="py-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Back Button --}}
            <div class="mb-6 py-2">
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
                    <div id="imageCarousel" class="relative w-full overflow-hidden" data-carousel="slide">
                        {{-- Carousel wrapper --}}
                        <div class="relative h-96 md:h-[500px] overflow-hidden rounded-lg">
                            @foreach ($house->pictures as $index => $picture)
                                <div class="opacity-0 transition-opacity duration-700 ease-in-out absolute inset-0" data-carousel-item
                                    @if ($loop->first) data-active @endif>
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
                                    <button type="button" class="w-3 h-3 rounded-full {{ $loop->first ? 'bg-white' : 'bg-white/30 hover:bg-white/50' }}"
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
</div>
    {{-- Ensure Font Awesome is loaded if not globally available --}}
    {{-- @push('head')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
            integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
    @endpush --}}

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const carouselElement = document.getElementById('imageCarousel');
            if (!carouselElement) return;

            const items = Array.from(carouselElement.querySelectorAll('[data-carousel-item]'));
            const prevButton = carouselElement.querySelector('[data-carousel-prev]');
            const nextButton = carouselElement.querySelector('[data-carousel-next]');
            const indicators = Array.from(carouselElement.querySelectorAll('[data-carousel-slide-to]'));

            if (items.length === 0) return;

            let activeIndex = items.findIndex(item => item.hasAttribute('data-active'));
            if (activeIndex === -1) { // If no item is marked active by Blade, default to the first one
                activeIndex = 0;
                // items[0].setAttribute('data-active', ''); // Blade should set this for the first item
            }

            function showItem(indexToShow) {
                if (indexToShow < 0 || indexToShow >= items.length) {
                    console.warn(`Carousel: Invalid index ${indexToShow}`);
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
                    const slideToIndex = parseInt(indicator.getAttribute('data-carousel-slide-to'));
                    showItem(slideToIndex);
                });
            });

            // Initialize the carousel to show the correct active item
            if (items.length > 0) {
                // activeIndex should be 0 if Blade set data-active on the first item,
                // or if it defaulted because no data-active was found.
                showItem(activeIndex);
            }
        });
    </script>
    @endpush
</x-layout>
