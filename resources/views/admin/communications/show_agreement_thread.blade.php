<x-adminLayout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Agreement Message Thread') }} - ID: {{ $agreement->id }}
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
                <h3 class="mb-2 text-lg font-semibold">Agreement Details</h3>
                <p><strong>House:</strong> {{ $agreement->booking->house->title ?? 'N/A' }}</p>
                <p><strong>Tenant:</strong> {{ $agreement->tenant->user_name ?? 'N/A' }} (ID: {{ $agreement->tenant_id }})</p>
                <p><strong>Landlord:</strong> {{ $agreement->landlord->user_name ?? 'N/A' }} (ID: {{ $agreement->landlord_id }})</p>
                <p><strong>Status:</strong> {{ ucfirst($agreement->status) }}</p>
            </div>

            <div class="overflow-hidden bg-white rounded-lg shadow-md">
                <div class="p-6 space-y-4 h-[600px] overflow-y-auto">
                    @forelse ($messages as $message)
                        @php
                            $isLandlordSender = $message->sender_id == $agreement->landlord_id;
                            $alignmentClass = $isLandlordSender ? 'justify-content-end' : 'justify-content-start'; // Bootstrap alignment
                            $bubbleClasses = $isLandlordSender ? 'bg-primary text-white' : 'bg-light text-dark';
                            $textAlignClass = $isLandlordSender ? 'text-right' : 'text-left';
                        @endphp
                        <div class="d-flex {{ $alignmentClass }} mb-3"> {{-- Using d-flex for Bootstrap --}}
                            <div class="px-3 py-2 rounded-lg shadow-sm {{ $bubbleClasses }}" style="max-width: 70%;">
                                <p class="text-sm font-weight-bold mb-1">
                                    {{ $message->sender->user_name }}
                                    <span class="text-xs font-weight-normal {{ $isLandlordSender ? 'text-white-50' : 'text-muted' }}">
                                        ({{ $isLandlordSender ? 'Landlord' : 'Tenant' }})
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
                        <p class="text-center text-gray-500">No messages in this agreement thread yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-adminLayout>
