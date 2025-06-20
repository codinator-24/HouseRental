<x-layout>
    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8 text-center text-gray-800">@lang('words.sent_bookings_title')</h1>

        @if ($sentBookings->isEmpty())
            <div class="text-center text-gray-500 py-10">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    aria-hidden="true">
                    <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                </svg>
                <p class="mt-3 text-xl font-semibold">@lang('words.sent_bookings_none_sent_title')</p>
                <p class="mt-2 text-sm">@lang('words.sent_bookings_none_sent_desc')</p>
                <div class="mt-6">
                    <a href="{{ route('home') }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        @lang('words.sent_bookings_explore_properties_button')
                    </a>
                </div>
            </div>
        @else
            <div class="space-y-6">
                @foreach ($sentBookings as $booking)
                    <div
                        class="bg-white shadow-lg rounded-xl overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <div class="p-6">
                            <div class="flex flex-col sm:flex-row justify-between sm:items-start">
                                <div>
                                    @if ($booking->house)
                                        <h2 class="text-xl sm:text-2xl font-semibold text-indigo-700 mb-1">
                                            <a href="{{ route('house.details', $booking->house->id) }}"
                                                class="hover:underline">{{ $booking->house->title ?? __('words.booking_property_title_na') }}</a>
                                        </h2>
                                        <p class="text-sm text-gray-500 mb-1">
                                            {{ $booking->house->address ?? __('words.booking_address_na') }}</p>
                                        @if ($booking->house->landlord)
                                            <p class="text-sm text-gray-600">
                                                <strong>@lang('words.booking_label_landlord_lowercase')</strong>
                                                {{ $booking->house->landlord->full_name ?? ($booking->house->landlord->user_name ?? __('words.booking_landlord_na')) }}
                                            </p>

                                            <p class="text-sm text-gray-600 mt-1">
                                                <strong>@lang('words.booking_label_status')</strong>
                                                @if ($booking->status === 'pending')
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        @lang('words.booking_status_pending')
                                                    </span>
                                                @elseif ($booking->status === 'accepted')
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-green-800">
                                                        @lang('words.booking_status_accepted')
                                                    </span>
                                                @elseif ($booking->status === 'rejected')
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        @lang('words.booking_status_rejected')
                                                    </span>
                                                @endif
                                        @endif
                                    @else
                                        <h2 class="text-xl sm:text-2xl font-semibold text-gray-700 mb-1">@lang('words.booking_for_deleted_property')</h2>
                                    @endif
                                </div>
                                <div class="mt-3 sm:mt-0 sm:ml-4 flex-shrink-0 flex items-center space-x-2">

                                    {{-- @if ($booking->status === 'accepted') --}}
                                        <a href="{{ route('agreement.create', $booking->id) }}"
                                            class="bg-green-500 hover:bg-green-600 text-white text-xs font-semibold py-2 px-3 rounded-md shadow-sm transition duration-150 ease-in-out whitespace-nowrap">
                                            @lang('words.booking_create_agreement_button')
                                        </a>
                                    {{-- @endif --}}
                                    
                                    <a href="{{ route('bookings.details.show', $booking->id) }}"
                                        class="bg-indigo-500 hover:bg-indigo-600 text-white text-xs font-semibold py-2 px-3 rounded-md shadow-sm transition duration-150 ease-in-out whitespace-nowrap">
                                        @lang('words.booking_view_update_button')
                                    </a>
                                    <form method="POST" action="{{ route('bookings.sent.destroy', $booking->id) }}"
                                        onsubmit="return confirm('@lang('words.booking_confirm_delete_request')');">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-500 hover:bg-red-600 text-white text-xs font-semibold py-2 px-3 rounded-md shadow-sm transition duration-150 ease-in-out whitespace-nowrap">
                                            @lang('words.booking_delete_button')
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <hr class="my-4 border-gray-200">
                            <p class="text-sm text-gray-600">
                                <strong>@lang('words.booking_label_your_duration_required')</strong>
                                {{ (int) $booking->month_duration }}
                                {{ (int)$booking->month_duration > 1 ? __('words.duration_month_plural') : __('words.duration_month_singular') }}
                            </p>

                            <div>
                                <p class="text-sm font-medium text-gray-800 mb-1">@lang('words.booking_label_your_message')</p>
                                <div
                                    class="bg-gray-50 p-3 rounded-md shadow-inner max-h-28 overflow-y-auto prose prose-sm">
                                    <p class="text-gray-700 whitespace-pre-line">
                                        {{ $booking->message ?? __('words.booking_no_message_provided') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($sentBookings->hasPages())
                <div class="mt-8">
                    {{ $sentBookings->links() }}
                </div>
            @endif
        @endif

    </div>

    <!-- Cash Appointment Modal -->
    {{-- <div x-data="{ show: false, bookingId: null }" x-on:open-cash-modal.window="show = true; bookingId = $event.detail.bookingId"
        x-show="show" x-cloak class="fixed inset-0 flex items-center justify-center z-50 bg-white bg-opacity-50">
        <div @click.away="show = false" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90"
            class="bg-white rounded-2xl shadow-2xl p-8 max-w-3xl w-full mx-4 sm:mx-auto" style="min-width: 400px;">
            <h2 class="text-2xl font-bold mb-6 text-indigo-700">Schedule Cash Appointment</h2>

            <form method="POST" action="{{ route('cash.appointment') }}">
                @csrf
                <input type="hidden" name="booking_id" :value="bookingId">

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Preferred Date</label>
                    <input type="date" name="date" required
                        class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Preferred Time</label>
                    <input type="time" name="time" required
                        class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition">
                </div>

                <div class="flex justify-end space-x-4">
                    <button type="button" @click="show = false"
                        class="px-6 py-2 rounded-lg bg-gray-300 text-gray-800 font-semibold hover:bg-gray-400 transition">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-6 py-2 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div> --}}

    <!-- Hidden Credit Checkout Form -->
    <form id="credit-checkout-form" action="{{ route('checkout') }}" method="POST" style="display: none;">
        @csrf
    </form>

</x-layout>
