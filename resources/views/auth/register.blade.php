<x-layout>
    <div class="flex items-center justify-center min-h-screen py-12 bg-gray-100">
        <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-md">
            <h2 class="text-2xl font-bold text-center">Register</h2>
            <form action="{{ route('register') }}" method="post" enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- Full Name --}}
                <div>
                    <label for="full_name" class="block text-sm font-medium text-gray-700">@lang('words.Full_Name')</label>
                    <input type="text" name="full_name" id="full_name" value="{{ old('full_name') }}" required
                           class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('full_name') border-red-500 @enderror">
                    @error('full_name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Username --}}
                <div>
                    <label for="user_name" class="block text-sm font-medium text-gray-700">@lang('words.Username')</label>
                    <input type="text" name="user_name" id="user_name" value="{{ old('user_name') }}" required
                           class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('user_name') border-red-500 @enderror">
                    @error('user_name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- First Phone Number --}}
                <div>
                    <label for="first_phoneNumber" class="block text-sm font-medium text-gray-700">@lang('words.First_Phno')</label>
                    <input type="tel" name="first_phoneNumber" id="first_phoneNumber" value="{{ old('first_phoneNumber') }}" required
                           class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('first_phoneNumber') border-red-500 @enderror">
                    @error('first_phoneNumber')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Second Phone Number --}}
                <div>
                    <label for="second_phoneNumber" class="block text-sm font-medium text-gray-700">@lang('words.Second_Phno')</label>
                    <input type="tel" name="second_phoneNumber" id="second_phoneNumber" value="{{ old('second_phoneNumber') }}"
                           class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('second_phoneNumber') border-red-500 @enderror">
                    @error('second_phoneNumber')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">@lang('words.Email')</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                           class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">@lang('words.Password')</label>
                    <input type="password" name="password" id="password" required
                           class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">@lang('words.Confirm_Password')</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                           class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>

                {{-- Role --}}
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700">@lang('words.Role')</label>
                    <select name="role" id="role" required
                            class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('role') border-red-500 @enderror">
                        <option value="tenant" {{ old('role', 'both') == 'tenant' ? 'selected' : '' }}>@lang('words.Tenant')</option>
                        <option value="lordland" {{ old('role', 'both') == 'lordland' ? 'selected' : '' }}>@lang('words.Landlord')</option>
                        <option value="both" {{ old('role', 'both') == 'both' ? 'selected' : '' }}>@lang('words.Both')</option>
                    </select>
                    @error('role')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Address --}}
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700">@lang('words.Address')</label>
                    <input type="text" name="address" id="address" value="{{ old('address') }}" required
                           class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('address') border-red-500 @enderror">
                    @error('address')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Picture --}}
                <div>
                    <label for="picture" class="block text-sm font-medium text-gray-700">@lang('words.Profile_Picture')</label>
                    <div class="flex items-center mt-1">
                        <label class="px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 cursor-pointer">
                            <span>Choose File</span>
                            <input type="file" name="picture" id="picture" class="sr-only"
                                   onchange="document.getElementById('picture-filename').textContent = this.files[0] ? this.files[0].name : 'No file chosen';">
                        </label>
                        <span id="picture-filename" class="ml-3 text-sm text-gray-500">No file chosen</span>
                    </div>
                    @error('picture')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit Button --}}
                <div>
                    <button type="submit"
                            class="w-full px-4 py-2 font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Submit
                    </button>
                </div>
            </form>

            {{-- Link to Login Page --}}
            <p class="mt-6 text-sm text-center text-gray-600">
                Already have an account?
                <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:underline">
                    Login
                </a>
            </p>
        </div>
    </div>

    {{-- Simple script to update filename --}}
    <script>
        const pictureInput = document.getElementById('picture');
        const pictureFilenameSpan = document.getElementById('picture-filename');

        if (pictureInput) {
            pictureInput.addEventListener('change', function() {
                if (this.files && this.files.length > 0) {
                    pictureFilenameSpan.textContent = this.files[0].name;
                } else {
                    pictureFilenameSpan.textContent = 'No file chosen';
                }
            });
        }
    </script>
</x-layout>
