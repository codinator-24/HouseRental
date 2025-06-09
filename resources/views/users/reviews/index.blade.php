<x-layout>
    <div class="container px-4 mx-auto sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">My Reviews & Ratings</h1>

        <!-- Section for Pending Reviews (Bookings to Review) -->
        <section class="mb-12">
            <h2 class="text-2xl font-semibold text-gray-700 mb-6">Rate Your Past Stays</h2>
            @if($bookings->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($bookings as $booking)
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
                            @if($booking->house)
                                @php
                                    $imageUrl = $booking->house->pictures->first()?->image_url ? asset($booking->house->pictures->first()->image_url) : 'https://images.pexels.com/photos/731082/pexels-photo-731082.jpeg';
                                @endphp
                                <a href="{{ route('house.details', $booking->house) }}">
                                    <img src="{{ $imageUrl }}" alt="{{ $booking->house->title }}" class="w-full h-48 object-cover">
                                </a>
                                <div class="p-5">
                                    <h3 class="text-lg font-bold text-gray-800 mb-2 truncate" title="{{ $booking->house->title }}">
                                        <a href="{{ route('house.details', $booking->house) }}" class="hover:underline">
                                            {{ Str::limit($booking->house->title, 40) }}
                                        </a>
                                    </h3>
                                    <p class="text-sm text-gray-500 mb-1">
                                        Booked on: {{ $booking->created_at->format('M d, Y') }}
                                    </p>
                                    <p class="text-sm text-gray-500 mb-3">
                                        Duration: {{ $booking->month_duration }} {{ Str::plural('month', $booking->month_duration) }}
                                    </p>
                                    <a href="{{ route('reviews.create', $booking) }}" class="inline-block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-300">
                                        Write a Review
                                    </a>
                                </div>
                            @else
                                <div class="p-5">
                                    <p class="text-red-500">Associated house details are unavailable.</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-lg shadow-md p-8 border border-gray-200 text-center">
                    <i class="fas fa-inbox fa-3x text-gray-400 mb-4"></i>
                    <p class="text-lg font-medium text-gray-700">No Pending Reviews</p>
                    <p class="text-sm text-gray-500 mt-1">You have no completed stays that are pending a review.</p>
                </div>
            @endif
        </section>

        <!-- Section for Submitted Reviews -->
        <section>
            <h2 class="text-2xl font-semibold text-gray-700 mb-6">My Submitted Reviews</h2>
            @if($submittedReviews->isNotEmpty())
                <div class="space-y-6">
                    @foreach ($submittedReviews as $review)
                        <div class="bg-white rounded-xl shadow-lg p-5 border border-gray-200">
                            <div class="flex flex-col sm:flex-row justify-between sm:items-start">
                                <div class="mb-4 sm:mb-0">
                                    @if($review->house)
                                        <h3 class="text-xl font-bold text-gray-800 mb-1">
                                            <a href="{{ route('house.details', $review->house) }}" class="hover:underline">
                                                {{ $review->house->title }}
                                            </a>
                                        </h3>
                                    @else
                                        <h3 class="text-xl font-bold text-red-600 mb-1">Property Unavailable</h3>
                                    @endif
                                    <p class="text-sm text-gray-500">Reviewed on: {{ $review->created_at->format('M d, Y') }}</p>
                                </div>
                                <div class="flex items-center mb-2 sm:mb-0">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                    @endfor
                                    <span class="ml-2 text-sm font-medium text-gray-600">({{ $review->rating }}/5)</span>
                                </div>
                            </div>
                            @if($review->comment)
                                <p class="text-gray-600 mt-3 leading-relaxed">{{ $review->comment }}</p>
                            @endif
                            <div class="mt-4">
                                @if($review->is_approved)
                                    <span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 border border-green-200 rounded-full">Approved</span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 border border-yellow-200 rounded-full">Pending Approval</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-8">
                    {{ $submittedReviews->links() }}
                </div>
            @else
                <div class="bg-white rounded-lg shadow-md p-8 border border-gray-200 text-center">
                    <i class="fas fa-comments fa-3x text-gray-400 mb-4"></i>
                    <p class="text-lg font-medium text-gray-700">No Reviews Submitted Yet</p>
                    <p class="text-sm text-gray-500 mt-1">You haven't written any reviews yet.</p>
                </div>
            @endif
        </section>
    </div>
</x-layout>
