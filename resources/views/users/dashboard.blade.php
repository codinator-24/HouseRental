<x-layout>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Hello, {{ auth()->user()->user_name }}!</h1>

        @if (auth()->user()->role === 'tenant' || auth()->user()->role === 'both')
            {{-- My Latest Sent Bookings Section --}}
            <section class="mb-12">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-700">My Latest Sent Bookings</h2>
                </div>

                @if ($sentBookings->isNotEmpty() || $hasMoreSentBookings)
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6 mb-6">
                        @foreach ($sentBookings as $booking)
                            <div
                                class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200 flex flex-col h-full transition-all duration-300 hover:shadow-xl">
                                <div class="p-4 flex flex-col flex-grow">
                                    @if ($booking->house)
                                        <h3 class="text-md font-semibold text-indigo-600 mb-2 truncate"
                                            title="{{ $booking->house->title }}">
                                            <a href="{{ route('house.details', $booking->house->id) }}"
                                                class="hover:underline">
                                                {{ Str::limit($booking->house->title, 35) ?? 'Property Title N/A' }}
                                            </a>
                                        </h3>
                                        <p class="text-xs text-gray-500 mb-1">
                                            To:
                                            {{ $booking->house->landlord->full_name ?? ($booking->house->landlord->user_name ?? 'Landlord N/A') }}
                                        </p>
                                    @else
                                        <h3 class="text-md font-semibold text-red-600 mb-2">Property Unavailable</h3>
                                        <p class="text-xs text-gray-500 mb-1">To: Landlord N/A</p>
                                    @endif

                                    <div class="text-sm text-gray-700 space-y-1 my-3 flex-grow">
                                        <p>
                                            <span class="font-medium text-gray-500">Date:</span>
                                            {{ $booking->created_at->format('M d, Y') }}
                                        </p>
                                        <p>
                                            <span class="font-medium text-gray-500">Duration:</span>
                                            {{ $booking->month_duration }}
                                            {{ Str::plural('month', $booking->month_duration) }}
                                        </p>
                                        <div>
                                            <span class="font-medium text-gray-500">Status:</span>
                                            <span
                                                class="px-2 py-0.5 ml-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                            {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $booking->status === 'accepted' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $booking->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                                            {{ $booking->status === 'cancelled' ? 'bg-gray-100 text-gray-700' : '' }}
                                            {{ !in_array($booking->status, ['pending', 'accepted', 'rejected', 'cancelled']) ? 'bg-blue-100 text-blue-800' : '' }}
                                        ">
                                                {{ ucfirst($booking->status ?? 'N/A') }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="mt-auto pt-3 border-t border-gray-200">
                                        <a href="{{ route('bookings.details.show', $booking->id) }}"
                                            class="text-sm text-indigo-500 hover:text-indigo-700 font-semibold hover:underline flex items-center">
                                            View Details
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        {{-- @if ($hasMoreSentBookings) --}}
                            <div
                                class="bg-white rounded-lg shadow-lg border border-gray-200 flex flex-col h-full items-center justify-center p-4 transition-all duration-300 hover:shadow-xl hover:border-indigo-300">
                                <a href="{{ route('bookings.sent') }}"
                                    class="text-sm text-indigo-500 hover:text-indigo-700 font-semibold hover:underline flex flex-col items-center text-center p-2">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-8 w-8 mb-1 text-indigo-500 group-hover:text-indigo-700" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                    <span class="mt-1">View More</span>
                                    <span class="text-xs">Sent Bookings</span>
                                </a>
                            </div>
                        {{-- @endif --}}
                    </div>
                @else
                    <div class="bg-white rounded-lg shadow-md p-8 border border-gray-200 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8v4m0 4h.01M21.75 12a9.75 9.75 0 01-9.75 9.75A9.75 9.75 0 012.25 12a9.75 9.75 0 019.75-9.75A9.75 9.75 0 0121.75 12zM12 18.75a.75.75 0 000-1.5.75.75 0 000 1.5z" />
                        </svg>
                        <p class="mt-4 text-lg font-medium text-gray-700">No Sent Bookings Yet</p>
                        <p class="text-sm text-gray-500 mt-1">When you send a booking request to a property, it will
                            appear here.</p>
                    </div>
                @endif
            </section>
        @endif

        @if (auth()->user()->role === 'landlord' || auth()->user()->role === 'both')
            {{-- Latest Received Bookings Section --}}
            <section class="mb-12">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-700">Latest Received Bookings</h2>
                </div>

                @if ($receivedBookings->isNotEmpty() || $hasMoreReceivedBookings)
                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6 mb-6">
                        @foreach ($receivedBookings as $booking)
                            <div
                                class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200 flex flex-col h-full transition-all duration-300 hover:shadow-xl">
                                <div class="p-4 flex flex-col flex-grow">
                                    @if ($booking->house)
                                        <h3 class="text-md font-semibold text-indigo-600 mb-2 truncate"
                                            title="{{ $booking->house->title }}">
                                            <a href="{{ route('house.details', $booking->house->id) }}"
                                                class="hover:underline">
                                                {{ Str::limit($booking->house->title, 35) ?? 'Property Title N/A' }}
                                            </a>
                                        </h3>
                                    @else
                                        <h3 class="text-md font-semibold text-red-600 mb-2">Property Unavailable</h3>
                                    @endif
                                    <p class="text-xs text-gray-500 mb-1">
                                        From:
                                        {{ $booking->tenant->full_name ?? ($booking->tenant->user_name ?? 'Tenant N/A') }}
                                    </p>

                                    <div class="text-sm text-gray-700 space-y-1 my-3 flex-grow">
                                        <p>
                                            <span class="font-medium text-gray-500">Date:</span>
                                            {{ $booking->created_at->format('M d, Y') }}
                                        </p>
                                        <p>
                                            <span class="font-medium text-gray-500">Duration:</span>
                                            {{ $booking->month_duration }}
                                            {{ Str::plural('month', $booking->month_duration) }}
                                        </p>
                                        <div>
                                            <span class="font-medium text-gray-500">Status:</span>
                                            <span
                                                class="px-2 py-0.5 ml-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                            {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $booking->status === 'accepted' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $booking->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                                            {{ $booking->status === 'cancelled' ? 'bg-gray-100 text-gray-700' : '' }}
                                            {{ !in_array($booking->status, ['pending', 'accepted', 'rejected', 'cancelled']) ? 'bg-blue-100 text-blue-800' : '' }}
                                        ">
                                                {{ ucfirst($booking->status ?? 'N/A') }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="mt-auto pt-3 border-t border-gray-200">
                                        <a href="{{ route('bookings.show', $booking->id) }}" {{-- Route for landlord to view booking details --}}
                                            class="text-sm text-indigo-500 hover:text-indigo-700 font-semibold hover:underline flex items-center">
                                            View Details
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        {{-- @if ($hasMoreReceivedBookings) --}}
                            <div
                                class="bg-white rounded-lg shadow-lg border border-gray-200 flex flex-col h-full items-center justify-center p-4 transition-all duration-300 hover:shadow-xl hover:border-indigo-300">
                                <a href="{{ route('my.bookings') }}" {{-- This route should lead to the page showing all received bookings --}}
                                    class="text-sm text-indigo-500 hover:text-indigo-700 font-semibold hover:underline flex flex-col items-center text-center p-2">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-8 w-8 mb-1 text-indigo-500 group-hover:text-indigo-700" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                    <span class="mt-1">View More</span>
                                    <span class="text-xs">Received Bookings</span>
                                </a>
                            </div>
                        {{-- @endif --}}
                    </div>
                @else
                    <div class="bg-white rounded-lg shadow-md p-8 border border-gray-200 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                        <p class="mt-4 text-lg font-medium text-gray-700">No Received Bookings Yet</p>
                        <p class="text-sm text-gray-500 mt-1">When a tenant sends a booking request for one of your
                            properties, it will appear here.</p>
                    </div>
                @endif
            </section>

            {{-- My Properties Section --}}
            <section class="py-8">
                <div>
                    <div class="flex justify-between items-center mb-8">
                        <h2 class="text-2xl font-semibold text-gray-700">My Properties</h2> {{-- Adjusted heading size for consistency --}}
                    </div>

                    {{-- Properties Grid --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-8">
                        @forelse ($houses as $house)
                            <div
                                class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200 flex flex-col transition-all duration-300 hover:shadow-xl h-full">
                                @php
                                    $imageUrl = $house->pictures->first()?->image_url
                                        ? asset($house->pictures->first()->image_url) // Using asset() as per your provided context
                                        : 'https://images.pexels.com/photos/731082/pexels-photo-731082.jpeg';
                                @endphp
                                <img src="{{ $imageUrl }}" alt="{{ $house->title }}"
                                    class="w-full h-48 object-cover">
                                <div class="p-5 flex flex-col flex-grow">
                                    <h3 class="text-xl font-semibold mb-2 truncate" title="{{ $house->title }}">
                                        {{ Str::limit($house->title, 45) }}</h3>
                                    <p class="text-gray-600 text-sm mb-3 leading-relaxed">
                                        <i class="fas fa-map-marker-alt mr-1.5 text-slate-400"></i>
                                        {{ Str::limit($house->city, 70) }} {{-- Using $house->city as per your context --}}
                                    </p>
                                    <div class="text-sm text-gray-500 mb-3 space-y-1.5">
                                        <div class="flex items-center">
                                            <i class="fas fa-door-open mr-2 w-4 text-center text-slate-400"></i>
                                            {{ $house->number_of_rooms }}
                                            {{ Str::plural('Room', $house->number_of_rooms) }}
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-bath mr-2 w-4 text-center text-slate-400"></i>
                                            {{ $house->number_of_bathrooms }}
                                            {{ Str::plural('Bathroom', $house->number_of_bathrooms) }}
                                        </div>
                                    </div>
                                    <p class="text-lg font-semibold text-indigo-600 mb-4">
                                        ${{ number_format($house->rent_amount, 2) }}/month</p> {{-- Using $house->rent_amount as per your context --}}

                                    {{-- Action Buttons --}}
                                    <div class="mt-auto pt-4 border-t border-gray-200">
                                        <div class="flex justify-end space-x-2">
                                            <a href="{{ route('Myhouse.edit', $house) }}"
                                                class="bg-blue-500 hover:bg-blue-600 text-white py-1.5 px-3 rounded text-xs font-semibold flex items-center justify-center transition-colors duration-150"
                                                title="Edit Property">
                                                <i class="fas fa-pencil-alt"></i>
                                                <span class="ml-1.5 hidden sm:inline">Edit</span>
                                            </a>
                                            <form action="{{ route('Myhouse.delete', $house) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    onclick="return confirm('Are you sure you want to delete this property? This action cannot be undone.');"
                                                    class="bg-red-500 hover:bg-red-600 text-white py-1.5 px-3 rounded text-xs font-semibold flex items-center justify-center transition-colors duration-150"
                                                    title="Delete Property">
                                                    <i class="fas fa-trash-alt"></i>
                                                    <span class="ml-1.5 hidden sm:inline">Delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            {{-- This @empty is for the @forelse. The main empty state is handled below. --}}
                        @endforelse

                        @if ($hasMoreProperties)
                            {{-- "View More Properties" as a card in the grid --}}
                            <div
                                class="bg-white rounded-lg shadow-lg border border-gray-200 flex flex-col h-full items-center justify-center p-4 transition-all duration-300 hover:shadow-xl hover:border-indigo-300">
                                <a href="{{ route('my.houses.index') }}" {{-- Make sure this route exists and lists all user's properties --}}
                                    class="text-sm text-indigo-500 hover:text-indigo-700 font-semibold hover:underline flex flex-col items-center text-center p-2">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-8 w-8 mb-1 text-indigo-500 group-hover:text-indigo-700"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                    <span class="mt-1">View More</span>
                                    <span class="text-xs">My Properties</span>
                                </a>
                            </div>
                        @endif

                        @if ($houses->isEmpty() && !$hasMoreProperties)
                            {{-- This is the main empty state for no properties at all --}}
                            <div
                                class="col-span-1 sm:col-span-2 md:col-span-3 lg:col-span-4 xl:col-span-5 bg-white rounded-lg shadow-md p-8 border border-gray-200 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 3.545A2.25 2.25 0 0115 5.795h4.5a2.25 2.25 0 012.25 2.25v9A2.25 2.25 0 0121.75 19.5h-4.5M8.25 21H3.75A2.25 2.25 0 011.5 18.75V5.795A2.25 2.25 0 013.75 3.545M8.25 21h4.5M16.5 3.545M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="mt-4 text-lg font-medium text-gray-700">No Properties Found</p>
                                <p class="text-sm text-gray-500 mt-1">You haven't listed any properties yet. Add your
                                    first
                                    property to see it here.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        @endif

    </div>
</x-layout>
