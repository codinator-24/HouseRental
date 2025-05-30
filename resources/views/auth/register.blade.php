<x-layout>
    <div class="flex items-center justify-center min-h-screen py-12 bg-gray-100">
        <div class="w-full max-w-3xl p-12 space-y-8 bg-white rounded-lg shadow-md"> {{-- Card size and padding updated --}}
            <h2 class="text-3xl font-bold text-center">Register</h2>
            <form action="{{ route('register') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="grid md:grid-cols-2 gap-6">
                    {{-- Full Name --}}
                    <div>
                        <label for="full_name" class="block text-sm font-medium text-gray-700">@lang('words.Full_Name')</label>
                        <div class="relative mt-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                  <path fill-rule="evenodd" d="M18.685 19.097A9.723 9.723 0 0 0 21.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 0 0 3.065 7.097A9.716 9.716 0 0 0 12 21.75a9.716 9.716 0 0 0 6.685-2.653Zm-12.54-1.285A7.486 7.486 0 0 1 12 15a7.486 7.486 0 0 1 5.855 2.812A8.224 8.224 0 0 1 12 20.25a8.224 8.224 0 0 1-5.855-2.438ZM15.75 9a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" name="full_name" id="full_name" value="{{ old('full_name') }}"
                                class="block w-full pl-10 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('full_name') border-red-500 @enderror">
                        </div>
                        @error('full_name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Username --}}
                    <div>
                        <label for="user_name" class="block text-sm font-medium text-gray-700">@lang('words.Username')</label>
                        <div class="relative mt-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" name="user_name" id="user_name" value="{{ old('user_name') }}"
                                class="block w-full pl-10 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('user_name') border-red-500 @enderror">
                        </div>
                        @error('user_name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- First Phone Number --}}
                    <div>
                        <label for="first_phoneNumber"
                            class="block text-sm font-medium text-gray-700">@lang('words.First_Phno')</label>
                        <div class="relative mt-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                  <path fill-rule="evenodd" d="M1.5 4.5a3 3 0 0 1 3-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 0 1-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 0 0 6.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 0 1 1.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 0 1-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5Z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="tel" name="first_phoneNumber" id="first_phoneNumber"
                                value="{{ old('first_phoneNumber') }}"
                                class="block w-full pl-10 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('first_phoneNumber') border-red-500 @enderror">
                        </div>
                        @error('first_phoneNumber')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Second Phone Number --}}
                    <div>
                        <label for="second_phoneNumber"
                            class="block text-sm font-medium text-gray-700">@lang('words.Second_Phno')</label>
                        <div class="relative mt-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                  <path fill-rule="evenodd" d="M1.5 4.5a3 3 0 0 1 3-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 0 1-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 0 0 6.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 0 1 1.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 0 1-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5Z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="tel" name="second_phoneNumber" id="second_phoneNumber"
                                value="{{ old('second_phoneNumber') }}"
                                class="block w-full pl-10 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('second_phoneNumber') border-red-500 @enderror">
                        </div>
                        @error('second_phoneNumber')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">@lang('words.Email')</label>
                        <div class="relative mt-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                </svg>
                            </div>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                class="block w-full pl-10 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('email') border-red-500 @enderror">
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">@lang('words.Password')</label>
                        <div class="relative mt-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="password" name="password" id="password"
                                class="block w-full pl-10 pr-10 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('password') border-red-500 @enderror">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <button type="button" id="togglePassword" class="text-gray-400 hover:text-gray-600">
                                    <svg id="eyeIcon" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.022 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                    </svg>
                                    <svg id="eyeSlashIcon" class="h-5 w-5 hidden" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M3.28 2.22a.75.75 0 00-1.06 1.06l14.5 14.5a.75.75 0 101.06-1.06l-1.745-1.745a10.029 10.029 0 003.088-3.088 1.752 1.752 0 000-2.312C18.389 7.429 14.473 5 10 5A9.739 9.739 0 005.999 6.38L3.28 2.22zM7.06 8.092A3.248 3.248 0 0010 7.75a3.25 3.25 0 003.25 3.25c0 .338-.052.663-.149.967l-2.071-2.071a1.752 1.752 0 00-.22-.22L7.06 8.092zM10 12.25a2.25 2.25 0 01-2.245-2.066L9.82 8.113a.75.75 0 00-1.06-1.06L6.69 9.123A2.25 2.25 0 0110 12.25z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label for="password_confirmation"
                            class="block text-sm font-medium text-gray-700">@lang('words.Confirm_Password')</label>
                        <div class="relative mt-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="block w-full pl-10 pr-10 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <button type="button" id="toggleConfirmPassword" class="text-gray-400 hover:text-gray-600">
                                    <svg id="eyeIconConfirm" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.022 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                    </svg>
                                    <svg id="eyeSlashIconConfirm" class="h-5 w-5 hidden" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M3.28 2.22a.75.75 0 00-1.06 1.06l14.5 14.5a.75.75 0 101.06-1.06l-1.745-1.745a10.029 10.029 0 003.088-3.088 1.752 1.752 0 000-2.312C18.389 7.429 14.473 5 10 5A9.739 9.739 0 005.999 6.38L3.28 2.22zM7.06 8.092A3.248 3.248 0 0010 7.75a3.25 3.25 0 003.25 3.25c0 .338-.052.663-.149.967l-2.071-2.071a1.752 1.752 0 00-.22-.22L7.06 8.092zM10 12.25a2.25 2.25 0 01-2.245-2.066L9.82 8.113a.75.75 0 00-1.06-1.06L6.69 9.123A2.25 2.25 0 0110 12.25z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Address --}}
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700">@lang('words.Address')</label>
                        <div class="relative mt-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 10.707V16.5A1.5 1.5 0 0115.5 18H14a1 1 0 01-1-1v-2.5a.5.5 0 00-.5-.5h-3a.5.5 0 00-.5.5V17a1 1 0 01-1 1H4.5A1.5 1.5 0 013 16.5v-5.793a1 1 0 01.293-.707l7-7z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" name="address" id="address" value="{{ old('address') }}"
                                class="block w-full pl-10 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('address') border-red-500 @enderror">
                        </div>
                        @error('address')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Role --}}
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700">@lang('words.Role')</label>
                        <div class="relative mt-1">
                             <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                  <path d="M7 8a3 3 0 100-6 3 3 0 000 6zM14.5 9a3.5 3.5 0 100-7 3.5 3.5 0 000 7zM1.396 16.404a.75.75 0 00-.17 1.008A8.963 8.963 0 0010 19.5a8.963 8.963 0 008.774-2.088.75.75 0 00-.17-1.008A7.465 7.465 0 0010 15a7.465 7.465 0 00-8.604 1.404z" />
                                </svg>
                            </div>
                            <select name="role" id="role"
                                class="block w-full pl-10 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('role') border-red-500 @enderror">
                                <option value="" {{ old('role') == '' ? 'selected' : '' }} disabled>--Select Your Role--</option>
                                <option value="tenant" {{ old('role') == 'tenant' ? 'selected' : '' }}>
                                    @lang('words.Tenant')</option>
                                <option value="lordland" {{ old('role') == 'lordland' ? 'selected' : '' }}>
                                    @lang('words.Landlord')</option>
                                <option value="both" {{ old('role') == 'both' ? 'selected' : '' }}>
                                    @lang('words.Both')</option>
                            </select>
                        </div>
                        @error('role')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Picture --}}
                    <div>
                        <label for="picture" class="block text-sm font-medium text-gray-700">@lang('words.Profile_Picture')</label>
                        <div class="relative mt-1 flex items-center">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                               <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                  <path fill-rule="evenodd" d="M1 5.25A2.25 2.25 0 013.25 3h13.5A2.25 2.25 0 0119 5.25v9.5A2.25 2.25 0 0116.75 17H3.25A2.25 2.25 0 011 14.75v-9.5zm1.5 5.81v3.69c0 .414.336.75.75.75h13.5a.75.75 0 00.75-.75v-2.69l-2.22-2.219a.75.75 0 00-1.06 0l-1.91 1.909.47.47a.75.75 0 11-1.06 1.06L6.53 8.091a.75.75 0 00-1.06 0l-2.97 2.97zM12 7a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <label
                                class="pl-10 pr-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 cursor-pointer w-auto">
                                <span>Choose File</span>
                                <input type="file" name="picture" id="picture" class="sr-only" accept="image/*">
                            </label>
                            <span id="picture-filename" class="ml-3 text-sm text-gray-500 self-center truncate">No file chosen</span>
                        </div>
                        <img id="picture-preview" src="#" alt="Profile picture preview" class="mt-2 h-24 w-24 object-cover rounded-md hidden border border-gray-300"/>
                        @error('picture')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- ID Card --}}
                    <div>
                        <label for="IdCard" class="block text-sm font-medium text-gray-700">@lang('words.idCard')</label>
                        <div class="relative mt-1 flex items-center">
                             <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                  <path fill-rule="evenodd" d="M2.5 3A1.5 1.5 0 001 4.5v11A1.5 1.5 0 002.5 17h15a1.5 1.5 0 001.5-1.5v-11A1.5 1.5 0 0017.5 3h-15zM2 4.5a.5.5 0 01.5-.5h15a.5.5 0 01.5.5v11a.5.5 0 01-.5.5h-15a.5.5 0 01-.5-.5v-11zm10.121 1.379a.75.75 0 00-1.242-.02L6.015 10.5H4.75a.75.75 0 000 1.5h1.757l2.063-3.505a.75.75 0 00-.02-1.242zM15.25 12a.75.75 0 000-1.5H12a.75.75 0 000 1.5h3.25z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <label
                                class="pl-10 pr-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 cursor-pointer w-auto">
                                <span>Choose File</span>
                                <input type="file" name="IdCard" id="IdCard" class="sr-only" accept="image/*">
                            </label>
                            <span id="idcard-filename" class="ml-3 text-sm text-gray-500 self-center truncate">No file chosen</span>
                        </div>
                        <img id="idcard-preview" src="#" alt="ID Card preview" class="mt-2 h-24 w-auto object-contain rounded-md hidden border border-gray-300" style="max-width: 150px;"/>
                        @error('IdCard') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
                
                {{-- Submit Button --}}
                <div class="pt-5">
                    <button type="submit"
                        class="w-full px-4 py-2 font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Submit
                    </button>
                </div>
            </form>

            {{-- Link to Login Page --}}
            <p class="mt-8 text-sm text-center text-gray-600">
                Already have an account?
                <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:underline">
                    Login
                </a>
            </p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // File input filename display and preview
            const pictureInput = document.getElementById('picture');
            const pictureFilenameSpan = document.getElementById('picture-filename');
            const picturePreview = document.getElementById('picture-preview');
            const idCardInput = document.getElementById('IdCard');
            const idCardFilenameSpan = document.getElementById('idcard-filename');
            const idCardPreview = document.getElementById('idcard-preview');

            function setupImagePreview(inputElement, filenameSpanElement, previewElement) {
                if (inputElement && filenameSpanElement && previewElement) {
                    inputElement.addEventListener('change', function() {
                        filenameSpanElement.textContent = this.files && this.files.length > 0 ? this.files[0].name : 'No file chosen';
                        if (this.files && this.files[0]) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                previewElement.src = e.target.result;
                                previewElement.classList.remove('hidden');
                            }
                            reader.readAsDataURL(this.files[0]);
                        } else {
                            previewElement.src = '#';
                            previewElement.classList.add('hidden');
                        }
                    });
                }
            }

            setupImagePreview(pictureInput, pictureFilenameSpan, picturePreview);
            setupImagePreview(idCardInput, idCardFilenameSpan, idCardPreview);

            // Password visibility toggle
            const passwordInput = document.getElementById('password');
            const togglePasswordButton = document.getElementById('togglePassword');
            const eyeIcon = document.getElementById('eyeIcon');
            const eyeSlashIcon = document.getElementById('eyeSlashIcon');

            if (passwordInput && togglePasswordButton && eyeIcon && eyeSlashIcon) {
                togglePasswordButton.addEventListener('click', function () {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    eyeIcon.classList.toggle('hidden');
                    eyeSlashIcon.classList.toggle('hidden');
                });
            }

            // Confirm Password visibility toggle
            const confirmPasswordInput = document.getElementById('password_confirmation');
            const toggleConfirmPasswordButton = document.getElementById('toggleConfirmPassword');
            const eyeIconConfirm = document.getElementById('eyeIconConfirm');
            const eyeSlashIconConfirm = document.getElementById('eyeSlashIconConfirm');

            if (confirmPasswordInput && toggleConfirmPasswordButton && eyeIconConfirm && eyeSlashIconConfirm) {
                toggleConfirmPasswordButton.addEventListener('click', function () {
                    const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    confirmPasswordInput.setAttribute('type', type);
                    eyeIconConfirm.classList.toggle('hidden');
                    eyeSlashIconConfirm.classList.toggle('hidden');
                });
            }
        });
    </script>
</x-layout>
