<x-layout>

    <h1 class="title">@lang('words.Welcome_Back')</h1>

    <div class="mx-auto max-w-screen-sm card">

        <form action="{{ route('login') }}" method="post">
            @csrf

            {{-- Email --}}
            <div class="mb-4">
                <label for="email">@lang('words.Email')</label>
                <input type="text" name="email" value="{{ old('email') }}"
                    class="input @error('email') ring-red-500 @enderror">
                @error('email')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-4">
                <label for="password">@lang('words.Password')</label>
                <input type="password" name="password" class="input @error('password') ring-red-500 @enderror">
                @error('password')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Remember me checkbox --}}
            <div class="mb-4 flex">
                <input type="checkbox" name="remember">
                <label for="checkbox" class="ml-2">@lang('words.Remember_Me')</label>
            </div>

            <div class="mb-2">
                @error('failed')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit Button --}}
            <button class="w-full rounded-md bg-blue-600 px-4 py-2 text-center font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50">
                @lang('words.Login')</button>
        </form>

        {{-- Link to Register Page --}}
        <div class="mt-4 text-center">
            <a href="{{ route('register') }}" class="text-sm text-blue-600 hover:underline">
                @lang('') @lang('words.Register')
            </a>
        </div>

    </div>
</x-layout>
