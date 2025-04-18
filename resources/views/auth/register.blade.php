<x-layout>

    <h1 class="title">Register new account</h1>

    <div class="mx-auto max-w-screen-sm card">

        <form action="{{ route('register') }}" method="post">
            @csrf

            {{-- Full Name --}}
            <div class="mb-4">
                <label for="fullName">Full Name</label>
                <input type="text" name="fullName" value="{{ old('fullName') }}"
                    class="input @error('fullName') ring-red-500 @enderror">
                @error('fullName')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div class="mb-4">
                <label for="email">email</label>
                <input type="text" name="email" value="{{ old('email') }}"
                    class="input @error('email') ring-red-500 @enderror">
                @error('email')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-4">
                <label for="password">Password</label>
                <input type="password" name="password" class="input @error('password') ring-red-500 @enderror">
                @error('password')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div class="mb-4">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" name="password_confirmation"
                    class="input @error('password') ring-red-500 @enderror">
            </div>

            {{-- Address --}}
            <div class="mb-4">
                <label for="address">Address</label>
                <input type="text" name="address" value="{{ old('address') }}"
                    class="input @error('address') ring-red-500 @enderror">
                @error('address')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            {{-- User Title --}}
            <div class="mb-4">
                <label for="userTitle">User Title</label>
                <input type="text" name="userTitle" value="{{ old('userTitle') }}"
                    class="input @error('userTitle') ring-red-500 @enderror">
                @error('userTitle')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Contact Number --}}
            <div class="mb-4">
                <label for="contactNo">Contact Number</label>
                <input type="tel" name="contactNo" value="{{ old('contactNo') }}"
                    class="input @error('contactNo') ring-red-500 @enderror">
                @error('contactNo')
                    <p class="error">Please enter the correct number</p>
                @enderror
            </div>

            {{-- Submit Button --}}
            <button
                class="py-3 px-4 inline-flex items-center gap-x-2 text-xl font-medium rounded-lg border border-transparent bg-blue-100 text-blue-800 hover:bg-blue-200 focus:outline-hidden focus:bg-blue-200 disabled:opacity-50 disabled:pointer-events-none dark:text-blue-400 dark:bg-blue-800/30 dark:hover:bg-blue-800/20 dark:focus:bg-blue-800/20">Register</button>
        </form>

    </div>
</x-layout>
