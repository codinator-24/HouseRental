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
            <button
                class="py-3 px-4 inline-flex items-center gap-x-2 text-xl font-medium rounded-lg border border-transparent bg-blue-100 text-blue-800 hover:bg-blue-200 focus:outline-hidden focus:bg-blue-200 disabled:opacity-50 disabled:pointer-events-none dark:text-blue-400 dark:bg-blue-800/30 dark:hover:bg-blue-800/20 dark:focus:bg-blue-800/20">@lang('words.Login')</button>
        </form>

    </div>
</x-layout>
