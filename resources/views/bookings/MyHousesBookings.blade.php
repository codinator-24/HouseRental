<x-layout>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6 text-center">@lang('words.my_house_bookings_title')</h1>

        @if ($bookings->isEmpty())
            <div class="text-center text-gray-500">
                <p class="text-xl">@lang('words.my_house_bookings_none')</p>
            </div>
        @else
            <div class="flex flex-col items-center space-y-6">
                @foreach ($bookings as $booking)
                    <div class="bg-white shadow-lg rounded-lg p-6 w-full max-w-3xl relative">

                        @if ($booking->house)
                            <div class="absolute top-6 right-6 flex space-x-2">
                                @if ($booking->agreement && $booking->agreement->status === 'active')
                                    <a href="{{ route('agreements.messages.index', $booking->agreement->id) }}"
                                        class="bg-green-500 hover:bg-green-600 text-white text-sm font-semibold py-2 px-4 rounded">
                                        @lang('words.booking_view_messages_button')
                                    </a>
                                @endif
                                <a href="{{ route('bookings.show', $booking->id) }}"
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold py-2 px-4 rounded">
                                    @lang('words.booking_view_details_button')
                                </a>
                            </div>
                        @endif
                        <h2 class="text-xl font-semibold mb-2">@lang('words.booking_for_property_title_prefix') {{ $booking->house->title ?? __('words.booking_property_title_na') }}</h2>
                        <p class="text-gray-700 mb-1"><strong>@lang('words.booking_label_house')</strong> {{ $booking->house->title ?? __('words.booking_property_title_na') }}</p>
                        <p class="text-gray-700 mb-1"><strong>@lang('words.booking_label_date')</strong>
                            {{ $booking->created_at->format('Y-m-d H:i') }}</p>
                        @if ($booking->tenant)
                            <p class="text-gray-700 mb-1"><strong>@lang('words.booking_label_from')</strong> {{ $booking->tenant->name }}
                                {{ $booking->tenant->full_name }}</p>
                        @else
                            <p class="text-gray-700 mb-1"><strong>@lang('words.booking_label_from')</strong> @lang('words.booking_tenant_info_unavailable')</p>
                        @endif
                        <p class="text-gray-700 mb-1">
                            <strong>@lang('words.booking_label_status')</strong>
                            @if ($booking->status === 'pending')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    @lang('words.booking_status_pending')
                                </span>
                            @elseif ($booking->status === 'accepted')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    @lang('words.booking_status_accepted')
                                </span>
                            @elseif ($booking->status === 'rejected')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    @lang('words.booking_status_rejected')
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ ucfirst($booking->status) }} {{-- Assuming other statuses might not be translated yet or are dynamic --}}
                                </span>
                            @endif
                        </p>
                        <hr class="my-2 border-gray-300">
                        <p class="text-gray- mb-1">
                            <strong>@lang('words.booking_label_required_duration')</strong><br>{{ (int)$booking->month_duration }} {{ (int)$booking->month_duration > 1 ? __('words.duration_month_plural') : __('words.duration_month_singular') }}</p>
                        <p class="text-gray-700">
                            <strong>@lang('words.booking_label_message')</strong><br>{{ $booking->message ?? __('words.booking_no_message_provided') }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layout>
