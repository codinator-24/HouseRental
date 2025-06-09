<x-layout>
    <div class="container px-4 mx-auto sm:px-6 lg:px-8 py-8">
        <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-lg p-6 md:p-8 border border-gray-200">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2">Write a Review</h1>
            @if($booking->house)
                <p class="text-gray-600 mb-6">For: 
                    <a href="{{ route('house.details', $booking->house) }}" class="text-blue-600 hover:underline font-semibold">
                        {{ $booking->house->title }}
                    </a>
                </p>
            @else
                <p class="text-red-500 mb-6">House details are unavailable for this booking.</p>
            @endif

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded" role="alert">
                    <p class="font-bold">Please correct the following errors:</p>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('reviews.store', $booking) }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label for="rating" class="block text-sm font-medium text-gray-700 mb-2">Your Rating (1-5 Stars)</label>
                    <div class="flex items-center space-x-1" id="star-rating-input">
                        @for ($i = 1; $i <= 5; $i++)
                            <button type="button" class="star text-3xl text-gray-300 hover:text-yellow-400 transition-colors duration-150" data-value="{{ $i }}" title="{{ $i }} star{{ $i > 1 ? 's' : '' }}">
                                &#9733; <!-- Star Unicode Character -->
                            </button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="rating" value="{{ old('rating') }}">
                    @error('rating')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="comment" class="block text-sm font-medium text-gray-700 mb-1">Your Comment (Optional)</label>
                    <textarea name="comment" id="comment" rows="5" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm placeholder-gray-400" placeholder="Share your experience...">{{ old('comment') }}</textarea>
                    @error('comment')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end space-x-4">
                    <a href="{{ route('reviews.my') }}" class="text-sm text-gray-600 hover:text-gray-800 transition-colors">Cancel</a>
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-300">
                        Submit Review
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const stars = document.querySelectorAll('#star-rating-input .star');
            const ratingInput = document.getElementById('rating');
            const initialRating = parseInt(ratingInput.value) || 0;

            function setActiveStars(rating) {
                stars.forEach(star => {
                    if (parseInt(star.dataset.value) <= rating) {
                        star.classList.remove('text-gray-300');
                        star.classList.add('text-yellow-400');
                    } else {
                        star.classList.remove('text-yellow-400');
                        star.classList.add('text-gray-300');
                    }
                });
            }

            // Set initial stars if old('rating') has a value
            if (initialRating > 0) {
                setActiveStars(initialRating);
            }

            stars.forEach(star => {
                star.addEventListener('click', function () {
                    const value = parseInt(this.dataset.value);
                    ratingInput.value = value;
                    setActiveStars(value);
                });

                // Optional: hover effect to show potential rating
                star.addEventListener('mouseover', function () {
                    const hoverValue = parseInt(this.dataset.value);
                    stars.forEach(s => {
                        if (parseInt(s.dataset.value) <= hoverValue) {
                            s.classList.remove('text-gray-300');
                            s.classList.add('text-yellow-400');
                        } else {
                            s.classList.remove('text-yellow-400');
                            s.classList.add('text-gray-300');
                        }
                    });
                });

                star.addEventListener('mouseout', function () {
                    // Reset to current selected rating on mouseout
                    const currentRating = parseInt(ratingInput.value) || 0;
                    setActiveStars(currentRating);
                });
            });
        });
    </script>
    @endpush
</x-layout>
