<x-layout>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">My Messages</h1>

        @if ($agreements->isEmpty())
            <p class="text-gray-600 text-center">You have no active agreements with messages.</p>
        @else
            <div class="bg-white shadow-md rounded-lg">
                <ul class="divide-y divide-gray-200">
                    @foreach ($agreements as $agreement)
                        @php
                            $otherParty = null;
                            if (Auth::id() == $agreement->tenant->id) {
                                $otherParty = $agreement->landlord;
                            } else {
                                $otherParty = $agreement->tenant;
                            }
                            $latestMessage = $agreement->messages->first(); // Already sorted by desc in controller
                            $unreadCount = $agreement->messages->where('receiver_id', Auth::id())->whereNull('read_at')->count();
                        @endphp
                        <li>
                            <a href="{{ route('agreements.messages.index', $agreement) }}" class="block hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition duration-150 ease-in-out">
                                <div class="flex items-center px-4 py-4 sm:px-6">
                                    <div class="min-w-0 flex-1 flex items-center">
                                        <div class="flex-shrink-0">
                                            <!-- You can add a user avatar or property image here -->
                                            <img class="h-12 w-12 rounded-full" src="{{ $otherParty->picture ? asset('storage/' . $otherParty->picture) : asset('images/default-avatar.png') }}" alt="{{ $otherParty->full_name }}" />
                                        </div>
                                        <div class="min-w-0 flex-1 px-4 md:grid md:grid-cols-2 md:gap-4">
                                            <div>
                                                <p class="text-sm font-medium text-indigo-600 truncate">Conversation with {{ $otherParty->full_name }}</p>
                                                <p class="mt-2 flex items-center text-sm text-gray-500">
                                                    <span class="truncate">Regarding: {{ $agreement->booking->house->title }}</span>
                                                </p>
                                            </div>
                                            <div class="hidden md:block">
                                                <div>
                                                    @if ($latestMessage)
                                                        <p class="text-sm text-gray-900">
                                                            {{ Str::limit($latestMessage->content, 50) }}
                                                        </p>
                                                        <p class="mt-2 flex items-center text-sm text-gray-500">
                                                            {{ $latestMessage->created_at->diffForHumans() }}
                                                            @if($latestMessage->sender_id == Auth::id() && $latestMessage->read_at)
                                                                <svg class="ml-1 w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                                            @elseif($latestMessage->sender_id == Auth::id())
                                                                <svg class="ml-1 w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                                            @endif
                                                        </p>
                                                    @else
                                                        <p class="text-sm text-gray-500">No messages yet.</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ml-4 flex-shrink-0 flex items-center">
                                        @if ($unreadCount > 0)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500 text-white">
                                                {{ $unreadCount }}
                                            </span>
                                        @endif
                                        <svg class="h-5 w-5 text-gray-400 ml-2" viewBox="0 0 20 20" fill="currentColor">
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
