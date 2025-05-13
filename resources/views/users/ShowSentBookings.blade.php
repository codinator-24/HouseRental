<x-layout>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if($booking)
            <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                <div class="p-6 md:p-8">
                    <div class="flex justify-between items-start mb-6">
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Booking Details</h1>
                        <a href="{{ url()->previous(route('home')) }}" class="text-sm text-indigo-600 hover:text-indigo-800 hover:underline flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Go Back
                        </a>
                    </div>

                    {{-- Property Information --}}
                    @if($booking->house)
                        <div class="mb-8 p-5 border border-gray-200 rounded-lg bg-gray-50">
                            <h2 class="text-xl sm:text-2xl font-semibold text-indigo-700 mb-3">
                                <a href="{{ route('house.details', $booking->house->id) }}" class="hover:underline">
                                    {{ $booking->house->title ?? 'Property Title N/A' }}
                                </a>
                            </h2>
                            <p class="text-sm text-gray-600 mb-1"><span class="font-semibold">Address:</span> {{ $booking->house->address ?? 'N/A' }}</p>
                            @if($booking->house->landlord)
                                <p class="text-sm text-gray-600 mb-1">
                                    <span class="font-semibold">Property landlord:</span>
                                    {{ $booking->house->landlord->full_name ?? $booking->house->landlord->user_name ?? 'N/A' }}
                                    (<a href="mailto:{{ $booking->house->landlord->email }}" class="text-indigo-500 hover:underline">{{ $booking->house->landlord->email }}</a>)
                                </p>
                            @else
                                <p class="text-sm text-gray-500">landlord information not available.</p>
                            @endif
                        </div>
                    @else
                        <div class="mb-8 p-5 border border-red-300 rounded-lg bg-red-50 text-red-700">
                            <h2 class="text-xl sm:text-2xl font-semibold mb-3">Property Information Not Available</h2>
                            <p class="text-sm">The property associated with this booking may have been removed or is no longer available.</p>
                        </div>
                    @endif

                    {{-- Booking Information --}}
                    <div class="mb-6">
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-700 mb-3">Booking Request Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-2 text-sm">
                            <p class="text-gray-700"><span class="font-semibold text-gray-500">Booking ID:</span> #{{ $booking->id }}</p>
                            <p class="text-gray-700"><span class="font-semibold text-gray-500">Requested On:</span> {{ $booking->created_at->format('F j, Y, g:i a') }} ({{ $booking->created_at->diffForHumans() }})</p>
                            {{-- Assuming Booking model has a 'status' field. Adjust if your field is named differently or doesn't exist. --}}
                            <p class="text-gray-700"><span class="font-semibold text-gray-500">Status:</span>
                                <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $booking->status === 'accepted' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $booking->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $booking->status === 'cancelled' ? 'bg-gray-200 text-gray-800' : '' }}
                                    {{ !in_array($booking->status, ['pending', 'accepted', 'rejected', 'cancelled']) ? 'bg-blue-100 text-blue-800' : '' }}
                                ">
                                    {{ ucfirst($booking->status ?? 'N/A') }}
                                </span>
                            </p>
                            {{-- Add other booking details like start/end dates if applicable --}}
                            {{-- <p class="text-gray-700"><span class="font-semibold text-gray-500">Check-in:</span> {{ $booking->start_date ? $booking->start_date->format('F j, Y') : 'N/A' }}</p> --}}
                            {{-- <p class="text-gray-700"><span class="font-semibold text-gray-500">Check-out:</span> {{ $booking->end_date ? $booking->end_date->format('F j, Y') : 'N/A' }}</p> --}}
                        </div>
                    </div>

                    {{-- Booker Information (Tenant) --}}
                    @if($booking->tenant)
                        <div class="mb-6">
                            <h3 class="text-lg sm:text-xl font-semibold text-gray-700 mb-3">
                                {{ Auth::id() == $booking->tenant_id ? 'Your Information (Booker)' : 'Booker Information' }}
                            </h3>
                             <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-2 text-sm">
                                <p class="text-gray-700"><span class="font-semibold text-gray-500">Name:</span> {{ $booking->tenant->full_name ?? $booking->tenant->user_name ?? 'N/A' }}</p>
                                <p class="text-gray-700"><span class="font-semibold text-gray-500">Email:</span> <a href="mailto:{{ $booking->tenant->email }}" class="text-indigo-500 hover:underline">{{ $booking->tenant->email }}</a></p>
                                <p class="text-gray-700"><span class="font-semibold text-gray-500">Phone:</span> {{ $booking->tenant->phone_number ?? 'N/A' }}</p>
                            </div>
                        </div>
                    @endif

                    {{-- Message --}}
                    <div class="mb-8">
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-700 mb-3">Message</h3>
                        <div class="bg-gray-50 p-4 rounded-md shadow-inner prose prose-sm max-w-none min-h-[60px]">
                            <p class="text-gray-700 whitespace-pre-line">{{ $booking->message ?? 'No message was provided with this booking.' }}</p>
                        </div>
                    </div>

                    {{-- Actions (e.g., Cancel booking, Contact landlord). These are examples and would require backend logic. --}}
                    <div class="mt-8 pt-6 border-t border-gray-200 flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3">
                        {{-- Example: Cancel button for the tenant if booking is pending/accepted --}}
                        {{-- @if(Auth::id() == $booking->tenant_id && in_array($booking->status, ['pending', 'accepted']))
                            <form action="{{-- route('bookings.cancel', $booking->id) --}}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 border border-red-500 text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    Cancel Booking
                                </button>
                            </form>
                        @endif --}}

                        {{-- Example: Accept/Reject buttons for the property landlord if booking is pending --}}
                        {{-- @if($booking->house && Auth::id() == $booking->house->landlord_id && $booking->status == 'pending')
                            <form action="{{-- route('bookings.respond', [$booking->id, 'accept']) --}}" method="POST" class="inline"> @csrf @method('PATCH') <button type="submit" class="w-full sm:w-auto ... bg-green-600 hover:bg-green-700 ...">Accept</button></form>
                            <form action="{{-- route('bookings.respond', [$booking->id, 'reject']) --}}" method="POST" class="inline"> @csrf @method('PATCH') <button type="submit" class="w-full sm:w-auto ... bg-red-600 hover:bg-red-700 ...">Reject</button></form>
                        @endif --}}
                    </div>

                </div>
            </div>
        @else
            <div class="text-center text-gray-500 py-10">
                <p class="text-xl font-semibold">Booking not found.</p>
                <p class="mt-2 text-sm">The booking you are looking for does not exist or could not be loaded.</p>
                <div class="mt-6">
                    <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Go to Homepage
                    </a>
                </div>
            </div>
        @endif
    </div>
</x-layout>