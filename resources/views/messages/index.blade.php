<x-layout>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Messages for Agreement #{{ $agreement->id }}</h1>

        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold mb-2">Agreement Details</h2>
            <p><strong>Property:</strong> {{ $agreement->booking->house->title }}</p>
            <p><strong>Landlord:</strong> {{ $landlord->full_name }} ({{ $landlord->email }})</p>
            <p><strong>Tenant:</strong> {{ $tenant->full_name }} ({{ $tenant->email }})</p>
            <p><strong>Status:</strong> <span class="capitalize">{{ $agreement->status }}</span></p>
        </div>

        <!-- Chat Area -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6" style="max-height: 500px; overflow-y: auto;" id="message-container">
            @forelse ($messages as $message)
                <div class="mb-4 p-3 rounded-lg {{ $message->sender_id == Auth::id() ? 'bg-blue-100 ml-auto' : 'bg-gray-100 mr-auto' }}" style="max-width: 75%;">
                    <p class="font-semibold text-sm {{ $message->sender_id == Auth::id() ? 'text-blue-700' : 'text-gray-700' }}">
                        {{ $message->sender->full_name }}
                    </p>
                    <p class="text-gray-800">{{ $message->content }}</p>
                    <p class="text-xs text-gray-500 mt-1 text-right">
                        {{ $message->created_at->format('M d, Y H:i A') }}
                        @if ($message->sender_id == Auth::id() && $message->read_at)
                            <span class="ml-2 text-green-500">&#10003;&#10003;</span> <!-- Double tick for read -->
                        @elseif ($message->sender_id == Auth::id())
                            <span class="ml-2 text-gray-400">&#10003;</span> <!-- Single tick for sent -->
                        @endif
                    </p>
                </div>
            @empty
                <p class="text-gray-500 text-center">No messages yet. Start the conversation!</p>
            @endforelse
        </div>

        <!-- Message Input Form -->
        @if ($agreement->status === 'active')
            <form method="POST" action="{{ route('agreements.messages.store', $agreement) }}" class="bg-white shadow-md rounded-lg p-6">
                @csrf
                <div class="mb-4">
                    <label for="content" class="block text-gray-700 text-sm font-bold mb-2">Your Message:</label>
                    <textarea name="content" id="content" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('content') border-red-500 @enderror" required>{{ old('content') }}</textarea>
                    @error('content')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Send Message
                    </button>
                </div>
            </form>
        @else
            <p class="text-center text-gray-600 bg-yellow-100 p-4 rounded-lg">Messaging is disabled as this agreement is not currently active.</p>
        @endif
    </div>

    <script>
        // Scroll to the bottom of the message container on page load
        const messageContainer = document.getElementById('message-container');
        if (messageContainer) {
            messageContainer.scrollTop = messageContainer.scrollHeight;
        }
    </script>
</x-layout>
