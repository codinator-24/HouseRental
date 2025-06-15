<x-adminLayout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Inquiry Message Thread') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('admin.communications.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to All Threads
                </a>
            </div>

            <div class="p-6 mb-6 bg-white rounded-lg shadow">
                <h3 class="mb-2 text-lg font-semibold">Inquiry Details</h3>
                <p><strong>House:</strong> {{ $house->title }} (ID: {{ $house->id }})</p>
                <p><strong>Landlord:</strong> {{ $house->landlord->user_name ?? 'N/A' }} (ID: {{ $house->landlord_id }})</p>
                <p><strong>Participants:</strong> {{ $userA->user_name }} (ID: {{ $userA->id }}) <--> {{ $userB->user_name }} (ID: {{ $userB->id }})</p>
            </div>

            <div class="overflow-hidden bg-white rounded-lg shadow-md">
                <div class="p-6 space-y-4 h-[600px] overflow-y-auto">
                    @forelse ($messages as $message)
                        @php
                            $isSenderUserA = $message->sender_id == $userA->id;
                            $isSenderUserB = $message->sender_id == $userB->id;
                            
                            // Determine alignment: if sender is userA, align one way, if userB, the other.
                            // Let's arbitrarily decide userA's messages are 'justify-start' (left) 
                            // and userB's are 'justify-end' (right) for admin view.
                            // Or, more consistently, always show landlord on one side.
                            $isLandlordSender = $message->sender_id == $house->landlord_id;
                            $alignmentClass = $isLandlordSender ? 'justify-end' : 'justify-start';
                            $bubbleClasses = $isLandlordSender ? 'bg-primary text-white' : 'bg-light text-dark';
                            $textAlignClass = $isLandlordSender ? 'text-right' : 'text-left';
                        @endphp
                        <div class="flex {{ $alignmentClass }} mb-3">
                            <div class="px-3 py-2 rounded-lg shadow-sm {{ $bubbleClasses }}" style="max-width: 70%;">
                                <p class="text-sm font-weight-bold mb-1">
                                    {{ $message->sender->user_name }}
                                    <span class="text-xs font-weight-normal {{ $isLandlordSender ? 'text-white-50' : 'text-muted' }}">
                                        ({{ $isLandlordSender ? 'Landlord' : 'User' }})
                                    </span>
                                </p>
                                <p class="text-sm mb-1" style="white-space: pre-wrap;">{{ $message->content }}</p>
                                <p class="text-xs {{ $isLandlordSender ? 'text-white-50' : 'text-muted' }} {{ $textAlignClass }}" style="font-size: 0.7rem;">
                                    {{ $message->created_at->format('M d, Y H:i A') }}
                                    @if ($message->read_at)
                                        <i class="bi bi-check2-all" title="Read: {{ $message->read_at->format('M d, H:i') }}"></i>
                                    @endif
                                </p>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500">No messages in this inquiry thread yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-adminLayout>
