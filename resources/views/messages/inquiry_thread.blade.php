<x-layout>
    <div class="container px-4 py-8 mx-auto sm:px-6 lg:px-8">
        {{-- Back Button --}}
        <div class="mb-6">
            <a href="{{ route('house.details', $house) }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                @lang('words.details_house_back_to_listings')
            </a>
        </div>

        <h1 class="flex items-center mb-2 text-2xl font-semibold text-gray-800">
            <span>@lang('words.inquiry_thread_title_prefix') <a href="{{ route('house.details', $house) }}" class="text-blue-600 hover:underline">{{ $house->title }}</a></span>
            @if (isset($otherParty) && $otherParty)
                <span class="mx-2">@lang('words.inquiry_thread_with_user_prefix')</span>
                <span>{{ $otherParty->user_name }}</span>
            @endif
        </h1>

        <div class="p-3 mb-4 text-sm text-yellow-800 bg-yellow-50 border-l-4 border-yellow-400 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.21 3.03-1.742 3.03H4.42c-1.532 0-2.492-1.696-1.742-3.03l5.58-9.92zM10 13a1 1 0 110-2 1 1 0 010 2zm-1-3a1 1 0 00-1 1v1a1 1 0 102 0v-1a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        <span class="font-semibold">@lang('words.privacy_warning_title'):</span>
                        @lang('words.privacy_warning_message')
                    </p>
                </div>
            </div>
        </div>

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
                {{-- The $otherParty is the recipient of the message being sent from this form --}}
                <form action="{{ route('messages.inquiry.thread.store', ['house' => $house, 'otherUser' => $otherParty]) }}" method="POST">
                    @csrf
                    {{-- No need for receiver_id_for_reply as $otherParty is now the explicit receiver in the route --}}

                    <div class="flex items-center space-x-3">
                        <textarea name="content" rows="1"
                            class="flex-1 block w-full px-4 py-3 text-base text-gray-900 border-gray-300 rounded-full shadow-sm resize-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="@lang('words.inquiry_message_placeholder')" required style="line-height: 1.5rem;">{{ old('content') }}</textarea>
                        <button type="submit"
                            class="inline-flex items-center justify-center w-12 h-12 text-white bg-blue-600 rounded-full shadow-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="text-xl fas fa-paper-plane"></i>
                        </button>
                        @auth
                            @if (Auth::id() !== $otherParty->id)
                                <button type="button" id="openReportUserModalBtn" class="inline-flex items-center justify-center w-12 h-12 text-gray-500 bg-gray-200 rounded-full hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <i class="fas fa-flag"></i>
                                </button>
                            @endif
                        @endauth
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

            // Report User Modal Logic
            const openReportUserBtn = document.getElementById('openReportUserModalBtn');
            const closeReportUserBtn = document.getElementById('closeReportUserModalBtn');
            const reportUserModal = document.getElementById('reportUserModal');

            if (openReportUserBtn && closeReportUserBtn && reportUserModal) {
                openReportUserBtn.addEventListener('click', function() {
                    reportUserModal.style.display = 'flex';
                });

                closeReportUserBtn.addEventListener('click', function() {
                    reportUserModal.style.display = 'none';
                });

                reportUserModal.addEventListener('click', function(event) {
                    if (event.target === reportUserModal) {
                        reportUserModal.style.display = 'none';
                    }
                });

                document.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape' && reportUserModal.style.display === 'flex') {
                        reportUserModal.style.display = 'none';
                    }
                });
            }
        });
    </script>
    @endpush

    {{-- Report User Modal --}}
    @if (isset($otherParty))
    <div id="reportUserModal" class="fixed inset-0 z-50 flex items-center justify-center bg-opacity-50 backdrop-blur-sm" style="display: none;">
        <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-xl">
            <div class="flex items-center justify-between pb-3 border-b">
                <h2 class="text-xl font-semibold">@lang('words.report_user_modal_title') {{ $otherParty->user_name }}</h2>
                <button id="closeReportUserModalBtn" class="text-2xl text-gray-500 hover:text-gray-800">&times;</button>
            </div>
            <form action="{{ route('report.user', ['reportedUser' => $otherParty->id]) }}" method="POST" class="mt-4">
                @csrf
                <input type="hidden" name="house_id" value="{{ $house->id }}">
                <div class="mb-4">
                    <label for="reason_category" class="block text-sm font-medium text-gray-700">@lang('words.report_modal_label_reason')</label>
                    <select name="reason_category" id="reason_category" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="Landlord Behavior">@lang('words.report_reason_landlord_behavior')</option>
                        <option value="Scam/Fraud">@lang('words.report_reason_scam_fraud')</option>
                        <option value="Other">@lang('words.report_reason_other')</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">@lang('words.add_house_section_description')</label>
                    <textarea name="description" id="description" rows="4" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required></textarea>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 text-white bg-red-600 rounded-md hover:bg-red-700">@lang('words.report_modal_submit_button')</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</x-layout>
