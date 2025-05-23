<x-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-100 border-b border-gray-200">
                <h1 class="text-xl font-semibold text-gray-700">Update Your Profile</h1>
            </div>

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="px-6 py-6">
                @csrf {{-- CSRF Protection --}}
                @method('PUT') {{-- Method Spoofing for PUT request --}}

                {{-- Success Message --}}
                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Password Update Success Message --}}
                @if (session('password_success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded" role="alert">
                        {{ session('password_success') }}
                    </div>
                @endif

                {{-- Display Validation Errors for Password Update --}}
                @if ($errors->updatePassword->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded" role="alert">
                        <p class="font-bold">Please correct the following errors:</p>
                        <ul>
                            @foreach ($errors->updatePassword->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


                {{-- Profile Picture --}}
                <div class="mb-6 text-center">
                    <label for="picture" class="block text-sm font-medium text-gray-700 mb-2">Profile Picture</label>
                    @if ($user->picture)
                        <img src="{{ Storage::url($user->picture) }}" alt="Current Profile Picture"
                            class="w-32 h-32 rounded-full mx-auto mb-4 object-cover border border-gray-300">
                    @else
                        <div
                            class="w-32 h-32 rounded-full mx-auto mb-4 bg-gray-200 flex items-center justify-center text-gray-500">
                            No Image
                        </div>
                    @endif
                    <input type="file" name="picture" id="picture"
                        class="mt-1 block w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-semibold
                        file:bg-blue-50 file:text-blue-700
                        hover:file:bg-blue-100
                    " />
                    @error('picture')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <p>Status:
                        @if ($user->status === 'Not Verified')
                            <span class="font-semibold text-yellow-600">Not Verified</span>
                        @elseif ($user->status === 'Verified')
                            <span class="font-semibold text-green-600">Verified</span>
                        @else
                            <span class="font-semibold text-green-600">{{ ucfirst($user->status) }}</span>
                        @endif
                    </p>
                </div>
                {{-- Full Name --}}
                <div class="mb-4">
                    <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" name="full_name" id="full_name"
                        value="{{ old('full_name', $user->full_name) }}" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('full_name') border-red-500 @enderror">
                    @error('full_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- User Name --}}
                <div class="mb-4">
                    <label for="user_name" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" name="user_name" id="user_name"
                        value="{{ old('user_name', $user->user_name) }}" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('user_name') border-red-500 @enderror">
                    @error('user_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                        required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- First Phone Number --}}
                <div class="mb-4">
                    <label for="first_phoneNumber" class="block text-sm font-medium text-gray-700">Primary Phone
                        Number</label>
                    <input type="tel" name="first_phoneNumber" id="first_phoneNumber"
                        value="{{ old('first_phoneNumber', $user->first_phoneNumber) }}" required pattern="07[0-9]{9}"
                        title="Format: 07xxxxxxxx"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('first_phoneNumber') border-red-500 @enderror">
                    @error('first_phoneNumber')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Second Phone Number --}}
                <div class="mb-4">
                    <label for="second_phoneNumber" class="block text-sm font-medium text-gray-700">Secondary Phone
                        Number (Optional)</label>
                    <input type="tel" name="second_phoneNumber" id="second_phoneNumber"
                        value="{{ old('second_phoneNumber', $user->second_phoneNumber) }}" pattern="07[0-9]{9}"
                        title="Format: 07xxxxxxxx"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('second_phoneNumber') border-red-500 @enderror">
                    @error('second_phoneNumber')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ID Card --}}
                <div class="mb-6">
                    <p class="block text-sm font-medium text-gray-700 mb-2">ID Card</p>
                    @if ($user->IdCard)
                        @php
                            $idCardPath = $user->IdCard;
                            $idCardUrl = Storage::url($idCardPath);
                            $idCardExtension = strtolower(pathinfo($idCardPath, PATHINFO_EXTENSION));
                            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                        @endphp
                        <div class="mb-2">
                            <p class="block text-xs font-medium text-gray-500">Current ID Card:</p>
                            @if (in_array($idCardExtension, $imageExtensions))
                                <img src="{{ $idCardUrl }}" alt="Current ID Card"
                                    class="mt-1 max-w-xs max-h-48 border border-gray-300 rounded">
                            @elseif ($idCardExtension === 'pdf')
                                <a href="{{ $idCardUrl }}" target="_blank"
                                    class="mt-1 text-blue-600 hover:underline">View ID Card (PDF)</a>
                            @else
                                <a href="{{ $idCardUrl }}" target="_blank"
                                    class="mt-1 text-blue-600 hover:underline">Download ID Card</a>
                            @endif
                        </div>
                    @else
                        <p class="text-sm text-gray-500 mt-1">No ID Card uploaded.</p>
                    @endif
                </div>

                {{-- Address --}}
                <div class="mb-6">
                    <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                    <textarea name="address" id="address" rows="3" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('address') border-red-500 @enderror">{{ old('address', $user->address) }}</textarea>
                    @error('address')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit Button --}}
                <div class="flex justify-end">
                    <button type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Update Profile
                    </button>
                </div>
            </form>
        </div>

        {{-- Button to open password modal --}}
        <div class="max-w-2xl mx-auto mt-8 text-center">
            <button id="openPasswordModalBtn"
                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                Change Password
            </button>
        </div>

        {{-- Password Update Modal --}}
        <div id="passwordUpdateModal" class="fixed inset-0 z-50 flex items-center justify-center bg-opacity-10"
            style="display: none;">
            <div class="bg-white rounded-lg shadow-xl overflow-hidden max-w-md w-full mx-4">
                <div class="px-6 py-4 bg-gray-100 border-b border-gray-200 flex justify-between items-center">
                    <h1 class="text-xl font-semibold text-gray-700">Update Your Password</h1>
                    <button id="closePasswordModalBtn"
                        class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
                </div>

                <form method="POST" action="{{ route('password.update') }}" class="px-6 py-6">
                    @csrf {{-- CSRF Protection --}}
                    @method('PUT') {{-- Method Spoofing for PUT request --}}

                    {{-- Current Password --}}
                    <div class="mb-4">
                        <label for="current_password" class="block text-sm font-medium text-gray-700">Current
                            Password</label>
                        <input type="password" name="current_password" id="current_password" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('current_password', 'updatePassword') border-red-500 @enderror">
                        @error('current_password', 'updatePassword')
                            {{-- Specify the error bag --}}
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- New Password --}}
                    <div class="mb-4">
                        <label for="new_password" class="block text-sm font-medium text-gray-700">New Password</label>
                        <input type="password" name="new_password" id="new_password" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('new_password', 'updatePassword') border-red-500 @enderror">
                        @error('new_password', 'updatePassword')
                            {{-- Specify the error bag --}}
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Confirm New Password --}}
                    <div class="mb-6">
                        <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">Confirm
                            New
                            Password</label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                            required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        {{-- No specific error needed here as 'confirmed' rule handles it on 'new_password' --}}
                    </div>

                    {{-- Submit Button --}}
                    <div class="flex justify-end">
                        <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div> {{-- Close modal content div --}}
    </div> {{-- Close modal container div --}}
    </div>

    {{-- Add this script at the bottom --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const openBtn = document.getElementById('openPasswordModalBtn');
            const closeBtn = document.getElementById('closePasswordModalBtn');
            const modal = document.getElementById('passwordUpdateModal');

            if (openBtn && closeBtn && modal) {
                // Open modal
                openBtn.addEventListener('click', function() {
                    modal.style.display = 'flex';
                });

                // Close modal via button
                closeBtn.addEventListener('click', function() {
                    modal.style.display = 'none';
                });

                // Close modal by clicking outside
                modal.addEventListener('click', function(event) {
                    if (event.target === modal) { // Check if the click is on the background overlay
                        modal.style.display = 'none';
                    }
                });
            }

            // Keep modal open if there are password validation errors
            @if ($errors->updatePassword->any())
                modal.style.display = 'flex';
            @endif
        });
    </script>
</x-layout>
