<x-layout>
    <div class="container px-4 py-8 mx-auto sm:px-6 lg:px-8">
        {{-- Back Button --}}
        <div class="mb-6">
            <a href="{{ route('house.details', $house) }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                @lang('words.details_house_back_to_listings') {{-- Re-use existing translation --}}
            </a>
        </div>

        <h1 class="mb-2 text-2xl font-semibold text-gray-800">
            @lang('words.inquiry_thread_title_prefix') <a href="{{ route('house.details', $house) }}" class="text-blue-600 hover:underline">{{ $house->title }}</a>
            @if ($currentUser->id === $house->landlord_id)
                {{-- Landlord is viewing, show who they are talking to if possible --}}
                @php
                    $otherParty = $messages->map(function($msg) use ($currentUser) {
                        return $msg->sender_id === $currentUser->id ? $msg->receiver : $msg->sender;
                    })->firstWhere('id', '!=', $currentUser->id);
                @endphp
                @if($otherParty)
                    @lang('words.inquiry_thread_with_user_prefix') {{ $otherParty->user_name }}
                @endif
            @else
                {{-- Tenant is viewing, they are talking to the landlord --}}
                @lang('words.inquiry_thread_with_user_prefix') {{ $landlord->user_name }}
            @endif
        </h1>

        @if (session('success'))
            <div class="relative px-4 py-3 mb-4 text-green-700 bg-green-100 border border-green-400 rounded" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="relative px-4 py-3 mb-4 text-red-700 bg-red-100 border border-red-400 rounded" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="overflow-hidden bg-white rounded-lg shadow-md">
            {{-- Messages Area --}}
            <div class="p-6 space-y-4 h-96 overflow-y-auto" id="message-container">
                @forelse ($messages as $message)
                    <div class="flex {{ $message->sender_id == Auth::id() ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-xs px-4 py-2 rounded-lg lg:max-w-md {{ $message->sender_id == Auth::id() ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800' }}">
                            <p class="text-sm font-semibold">{{ $message->sender->user_name }}</p>
                            <p class="text-sm">{{ $message->content }}</p>
                            <p class="mt-1 text-xs {{ $message->sender_id == Auth::id() ? 'text-blue-200' : 'text-gray-500' }}">
                                {{ $message->created_at->format('M d, Y H:i A') }}
                                @if ($message->sender_id == Auth::id() && $message->read_at)
                                    <i class="ml-1 fas fa-check-double text-sky-300" title="Read at {{ $message->read_at->format('M d, H:i') }}"></i>
                                @elseif ($message->sender_id == Auth::id())
                                    <i class="ml-1 fas fa-check text-sky-300" title="Sent"></i>
                                @endif
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500">@lang('words.inquiry_no_messages_yet')</p>
                @endforelse
            </div>

            {{-- Message Input Form --}}
            <div class="p-4 bg-gray-100 border-t border-gray-200">
                <form action="{{ route('houses.inquiry.store', $house) }}" method="POST">
                    @csrf
                    {{-- Hidden field for landlord to specify receiver if replying to a specific inquirer in a multi-inquirer scenario (future enhancement) --}}
                    @if ($currentUser->id === $house->landlord_id && isset($otherParty) && $otherParty)
                        <input type="hidden" name="receiver_id_for_reply" value="{{ $otherParty->id }}">
                    @endif

                    <div class="flex items-start space-x-3">
                        <textarea name="content" rows="3"
                            class="flex-1 block w-full px-3 py-2 text-gray-900 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            placeholder="{{ $currentUser->id === $house->landlord_id ? __('words.inquiry_landlord_reply_placeholder') : __('words.inquiry_message_placeholder') }}" required>{{ old('content') }}</textarea>
                        <button type="submit"
                            class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            @lang('words.inquiry_send_message_button')
                            <i class="ml-2 fas fa-paper-plane"></i>
                        </button>
                    </div>
                    @error('content')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const messageContainer = document.getElementById('message-container');
            if (messageContainer) {
                messageContainer.scrollTop = messageContainer.scrollHeight;
            }
        });
    </script>
    @endpush
</x-layout>
