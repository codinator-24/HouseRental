<x-layout>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6 text-center">My House Bookings</h1>

        @if($bookings->isEmpty())
        <div class="text-center text-gray-500">
            <p class="text-xl">You have no bookings for your properties yet.</p>
        </div>
    @else
        <div class="flex flex-col items-center space-y-6">
            @foreach($bookings as $booking)
                <div class="bg-white shadow-lg rounded-lg p-6 w-full max-w-3xl relative">
                    @if($booking->house)
                    <a href="" class="absolute top-6 right-6 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold py-2 px-4 rounded">View Details</a>
                    @endif
                    <h2 class="text-xl font-semibold mb-2">Booking for: {{ $booking->house->title ?? 'N/A' }}</h2>
                    <p class="text-gray-700 mb-1"><strong>House:</strong> {{ $booking->house->title ?? 'N/A' }}</p>
                    <p class="text-gray-700 mb-1"><strong>Booking Date:</strong> {{ $booking->created_at->format('Y-m-d H:i') }}</p>
                    @if($booking->tenant)
                    <p class="text-gray-700 mb-1"><strong>From:</strong> {{ $booking->tenant->name }} {{ $booking->tenant->full_name }}</p>
                    @else
                    <p class="text-gray-700 mb-1"><strong>From:</strong> Tenant information not available</p>
                    @endif
                    <hr class="my-2 border-gray-300">
                    <p class="text-gray-700"><strong>Message:</strong><br>{{ $booking->message ?? 'No message provided.' }}</p>
                </div>
            @endforeach
        </div>
    @endif
    </div>
</x-layout>
