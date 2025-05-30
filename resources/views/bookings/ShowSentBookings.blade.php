<x-layout>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if ($booking)
            <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                <div class="p-6 md:p-8">
                    {{-- Page Header --}}
                    <div class="flex justify-between items-start mb-6 pb-6 border-b border-gray-200">
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Booking Details</h1>
                        <a href="{{ url()->previous(route('home')) }}"
                            class="text-sm text-indigo-600 hover:text-indigo-800 hover:underline flex items-center shrink-0 ml-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Go Back
                        </a>
                    </div>

                    <div class="divide-y divide-gray-200">

                        {{-- Property Information --}}
                        <section class="py-6">
                            @if ($booking->house)
                                <div class="p-5 border border-gray-200 rounded-lg bg-gray-50/50">
                                    <h2 class="text-xl font-semibold text-indigo-700 mb-3 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path
                                                d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                                        </svg>
                                        <a href="{{ route('house.details', $booking->house->id) }}"
                                            class="hover:underline">
                                            {{ $booking->house->title ?? 'Property Title N/A' }}
                                        </a>
                                    </h2>
                                    <div class="space-y-1.5 text-sm">
                                        <p class="text-gray-600 flex items-start">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 mr-1.5 text-gray-400 shrink-0 mt-0.5" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <span class="font-medium text-gray-500 mr-1">Address:</span>
                                            {{ $booking->house->address ?? 'N/A' }}
                                        </p>
                                        @if ($booking->house->landlord)
                                            <p class="text-gray-600 flex items-start">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4 mr-1.5 text-gray-400 shrink-0 mt-0.5"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <span class="font-medium text-gray-500 mr-1">Property Landlord:</span>
                                                {{ $booking->house->landlord->full_name ?? ($booking->house->landlord->user_name ?? 'N/A') }}
                                                (<a href="mailto:{{ $booking->house->landlord->email }}"
                                                    class="text-indigo-500 hover:underline">{{ $booking->house->landlord->email }}</a>)
                                            </p>
                                        @else
                                            <p class="text-sm text-gray-500 italic">Landlord information not available.
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="p-5 border border-red-300 rounded-lg bg-red-50 text-red-700">
                                    <h2 class="text-xl font-semibold mb-3">Property Information Not Available</h2>
                                    <p class="text-sm">The property associated with this booking may have been removed
                                        or is no longer available.</p>
                                </div>
                            @endif
                        </section>

                        {{-- Booking Information --}}
                        <section class="py-6">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">Booking Request Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-3 text-sm">
                                <p class="text-gray-700"><span class="font-medium text-gray-500">Booking ID:</span>
                                    #{{ $booking->id }}</p>
                                <p class="text-gray-700"><span class="font-medium text-gray-500">Requested On:</span>
                                    {{ $booking->created_at->format('F j, Y, g:i a') }}
                                    ({{ $booking->created_at->diffForHumans() }})</p>
                                <p class="text-gray-700"><span class="font-medium text-gray-500">Status:</span>
                                    <span
                                        class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $booking->status === 'accepted' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $booking->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $booking->status === 'cancelled' ? 'bg-gray-200 text-gray-800' : '' }}
                                        {{ !in_array($booking->status, ['pending', 'accepted', 'rejected', 'cancelled']) ? 'bg-blue-100 text-blue-800' : '' }}
                                    ">
                                        {{ ucfirst($booking->status ?? 'N/A') }}
                                    </span>
                                </p>
                                {{-- Example placeholder for other booking details --}}
                                {{-- <p class="text-gray-700"><span class="font-medium text-gray-500">Check-in:</span> {{ $booking->start_date ? $booking->start_date->format('F j, Y') : 'N/A' }}</p> --}}
                                {{-- <p class="text-gray-700"><span class="font-medium text-gray-500">Check-out:</span> {{ $booking->end_date ? $booking->end_date->format('F j, Y') : 'N/A' }}</p> --}}
                            </div>
                        </section>

                        {{-- Booker Information (Tenant) --}}
                        @if ($booking->tenant)
                            <section class="py-6">
                                <h3 class="text-xl font-semibold text-gray-800 mb-4">
                                    {{ Auth::id() == $booking->tenant_id ? 'Your Information (Booker)' : 'Booker Information' }}
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-3 text-sm">
                                    <p class="text-gray-700"><span class="font-medium text-gray-500">Name:</span>
                                        {{ $booking->tenant->full_name ?? ($booking->tenant->user_name ?? 'N/A') }}</p>
                                    <p class="text-gray-700"><span class="font-medium text-gray-500">Email:</span> <a
                                            href="mailto:{{ $booking->tenant->email }}"
                                            class="text-indigo-500 hover:underline">{{ $booking->tenant->email }}</a>
                                    </p>
                                    <p class="text-gray-700"><span class="font-medium text-gray-500">Phone:</span>
                                        {{ $booking->tenant->first_phoneNumber ?? 'N/A' }} @if ($booking->tenant->second_phoneNumber)
                                            || {{ $booking->tenant->second_phoneNumber }}
                                        @endif
                                    </p>
                                </div>
                            </section>
                        @endif

                        {{-- Form for updating booking details --}}
                        <section class="py-6">
                            <form method="POST" action="{{ route('bookings.sent.update', $booking->id) }}">
                                @csrf
                                @method('PATCH')

                                <div class="space-y-6">
                                    <div>
                                        <label for="month_duration"
                                            class="block text-sm font-medium text-gray-700 mb-1">Your Required Duration
                                            (Months)</label>
                                        <input type="number" name="month_duration" id="month_duration"
                                            value="{{ old('month_duration', (int) $booking->month_duration) }}"
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('month_duration') border-red-500 @enderror"
                                            required min="1">
                                        {{-- @error('month_duration')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror --}}
                                    </div>

                                    <div>
                                        <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Your
                                            Message</label>
                                        <textarea name="message" id="message" rows="5"
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('message') border-red-500 @enderror"
                                            placeholder="Update your message to the landlord (optional)">{{ old('message', $booking->message) }}</textarea>
                                        {{-- @error('message')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror --}}
                                    </div>
                                </div>

                                {{-- Actions for Update Form and Delete action --}}
                                    {{-- Update Button (submit for the main form) --}}
                                    @if (Auth::id() == $booking->tenant_id && in_array($booking->status, ['pending']))
                                        <button type="submit"
                                            class="w-full sm:w-auto justify-center bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 border border-transparent rounded-md shadow-sm text-sm transition duration-150 ease-in-out mt-3">
                                            Update Booking
                                        </button>
                                    @endif

                            </form> {{-- End of update form --}}

                                  {{-- Delete Button (as a separate form) --}}
                                    @if (Auth::id() == $booking->tenant_id && in_array($booking->status, ['pending','rejected','accepted']))
                                        <form method="POST"
                                            action="{{ route('bookings.sent.destroy', $booking->id) }}"
                                            onsubmit="return confirm('Are you sure you want to delete this booking request? This action cannot be undone.');"
                                            class="w-full sm:w-auto">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                              class="w-full sm:w-auto flex items-center justify-center bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 border border-transparent rounded-md shadow-sm text-sm transition duration-150 mt-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                        </section>
                        {{-- End of form section --}}

                    </div> {{-- End of divide-y container --}}
                </div> {{-- End of main card padding --}}
            </div> {{-- End of main card --}}
        @else
            <div class="text-center text-gray-600 py-12">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="mt-4 text-xl font-semibold text-gray-700">Booking not found.</p>
                <p class="mt-2 text-sm">The booking you are looking for does not exist or could not be loaded.</p>
                <div class="mt-6">
                    <a href="{{ route('home') }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Go to Homepage
                    </a>
                </div>
            </div>
        @endif
    </div>
</x-layout>
