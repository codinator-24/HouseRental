<x-layout>
    <div class="px-10 bg-gray-50">
        <h1 class="title">@lang('words.my_houses_greeting_hello') {{ auth()->user()->user_name }}</h1>
        {{-- Featured Properties Section --}}
        <section class="py-16">
            <div class="container mx-auto px-6">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-800">@lang('words.dashboard_tab_my_properties')</h2>
                </div>

                {{-- Property Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse ($houses as $house)
                        {{-- Property Card --}}
                        <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200 flex flex-col">
                            {{-- Use first picture if available, otherwise a placeholder --}}
                            @php
                                $imageUrl = $house->pictures->first()?->image_url
                                    ? asset($house->pictures->first()->image_url)
                                    : 'https://images.pexels.com/photos/731082/pexels-photo-731082.jpeg';
                            @endphp
                            <img src="{{ $imageUrl }}" alt="{{ $house->title }}" class="w-full h-48 object-cover">
                            <div class="p-6 flex flex-col flex-grow">
                                <h3 class="text-xl font-semibold mb-2">{{ $house->title }}</h3>
                                <p class="text-gray-600 text-sm mb-4 flex-grow">
                                    <i class="fas fa-map-marker-alt mr-1 text-slate-500"></i>
                                    {{ $house->city }}, {{ $house->first_address }} {{ $house->second_address ?? '' }}
                                </p>
                                <div class="flex justify-between items-center mb-4">
                                    <span class="text-2xl font-bold text-blue-600 convertible-price" data-base-price-usd="{{ $house->rent_amount }}">
                                        {{-- Initial display, will be updated by JS --}}
                                        ${{ number_format($house->rent_amount, 2) }}
                                    </span>
                                    <span class="text-gray-600 text-sm">@lang('words.my_houses_per_month')</span>
                                </div>
                                <div class="flex justify-between text-sm text-gray-600 border-t pt-4 mb-4">
                                    <span><i class="fas fa-bed mr-1"></i> {{ $house->num_room }}
                                        {{ Str::plural(__('words.property_card_room_singular'), $house->num_room) }}</span>
                                    <span><i class="fas fa-layer-group mr-1"></i> {{ $house->num_floor }}
                                        {{ Str::plural(__('words.property_card_floor_singular'), $house->num_floor) }}</span>
                                    <span><i class="fas fa-ruler-combined mr-1"></i> {{ $house->square_footage }}
                                        m<sup>2</sup></span>
                                </div>
                                {{-- Action Buttons --}}
                                <div class="flex justify-end space-x-3 mt-auto">
                                    <a href="{{ route('Myhouse.edit', $house) }}"
                                        class="w-25 bg-blue-600 hover:bg-blue-700 text-white py-1 px-1 rounded text-center transition duration-300" title="@lang('words.my_houses_update_property_tooltip')">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <form action="{{ route('Myhouse.delete', $house) }}" method="POST" class="">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            onclick="return confirm('@lang('words.dashboard_confirm_delete_property')');"
                                            class="w-12 bg-red-600 hover:bg-red-700 text-white py-1 px-1 rounded text-center transition duration-300" title="@lang('words.my_houses_delete_property_tooltip')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-600 md:col-span-2 lg:col-span-3 text-center">@lang('words.featured_properties_none_found')
                        </p>
                    @endforelse
                </div>
            </div>
        </section>
    </div>
</x-layout>
