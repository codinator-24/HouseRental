<x-layout>
    <div class="container px-4 py-8 mx-auto">
        <h1 class="mb-6 text-3xl font-bold text-gray-800">@lang('words.Notifications')</h1> {{-- Re-using Notifications as it's a general term for messages/alerts --}}

        @if ($threads->isEmpty())
            <p class="text-center text-gray-600">@lang('words.message_overview_no_threads')</p>
        @else
            <div class="bg-white rounded-lg shadow-md">
                <ul class="divide-y divide-gray-200">
                    @foreach ($threads as $thread)
                        @php
                            $unreadCount = 0;
                            $item = $thread->item; // This is either Agreement or House model
                            $otherParty = $thread->otherParty;
                            $latestMessage = $thread->latestMessage;

                            if ($thread->type === 'agreement') {
                                $unreadCount = $item->messages()->where('receiver_id', Auth::id())->whereNull('read_at')->count();
                                $itemTitle = $item->booking->house->title ?? __('words.booking_property_info_na');
                                $titleText = __('words.message_overview_agreement_title') . $item->id . ' ' . __('words.message_overview_with_user') . ' ' . ($otherParty->user_name ?? __('words.booking_tenant_info_unavailable'));
                            } elseif ($thread->type === 'inquiry') {
                                // For inquiries, item is the House model
                                // Unread count for inquiry messages related to this house and user
                                $unreadCount = $item->inquiryMessages()
                                                    ->where('receiver_id', Auth::id())
                                                    ->where(function($q) use ($otherParty) { // Filter by the specific other party in this thread
                                                        if ($otherParty) {
                                                            $q->where('sender_id', $otherParty->id);
                                                        }
                                                    })
                                                    ->whereNull('read_at')
                                                    ->count();
                                $itemTitle = $item->title;
                                $titleText = __('words.message_overview_inquiry_title') . ' ' . $item->title . ' ' . __('words.message_overview_with_user') . ' ' . ($otherParty->user_name ?? __('words.booking_landlord_na'));
                            }
                        @endphp
                        <li>
                            <a href="{{ $thread->link }}" class="block संक्रमण duration-150 ease-in-out hover:bg-gray-50 focus:outline-none focus:bg-gray-50">
                                <div class="flex items-center px-4 py-4 sm:px-6">
                                    <div class="flex items-center flex-1 min-w-0">
                                        <div class="flex-shrink-0">
                                            <img class="w-12 h-12 rounded-full" 
                                                 src="{{ $otherParty && $otherParty->picture ? asset($otherParty->picture) : asset('images/default-profile.png') }}" 
                                                 alt="{{ $otherParty->user_name ?? 'User' }}" />
                                        </div>
                                        <div class="flex-1 min-w-0 px-4 md:grid md:grid-cols-2 md:gap-4">
                                            <div>
                                                <p class="text-sm font-medium text-indigo-600 truncate">{{ $titleText }}</p>
                                                <p class="flex items-center mt-2 text-sm text-gray-500">
                                                    <span class="truncate">
                                                        @if ($thread->type === 'agreement')
                                                            @lang('words.booking_label_house') {{ $itemTitle }}
                                                        @else
                                                            {{-- For inquiry, item is the house itself --}}
                                                            @lang('words.booking_label_house') {{ $itemTitle }}
                                                        @endif
                                                    </span>
                                                </p>
                                            </div>
                                            <div class="hidden md:block">
                                                <div>
                                                    @if ($latestMessage)
                                                        <p class="text-sm text-gray-900">
                                                            <span class="font-semibold">{{ $latestMessage->sender_id == Auth::id() ? 'You' : $latestMessage->sender->user_name }}:</span>
                                                            {{ Str::limit($latestMessage->content, 40) }}
                                                        </p>
                                                        <p class="flex items-center mt-2 text-sm text-gray-500">
                                                            {{ $latestMessage->created_at->diffForHumans() }}
                                                            @if($latestMessage->sender_id == Auth::id() && $latestMessage->read_at)
                                                                <i class="ml-1 fas fa-check-double text-sky-400" title="Read"></i>
                                                            @elseif($latestMessage->sender_id == Auth::id())
                                                                <i class="ml-1 fas fa-check text-gray-400" title="Sent"></i>
                                                            @endif
                                                        </p>
                                                    @else
                                                        <p class="text-sm text-gray-500">@lang('words.inquiry_no_messages_yet')</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center flex-shrink-0 ml-4">
                                        @if ($unreadCount > 0)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500 text-white">
                                                {{ $unreadCount }}
                                            </span>
                                        @endif
                                        <svg class="w-5 h-5 ml-2 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</x-layout>
