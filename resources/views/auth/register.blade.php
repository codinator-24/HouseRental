<x-layout>

    <h1 class="title">@lang('words.Register_New')</h1>

    <div class="mx-auto max-w-screen-sm card">

        <form action="{{ route('register') }}" method="post" enctype="multipart/form-data">
            @csrf

            {{-- Full Name --}}
            <div class="mb-4">
                <label for="full_name">@lang('words.Full_Name')</label>
                <input type="text" name="full_name" id="full_name" value="{{ old('full_name') }}"
                    class="input @error('full_name') ring-red-500 @enderror">
                @error('full_name')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Username --}}
            <div class="mb-4">
                <label for="user_name">@lang('words.Username')</label>
                <input type="text" name="user_name" id="user_name" value="{{ old('user_name') }}"
                    class="input @error('user_name') ring-red-500 @enderror">
                @error('user_name')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            {{-- First Phone Number --}}
            <div class="mb-4">
                <label for="first_phoneNumber">@lang('words.First_Phno')</label>
                <input type="tel" name="first_phoneNumber" id="first_phoneNumber"
                    value="{{ old('first_phoneNumber') }}"
                    class="input @error('first_phoneNumber') ring-red-500 @enderror">
                @error('first_phoneNumber')
                    <p class="error">{{ $message }}</p> {{-- Use dynamic message --}}
                @enderror
            </div>

            {{-- Second Phone Number --}}
            <div class="mb-4">
                <label for="second_phoneNumber">@lang('words.Second_Phno')</label>
                <input type="tel" name="second_phoneNumber" id="second_phoneNumber"
                    value="{{ old('second_phoneNumber') }}"
                    class="input @error('second_phoneNumber') ring-red-500 @enderror">
                @error('second_phoneNumber')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div class="mb-4">
                <label for="email">@lang('words.Email')</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                    class="input @error('email') ring-red-500 @enderror"> {{-- Changed type to email --}}
                @error('email')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-4">
                <label for="password">@lang('words.Password')</label>
                <input type="password" name="password" id="password"
                    class="input @error('password') ring-red-500 @enderror">
                @error('password')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div class="mb-4">
                <label for="password_confirmation">@lang('words.Confirm_Password')</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="input @error('password') ring-red-500 @enderror">
            </div>

            {{-- Role --}}
            <div class="mb-4">
                <label for="role">@lang('words.Role')</label>
                <select name="role" id="role"
                    class="input appearance-none block px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('role') ring-red-500 border-red-500 @enderror">
                    <option value="tenant" {{ old('role', 'both') == 'tenant' ? 'selected' : '' }}>@lang('words.Tenant')
                    </option>
                    <option value="lordland" {{ old('role', 'both') == 'lordland' ? 'selected' : '' }}>
                        @lang('words.Landlord')
                    </option>
                    <option value="both" {{ old('role', 'both') == 'both' ? 'selected' : '' }}>@lang('words.Both')
                    </option>
                </select>
                @error('role')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Address --}}
            <div class="mb-4">
                <label for="address">@lang('words.Address')</label>
                <input type="text" name="address" id="address" value="{{ old('address') }}"
                    class="input @error('address') ring-red-500 @enderror">
                @error('address')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Picture --}}
            <div class="mb-4">
                <label for="picture">@lang('words.Profile_Picture')</label>
                <div class="flex items-center">
                    <button type="button" onclick="document.getElementById('picture').click()"
                        class="px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-3">Choose
                        File</button>
                    <span id="picture-filename" class="text-sm text-gray-500">No file chosen</span>
                    <input type="file" name="picture" id="picture" class="hidden"
                        onchange="document.getElementById('picture-filename').textContent = this.files[0] ? this.files[0].name : 'No file chosen';">
                </div>
                @error('picture')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit Button --}}
            <button type="submit"
                class="py-3 px-4 inline-flex items-center gap-x-2 text-xl font-medium rounded-lg border border-transparent bg-blue-100 text-blue-800 hover:bg-blue-200 focus:outline-hidden focus:bg-blue-200 disabled:opacity-50 disabled:pointer-events-none dark:text-blue-400 dark:bg-blue-800/30 dark:hover:bg-blue-800/20 dark:focus:bg-blue-800/20">@lang('words.Register')</button>
        </form>

    </div>

    {{-- Simple script to update filename --}}
    <script>
        const pictureInput = document.getElementById('picture');
        const pictureFilenameSpan = document.getElementById('picture-filename');

        if (pictureInput) {
            pictureInput.addEventListener('change', function() {
                pictureFilenameSpan.textContent = this.files[0] ? this.files[0].name : 'No file chosen';
            });
        }
    </script>

</x-layout>
