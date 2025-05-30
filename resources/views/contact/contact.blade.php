<x-layout>
    @push('head')
        <style>
            main.contact-page-main { /* Added specific class to target main on this page */
              background-color: #f5f8fa;
              font-family: 'Segoe UI', sans-serif;
              padding: 2rem 0;
            }
            .feedback-container {
              max-width: 700px;
              margin: 50px auto;
              background: #fff;
              padding: 40px;
              border-radius: 15px;
              box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            }
            .form-title {
              font-size: 28px;
              font-weight: bold;
              color: #1a1a2e;
              text-align: center;
              margin-bottom: 35px;
            }
            .form-control, .form-textarea { /* .form-select removed as it's not used here */
              border-radius: 8px;
              padding: 10px 15px;
              border: 1px solid #ced4da;
              width: 100%;
              margin-bottom: 1rem;
            }
            .form-label {
                display: block;
                margin-bottom: .5rem;
                font-weight: 500;
            }
            .custom-btn { /* This class might conflict if layout.blade.php also defines it. Consider namespacing or ensuring consistency. */
              background-color: #4a90e2;
              color: white;
              padding: 12px 30px;
              font-size: 16px;
              font-weight: 600;
              border: none;
              border-radius: 10px;
              transition: all 0.3s ease;
              cursor: pointer;
            }
            .custom-btn:hover {
              background-color: #3a78c2;
              box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            }
            .custom-btn:active {
              transform: scale(0.98);
            }
            .guest-message {
                text-align: center;
                padding: 20px;
                background-color: #e9ecef;
                border-radius: 8px;
                margin-top: 20px;
                color: #495057;
            }
            .guest-message a {
                color: #007bff;
                text-decoration: underline;
            }
            .guest-message a:hover {
                color: #0056b3;
            }
          </style>
    @endpush

    <!-- Feedback Form -->
    {{-- The <main> tag from the original file is now part of the layout, so we use a div or section here --}}
    {{-- Added a specific class to the main element via @push('head') to style it without affecting other pages using the layout --}}
    <div class="contact-page-main"> {{-- This div will be styled by main.contact-page-main --}}
        <div class="feedback-container">
            <h2 class="form-title flex items-center justify-center">
                <span>Feedback</span>
                <i class="fas fa-comment-dots ml-2"></i>
            </h2>

            @if (session('success'))
                <div class="mb-4 p-3 text-green-700 bg-green-100 border border-green-400 rounded" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-3 text-red-700 bg-red-100 border border-red-400 rounded" role="alert">
                    {{ session('error') }}
                </div>
            @endif
            @if ($errors->any() && !session('success') && !session('error'))
                <div class="mb-4 p-3 text-red-700 bg-red-100 border border-red-400 rounded" role="alert">
                    <p class="font-bold">Please correct the following errors:</p>
                    <ul class="mt-1 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            @auth
                <form method="POST" action="{{ route('submit.contact') }}">
                    @csrf
                    <div>
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control @error('title') border-red-500 @enderror" id="title" name="title" value="{{ old('title') }}" required>
                        @error('title')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-textarea @error('description') border-red-500 @enderror" id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-6 text-center">
                        <button type="submit" class="custom-btn">Submit Feedback</button>
                    </div>
                </form>
            @else
                <div class="guest-message">
                    <p>Please <a href="{{ route('login') }}">login</a> or <a href="{{ route('register') }}">register</a> to submit feedback.</p>
                </div>
            @endauth
        </div>
    </div>
</x-layout>
