<x-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-xl rounded-lg overflow-hidden max-w-3xl mx-auto">
            <!-- Header Section -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-700 p-6 sm:p-8 text-white">
                <h1 class="text-2xl sm:text-3xl font-bold text-center">Booking Details</h1>
            </div>

            <div class="p-6 sm:p-8 space-y-6">
                <!-- Tenant Information -->
                <section aria-labelledby="tenant-info-heading">
                    <h2 id="tenant-info-heading" class="text-xl sm:text-2xl font-semibold text-gray-800 mb-4 border-b border-gray-300 pb-2">
                        Tenant Information
                    </h2>
                    <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-6">
                        <img src="{{ $booking->tenant->picture ? Storage::url($booking->tenant->picture) : 'https://ui-avatars.com/api/?name=' . urlencode($booking->tenant->user_name ?? $booking->tenant->full_name ?? 'Tenant') . '&background=random&size=128' }}"
                             alt="{{ $booking->tenant->user_name ?? 'Tenant' }}'s profile picture"
                             class="w-24 h-24 sm:w-32 sm:h-32 rounded-full object-cover border-4 border-indigo-300 shadow-md">
                        <div class="text-center sm:text-left">
                            <p class="text-xl font-bold text-gray-700">
                                {{ $booking->tenant->full_name ?? 'Tenant Name Not Available' }}
                            </p>
                            {{-- Display secondary name if `full_name` was different from `name` and both exist --}}
                            @if(isset($booking->tenant->full_name) && isset($booking->tenant->user_name) && $booking->tenant->full_name !== $booking->tenant->user_name)
                                <p class="text-md text-gray-500">({{ $booking->tenant->user_name }})</p>
                            @endif
                            <p class="text-gray-600 mt-1">
                                <span class="font-semibold">Phone :</span> {{ $booking->tenant->first_phoneNumber ?? 'Not provided' }} | {{ $booking->tenant->second_phoneNumber ?? 'Not provided' }}
                            </p>
                        </div>
                    </div>
                </section>

                <!-- Booking Message -->
                <section aria-labelledby="booking-message-heading">
                    <h2 id="booking-message-heading" class="text-xl sm:text-2xl font-semibold text-gray-800 mb-3 border-b border-gray-300 pb-2">
                        Message from Tenant
                    </h2>
                    <div class="bg-gray-50 p-4 rounded-md shadow-inner">
                        <p class="text-gray-700 whitespace-pre-line">{{ $booking->message ?? 'No message provided.' }}</p>
                    </div>
                </section>

                <!-- Booking & Property Details -->
                <section aria-labelledby="booking-property-details-heading">
                    <h2 id="booking-property-details-heading" class="text-xl sm:text-2xl font-semibold text-gray-800 mb-4 border-b border-gray-300 pb-2">
                        Booking & Property Information
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm sm:text-base">
                        <div>
                            <p class="text-gray-700"><strong class="text-gray-900">Booking Sent:</strong></p>
                            <p class="text-indigo-700 font-medium">{{ $booking->created_at->format('F j, Y, g:i a') }}</p>
                            <p class="text-xs text-gray-500">({{ $booking->created_at->diffForHumans() }})</p>
                        </div>
                        <div>
                            <p class="text-gray-700"><strong class="text-gray-900">Booked Property:</strong></p>
                            @if($booking->house)
                                <a href="{{ route('house.details', $booking->house->id) }}" class="text-indigo-600 hover:text-indigo-800 hover:underline font-medium">{{ $booking->house->title }}</a>
                                <p class="text-xs text-gray-500">{{ $booking->house->address ?? 'Address not available' }}</p>
                            @else
                                <p class="text-gray-500">Property information not available.</p>
                            @endif
                        </div>
                    </div>
                </section>

                <!-- Booking Status -->
                <section aria-labelledby="booking-status-display-heading" class="mt-6">
                    <h2 id="booking-status-display-heading" class="text-xl sm:text-2xl font-semibold text-gray-800 mb-3 border-b border-gray-300 pb-2">
                        Current Status
                    </h2>
                    <div class="bg-gray-100 p-4 rounded-md shadow">
                        <p class="text-lg text-center font-medium">
                            @if($booking->status === 'pending')
                                Status: <span class="text-yellow-600 font-bold">Pending Landlord Action</span>
                            @elseif($booking->status === 'accepted')
                                Status: <span class="text-green-600 font-bold">Accepted</span>
                            @elseif($booking->status === 'rejected')
                                Status: <span class="text-red-600 font-bold">Rejected</span>
                            @else
                                Status: <span class="text-gray-700 font-bold">{{ ucfirst($booking->status) }}</span>
                            @endif
                        </p>
                    </div>
                </section>

                {{-- Landlord Actions --}}
                @if($booking->house && Auth::check() && Auth::id() === $booking->house->landlord_id && $booking->status === 'pending')
                    <section aria-labelledby="landlord-actions-heading" class="mt-8 pt-6 border-t border-gray-200">
                        <h2 id="landlord-actions-heading" class="text-xl sm:text-2xl font-semibold text-gray-800 mb-4 text-center">
                            Manage Booking Request
                        </h2>
                        <div class="flex justify-center items-center space-x-3 sm:space-x-4">
                            <form action="{{ route('bookings.accept', $booking) }}" method="POST" onsubmit="return confirm('Are you sure you want to accept this booking?');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 sm:px-6 rounded-lg shadow-md transition duration-150 ease-in-out text-sm sm:text-base">
                                    Accept Booking
                                </button>
                            </form>
                            <form action="{{ route('bookings.reject', $booking) }}" method="POST" onsubmit="return confirm('Are you sure you want to reject this booking?');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 sm:px-6 rounded-lg shadow-md transition duration-150 ease-in-out text-sm sm:text-base">
                                    Reject Booking
                                </button>
                            </form>
                        </div>
                    </section>
                @endif

                <div class="mt-8 pt-6 border-t border-gray-200 text-center">
                    @if(Auth::check())
                        @if($booking->house && Auth::id() === $booking->house->landlord_id)
                            <a href="{{ route('my.bookings') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-6 rounded-lg shadow-md transition duration-150 ease-in-out">
                                Back to Received Bookings
                            </a>
                        @elseif(Auth::id() === $booking->tenant_id)
                            <a href="{{ route('bookings.sent') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-6 rounded-lg shadow-md transition duration-150 ease-in-out">
                                Back to Sent Bookings
                            </a>
                        @else
                             {{-- Fallback, though authorization should ideally prevent reaching here without a defined role --}}
                            <a href="{{ url()->previous() }}" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-6 rounded-lg shadow-md transition duration-150 ease-in-out">
                                Go Back
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layout>