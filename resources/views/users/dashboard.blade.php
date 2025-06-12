<x-layout>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">@lang('words.dashboard_greeting') {{ auth()->user()->user_name }}!</h1>
            <p class="text-gray-600">@lang('words.dashboard_welcome_back')</p>
        </div>

        <!-- Tab Navigation -->
        <div class="mb-8">
            <div class="flex flex-wrap gap-2 bg-white p-2 rounded-xl shadow-sm border border-gray-100">
                @if (auth()->user()->role === 'tenant' || auth()->user()->role === 'both')
                    <button onclick="switchTab('sent-bookings')" id="tab-sent-bookings"
                        class="tab-btn px-6 py-3 rounded-lg font-semibold transition-all duration-300 flex items-center gap-2">
                        <i class="fas fa-paper-plane text-sm"></i>
                        <span>@lang('words.sent_bookings_title')</span>
                        <span
                            class="count-badge px-2 py-1 rounded-full text-xs font-bold">{{ $sentBookings->count() }}</span>
                    </button>
                    <button onclick="switchTab('rented-houses')" id="tab-rented-houses"
                        class="tab-btn px-6 py-3 rounded-lg font-semibold transition-all duration-300 flex items-center gap-2">
                        <i class="fas fa-house-user text-sm"></i>
                        <span>@lang('words.dashboard_tab_rented_houses')</span>
                        <span
                            class="count-badge px-2 py-1 rounded-full text-xs font-bold">{{ $rentedHouses->count() }}</span>
                    </button>
                @endif
                @if (auth()->user()->role === 'landlord' || auth()->user()->role === 'both')
                    <button onclick="switchTab('received-bookings')" id="tab-received-bookings"
                        class="tab-btn px-6 py-3 rounded-lg font-semibold transition-all duration-300 flex items-center gap-2">
                        <i class="fas fa-inbox text-sm"></i>
                        <span>@lang('words.dashboard_tab_received_bookings')</span>
                        <span
                            class="count-badge px-2 py-1 rounded-full text-xs font-bold">{{ $receivedBookings->count() }}</span>
                    </button>
                    <button onclick="switchTab('properties')" id="tab-properties"
                        class="tab-btn px-6 py-3 rounded-lg font-semibold transition-all duration-300 flex items-center gap-2">
                        <i class="fas fa-home text-sm"></i>
                        <span>@lang('words.dashboard_tab_my_properties')</span>
                        <span class="count-badge px-2 py-1 rounded-full text-xs font-bold">{{ $houses->count() }}</span>
                    </button>
                @endif
                <button onclick="switchTab('maintenance')" id="tab-maintenance"
                    class="tab-btn px-6 py-3 rounded-lg font-semibold transition-all duration-300 flex items-center gap-2">
                    <i class="fas fa-wrench text-sm"></i>
                    <span>@lang('words.dashboard_tab_maintenance')</span>
                    <span
                        class="count-badge px-2 py-1 rounded-full text-xs font-bold">{{ $totalMaintenanceItemsForBadge ?? 0 }}</span>
                </button>
                @if (auth()->user()->role === 'tenant' || auth()->user()->role === 'both')
                    <button onclick="switchTab('my-reviews')" id="tab-my-reviews"
                        class="tab-btn px-6 py-3 rounded-lg font-semibold transition-all duration-300 flex items-center gap-2">
                        <i class="fas fa-star text-sm"></i>
                        <span>@lang('words.dashboard_tab_my_reviews')</span>
                        {{-- You might want to add a count of pending reviews or total reviews here later --}}
                        {{-- <span class="count-badge px-2 py-1 rounded-full text-xs font-bold">0</span> --}}
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
                            <h2 class="text-2xl font-bold text-gray-800">@lang('words.dashboard_latest_sent_bookings_title')</h2>
                        </div>

                        @if ($sentBookings->isNotEmpty() || $hasMoreSentBookings)
                            <div
                                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6">
                                @foreach ($sentBookings as $booking)
                                    <div
                                        class="bg-white rounded-xl shadow-sm border border-gray-100 card-hover overflow-hidden">
                                        <div class="p-5">
                                            @if ($booking->house)
                                                <h3 class="text-lg font-bold text-gray-800 mb-2 truncate"
                                                    title="{{ $booking->house->title }}">
                                                    <a href="{{ route('house.details', $booking->house->id) }}"
                                                        class="hover:underline">
                                                        {{ Str::limit($booking->house->title, 35) ?? __('words.booking_property_title_na') }}
                                                    </a>
                                                </h3>
                                                <p class="text-sm text-gray-500 mb-3">
                                                    @lang('words.dashboard_label_to_landlord')
                                                    {{ $booking->house->landlord->full_name ?? ($booking->house->landlord->user_name ?? __('words.booking_landlord_na')) }}
                                                </p>
                                            @else
                                                <h3 class="text-lg font-bold text-red-600 mb-2">@lang('words.dashboard_property_unavailable')
                                                </h3>
                                                <p class="text-sm text-gray-500 mb-3">@lang('words.dashboard_label_to_landlord') @lang('words.booking_landlord_na')</p>
                                            @endif

                                            <div class="space-y-2 mb-4">
                                                <div class="flex items-center text-sm text-gray-600">
                                                    <i class="fas fa-calendar w-4 mr-2" style="color: #1b61c2;"></i>
                                                    <span>{{ $booking->created_at->format('M d, Y') }}</span>
                                                </div>
                                                <div class="flex items-center text-sm text-gray-600">
                                                    <i class="fas fa-clock w-4 mr-2" style="color: #1b61c2;"></i>
                                                    <span>{{ $booking->month_duration }}
                                                        {{ $booking->month_duration == 1 ? __('words.duration_month_singular') : __('words.duration_month_plural') }}</span>
                                                </div>
                                                <div class="flex items-center">
                                                    <i class="fas fa-info-circle w-4 mr-2" style="color: #1b61c2;"></i>
                                                    <span
                                                        class="px-2 py-1 rounded-full text-xs font-semibold border
                                                        {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800 border-yellow-200' : '' }}
                                                        {{ $booking->status === 'accepted' ? 'bg-green-100 text-green-800 border-green-200' : '' }}
                                                        {{ $booking->status === 'rejected' ? 'bg-red-100 text-red-800 border-red-200' : '' }}
                                                        {{ $booking->status === 'cancelled' ? 'bg-gray-100 text-gray-700 border-gray-200' : '' }}
                                                        {{ !in_array($booking->status, ['pending', 'accepted', 'rejected', 'cancelled']) ? 'bg-blue-100 text-blue-800 border-blue-200' : '' }}">
                                                        {{ $booking->status === 'pending' ? __('words.booking_status_pending') : '' }}
                                                        {{ $booking->status === 'accepted' ? __('words.booking_status_accepted') : '' }}
                                                        {{ $booking->status === 'rejected' ? __('words.booking_status_rejected') : '' }}
                                                        {{ $booking->status === 'cancelled' ? __('words.booking_status_cancelled') : '' }}
                                                        {{ !in_array($booking->status, ['pending', 'accepted', 'rejected', 'cancelled']) ? ucfirst($booking->status ?? 'N/A') : '' }}
                                                    </span>
                                                </div>
                                            </div>

                                            <a href="{{ route('bookings.details.show', $booking->id) }}"
                                                class="w-full text-center py-2 px-4 rounded-lg font-semibold text-sm transition-all duration-300 border-t border-gray-100 mt-4 pt-4 flex items-center justify-center gap-2"
                                                style="color: #1b61c2;"
                                                onmouseover="this.style.backgroundColor='rgba(27, 97, 194, 0.1)'"
                                                onmouseout="this.style.backgroundColor='transparent'">
                                                <i class="fas fa-eye"></i>@lang('words.booking_view_details_button')
                                            </a>
                                        </div>
                                    </div>
                                @endforeach

                                @if ($hasMoreSentBookings)
                                    <div
                                        class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border-2 border-dashed border-gray-300 card-hover flex flex-col items-center justify-center p-6 min-h-[280px]">
                                        <a href="{{ route('bookings.sent') }}" class="text-center">
                                            <div class="w-12 h-12 mx-auto mb-3 rounded-full flex items-center justify-center"
                                                style="background-color: rgba(27, 97, 194, 0.1);">
                                                <i class="fas fa-arrow-right text-xl" style="color: #1b61c2;"></i>
                                            </div>
                                            <p class="font-semibold mb-1" style="color: #1b61c2;">@lang('words.dashboard_view_all_link')</p>
                                            <p class="text-sm text-gray-500">@lang('words.sent_bookings_title')</p>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="bg-white rounded-lg shadow-md p-8 border border-gray-200 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 8v4m0 4h.01M21.75 12a9.75 9.75 0 01-9.75 9.75A9.75 9.75 0 012.25 12a9.75 9.75 0 019.75-9.75A9.75 9.75 0 0121.75 12zM12 18.75a.75.75 0 000-1.5.75.75 0 000 1.5z" />
                                </svg>
                                <p class="mt-4 text-lg font-medium text-gray-700">@lang('words.dashboard_no_sent_bookings_title')</p>
                                <p class="text-sm text-gray-500 mt-1">@lang('words.dashboard_no_sent_bookings_text')</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @if (auth()->user()->role === 'tenant' || auth()->user()->role === 'both')
                <!-- My Rented Houses Tab -->
                <div id="content-rented-houses" class="tab-panel hidden">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">@lang('words.dashboard_tab_rented_houses')</h2>
                            {{-- Optional: Add a "View All Rented Houses" button if you implement a separate page --}}
                        </div>

                        @if ($rentedHouses->isNotEmpty())
                            <div
                                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                                @foreach ($rentedHouses as $house)
                                    <div
                                        class="bg-white rounded-xl shadow-sm border border-gray-100 card-hover overflow-hidden">
                                        @php
                                            $imageUrl = $house->pictures->first()?->image_url
                                                ? asset($house->pictures->first()->image_url)
                                                : 'https://images.pexels.com/photos/731082/pexels-photo-731082.jpeg';
                                        @endphp
                                        <a href="{{ route('house.details', $house->id) }}">
                                            <img src="{{ $imageUrl }}" alt="{{ $house->title }}"
                                                class="w-full h-48 object-cover">
                                        </a>
                                        <div class="p-5">
                                            <h3 class="text-lg font-bold text-gray-800 mb-2 truncate"
                                                title="{{ $house->title }}">
                                                <a href="{{ route('house.details', $house->id) }}"
                                                    class="hover:underline">
                                                    {{ Str::limit($house->title, 45) }}
                                                </a>
                                            </h3>
                                            <p class="text-sm text-gray-500 mb-3 flex items-center">
                                                <i class="fas fa-map-marker-alt mr-2" style="color: #1b61c2;"></i>
                                                {{ Str::limit($house->full_address, 70) }}
                                            </p>

                                            @if ($house->landlord)
                                                <p class="text-sm text-gray-500 mb-3 flex items-center">
                                                    <i class="fas fa-user-tie mr-2" style="color: #1b61c2;"></i>
                                                    @lang('words.dashboard_label_landlord_colon')
                                                    {{ $house->landlord->full_name ?? $house->landlord->user_name }}
                                                </p>
                                            @endif

                                            <p class="text-lg font-semibold mb-4" style="color: #1b61c2;">
                                                ${{ number_format($house->rent_amount, 2) }}@lang('words.property_card_per_month')
                                            </p>

                                            <div class="border-t border-gray-100 mt-4 pt-4">
                                                <a href="{{ route('house.details', $house->id) }}"
                                                    class="w-full text-center py-2 px-4 rounded-lg font-semibold text-sm transition-all duration-300 flex items-center justify-center gap-2"
                                                    style="color: #1b61c2;"
                                                    onmouseover="this.style.backgroundColor='rgba(27, 97, 194, 0.1)'"
                                                    onmouseout="this.style.backgroundColor='transparent'">
                                                    <i class="fas fa-eye"></i>@lang('words.dashboard_view_property_details_button')
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                {{-- Add "View All" card if $hasMoreRentedHouses logic is implemented --}}
                            </div>
                        @else
                            <div class="bg-white rounded-lg shadow-md p-8 border border-gray-200 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5M3.75 18.75V9.75m0 9V6.75m0 0H2.25m1.5 0H5.25m0 0H3.75m0 0h7.5M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 6.75h.008v.008H12v-.008z" />
                                </svg>
                                <p class="mt-4 text-lg font-medium text-gray-700">@lang('words.dashboard_no_rented_houses_title')</p>
                                <p class="text-sm text-gray-500 mt-1">@lang('words.dashboard_no_rented_houses_text')</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Maintenance Tab -->
            <div id="content-maintenance" class="tab-panel hidden">

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

                    {{-- tenant Maintenance section --}}
                    @if (auth()->user()->role === 'tenant' || auth()->user()->role === 'both')
                        <div class="flex justify-between items-center mb-6">
                             <h2 class="text-2xl font-bold text-gray-800">@lang('words.dashboard_sent_maintenance_requests_title')</h2>
                            <button type="button" id="openNewMaintenanceModalBtn"
                                class="text-white px-4 py-2 rounded-lg font-semibold transition-all duration-300 flex items-center gap-2"
                                style="background: linear-gradient(135deg, #1b61c2, #3b82f6);">
                                <i class="fas fa-plus"></i>@lang('words.dashboard_new_request_button')
                            </button>
                        </div>

                        @if ($maintenanceRequests->isNotEmpty())
                            <div
                                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                                @foreach ($maintenanceRequests as $request)
                                    <div
                                        class="bg-white rounded-xl shadow-sm border border-gray-100 card-hover overflow-hidden">
                                        <div class="p-5">
                                            <div class="mb-3">
                                                <h3 class="text-lg font-bold text-gray-800 truncate"
                                                    title="{{ $request->area_of_house }} - {{ Str::limit($request->description, 70) }}">
                                                    {{ Str::limit($request->area_of_house ?? __('words.dashboard_maintenance_request_default_title'), 25) }}
                                                </h3>
                                            </div>

                                            @if ($request->house)
                                                <p class="text-sm text-gray-500 mb-3 flex items-center">
                                                    <i class="fas fa-home mr-2" style="color: #1b61c2;"></i>
                                                    {{ Str::limit($request->house->title ?? __('words.booking_property_title_na'), 30) }}
                                                </p>
                                            @endif

                                            <div class="space-y-2 mb-4">
                                                <div class="flex items-center text-sm text-gray-600">
                                                    <i class="fas fa-calendar w-4 mr-2" style="color: #1b61c2;"></i>
                                                    <span>{{ $request->created_at->format('M d, Y') }}</span>
                                                </div>
                                                <div class="flex items-center text-sm text-gray-600">
                                                    <i class="fas fa-user w-4 mr-2" style="color: #1b61c2;"></i>
                                                    <span>{{ $request->tenant->full_name ?? ($request->tenant->user_name ?? __('words.dashboard_tenant_na')) }}</span>
                                                </div>
                                                <div class="flex items-center">
                                                    <i class="fas fa-info-circle w-4 mr-2"
                                                        style="color: #1b61c2;"></i>
                                                    <span
                                                        class="px-2 py-1 rounded-full text-xs font-semibold border
                                                        {{ $request->status === 'pending' ? 'bg-yellow-100 text-yellow-800 border-yellow-200' : '' }}
                                                        {{ $request->status === 'in_progress' ? 'bg-blue-100 text-blue-800 border-blue-200' : '' }}
                                                        {{ $request->status === 'completed' ? 'bg-green-100 text-green-800 border-green-200' : '' }}
                                                        {{ $request->status === 'cancelled' ? 'bg-gray-100 text-gray-700 border-gray-200' : '' }}
                                                        {{ $request->status === 'awaiting_parts' ? 'bg-orange-100 text-orange-800 border-orange-200' : '' }}
                                                        {{ $request->status === 'needs_tenant_input' ? 'bg-purple-100 text-purple-800 border-purple-200' : '' }}
                                                        {{ $request->status === 'accepted' ? 'bg-green-100 text-green-800 border-green-200' : '' }}
                                                        {{ $request->status === 'rejected' ? 'bg-red-100 text-red-800 border-red-200' : '' }}
                                                        {{ !in_array($request->status, ['pending', 'in_progress', 'completed', 'cancelled', 'awaiting_parts', 'needs_tenant_input', 'accepted', 'rejected']) ? 'bg-blue-100 text-blue-800 border-blue-200' : '' }}">
                                                        {{ $request->status === 'pending' ? __('words.booking_status_pending') : '' }}
                                                        {{ $request->status === 'in_progress' ? __('words.maintenance_status_in_progress') : '' }}
                                                        {{ $request->status === 'completed' ? __('words.maintenance_status_completed') : '' }}
                                                        {{ $request->status === 'cancelled' ? __('words.booking_status_cancelled') : '' }}
                                                        {{ $request->status === 'awaiting_parts' ? __('words.maintenance_status_awaiting_parts') : '' }}
                                                        {{ $request->status === 'needs_tenant_input' ? __('words.maintenance_status_needs_tenant_input') : '' }}
                                                        {{ $request->status === 'accepted' ? __('words.booking_status_accepted') : '' }}
                                                        {{ $request->status === 'rejected' ? __('words.booking_status_rejected') : '' }}
                                                        {{ !in_array($request->status, ['pending', 'in_progress', 'completed', 'cancelled', 'awaiting_parts', 'needs_tenant_input', 'accepted', 'rejected']) ? ucfirst(str_replace('_', ' ', $request->status ?? 'Pending')) : '' }}
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="border-t border-gray-100 mt-4 pt-4">
                                                <button type="button"
                                                    class="open-update-sent-maintenance-modal-btn w-full text-center py-2 px-4 rounded-lg font-semibold text-sm transition-all duration-300 flex items-center justify-center gap-2"
                                                    style="color: #1b61c2;"
                                                    onmouseover="this.style.backgroundColor='rgba(27, 97, 194, 0.1)'"
                                                    onmouseout="this.style.backgroundColor='transparent'"
                                                    data-request-id="{{ $request->id }}"
                                                    data-house-title="{{ $request->house->title ?? __('words.booking_property_title_na') }}"
                                                    data-area-of-house="{{ $request->area_of_house }}"
                                                    data-description="{{ $request->description }}"
                                                    data-current-picture-url="{{ $request->picture ? asset('storage/' . $request->picture) : '' }}"
                                                    data-status="{{ $request->status }}"
                                                    data-landlord-response="{{ $request->landlord_response ?? '' }}"
                                                    data-created-at="{{ $request->created_at->format('M d, Y H:i') }}"
                                                    data-update-action-template="{{ route('maintenance.tenant.update', ['maintenance' => 'REQUEST_ID_PLACEHOLDER']) }}"
                                                    data-cancel-action-template="{{ route('maintenance.tenant.cancel', ['maintenance' => 'REQUEST_ID_PLACEHOLDER']) }}"
                                                    >
                                                    <i class="fas fa-edit"></i> @lang('words.dashboard_maintenance_view_update_button')
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                @if ($hasMoreMaintenance)
                                    <div
                                        class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border-2 border-dashed border-gray-300 card-hover flex flex-col items-center justify-center p-6 min-h-[280px] sm:min-h-[400px]">
                                        <div class="w-12 h-12 mx-auto mb-3 rounded-full flex items-center justify-center"
                                            style="background-color: rgba(27, 97, 194, 0.1);">
                                            <i class="fas fa-ellipsis-h text-xl" style="color: #1b61c2;"></i>
                                        </div>
                                        <p class="font-semibold mb-1" style="color: #1b61c2;">@lang('words.dashboard_more_requests_link')</p>
                                        <p class="text-sm text-gray-500">@lang('words.dashboard_full_list_not_implemented')</p>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="bg-white rounded-lg shadow-md p-8 border border-gray-200 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z" />
                                </svg>
                                <p class="mt-4 text-lg font-medium text-gray-700">@lang('words.dashboard_no_sent_maintenance_title')</p>
                                <p class="text-sm text-gray-500 mt-1">@lang('words.dashboard_no_sent_maintenance_text')</p>
                            </div>
                        @endif
                    @endif
                    {{-- end of tenant maintenance section --}}

                    {{-- landlord maintenance section --}}
                    @if (auth()->user()->role === 'landlord' || auth()->user()->role === 'both')
                        <h2 class="text-2xl font-bold text-gray-800 mt-10 mb-6">@lang('words.dashboard_received_maintenance_requests_title')</h2>

                        @if ($receivedMaintenanceRequests->isNotEmpty())
                            <div
                                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                                @foreach ($receivedMaintenanceRequests as $receivedRequest)
                                    <button type="button"
                                        data-request-id="{{ $receivedRequest->id }}"
                                        data-tenant-name="{{ $receivedRequest->tenant->full_name ?? $receivedRequest->tenant->user_name ?? __('words.dashboard_tenant_na') }}"
                                        data-picture-url="{{ $receivedRequest->picture ? asset('storage/' . $receivedRequest->picture) : '' }}"
                                        data-area-of-house="{{ $receivedRequest->area_of_house }}"
                                        data-description="{{ $receivedRequest->description }}"
                                        data-refund-amount="{{ $receivedRequest->refund_amount ?? '0.00' }}"
                                        data-current-status="{{ $receivedRequest->status }}"
                                        data-landlord-response="{{ $receivedRequest->landlord_response ?? '' }}"
                                        data-accept-url="{{ route('maintenance.initiate_payment', $receivedRequest->id) }}"
                                        data-reject-url="{{ route('maintenance.reject', $receivedRequest->id) }}"
                                        class="bg-white rounded-xl shadow-sm border border-gray-100 card-hover overflow-hidden text-left received-maintenance-card">
                                        <div class="p-5">
                                            <div class="mb-3">
                                                <h3 class="text-lg font-bold text-gray-800 truncate"
                                                    title="{{ $receivedRequest->area_of_house }} - {{ Str::limit($receivedRequest->description, 70) }}">
                                                    {{ Str::limit($receivedRequest->area_of_house ?? __('words.dashboard_maintenance_request_default_title'), 25) }}
                                                </h3>
                                            </div>

                                            @if ($receivedRequest->house)
                                                <p class="text-sm text-gray-500 mb-3 flex items-center">
                                                    <i class="fas fa-home mr-2" style="color: #1b61c2;"></i>
                                                    @lang('words.dashboard_label_for_property') {{ Str::limit($receivedRequest->house->title ?? __('words.booking_property_title_na'), 30) }}
                                                </p>
                                            @endif

                                            <div class="space-y-2 mb-4">
                                                <div class="flex items-center text-sm text-gray-600">
                                                    <i class="fas fa-calendar w-4 mr-2" style="color: #1b61c2;"></i>
                                                    <span>{{ $receivedRequest->created_at->format('M d, Y') }}</span>
                                                </div>
                                                <div class="flex items-center text-sm text-gray-600">
                                                    <i class="fas fa-user w-4 mr-2" style="color: #1b61c2;"></i>
                                                    <span>@lang('words.dashboard_label_by_tenant') {{ $receivedRequest->tenant->full_name ?? ($receivedRequest->tenant->user_name ?? __('words.dashboard_tenant_na')) }}</span>
                                                </div>
                                                <div class="flex items-center">
                                                    <i class="fas fa-info-circle w-4 mr-2"
                                                        style="color: #1b61c2;"></i>
                                                    <span
                                                        class="px-2 py-1 rounded-full text-xs font-semibold border
                                                        {{ $receivedRequest->status === 'pending' ? 'bg-yellow-100 text-yellow-800 border-yellow-200' : '' }}
                                                        {{ $receivedRequest->status === 'in_progress' ? 'bg-blue-100 text-blue-800 border-blue-200' : '' }}
                                                        {{ $receivedRequest->status === 'accepted' ? 'bg-green-100 text-green-800 border-green-200' : '' }} {{-- Added accepted status style --}}
                                                        {{ $receivedRequest->status === 'completed' ? 'bg-green-100 text-green-800 border-green-200' : '' }}
                                                        {{ $receivedRequest->status === 'rejected' ? 'bg-red-100 text-red-800 border-red-200' : '' }} {{-- Added rejected status style --}}
                                                        {{ $receivedRequest->status === 'cancelled' ? 'bg-gray-100 text-gray-700 border-gray-200' : '' }}
                                                        {{ $receivedRequest->status === 'awaiting_parts' ? 'bg-orange-100 text-orange-800 border-orange-200' : '' }}
                                                        {{ $receivedRequest->status === 'needs_tenant_input' ? 'bg-purple-100 text-purple-800 border-purple-200' : '' }}
                                                        {{ !in_array($receivedRequest->status, ['pending', 'in_progress', 'completed', 'cancelled', 'awaiting_parts', 'needs_tenant_input', 'accepted', 'rejected']) ? 'bg-blue-100 text-blue-800 border-blue-200' : '' }}">
                                                        {{ $receivedRequest->status === 'pending' ? __('words.booking_status_pending') : '' }}
                                                        {{ $receivedRequest->status === 'in_progress' ? __('words.maintenance_status_in_progress') : '' }}
                                                        {{ $receivedRequest->status === 'completed' ? __('words.maintenance_status_completed') : '' }}
                                                        {{ $receivedRequest->status === 'cancelled' ? __('words.booking_status_cancelled') : '' }}
                                                        {{ $receivedRequest->status === 'awaiting_parts' ? __('words.maintenance_status_awaiting_parts') : '' }}
                                                        {{ $receivedRequest->status === 'needs_tenant_input' ? __('words.maintenance_status_needs_tenant_input') : '' }}
                                                        {{ $receivedRequest->status === 'accepted' ? __('words.booking_status_accepted') : '' }}
                                                        {{ $receivedRequest->status === 'rejected' ? __('words.booking_status_rejected') : '' }}
                                                        {{ !in_array($receivedRequest->status, ['pending', 'in_progress', 'completed', 'cancelled', 'awaiting_parts', 'needs_tenant_input', 'accepted', 'rejected']) ? ucfirst(str_replace('_', ' ', $receivedRequest->status ?? 'Pending')) : '' }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="border-t border-gray-100 mt-4 pt-4 text-center">
                                                <span class="text-sm font-semibold" style="color: #1b61c2;">@lang('words.dashboard_maintenance_view_reply_button')</span>
                                            </div>
                                        </div>
                                    </button>
                                @endforeach

                                @if ($hasMoreReceivedMaintenance)
                                    <div
                                        class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border-2 border-dashed border-gray-300 card-hover flex flex-col items-center justify-center p-6 min-h-[280px]">
                                        <div class="w-12 h-12 mx-auto mb-3 rounded-full flex items-center justify-center"
                                            style="background-color: rgba(27, 97, 194, 0.1);">
                                            <i class="fas fa-ellipsis-h text-xl" style="color: #1b61c2;"></i>
                                        </div>
                                        <p class="font-semibold mb-1" style="color: #1b61c2;">@lang('words.dashboard_more_requests_link')</p>
                                        <p class="text-sm text-gray-500">@lang('words.dashboard_full_list_not_implemented')</p>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="bg-white rounded-lg shadow-md p-8 border border-gray-200 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 13.5V5.25A2.25 2.25 0 015.25 3h13.5A2.25 2.25 0 0121 5.25v8.25m-18 0A2.25 2.25 0 005.25 15h9.75A2.25 2.25 0 0017.25 13.5m-14.25 0H21m-3.75 3.75h.008v.008h-.008v-.008zm0 0H12m3.75 0a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5z" />
                                </svg>
                                <p class="mt-4 text-lg font-medium text-gray-700">@lang('words.dashboard_no_received_maintenance_title')</p>
                                <p class="text-sm text-gray-500 mt-1">@lang('words.dashboard_no_received_maintenance_text')</p>
                            </div>
                        @endif
                    @endif
                    {{-- end of landlord maintenance section --}}

                </div>

            </div>

            @if (auth()->user()->role === 'landlord' || auth()->user()->role === 'both')
                <!-- Received Bookings Tab -->
                <div id="content-received-bookings" class="tab-panel hidden">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">@lang('words.dashboard_latest_received_bookings_title')</h2>
                        </div>

                        @if ($receivedBookings->isNotEmpty() || $hasMoreReceivedBookings)
                            <div
                                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6">
                                @foreach ($receivedBookings as $booking)
                                    <div
                                        class="bg-white rounded-xl shadow-sm border border-gray-100 card-hover overflow-hidden">
                                        <div class="p-5">
                                            @if ($booking->house)
                                                <h3 class="text-lg font-bold text-gray-800 mb-2 truncate"
                                                    title="{{ $booking->house->title }}">
                                                    <a href="{{ route('house.details', $booking->house->id) }}"
                                                        class="hover:underline">
                                                        {{ Str::limit($booking->house->title, 35) ?? __('words.booking_property_title_na') }}
                                                    </a>
                                                </h3>
                                            @else
                                                <h3 class="text-lg font-bold text-red-600 mb-2">@lang('words.dashboard_property_unavailable')
                                                </h3>
                                            @endif
                                            <p class="text-sm text-gray-500 mb-3">
                                                @lang('words.booking_label_from')
                                                {{ $booking->tenant->full_name ?? ($booking->tenant->user_name ?? __('words.dashboard_tenant_na')) }}
                                            </p>

                                            <div class="space-y-2 mb-4">
                                                <div class="flex items-center text-sm text-gray-600">
                                                    <i class="fas fa-calendar w-4 mr-2" style="color: #1b61c2;"></i>
                                                    <span>{{ $booking->created_at->format('M d, Y') }}</span>
                                                </div>
                                                <div class="flex items-center text-sm text-gray-600">
                                                    <i class="fas fa-clock w-4 mr-2" style="color: #1b61c2;"></i>
                                                    <span>{{ $booking->month_duration }}
                                                        {{ $booking->month_duration == 1 ? __('words.duration_month_singular') : __('words.duration_month_plural') }}</span>
                                                </div>
                                                <div class="flex items-center">
                                                    <i class="fas fa-info-circle w-4 mr-2"
                                                        style="color: #1b61c2;"></i>
                                                    <span
                                                        class="px-2 py-1 rounded-full text-xs font-semibold border
                                                        {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800 border-yellow-200' : '' }}
                                                        {{ $booking->status === 'accepted' ? 'bg-green-100 text-green-800 border-green-200' : '' }}
                                                        {{ $booking->status === 'rejected' ? 'bg-red-100 text-red-800 border-red-200' : '' }}
                                                        {{ $booking->status === 'cancelled' ? 'bg-gray-100 text-gray-700 border-gray-200' : '' }}
                                                        {{ !in_array($booking->status, ['pending', 'accepted', 'rejected', 'cancelled']) ? 'bg-blue-100 text-blue-800 border-blue-200' : '' }}">
                                                        {{ $booking->status === 'pending' ? __('words.booking_status_pending') : '' }}
                                                        {{ $booking->status === 'accepted' ? __('words.booking_status_accepted') : '' }}
                                                        {{ $booking->status === 'rejected' ? __('words.booking_status_rejected') : '' }}
                                                        {{ $booking->status === 'cancelled' ? __('words.booking_status_cancelled') : '' }}
                                                        {{ !in_array($booking->status, ['pending', 'accepted', 'rejected', 'cancelled']) ? ucfirst($booking->status ?? 'N/A') : '' }}
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="border-t border-gray-100 mt-4 pt-4">
                                                <a href="{{ route('bookings.show', $booking->id) }}"
                                                    class="w-full text-center py-2 px-4 rounded-lg font-semibold text-sm transition-all duration-300 flex items-center justify-center gap-2"
                                                    style="color: #1b61c2;"
                                                    onmouseover="this.style.backgroundColor='rgba(27, 97, 194, 0.1)'"
                                                    onmouseout="this.style.backgroundColor='transparent'">
                                                    <i class="fas fa-eye"></i>@lang('words.booking_view_details_button')
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                @if ($hasMoreReceivedBookings)
                                    <div
                                        class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border-2 border-dashed border-gray-300 card-hover flex flex-col items-center justify-center p-6 min-h-[280px]">
                                        <a href="{{ route('my.bookings') }}" class="text-center">
                                        <div class="w-12 h-12 mx-auto mb-3 rounded-full flex items-center justify-center"
                                            style="background-color: rgba(27, 97, 194, 0.1);">
                                            <i class="fas fa-arrow-right text-xl" style="color: #1b61c2;"></i>
                                        </div>
                                        <p class="font-semibold mb-1" style="color: #1b61c2;">@lang('words.dashboard_view_all_link')</p>
                                        <p class="text-sm text-gray-500">@lang('words.dashboard_tab_received_bookings')</p>
                                    </a>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="bg-white rounded-lg shadow-md p-8 border border-gray-200 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                            <p class="mt-4 text-lg font-medium text-gray-700">@lang('words.dashboard_no_received_bookings_title')</p>
                            <p class="text-sm text-gray-500 mt-1">@lang('words.dashboard_no_received_bookings_text')</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Properties Tab -->
                <div id="content-properties" class="tab-panel hidden">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">@lang('words.dashboard_tab_my_properties')</h2>
                            <a href="{{ route('Show.house.add') }}"
                                class="text-white px-4 py-2 rounded-lg font-semibold transition-all duration-300 flex items-center gap-2"
                                style="background: linear-gradient(135deg, #1b61c2, #3b82f6);">
                                <i class="fas fa-plus"></i>@lang('words.Add House')
                            </a>
                        </div>

                        <div
                            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                            @forelse ($houses as $house)
                                <div
                                    class="bg-white rounded-xl shadow-sm border border-gray-100 card-hover overflow-hidden">
                                    @php
                                        $imageUrl = $house->pictures->first()?->image_url
                                            ? asset($house->pictures->first()->image_url)
                                            : 'https://images.pexels.com/photos/731082/pexels-photo-731082.jpeg';
                                    @endphp
                                    <img src="{{ $imageUrl }}" alt="{{ $house->title }}"
                                        class="w-full h-48 object-cover">
                                    <div class="p-5">
                                        <h3 class="text-lg font-bold text-gray-800 mb-2 truncate"
                                            title="{{ $house->title }}">
                                            {{ Str::limit($house->title, 45) }}
                                        </h3>
                                        <p class="text-sm text-gray-500 mb-3 flex items-center">
                                            <i class="fas fa-map-marker-alt mr-2" style="color: #1b61c2;"></i>
                                            {{ Str::limit($house->city, 70) }}
                                        </p>

                                        <div class="space-y-2 mb-4">
                                            <div class="flex items-center text-sm text-gray-600">
                                                <i class="fas fa-door-open w-4 mr-2" style="color: #1b61c2;"></i>
                                                <span>{{ $house->number_of_rooms }}
                                                    {{ $house->number_of_rooms == 1 ? __('words.property_card_room_singular') : __('words.property_card_room_plural') }}</span>
                                            </div>
                                            <div class="flex items-center text-sm text-gray-600">
                                                <i class="fas fa-bath w-4 mr-2" style="color: #1b61c2;"></i>
                                                <span>{{ $house->number_of_bathrooms }}
                                                    {{ $house->number_of_bathrooms == 1 ? __('words.dashboard_property_card_bathroom_singular') : __('words.dashboard_property_card_bathroom_plural') }}</span>
                                            </div>
                                        </div>

                                        <p class="text-xl font-bold mb-4" style="color: #1b61c2;">
                                            ${{ number_format($house->rent_amount, 2) }}@lang('words.property_card_per_month')</p>

                                        <div class="flex gap-2 border-t border-gray-100 pt-4">
                                            <a href="{{ route('Myhouse.edit', $house) }}"
                                                class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-2 px-3 rounded-lg text-xs font-semibold transition-all text-center">
                                                <i class="fas fa-edit"></i>
                                                <span class="ml-1 hidden sm:inline">@lang('words.dashboard_edit_button')</span>
                                            </a>
                                            <form action="{{ route('Myhouse.delete', $house) }}" method="POST"
                                                class="flex-1">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    onclick="return confirm('@lang('words.dashboard_confirm_delete_property')');"
                                                    class="w-full bg-red-500 hover:bg-red-600 text-white py-2 px-3 rounded-lg text-xs font-semibold transition-all">
                                                    <i class="fas fa-trash"></i>
                                                    <span class="ml-1 hidden sm:inline">@lang('words.booking_delete_button')</span> {{-- Reusing delete button text --}}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                            @endforelse

                            @if ($hasMoreProperties)
                                <div
                                    class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border-2 border-dashed border-gray-300 card-hover flex flex-col items-center justify-center p-6 min-h-[400px]">
                                    <a href="{{ route('my.houses') }}" class="text-center">
                                        <div class="w-12 h-12 mx-auto mb-3 rounded-full flex items-center justify-center"
                                            style="background-color: rgba(27, 97, 194, 0.1);">
                                            <i class="fas fa-arrow-right text-xl" style="color: #1b61c2;"></i>
                                        </div>
                                        <p class="font-semibold mb-1" style="color: #1b61c2;">@lang('words.dashboard_view_all_link')</p>
                                        <p class="text-sm text-gray-500">@lang('words.dashboard_tab_my_properties')</p>
                                    </a>
                                </div>
                            @endif

                            @if ($houses->isEmpty() && !$hasMoreProperties)
                                <div
                                    class="col-span-1 sm:col-span-2 md:col-span-3 lg:col-span-4 xl:grid-cols-5 bg-white rounded-lg shadow-md p-8 border border-gray-200 text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 3.545A2.25 2.25 0 0115 5.795h4.5a2.25 2.25 0 012.25 2.25v9A2.25 2.25 0 0121.75 19.5h-4.5M8.25 21H3.75A2.25 2.25 0 011.5 18.75V5.795A2.25 2.25 0 013.75 3.545M8.25 21h4.5M16.5 3.545M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="mt-4 text-lg font-medium text-gray-700">@lang('words.dashboard_no_properties_found_title')</p>
                                    <p class="text-sm text-gray-500 mt-1">@lang('words.dashboard_no_properties_found_text')</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            @if (auth()->user()->role === 'tenant' || auth()->user()->role === 'both')
                <!-- My Reviews Tab Panel -->
                <div id="content-my-reviews" class="tab-panel hidden">
                    {{-- The UserReviewController@index will pass 'bookings' (eligible for review) and 'submittedReviews' --}}
                    {{-- We can reuse the view created in Step 16, or include its content here. --}}
                    {{-- For simplicity, let's assume UserReviewController@index is called by a route,
                         and this tab just links to that route, or we load content via AJAX.
                         Alternatively, the DashboardController could prepare this data.
                         For now, let's make this tab link to the dedicated reviews page.
                         Or, better, include the content directly if DashboardController is updated.
                         Let's assume DashboardController will be updated to provide $reviewableBookings and $userSubmittedReviews
                    --}}
                    @php
                        // This data would ideally be passed from DashboardController if this tab is part of the main dashboard view
                        // For now, this is a placeholder. The actual data loading is in UserReviewController@index
                        // and this tab content will be populated by the view users.reviews.index when navigated to.
                        // If you want this tab to show content directly without navigating, DashboardController needs to fetch this data.

                        // Fetching data again here for direct display (not ideal for performance, better to pass from controller)
                        $currentUser = Auth::user();
                        $reviewableBookings = \App\Models\Booking::where('tenant_id', $currentUser->id)
                            ->where('status', 'completed')
                            ->with(['house', 'review'])
                            ->get()
                            ->filter(function ($booking) {
                                return $booking->isCompletedAndPast() && is_null($booking->review);
                            });
                        $userSubmittedReviews = \App\Models\Review::where('user_id', $currentUser->id)
                                                        ->with(['house', 'booking'])
                                                        ->latest()
                                                        ->paginate(5, ['*'], 'submitted_reviews_page'); // Paginate with a unique page name
                    @endphp

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">@lang('words.dashboard_my_reviews_ratings_title')</h2>
                            {{-- Link to the full page if desired, or manage all here --}}
                            <a href="{{ route('reviews.my') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">
                                @lang('words.dashboard_view_all_my_reviews_link')
                            </a>
                        </div>

                        <!-- Section for Pending Reviews (Bookings to Review) -->
                        <section class="mb-10">
                            <h3 class="text-xl font-semibold text-gray-700 mb-4">@lang('words.dashboard_rate_past_stays_title')</h3>
                            @if($reviewableBookings->isNotEmpty())
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach ($reviewableBookings as $booking)
                                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                                            @if($booking->house)
                                                <h4 class="text-md font-bold text-gray-800 mb-1 truncate" title="{{ $booking->house->title }}">
                                                    {{ Str::limit($booking->house->title, 30) }}
                                                </h4>
                                                <p class="text-xs text-gray-500 mb-1">
                                                    @lang('words.dashboard_label_booked_date') {{ $booking->created_at->format('M d, Y') }} | @lang('words.dashboard_label_duration') {{ $booking->month_duration }} {{ $booking->month_duration == 1 ? __('words.duration_month_singular') : __('words.duration_month_plural') }}
                                                </p>
                                                <a href="{{ route('reviews.create', $booking) }}" class="mt-2 inline-block w-full text-center bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-3 rounded-md text-sm transition duration-300">
                                                    @lang('words.dashboard_write_review_button')
                                                </a>
                                            @else
                                                <p class="text-red-500 text-sm">@lang('words.dashboard_house_details_unavailable')</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-500">@lang('words.dashboard_no_pending_reviews_text')</p>
                            @endif
                        </section>

                        <!-- Section for Submitted Reviews -->
                        <section>
                            <h3 class="text-xl font-semibold text-gray-700 mb-4">@lang('words.dashboard_my_submitted_reviews_title')</h3>
                            @if($userSubmittedReviews->isNotEmpty())
                                <div class="space-y-4">
                                    @foreach ($userSubmittedReviews as $review)
                                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    @if($review->house)
                                                        <h4 class="text-md font-bold text-gray-800">{{ $review->house->title }}</h4>
                                                    @else
                                                        <h4 class="text-md font-bold text-red-600">@lang('words.dashboard_property_unavailable')</h4>
                                                    @endif
                                                    <p class="text-xs text-gray-500">@lang('words.dashboard_label_reviewed_date') {{ $review->created_at->format('M d, Y') }}</p>
                                                </div>
                                                <div class="flex items-center text-sm">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                            @if($review->comment)
                                                <p class="text-sm text-gray-600 mt-2">{{ Str::limit($review->comment, 150) }}</p>
                                            @endif
                                            <div class="mt-2">
                                                @if($review->is_approved)
                                                    <span class="px-2 py-0.5 text-xs font-semibold text-green-700 bg-green-100 rounded-full">@lang('words.review_status_approved')</span>
                                                @else
                                                    <span class="px-2 py-0.5 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded-full">@lang('words.booking_status_pending')</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-4">
                                    {{ $userSubmittedReviews->links('pagination::tailwind') }}
                                </div>
                            @else
                                <p class="text-sm text-gray-500">@lang('words.dashboard_no_submitted_reviews_text')</p>
                            @endif
                        </section>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- New Maintenance Request Modal --}}
    @if (auth()->user()->role === 'tenant' || auth()->user()->role === 'both')
        <div id="newMaintenanceRequestModal"
            class="fixed inset-0 z-[70] flex items-center justify-center bg-opacity-50 backdrop-blur-sm"
            style="display: none;" role="dialog" aria-modal="true" aria-labelledby="newMaintenanceModalTitle">
                <div class="w-full max-w-lg mx-4 overflow-hidden bg-white rounded-lg shadow-xl">
                    <div class="flex items-center justify-between px-6 py-4 bg-gray-100 border-b border-gray-200">
                        <h1 id="newMaintenanceModalTitle" class="text-xl font-semibold text-gray-700">@lang('words.maintenance_modal_create_title')</h1>
                        <button id="closeNewMaintenanceModalBtn" aria-label="Close new maintenance modal"
                            class="text-2xl text-gray-500 hover:text-gray-700">&times;</button>
                    </div>

                    <form method="POST" action="{{ route('maintenance.insert') }}" enctype="multipart/form-data"
                        class="px-6 py-6">
                        @csrf

                        {{-- Display Validation Errors --}}
                        @if ($errors->hasBag('newMaintenanceRequestErrors') && $errors->newMaintenanceRequestErrors->any())
                            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded" role="alert">
                                <p class="font-bold">@lang('words.error_fix_following')</p>
                                <ul>
                                    @foreach ($errors->newMaintenanceRequestErrors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- House Selection --}}
                        <div class="mb-4">
                            <label for="maintenance_house_id"
                                class="block text-sm font-medium text-gray-700">@lang('words.maintenance_modal_label_property')</label>
                            <select name="house_id" id="maintenance_house_id" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('house_id', 'newMaintenanceRequestErrors') border-red-500 @enderror">
                                <option value="">@lang('words.maintenance_modal_select_property_placeholder')</option>
                                @if (isset($rentedHouses) && $rentedHouses->count() > 0)
                                    @foreach ($rentedHouses as $house)
                                        <option value="{{ $house->id }}"
                                            {{ old('house_id') == $house->id ? 'selected' : '' }}>
                                            {{ $house->title }}
                                            @if (!empty($house->full_address))
                                                ({{ Str::limit($house->full_address, 40) }})
                                            @endif
                                        </option>
                                    @endforeach
                                @else
                                    <option value="" disabled>@lang('words.maintenance_modal_no_eligible_properties')</option>
                                @endif
                            </select>
                        </div>

                        {{-- Picture --}}
                        <div class="mb-4">
                            <label for="maintenance_picture" class="block text-sm font-medium text-gray-700">@lang('words.maintenance_modal_label_picture_optional')</label>
                            <input type="file" name="picture" id="maintenance_picture" accept="image/*"
                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('picture', 'newMaintenanceRequestErrors') border-red-500 @enderror">
                        </div>

                        {{-- Area of House --}}
                        <div class="mb-4">
                            <label for="area_of_house" class="block text-sm font-medium text-gray-700">@lang('words.maintenance_modal_label_area')</label>
                            <input type="text" name="area_of_house" id="area_of_house"
                                value="{{ old('area_of_house') }}" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('area_of_house', 'newMaintenanceRequestErrors') border-red-500 @enderror">
                        </div>

                        {{-- Description --}}
                        <div class="mb-4">
                            <label for="maintenance_description"
                                class="block text-sm font-medium text-gray-700">@lang('words.maintenance_modal_label_description')</label>
                            <textarea name="description" id="maintenance_description" rows="4" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('description', 'newMaintenanceRequestErrors') border-red-500 @enderror">{{ old('description') }}</textarea>
                        </div>

                        {{-- Refund Amount --}}
                        <div class="mb-6">
                            <label for="refund_amount" class="block text-sm font-medium text-gray-700">@lang('words.maintenance_modal_label_refund_optional')</label>
                            <input type="number" name="refund_amount" id="refund_amount"
                                value="{{ old('refund_amount') }}" step="0.01" min="0"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('refund_amount', 'newMaintenanceRequestErrors') border-red-500 @enderror">
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                @lang('words.maintenance_modal_submit_request_button')
                            </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Received Maintenance Request Detail Modal --}}
    @if (auth()->user()->role === 'landlord' || auth()->user()->role === 'both')
        <div id="receivedMaintenanceDetailModal"
            class="fixed inset-0 z-[70] flex items-center justify-center bg-opacity-50 backdrop-blur-sm"
            style="display: none;" role="dialog" aria-modal="true"
            aria-labelledby="receivedMaintenanceDetailModalTitle">
            <div class="w-full max-w-2xl mx-4 bg-white rounded-lg shadow-xl overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 bg-gray-100 border-b border-gray-200">
                    <h1 id="receivedMaintenanceDetailModalTitle" class="text-xl font-semibold text-gray-700">
                        @lang('words.maintenance_modal_details_title_landlord')</h1>
                    <button id="closeReceivedMaintenanceDetailModalBtn"
                        aria-label="Close received maintenance detail modal"
                        class="text-2xl text-gray-500 hover:text-gray-700">&times;</button>
                </div>

                <div class="p-6 space-y-6 max-h-[80vh] overflow-y-auto">
                    <div id="landlordResponseErrorContainer" class="hidden">
                        {{-- Errors will be injected here by JS if needed --}}
                    </div>

                    <!-- Request Info Section -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">@lang('words.maintenance_modal_label_tenant_name')</h3>
                            <p id="modal_tenant_name" class="text-lg text-gray-800 font-semibold"></p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">@lang('words.maintenance_modal_label_area')</h3>
                            <p id="modal_area_of_house" class="text-lg text-gray-800"></p>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-4">
                        <h3 class="text-sm font-medium text-gray-500 mb-1">@lang('words.maintenance_modal_label_description')</h3>
                        <p id="modal_description" class="text-gray-700 whitespace-pre-wrap bg-gray-50 p-3 rounded-md"></p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                        <div id="modal_picture_container">
                            <h3 class="text-sm font-medium text-gray-500 mb-2">@lang('words.maintenance_modal_label_attached_picture')</h3>
                            <img id="modal_picture" src="" alt="Maintenance Picture"
                                class="mt-1 w-full max-w-sm rounded-lg border shadow-sm object-contain">
                            <p id="modal_no_picture_text" class="text-gray-500 italic mt-1 p-3 bg-gray-50 rounded-md" style="display:none;">@lang('words.maintenance_modal_no_picture_provided')</p>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-1">@lang('words.maintenance_modal_label_refund_optional')</h3>
                                <p id="modal_refund_amount" class="text-lg text-gray-800 font-semibold"></p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-1">@lang('words.booking_section_current_status')</h3>
                                <p id="modal_current_status_display" class="text-lg text-gray-800 capitalize-first"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Landlord Response and Actions Section -->
                    <form id="landlordProcessResponseForm" method="POST" action="" class="border-t border-gray-200 pt-6 mt-6">
                        @csrf
                        {{-- The hidden input for new_status is no longer strictly needed if using separate routes for accept/reject,
                             but kept for potential compatibility or if the old processLandlordResponse route is still used by something.
                             The new controller actions acceptMaintenanceRequest and rejectMaintenanceRequest do not use it. --}}
                        <input type="hidden" name="action_type_for_old_route" id="modal_new_status"> {{-- Renamed to avoid confusion --}}

                        <div>
                            <label for="modal_landlord_response"
                                class="block text-md font-semibold text-gray-700">@lang('words.maintenance_modal_label_your_reply')</label>
                            <textarea name="landlord_response" id="modal_landlord_response" rows="4"
                                class="mt-2 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="@lang('words.maintenance_modal_placeholder_landlord_response')"></textarea>
                            <div id="modal_landlord_response_error" class="mt-1 text-xs text-red-500"></div>
                        </div>
                        
                        <div id="modal_accept_reject_actions_container" class="mt-6 flex flex-col sm:flex-row justify-end gap-3" style="display: none;">
                            <button type="button" id="modalAcceptBtn"
                                class="w-full sm:w-auto inline-flex items-center justify-center py-2.5 px-6 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                <i class="fas fa-check-circle mr-2"></i>@lang('words.maintenance_modal_accept_reply_button')
                            </button>
                            <button type="button" id="modalRejectBtn"
                                class="w-full sm:w-auto inline-flex items-center justify-center py-2.5 px-6 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                <i class="fas fa-times-circle mr-2"></i>@lang('words.maintenance_modal_reject_reply_button')
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Update Sent Maintenance Request Modal (for Tenant) --}}
    @if (auth()->user()->role === 'tenant' || auth()->user()->role === 'both')
    <div id="updateSentMaintenanceRequestModal" class="fixed inset-0 z-[70] flex items-center justify-center bg-opacity-50 backdrop-blur-sm" style="display: none;" role="dialog" aria-modal="true" aria-labelledby="updateSentMaintenanceModalTitle">
        <div class="w-full max-w-2xl mx-4 bg-white rounded-lg shadow-xl overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 bg-gray-100 border-b border-gray-200">
                <h1 id="updateSentMaintenanceModalTitle" class="text-xl font-semibold text-gray-700">@lang('words.maintenance_modal_update_sent_title')</h1>
                <button id="closeUpdateSentMaintenanceModalBtn" aria-label="Close update sent maintenance modal" class="text-2xl text-gray-500 hover:text-gray-700">&times;</button>
            </div>

            <form id="updateSentMaintenanceForm" method="POST" action="" enctype="multipart/form-data" class="px-6 py-6 max-h-[80vh] overflow-y-auto">
                @csrf
                @method('PUT') {{-- Or PATCH --}}

                {{-- Display Validation Errors (Example, adjust error bag name) --}}
                <div id="updateSentMaintenanceErrorContainer" class="hidden mb-4 p-3 bg-red-100 border border-red-300 text-red-700 rounded-md">
                    <p class="font-bold">@lang('words.error_fix_following')</p>
                    <ul id="updateSentMaintenanceErrorList" class="list-disc list-inside text-sm"></ul>
                </div>
                
                <input type="hidden" name="maintenance_request_id" id="update_maintenance_request_id">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">@lang('words.maintenance_modal_label_property')</h3>
                        <p id="modal_update_house_title" class="text-lg text-gray-800 font-semibold"></p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">@lang('words.maintenance_modal_label_area')</h3>
                        <p id="modal_update_area_of_house" class="text-lg text-gray-800"></p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">@lang('words.maintenance_modal_label_request_date')</h3>
                        <p id="modal_update_created_at" class="text-gray-700"></p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">@lang('words.booking_section_current_status')</h3>
                        <p id="modal_update_status" class="text-lg text-gray-800 capitalize-first"></p>
                    </div>
                </div>

                <div class="mb-6 border-t border-gray-200 pt-4">
                    <h3 class="text-sm font-medium text-gray-500 mb-1">@lang('words.maintenance_modal_label_landlord_response')</h3>
                    <p id="modal_update_landlord_response" class="text-gray-700 whitespace-pre-wrap bg-gray-50 p-3 rounded-md min-h-[50px]"></p>
                </div>
                
                <div class="mb-4">
                    <label for="modal_update_description" class="block text-sm font-medium text-gray-700">@lang('words.maintenance_modal_label_update_description')</label>
                    <textarea name="description" id="modal_update_description" rows="4" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700">@lang('words.maintenance_modal_label_current_picture')</label>
                    <div id="modal_update_current_picture_container" class="mt-1">
                        <img id="modal_update_current_picture" src="" alt="Current Maintenance Picture" class="max-w-xs max-h-48 rounded border object-contain shadow-sm">
                        <p id="modal_update_no_current_picture_text" class="text-gray-500 italic" style="display:none;">@lang('words.maintenance_modal_no_picture_provided')</p>
                    </div>
                </div>

                <div class="mb-6">
                    <label for="modal_update_new_picture" class="block text-sm font-medium text-gray-700">@lang('words.maintenance_modal_label_upload_new_picture_optional')</label>
                    <input type="file" name="picture" id="modal_update_new_picture" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>

                <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-gray-200">
                    <button type="button" id="cancelSentMaintenanceRequestBtn" class="px-4 py-2 text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" style="display: none;">
                        <i class="fas fa-times-circle mr-2"></i>@lang('words.maintenance_modal_cancel_request_button')
                    </button>
                    <button type="submit" id="updateSentMaintenanceSaveChangesBtn" class="px-4 py-2 text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-save mr-2"></i>@lang('words.maintenance_modal_save_changes_button')
                    </button>
                </div>
            </form>
            {{-- Separate form for cancellation to avoid issues with file uploads on cancel --}}
            <form id="cancelSentMaintenanceForm" method="POST" action="" style="display: none;">
                @csrf
                @method('POST') {{-- Or DELETE, depending on how you want to handle it semantically --}}
            </form>
        </div>
    </div>
    @endif

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

        .capitalize-first::first-letter {
            text-transform: uppercase;
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
            // --- New Maintenance Request Modal Script ---
            const openNewMaintenanceBtn = document.getElementById('openNewMaintenanceModalBtn');
            const closeNewMaintenanceBtn = document.getElementById('closeNewMaintenanceModalBtn');
            const newMaintenanceModal = document.getElementById('newMaintenanceRequestModal');

            if (openNewMaintenanceBtn && closeNewMaintenanceBtn && newMaintenanceModal) {
                openNewMaintenanceBtn.addEventListener('click', function() {
                    newMaintenanceModal.style.display = 'flex';
                });

                closeNewMaintenanceBtn.addEventListener('click', function() {
                    newMaintenanceModal.style.display = 'none';
                });

                newMaintenanceModal.addEventListener('click', function(event) {
                    if (event.target === newMaintenanceModal) { // Click on overlay
                        newMaintenanceModal.style.display = 'none';
                    }
                });

                document.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape' && newMaintenanceModal.style.display === 'flex') {
                        newMaintenanceModal.style.display = 'none';
                    }
                });
            }

            // --- Received Maintenance Detail Modal Script ---
            const receivedMaintenanceCards = document.querySelectorAll('.received-maintenance-card');
            const receivedMaintenanceDetailModal = document.getElementById('receivedMaintenanceDetailModal');
            const closeReceivedMaintenanceDetailModalBtn = document.getElementById('closeReceivedMaintenanceDetailModalBtn');
            const landlordProcessResponseForm = document.getElementById('landlordProcessResponseForm');
            const modalLandlordResponseTextarea = document.getElementById('modal_landlord_response');
            const modalNewStatusInput = document.getElementById('modal_new_status');
            const modalAcceptRejectActionsContainer = document.getElementById('modal_accept_reject_actions_container');
            const modalAcceptBtn = document.getElementById('modalAcceptBtn');
            const modalRejectBtn = document.getElementById('modalRejectBtn');


            // Determine initial active tab
            let initialTab = null;
            @if (session('active_tab'))
                initialTab = '{{ session('active_tab') }}';
            @elseif (session('error_modal_open') === 'newMaintenanceRequestModal' &&
                    $errors->hasBag('newMaintenanceRequestErrors') &&
                    $errors->newMaintenanceRequestErrors->any())
                initialTab = 'maintenance'; // If new maintenance modal error, open maintenance tab
            @elseif (session('error_modal_open') === 'receivedMaintenanceDetailModal' && session('open_modal_request_id'))
                initialTab = 'maintenance'; // If landlord response modal error, open maintenance tab
            @elseif (auth()->user()->role === 'tenant' || auth()->user()->role === 'both')
                initialTab = 'sent-bookings';
            @elseif (auth()->user()->role === 'landlord')
                initialTab = 'received-bookings';
            @else
                const firstTabButton = document.querySelector('.tab-btn');
                if (firstTabButton) {
                    initialTab = firstTabButton.id.replace('tab-', '');
                }
            @endif

            if (initialTab) {
                switchTab(initialTab);
            }

            // Keep new maintenance modal open if there are validation errors for it
            @if (session('error_modal_open') === 'newMaintenanceRequestModal' &&
                    $errors->hasBag('newMaintenanceRequestErrors') &&
                    $errors->newMaintenanceRequestErrors->any())
                if (newMaintenanceModal) {
                    newMaintenanceModal.style.display = 'flex';
                }
            @endif

            // --- Received Maintenance Detail Modal Logic ---
            if (receivedMaintenanceDetailModal && closeReceivedMaintenanceDetailModalBtn && landlordProcessResponseForm && modalAcceptBtn && modalRejectBtn) {
                receivedMaintenanceCards.forEach(card => {
                    card.addEventListener('click', function() {
                        const data = this.dataset;
                        const requestId = data.requestId;

                        document.getElementById('modal_tenant_name').textContent = data.tenantName;
                        const pictureElement = document.getElementById('modal_picture');
                        const noPictureText = document.getElementById('modal_no_picture_text');
                        if (data.pictureUrl) {
                            pictureElement.src = data.pictureUrl;
                            pictureElement.style.display = 'block';
                            noPictureText.style.display = 'none';
                        } else {
                            pictureElement.style.display = 'none';
                            noPictureText.style.display = 'block';
                        }
                        document.getElementById('modal_area_of_house').textContent = data.areaOfHouse;
                        document.getElementById('modal_description').textContent = data.description;
                        document.getElementById('modal_refund_amount').textContent = '$' + parseFloat(data.refundAmount).toFixed(2);
                        document.getElementById('modal_current_status_display').textContent = data.currentStatus.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                        
                        if(modalLandlordResponseTextarea) modalLandlordResponseTextarea.value = data.landlordResponse || '';
                        
                        // Store accept/reject URLs from card data
                        landlordProcessResponseForm.dataset.acceptUrl = data.acceptUrl;
                        landlordProcessResponseForm.dataset.rejectUrl = data.rejectUrl;

                        // Show/hide Accept/Reject buttons based on status
                        if (data.currentStatus === 'pending' && modalAcceptRejectActionsContainer) {
                            modalAcceptRejectActionsContainer.style.display = 'flex';
                        } else if (modalAcceptRejectActionsContainer) {
                            modalAcceptRejectActionsContainer.style.display = 'none';
                        }

                        // Clear previous errors
                        const landlordResponseErrorContainer = document.getElementById('landlordResponseErrorContainer');
                        if(landlordResponseErrorContainer) {
                            landlordResponseErrorContainer.classList.add('hidden');
                            landlordResponseErrorContainer.innerHTML = '';
                        }
                        const landlordResponseErrorEl = document.getElementById('modal_landlord_response_error');
                        if(landlordResponseErrorEl) landlordResponseErrorEl.textContent = '';


                        receivedMaintenanceDetailModal.style.display = 'flex';
                    });
                });

                if(modalAcceptBtn) {
                    modalAcceptBtn.addEventListener('click', function() {
                        if (landlordProcessResponseForm && landlordProcessResponseForm.dataset.acceptUrl) {
                            landlordProcessResponseForm.action = landlordProcessResponseForm.dataset.acceptUrl;
                            // modalNewStatusInput.value = 'accepted'; // Not strictly needed for new routes
                            landlordProcessResponseForm.submit();
                        }
                    });
                }

                if(modalRejectBtn) {
                    modalRejectBtn.addEventListener('click', function() {
                        if (landlordProcessResponseForm && landlordProcessResponseForm.dataset.rejectUrl) {
                            landlordProcessResponseForm.action = landlordProcessResponseForm.dataset.rejectUrl;
                            // modalNewStatusInput.value = 'rejected'; // Not strictly needed for new routes
                            landlordProcessResponseForm.submit();
                        }
                    });
                }

                closeReceivedMaintenanceDetailModalBtn.addEventListener('click', function() {
                    receivedMaintenanceDetailModal.style.display = 'none';
                });

                receivedMaintenanceDetailModal.addEventListener('click', function(event) {
                    if (event.target === receivedMaintenanceDetailModal) {
                        receivedMaintenanceDetailModal.style.display = 'none';
                    }
                });

                document.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape' && receivedMaintenanceDetailModal.style.display === 'flex') {
                        receivedMaintenanceDetailModal.style.display = 'none';
                    }
                });

                // Re-open modal if there were validation errors
                @if (session('error_modal_open') === 'receivedMaintenanceDetailModal' && session('open_modal_request_id'))
                    const errorRequestId = "{{ session('open_modal_request_id') }}"; 
                    const cardToReopen = document.querySelector(`.received-maintenance-card[data-request-id="${errorRequestId}"]`);
                    
                    if (cardToReopen) {
                        receivedMaintenanceDetailModal.style.display = 'flex'; 
                        const data = cardToReopen.dataset;
                        const requestId = data.requestId; // Use requestId from the card data for consistency

                        document.getElementById('modal_tenant_name').textContent = data.tenantName;
                        const pictureElement = document.getElementById('modal_picture');
                        const noPictureText = document.getElementById('modal_no_picture_text');
                        if (data.pictureUrl) { pictureElement.src = data.pictureUrl; pictureElement.style.display = 'block'; noPictureText.style.display = 'none'; } 
                        else { pictureElement.style.display = 'none'; noPictureText.style.display = 'block'; }
                        document.getElementById('modal_area_of_house').textContent = data.areaOfHouse;
                        document.getElementById('modal_description').textContent = data.description;
                        document.getElementById('modal_refund_amount').textContent = '$' + parseFloat(data.refundAmount).toFixed(2);
                        document.getElementById('modal_current_status_display').textContent = data.currentStatus.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                        
                        if(modalLandlordResponseTextarea) modalLandlordResponseTextarea.value = "{{ old('landlord_response', '') }}";
                        
                        // Store accept/reject URLs from card data when reopening on error
                        if (landlordProcessResponseForm && data.acceptUrl && data.rejectUrl) {
                            landlordProcessResponseForm.dataset.acceptUrl = data.acceptUrl;
                            landlordProcessResponseForm.dataset.rejectUrl = data.rejectUrl;
                        }
                        // Note: The form action for re-submission on error might still point to the old generic route
                        // if `data.formAction` was used. This part of error handling might need refinement
                        // if we want errors from new routes to also re-populate correctly.
                        // For now, the primary submission paths are updated.

                        if (data.currentStatus === 'pending' && modalAcceptRejectActionsContainer) {
                            modalAcceptRejectActionsContainer.style.display = 'flex';
                        } else if (modalAcceptRejectActionsContainer) {
                            modalAcceptRejectActionsContainer.style.display = 'none';
                        }

                        // Display validation errors
                        const landlordResponseErrorContainer = document.getElementById('landlordResponseErrorContainer');
                        // const modalLandlordResponseError = document.getElementById('modal_landlord_response_error'); // This is for a single field, the container is better for multiple errors
                        
                        @if ($errors->hasBag('landlordResponseErrors_' . session('open_modal_request_id')))
                            const errorsForThisModal = @json($errors->{'landlordResponseErrors_' . session('open_modal_request_id')}->all());
                            if (errorsForThisModal.length > 0 && landlordResponseErrorContainer) {
                                landlordResponseErrorContainer.classList.remove('hidden');
                                landlordResponseErrorContainer.innerHTML = '<p class="font-bold">Please correct the following errors:</p><ul class="list-disc list-inside text-sm"></ul>';
                                const errorList = landlordResponseErrorContainer.querySelector('ul');
                                errorsForThisModal.forEach(error => {
                                    const li = document.createElement('li');
                                    li.textContent = error;
                                    errorList.appendChild(li);
                                });
                            }
                        @endif
                    }
                @endif
            }

            // --- Update Sent Maintenance Request Modal Script ---
            const openUpdateSentMaintenanceModalBtns = document.querySelectorAll('.open-update-sent-maintenance-modal-btn');
            const updateSentMaintenanceModal = document.getElementById('updateSentMaintenanceRequestModal');
            const closeUpdateSentMaintenanceModalBtn = document.getElementById('closeUpdateSentMaintenanceModalBtn');
            const updateSentMaintenanceForm = document.getElementById('updateSentMaintenanceForm');
            const cancelSentMaintenanceRequestBtn = document.getElementById('cancelSentMaintenanceRequestBtn');
            const cancelSentMaintenanceForm = document.getElementById('cancelSentMaintenanceForm');
            const updateSentMaintenanceSaveChangesBtn = document.getElementById('updateSentMaintenanceSaveChangesBtn');

            if (updateSentMaintenanceModal && closeUpdateSentMaintenanceModalBtn && updateSentMaintenanceForm && updateSentMaintenanceSaveChangesBtn) {
                openUpdateSentMaintenanceModalBtns.forEach(btn => {
                    btn.addEventListener('click', function() {
                        const data = this.dataset;
                        const requestId = data.requestId;

                        // Populate display fields
                        document.getElementById('modal_update_house_title').textContent = data.houseTitle;
                        document.getElementById('modal_update_area_of_house').textContent = data.areaOfHouse;
                        document.getElementById('modal_update_created_at').textContent = data.createdAt;
                        document.getElementById('modal_update_status').textContent = data.status.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                        
                        const landlordResponseEl = document.getElementById('modal_update_landlord_response');
                        if (data.landlordResponse) {
                            landlordResponseEl.textContent = data.landlordResponse;
                            landlordResponseEl.classList.remove('italic', 'text-gray-500');
                        } else {
                            landlordResponseEl.textContent = "@lang('words.maintenance_modal_no_landlord_response')";
                            landlordResponseEl.classList.add('italic', 'text-gray-500');
                        }

                        // Populate editable fields
                        document.getElementById('modal_update_description').value = data.description;
                        
                        // Populate current picture
                        const currentPicImg = document.getElementById('modal_update_current_picture');
                        const noCurrentPicText = document.getElementById('modal_update_no_current_picture_text');
                        if (data.currentPictureUrl) {
                            currentPicImg.src = data.currentPictureUrl;
                            currentPicImg.style.display = 'block';
                            noCurrentPicText.style.display = 'none';
                        } else {
                            currentPicImg.style.display = 'none';
                            noCurrentPicText.style.display = 'block';
                            noCurrentPicText.textContent = "@lang('words.maintenance_modal_no_picture_provided')";
                        }
                        // Clear the file input for new picture
                        document.getElementById('modal_update_new_picture').value = '';


                        // Set form action
                        updateSentMaintenanceForm.action = data.updateActionTemplate.replace('REQUEST_ID_PLACEHOLDER', requestId);
                        document.getElementById('update_maintenance_request_id').value = requestId;

                        const updatableStatuses = ['pending', 'in_progress', 'needs_tenant_input'];
                        const canUpdate = updatableStatuses.includes(data.status) && !data.landlordResponse;

                        if (canUpdate) {
                            updateSentMaintenanceSaveChangesBtn.style.display = 'inline-flex';
                        } else {
                            updateSentMaintenanceSaveChangesBtn.style.display = 'none';
                        }

                        // Handle "Cancel Request" button
                        if (data.status === 'pending') { // Only show cancel if status is pending
                            cancelSentMaintenanceRequestBtn.style.display = 'inline-flex';
                            cancelSentMaintenanceForm.action = data.cancelActionTemplate.replace('REQUEST_ID_PLACEHOLDER', requestId);
                        } else {
                            cancelSentMaintenanceRequestBtn.style.display = 'none';
                        }
                        
                        // Clear previous errors
                        const errorContainer = document.getElementById('updateSentMaintenanceErrorContainer');
                        const errorList = document.getElementById('updateSentMaintenanceErrorList');
                        errorContainer.classList.add('hidden');
                        errorList.innerHTML = '';

                        updateSentMaintenanceModal.style.display = 'flex';
                    });
                });

                closeUpdateSentMaintenanceModalBtn.addEventListener('click', function() {
                    updateSentMaintenanceModal.style.display = 'none';
                });

                updateSentMaintenanceModal.addEventListener('click', function(event) {
                    if (event.target === updateSentMaintenanceModal) {
                        updateSentMaintenanceModal.style.display = 'none';
                    }
                });

                document.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape' && updateSentMaintenanceModal.style.display === 'flex') {
                        updateSentMaintenanceModal.style.display = 'none';
                    }
                });

                if (cancelSentMaintenanceRequestBtn) {
                    cancelSentMaintenanceRequestBtn.addEventListener('click', function() {
                        if (confirm("@lang('words.maintenance_confirm_cancel_request')")) {
                            cancelSentMaintenanceForm.submit();
                        }
                    });
                }
                
                // Handle re-opening modal if there were validation errors (example)
                // This requires session flashing of error bag name and request ID
                @if (session('error_modal_open') === 'updateSentMaintenanceRequestModal' && session('open_modal_request_id_tenant_update'))
                    const errorRequestIdTenantUpdate = "{{ session('open_modal_request_id_tenant_update') }}";
                    const cardToReopenTenantUpdate = document.querySelector(`.open-update-sent-maintenance-modal-btn[data-request-id="${errorRequestIdTenantUpdate}"]`);
                    if (cardToReopenTenantUpdate) {
                        // Simulate click to reopen and populate
                        updateSentMaintenanceModal.style.display = 'flex'; // Show modal first
                        
                        const data = cardToReopenTenantUpdate.dataset;
                        const requestId = data.requestId;

                        document.getElementById('modal_update_house_title').textContent = data.houseTitle;
                        document.getElementById('modal_update_area_of_house').textContent = data.areaOfHouse;
                        document.getElementById('modal_update_created_at').textContent = data.createdAt;
                        document.getElementById('modal_update_status').textContent = data.status.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                        const landlordResponseEl = document.getElementById('modal_update_landlord_response');
                        if (data.landlordResponse) { landlordResponseEl.textContent = data.landlordResponse; landlordResponseEl.classList.remove('italic', 'text-gray-500'); } 
                        else { landlordResponseEl.textContent = "@lang('words.maintenance_modal_no_landlord_response')"; landlordResponseEl.classList.add('italic', 'text-gray-500'); }
                        
                        // Keep old input for description if validation failed
                        // document.getElementById('modal_update_description').value = "{{ old('description') }}"; // This would be for Laravel old() helper
                        
                        const currentPicImg = document.getElementById('modal_update_current_picture');
                        const noCurrentPicText = document.getElementById('modal_update_no_current_picture_text');
                        if (data.currentPictureUrl) { currentPicImg.src = data.currentPictureUrl; currentPicImg.style.display = 'block'; noCurrentPicText.style.display = 'none';} 
                        else { currentPicImg.style.display = 'none'; noCurrentPicText.style.display = 'block'; noCurrentPicText.textContent = "@lang('words.maintenance_modal_no_picture_provided')";}
                        
                        updateSentMaintenanceForm.action = data.updateActionTemplate.replace('REQUEST_ID_PLACEHOLDER', requestId);
                        document.getElementById('update_maintenance_request_id').value = requestId;

                        const updatableStatusesReopen = ['pending', 'in_progress', 'needs_tenant_input'];
                        const canUpdateReopen = updatableStatusesReopen.includes(data.status) && !data.landlordResponse;

                        if (canUpdateReopen) {
                            updateSentMaintenanceSaveChangesBtn.style.display = 'inline-flex';
                        } else {
                            updateSentMaintenanceSaveChangesBtn.style.display = 'none';
                        }

                        if (data.status === 'pending') {
                            cancelSentMaintenanceRequestBtn.style.display = 'inline-flex';
                            cancelSentMaintenanceForm.action = data.cancelActionTemplate.replace('REQUEST_ID_PLACEHOLDER', requestId);
                        } else {
                            cancelSentMaintenanceRequestBtn.style.display = 'none';
                        }

                        // Display validation errors
                        const errorContainer = document.getElementById('updateSentMaintenanceErrorContainer');
                        const errorList = document.getElementById('updateSentMaintenanceErrorList');
                        errorContainer.classList.remove('hidden');
                        errorList.innerHTML = ''; // Clear previous errors
                        
                        @if ($errors->hasBag('updateSentMaintenanceRequestErrors_' . session('open_modal_request_id_tenant_update')))
                            @foreach ($errors->{'updateSentMaintenanceRequestErrors_' . session('open_modal_request_id_tenant_update')}->all() as $error)
                                const li = document.createElement('li');
                                li.textContent = "{{ $error }}";
                                errorList.appendChild(li);
                            @endforeach
                        @endif
                    }
                @endif
            }
        });
    </script>
</x-layout>
