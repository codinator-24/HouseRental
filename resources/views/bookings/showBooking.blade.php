<x-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-xl rounded-lg overflow-hidden max-w-3xl mx-auto">
            <!-- Header Section -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-700 p-6 sm:p-8 text-white">
                <h1 class="text-2xl sm:text-3xl font-bold text-center">@lang('words.booking_details_page_title')</h1>
            </div>

            <div class="p-6 sm:p-8 space-y-6">
                <!-- Tenant Information -->
                <section aria-labelledby="tenant-info-heading">
                    <h2 id="tenant-info-heading" class="text-xl sm:text-2xl font-semibold text-gray-800 mb-4 border-b border-gray-300 pb-2">
                        @lang('words.booking_section_tenant_info')
                    </h2>
                    <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-6">
                        <img src="{{ $booking->tenant->picture ? Storage::url($booking->tenant->picture) : 'https://ui-avatars.com/api/?name=' . urlencode($booking->tenant->user_name ?? $booking->tenant->full_name ?? 'Tenant') . '&background=random&size=128' }}"
                             alt="{{ $booking->tenant->user_name ?? 'Tenant' }}'s profile picture"
                             class="w-24 h-24 sm:w-32 sm:h-32 rounded-full object-cover border-4 border-indigo-300 shadow-md">
                        <div class="text-center sm:text-left">
                            <p class="text-xl font-bold text-gray-700">
                                {{ $booking->tenant->full_name ?? __('words.booking_tenant_name_na') }}
                            </p>
                            {{-- Display secondary name if `full_name` was different from `name` and both exist --}}
                            @if(isset($booking->tenant->full_name) && isset($booking->tenant->user_name) && $booking->tenant->full_name !== $booking->tenant->user_name)
                                <p class="text-md text-gray-500">({{ $booking->tenant->user_name }})</p>
                            @endif
                            
                           @if($booking->status === 'accepted')
                                <p class="text-gray-600 mt-1">
                                    <span class="font-semibold">@lang('words.booking_label_phone')</span>
                                    {{ $booking->tenant->first_phoneNumber ?? __('words.booking_phone_not_provided') }}
                                    @if($booking->tenant->second_phoneNumber)
                                        | {{ $booking->tenant->second_phoneNumber }}
                                    @endif
                                </p>
                            @else
                                <p class="text-gray-600 mt-1">
                                    <span class="font-semibold">@lang('words.booking_label_phone')</span> <span class="italic text-gray-500">@lang('words.booking_phone_visible_on_acceptance')</span>
                                </p>
                            @endif
                        </div>
                    </div>
                </section>

                <!-- Booking Message -->
                <section aria-labelledby="booking-message-heading">
                    <h2 id="booking-message-heading" class="text-xl sm:text-2xl font-semibold text-gray-800 mb-3 border-b border-gray-300 pb-2">
                        @lang('words.booking_section_message_from_tenant')
                    </h2>
                    <div class="bg-gray-50 p-4 rounded-md shadow-inner">
                        <p class="text-gray-700 whitespace-pre-line">{{ $booking->message ?? __('words.booking_no_message_provided') }}</p>
                    </div>
                </section>

                <!-- Booking & Property Details -->
                <section aria-labelledby="booking-property-details-heading">
                    <h2 id="booking-property-details-heading" class="text-xl sm:text-2xl font-semibold text-gray-800 mb-4 border-b border-gray-300 pb-2">
                        @lang('words.booking_section_booking_property_info')
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm sm:text-base">
                        <div>
                            <p class="text-gray-700"><strong class="text-gray-900">@lang('words.booking_label_required_duration')</strong></p>
                            <p class="mb-2 text-indigo-700 font-medium">{{ (int)$booking->month_duration }} {{ (int)$booking->month_duration > 1 ? __('words.duration_month_plural') : __('words.duration_month_singular') }}</p>
                            <p class="text-gray-700"><strong class="text-gray-900">@lang('words.booking_label_booking_sent')</strong></p>
                            <p class="text-indigo-700 font-medium">{{ $booking->created_at->format('F j, Y, g:i a') }}</p>
                            <p class="text-xs text-gray-500">({{ $booking->created_at->diffForHumans() }})</p>
                        </div>
                        <div>
                            <p class="text-gray-700"><strong class="text-gray-900">@lang('words.booking_label_booked_property')</strong></p>
                            @if($booking->house)
                                <a href="{{ route('house.details', $booking->house->id) }}" class="text-indigo-600 hover:text-indigo-800 hover:underline font-medium">{{ $booking->house->title }}</a>
                                <p class="text-xs text-gray-500">{{ $booking->house->address ?? __('words.booking_address_na') }}</p>
                            @else
                                <p class="text-gray-500">@lang('words.booking_property_info_na')</p>
                            @endif
                        </div>
                    </div>
                </section>

                <!-- Booking Status -->
                <section aria-labelledby="booking-status-display-heading" class="mt-6">
                    <h2 id="booking-status-display-heading" class="text-xl sm:text-2xl font-semibold text-gray-800 mb-3 border-b border-gray-300 pb-2">
                        @lang('words.booking_section_current_status')
                    </h2>
                    <div class="bg-gray-100 p-4 rounded-md shadow">
                        <p class="text-lg text-center font-medium">
                            @if($booking->status === 'pending')
                                @lang('words.booking_status_detail_pending_landlord')
                            @elseif($booking->status === 'accepted')
                                @lang('words.booking_status_detail_accepted')
                            @elseif($booking->status === 'rejected')
                                @lang('words.booking_status_detail_rejected')
                            @else
                                @lang('words.booking_label_status') <span class="text-gray-700 font-bold">{{ ucfirst($booking->status) }}</span>
                            @endif
                        </p>
                    </div>
                </section>

                {{-- Landlord Actions --}}
                @if($booking->house && Auth::check() && Auth::id() === $booking->house->landlord_id && $booking->status === 'pending')
                    <section aria-labelledby="landlord-actions-heading" class="mt-8 pt-6 border-t border-gray-200">
                        <h2 id="landlord-actions-heading" class="text-xl sm:text-2xl font-semibold text-gray-800 mb-4 text-center">
                            @lang('words.booking_section_manage_request')
                        </h2>
                        <div class="flex justify-center items-center space-x-3 sm:space-x-4">
                            <form action="{{ route('bookings.accept', $booking) }}" method="POST" onsubmit="return confirm('@lang('words.booking_confirm_accept')');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 sm:px-6 rounded-lg shadow-md transition duration-150 ease-in-out text-sm sm:text-base">
                                    @lang('words.booking_accept_button')
                                </button>
                            </form>
                            <form action="{{ route('bookings.reject', $booking) }}" method="POST" onsubmit="return confirm('@lang('words.booking_confirm_reject')');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 sm:px-6 rounded-lg shadow-md transition duration-150 ease-in-out text-sm sm:text-base">
                                    @lang('words.booking_reject_button')
                                </button>
                            </form>
                        </div>
                    </section>
                @endif

                <div class="mt-8 pt-6 border-t border-gray-200 text-center">
                    @if(Auth::check())
                        @if($booking->house && Auth::id() === $booking->house->landlord_id)
                            <a href="{{ route('my.bookings') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-6 rounded-lg shadow-md transition duration-150 ease-in-out">
                                @lang('words.booking_back_to_received_button')
                            </a>
                        @elseif(Auth::id() === $booking->tenant_id)
                            <a href="{{ route('bookings.sent') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-6 rounded-lg shadow-md transition duration-150 ease-in-out">
                                @lang('words.booking_back_to_sent_button')
                            </a>
                        @else
                             {{-- Fallback, though authorization should ideally prevent reaching here without a defined role --}}
                            <a href="{{ url()->previous() }}" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-6 rounded-lg shadow-md transition duration-150 ease-in-out">
                                @lang('words.booking_go_back_button')
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layout>
