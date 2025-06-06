<x-layout>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Hello, {{ auth()->user()->user_name }}!</h1>
            <p class="text-gray-600">Welcome back to your dashboard</p>
        </div>

        <!-- Tab Navigation -->
        <div class="mb-8">
            <div class="flex flex-wrap gap-2 bg-white p-2 rounded-xl shadow-sm border border-gray-100">
                @if (auth()->user()->role === 'tenant' || auth()->user()->role === 'both')
                    <button onclick="switchTab('sent-bookings')" id="tab-sent-bookings" 
                            class="tab-btn px-6 py-3 rounded-lg font-semibold transition-all duration-300 flex items-center gap-2">
                        <i class="fas fa-paper-plane text-sm"></i>
                        <span>My Sent Bookings</span>
                        <span class="count-badge px-2 py-1 rounded-full text-xs font-bold">{{ $sentBookings->count() }}</span>
                    </button>
                @endif
                @if (auth()->user()->role === 'landlord' || auth()->user()->role === 'both')
                    <button onclick="switchTab('received-bookings')" id="tab-received-bookings" 
                            class="tab-btn px-6 py-3 rounded-lg font-semibold transition-all duration-300 flex items-center gap-2">
                        <i class="fas fa-inbox text-sm"></i>
                        <span>Received Bookings</span>
                        <span class="count-badge px-2 py-1 rounded-full text-xs font-bold">{{ $receivedBookings->count() }}</span>
                    </button>
                    <button onclick="switchTab('properties')" id="tab-properties" 
                            class="tab-btn px-6 py-3 rounded-lg font-semibold transition-all duration-300 flex items-center gap-2">
                        <i class="fas fa-home text-sm"></i>
                        <span>My Properties</span>
                        <span class="count-badge px-2 py-1 rounded-full text-xs font-bold">{{ $houses->count() }}</span>
                    </button>
                    <button onclick="switchTab('maintenance')" id="tab-maintenance" 
                            class="tab-btn px-6 py-3 rounded-lg font-semibold transition-all duration-300 flex items-center gap-2">
                        <i class="fas fa-wrench text-sm"></i>
                        <span>Maintenance</span>
                        <span class="count-badge px-2 py-1 rounded-full text-xs font-bold">{{ 3 }}</span>
                        {{-- <span class="count-badge px-2 py-1 rounded-full text-xs font-bold">{{ $maintenanceRequests->count() ?? 0 }}</span> --}}
                    </button>
                @endif
            </div>
        </div>

        <!-- Tab Content -->
        <div class="tab-content">
            @if (auth()->user()->role === 'tenant' || auth()->user()->role === 'both')
                <!-- Sent Bookings Tab -->
                <div id="content-sent-bookings" class="tab-panel">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">My Latest Sent Bookings</h2>
                        </div>

                        @if ($sentBookings->isNotEmpty() || $hasMoreSentBookings)
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6">
                                @foreach ($sentBookings as $booking)
                                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 card-hover overflow-hidden">
                                        <div class="p-5">
                                            @if ($booking->house)
                                                <h3 class="text-lg font-bold text-gray-800 mb-2 truncate" title="{{ $booking->house->title }}">
                                                    <a href="{{ route('house.details', $booking->house->id) }}" class="hover:underline">
                                                        {{ Str::limit($booking->house->title, 35) ?? 'Property Title N/A' }}
                                                    </a>
                                                </h3>
                                                <p class="text-sm text-gray-500 mb-3">
                                                    To: {{ $booking->house->landlord->full_name ?? ($booking->house->landlord->user_name ?? 'Landlord N/A') }}
                                                </p>
                                            @else
                                                <h3 class="text-lg font-bold text-red-600 mb-2">Property Unavailable</h3>
                                                <p class="text-sm text-gray-500 mb-3">To: Landlord N/A</p>
                                            @endif
                                            
                                            <div class="space-y-2 mb-4">
                                                <div class="flex items-center text-sm text-gray-600">
                                                    <i class="fas fa-calendar w-4 mr-2" style="color: #1b61c2;"></i>
                                                    <span>{{ $booking->created_at->format('M d, Y') }}</span>
                                                </div>
                                                <div class="flex items-center text-sm text-gray-600">
                                                    <i class="fas fa-clock w-4 mr-2" style="color: #1b61c2;"></i>
                                                    <span>{{ $booking->month_duration }} {{ Str::plural('month', $booking->month_duration) }}</span>
                                                </div>
                                                <div class="flex items-center">
                                                    <i class="fas fa-info-circle w-4 mr-2" style="color: #1b61c2;"></i>
                                                    <span class="px-2 py-1 rounded-full text-xs font-semibold border
                                                        {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800 border-yellow-200' : '' }}
                                                        {{ $booking->status === 'accepted' ? 'bg-green-100 text-green-800 border-green-200' : '' }}
                                                        {{ $booking->status === 'rejected' ? 'bg-red-100 text-red-800 border-red-200' : '' }}
                                                        {{ $booking->status === 'cancelled' ? 'bg-gray-100 text-gray-700 border-gray-200' : '' }}
                                                        {{ !in_array($booking->status, ['pending', 'accepted', 'rejected', 'cancelled']) ? 'bg-blue-100 text-blue-800 border-blue-200' : '' }}">
                                                        {{ ucfirst($booking->status ?? 'N/A') }}
                                                    </span>
                                                </div>
                                            </div>

                                            <a href="{{ route('bookings.details.show', $booking->id) }}" 
                                               class="w-full text-center py-2 px-4 rounded-lg font-semibold text-sm transition-all duration-300 border-t border-gray-100 mt-4 pt-4 flex items-center justify-center gap-2" 
                                               style="color: #1b61c2;" onmouseover="this.style.backgroundColor='rgba(27, 97, 194, 0.1)'" 
                                               onmouseout="this.style.backgroundColor='transparent'">
                                                <i class="fas fa-eye"></i>View Details
                                            </a>
                                        </div>
                                    </div>
                                @endforeach

                                @if ($hasMoreSentBookings)
                                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border-2 border-dashed border-gray-300 card-hover flex flex-col items-center justify-center p-6 min-h-[280px]">
                                        <a href="{{ route('bookings.sent') }}" class="text-center">
                                            <div class="w-12 h-12 mx-auto mb-3 rounded-full flex items-center justify-center" style="background-color: rgba(27, 97, 194, 0.1);">
                                                <i class="fas fa-arrow-right text-xl" style="color: #1b61c2;"></i>
                                            </div>
                                            <p class="font-semibold mb-1" style="color: #1b61c2;">View All</p>
                                            <p class="text-sm text-gray-500">Sent Bookings</p>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="bg-white rounded-lg shadow-md p-8 border border-gray-200 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 8v4m0 4h.01M21.75 12a9.75 9.75 0 01-9.75 9.75A9.75 9.75 0 012.25 12a9.75 9.75 0 019.75-9.75A9.75 9.75 0 0121.75 12zM12 18.75a.75.75 0 000-1.5.75.75 0 000 1.5z" />
                                </svg>
                                <p class="mt-4 text-lg font-medium text-gray-700">No Sent Bookings Yet</p>
                                <p class="text-sm text-gray-500 mt-1">When you send a booking request to a property, it will appear here.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Maintenance Tab -->
                <div id="content-maintenance" class="tab-panel hidden">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">Maintenance Requests</h2>
                            <a href="#" 
                               class="text-white px-4 py-2 rounded-lg font-semibold transition-all duration-300 flex items-center gap-2" 
                               style="background: linear-gradient(135deg, #1b61c2, #3b82f6);">
                                <i class="fas fa-plus"></i>New Request
                            </a>
                        </div>

                        @if (isset($maintenanceRequests) && $maintenanceRequests->isNotEmpty())
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                                @foreach ($maintenanceRequests as $request)
                                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 card-hover overflow-hidden">
                                        <div class="p-5">
                                            <div class="flex items-center justify-between mb-3">
                                                <h3 class="text-lg font-bold text-gray-800 truncate" title="{{ $request->title }}">
                                                    {{ Str::limit($request->title ?? 'Maintenance Request', 25) }}
                                                </h3>
                                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                                    {{ $request->priority === 'high' ? 'bg-red-100 text-red-800 border border-red-200' : '' }}
                                                    {{ $request->priority === 'medium' ? 'bg-yellow-100 text-yellow-800 border border-yellow-200' : '' }}
                                                    {{ $request->priority === 'low' ? 'bg-green-100 text-green-800 border border-green-200' : '' }}
                                                    {{ !in_array($request->priority ?? '', ['high', 'medium', 'low']) ? 'bg-gray-100 text-gray-700 border border-gray-200' : '' }}">
                                                    {{ ucfirst($request->priority ?? 'Normal') }}
                                                </span>
                                            </div>
                                            
                                            @if ($request->property)
                                                <p class="text-sm text-gray-500 mb-3 flex items-center">
                                                    <i class="fas fa-home mr-2" style="color: #1b61c2;"></i>
                                                    {{ Str::limit($request->property->title ?? 'Property', 30) }}
                                                </p>
                                            @endif
                                            
                                            <div class="space-y-2 mb-4">
                                                <div class="flex items-center text-sm text-gray-600">
                                                    <i class="fas fa-calendar w-4 mr-2" style="color: #1b61c2;"></i>
                                                    <span>{{ $request->created_at->format('M d, Y') }}</span>
                                                </div>
                                                <div class="flex items-center text-sm text-gray-600">
                                                    <i class="fas fa-user w-4 mr-2" style="color: #1b61c2;"></i>
                                                    <span>{{ $request->requester->full_name ?? $request->requester->user_name ?? 'Unknown' }}</span>
                                                </div>
                                                <div class="flex items-center">
                                                    <i class="fas fa-info-circle w-4 mr-2" style="color: #1b61c2;"></i>
                                                    <span class="px-2 py-1 rounded-full text-xs font-semibold border
                                                        {{ $request->status === 'pending' ? 'bg-yellow-100 text-yellow-800 border-yellow-200' : '' }}
                                                        {{ $request->status === 'in_progress' ? 'bg-blue-100 text-blue-800 border-blue-200' : '' }}
                                                        {{ $request->status === 'completed' ? 'bg-green-100 text-green-800 border-green-200' : '' }}
                                                        {{ $request->status === 'cancelled' ? 'bg-gray-100 text-gray-700 border-gray-200' : '' }}
                                                        {{ !in_array($request->status, ['pending', 'in_progress', 'completed', 'cancelled']) ? 'bg-blue-100 text-blue-800 border-blue-200' : '' }}">
                                                        {{ ucfirst(str_replace('_', ' ', $request->status ?? 'Pending')) }}
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="border-t border-gray-100 mt-4 pt-4">
                                                <a href="{{ route('maintenance.show', $request->id) }}" 
                                                   class="w-full text-center py-2 px-4 rounded-lg font-semibold text-sm transition-all duration-300 flex items-center justify-center gap-2" 
                                                   style="color: #1b61c2;" onmouseover="this.style.backgroundColor='rgba(27, 97, 194, 0.1)'" 
                                                   onmouseout="this.style.backgroundColor='transparent'">
                                                    <i class="fas fa-eye"></i>View Details
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                @if (isset($hasMoreMaintenance) && $hasMoreMaintenance)
                                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border-2 border-dashed border-gray-300 card-hover flex flex-col items-center justify-center p-6 min-h-[400px]">
                                        <a href="{{ route('maintenance.index') }}" class="text-center">
                                            <div class="w-12 h-12 mx-auto mb-3 rounded-full flex items-center justify-center" style="background-color: rgba(27, 97, 194, 0.1);">
                                                <i class="fas fa-arrow-right text-xl" style="color: #1b61c2;"></i>
                                            </div>
                                            <p class="font-semibold mb-1" style="color: #1b61c2;">View All</p>
                                            <p class="text-sm text-gray-500">Maintenance Requests</p>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="bg-white rounded-lg shadow-md p-8 border border-gray-200 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z" />
                                </svg>
                                <p class="mt-4 text-lg font-medium text-gray-700">No Maintenance Requests</p>
                                <p class="text-sm text-gray-500 mt-1">When maintenance requests are submitted, they will appear here.</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @if (auth()->user()->role === 'landlord' || auth()->user()->role === 'both')
                <!-- Received Bookings Tab -->
                <div id="content-received-bookings" class="tab-panel hidden">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">Latest Received Bookings</h2>
                        </div>

                        @if ($receivedBookings->isNotEmpty() || $hasMoreReceivedBookings)
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6">
                                @foreach ($receivedBookings as $booking)
                                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 card-hover overflow-hidden">
                                        <div class="p-5">
                                            @if ($booking->house)
                                                <h3 class="text-lg font-bold text-gray-800 mb-2 truncate" title="{{ $booking->house->title }}">
                                                    <a href="{{ route('house.details', $booking->house->id) }}" class="hover:underline">
                                                        {{ Str::limit($booking->house->title, 35) ?? 'Property Title N/A' }}
                                                    </a>
                                                </h3>
                                            @else
                                                <h3 class="text-lg font-bold text-red-600 mb-2">Property Unavailable</h3>
                                            @endif
                                            <p class="text-sm text-gray-500 mb-3">
                                                From: {{ $booking->tenant->full_name ?? ($booking->tenant->user_name ?? 'Tenant N/A') }}
                                            </p>
                                            
                                            <div class="space-y-2 mb-4">
                                                <div class="flex items-center text-sm text-gray-600">
                                                    <i class="fas fa-calendar w-4 mr-2" style="color: #1b61c2;"></i>
                                                    <span>{{ $booking->created_at->format('M d, Y') }}</span>
                                                </div>
                                                <div class="flex items-center text-sm text-gray-600">
                                                    <i class="fas fa-clock w-4 mr-2" style="color: #1b61c2;"></i>
                                                    <span>{{ $booking->month_duration }} {{ Str::plural('month', $booking->month_duration) }}</span>
                                                </div>
                                                <div class="flex items-center">
                                                    <i class="fas fa-info-circle w-4 mr-2" style="color: #1b61c2;"></i>
                                                    <span class="px-2 py-1 rounded-full text-xs font-semibold border
                                                        {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800 border-yellow-200' : '' }}
                                                        {{ $booking->status === 'accepted' ? 'bg-green-100 text-green-800 border-green-200' : '' }}
                                                        {{ $booking->status === 'rejected' ? 'bg-red-100 text-red-800 border-red-200' : '' }}
                                                        {{ $booking->status === 'cancelled' ? 'bg-gray-100 text-gray-700 border-gray-200' : '' }}
                                                        {{ !in_array($booking->status, ['pending', 'accepted', 'rejected', 'cancelled']) ? 'bg-blue-100 text-blue-800 border-blue-200' : '' }}">
                                                        {{ ucfirst($booking->status ?? 'N/A') }}
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="border-t border-gray-100 mt-4 pt-4">
                                                <a href="{{ route('bookings.show', $booking->id) }}" 
                                                   class="w-full text-center py-2 px-4 rounded-lg font-semibold text-sm transition-all duration-300 flex items-center justify-center gap-2" 
                                                   style="color: #1b61c2;" onmouseover="this.style.backgroundColor='rgba(27, 97, 194, 0.1)'" 
                                                   onmouseout="this.style.backgroundColor='transparent'">
                                                    <i class="fas fa-eye"></i>View Details
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                @if ($hasMoreReceivedBookings)
                                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border-2 border-dashed border-gray-300 card-hover flex flex-col items-center justify-center p-6 min-h-[280px]">
                                        <a href="{{ route('my.bookings') }}" class="text-center">
                                            <div class="w-12 h-12 mx-auto mb-3 rounded-full flex items-center justify-center" style="background-color: rgba(27, 97, 194, 0.1);">
                                                <i class="fas fa-arrow-right text-xl" style="color: #1b61c2;"></i>
                                            </div>
                                            <p class="font-semibold mb-1" style="color: #1b61c2;">View All</p>
                                            <p class="text-sm text-gray-500">Received Bookings</p>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="bg-white rounded-lg shadow-md p-8 border border-gray-200 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                                <p class="mt-4 text-lg font-medium text-gray-700">No Received Bookings Yet</p>
                                <p class="text-sm text-gray-500 mt-1">When a tenant sends a booking request for one of your properties, it will appear here.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Properties Tab -->
                <div id="content-properties" class="tab-panel hidden">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">My Properties</h2>
                            <a href="{{ route('Show.house.add') }}" 
                               class="text-white px-4 py-2 rounded-lg font-semibold transition-all duration-300 flex items-center gap-2" 
                               style="background: linear-gradient(135deg, #1b61c2, #3b82f6);">
                                <i class="fas fa-plus"></i>Add New Property
                            </a>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                            @forelse ($houses as $house)
                                <div class="bg-white rounded-xl shadow-sm border border-gray-100 card-hover overflow-hidden">
                                    @php
                                        $imageUrl = $house->pictures->first()?->image_url
                                            ? asset($house->pictures->first()->image_url)
                                            : 'https://images.pexels.com/photos/731082/pexels-photo-731082.jpeg';
                                    @endphp
                                    <img src="{{ $imageUrl }}" alt="{{ $house->title }}" class="w-full h-48 object-cover">
                                    <div class="p-5">
                                        <h3 class="text-lg font-bold text-gray-800 mb-2 truncate" title="{{ $house->title }}">
                                            {{ Str::limit($house->title, 45) }}
                                        </h3>
                                        <p class="text-sm text-gray-500 mb-3 flex items-center">
                                            <i class="fas fa-map-marker-alt mr-2" style="color: #1b61c2;"></i>
                                            {{ Str::limit($house->city, 70) }}
                                        </p>
                                        
                                        <div class="space-y-2 mb-4">
                                            <div class="flex items-center text-sm text-gray-600">
                                                <i class="fas fa-door-open w-4 mr-2" style="color: #1b61c2;"></i>
                                                <span>{{ $house->number_of_rooms }} {{ Str::plural('Room', $house->number_of_rooms) }}</span>
                                            </div>
                                            <div class="flex items-center text-sm text-gray-600">
                                                <i class="fas fa-bath w-4 mr-2" style="color: #1b61c2;"></i>
                                                <span>{{ $house->number_of_bathrooms }} {{ Str::plural('Bathroom', $house->number_of_bathrooms) }}</span>
                                            </div>
                                        </div>

                                        <p class="text-xl font-bold mb-4" style="color: #1b61c2;">${{ number_format($house->rent_amount, 2) }}/month</p>

                                        <div class="flex gap-2 border-t border-gray-100 pt-4">
                                            <a href="{{ route('Myhouse.edit', $house) }}" 
                                               class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-2 px-3 rounded-lg text-xs font-semibold transition-all text-center">
                                                <i class="fas fa-edit"></i>
                                                <span class="ml-1 hidden sm:inline">Edit</span>
                                            </a>
                                            <form action="{{ route('Myhouse.delete', $house) }}" method="POST" class="flex-1">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        onclick="return confirm('Are you sure you want to delete this property? This action cannot be undone.');"
                                                        class="w-full bg-red-500 hover:bg-red-600 text-white py-2 px-3 rounded-lg text-xs font-semibold transition-all">
                                                    <i class="fas fa-trash"></i>
                                                    <span class="ml-1 hidden sm:inline"></span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                            @endforelse

                            @if ($hasMoreProperties)
                                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border-2 border-dashed border-gray-300 card-hover flex flex-col items-center justify-center p-6 min-h-[400px]">
                                    <a href="{{ route('my.houses') }}" class="text-center">
                                        <div class="w-12 h-12 mx-auto mb-3 rounded-full flex items-center justify-center" style="background-color: rgba(27, 97, 194, 0.1);">
                                            <i class="fas fa-arrow-right text-xl" style="color: #1b61c2;"></i>
                                        </div>
                                        <p class="font-semibold mb-1" style="color: #1b61c2;">View All</p>
                                        <p class="text-sm text-gray-500">My Properties</p>
                                    </a>
                                </div>
                            @endif

                            @if ($houses->isEmpty() && !$hasMoreProperties)
                                <div class="col-span-1 sm:col-span-2 md:col-span-3 lg:col-span-4 xl:col-span-5 bg-white rounded-lg shadow-md p-8 border border-gray-200 text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 3.545A2.25 2.25 0 0115 5.795h4.5a2.25 2.25 0 012.25 2.25v9A2.25 2.25 0 0121.75 19.5h-4.5M8.25 21H3.75A2.25 2.25 0 011.5 18.75V5.795A2.25 2.25 0 013.75 3.545M8.25 21h4.5M16.5 3.545M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="mt-4 text-lg font-medium text-gray-700">No Properties Found</p>
                                    <p class="text-sm text-gray-500 mt-1">You haven't listed any properties yet. Add your first property to see it here.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>
        :root {
            --primary-color: #1b61c2;
            --primary-light: #3b82f6;
            --primary-dark: #1e40af;
        }
        
        .tab-active {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(27, 97, 194, 0.3);
        }
        
        .tab-active .count-badge {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .tab-inactive {
            background: rgba(27, 97, 194, 0.1);
            color: var(--primary-color);
            border: 1px solid rgba(27, 97, 194, 0.2);
        }
        
        .tab-inactive .count-badge {
            background: white;
            color: var(--primary-color);
            border: 1px solid rgba(27, 97, 194, 0.2);
        }
        
        .tab-inactive:hover {
            background: rgba(27, 97, 194, 0.15);
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(27, 97, 194, 0.2);
        }
        
        .tab-inactive:hover .count-badge {
            background: rgba(255, 255, 255, 0.9);
            border-color: rgba(27, 97, 194, 0.3);
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }
    </style>

    <script>
        function switchTab(tabName) {
            // Hide all tab panels
            const panels = document.querySelectorAll('.tab-panel');
            panels.forEach(panel => panel.classList.add('hidden'));

            // Show selected tab panel
            const targetPanel = document.getElementById(`content-${tabName}`);
            if (targetPanel) {
                targetPanel.classList.remove('hidden');
            }

            // Update tab button styles
            const buttons = document.querySelectorAll('.tab-btn');
            buttons.forEach(button => {
                button.classList.remove('tab-active');
                button.classList.add('tab-inactive');
            });

            // Activate selected tab button
            const activeButton = document.getElementById(`tab-${tabName}`);
            if (activeButton) {
                activeButton.classList.remove('tab-inactive');
                activeButton.classList.add('tab-active');
            }
        }

        // Initialize first available tab as active
        document.addEventListener('DOMContentLoaded', function() {
            @if (auth()->user()->role === 'tenant' || auth()->user()->role === 'both')
                switchTab('sent-bookings');
            @elseif (auth()->user()->role === 'landlord')
                switchTab('received-bookings');
            @endif
        });
    </script>
</x-layout>