<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> {{ env('APP_NAME') }} </title>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-100 text-shadow-slate-900">

    <header class="bg-slate-800 shadow-lg">
        <nav class="flex items-center justify-between px-4 py-2">
            <!-- Left side -->
            <a href="{{ route('home') }}" class="nav-link">@lang('words.Home')</a>

            <!-- Right side -->
            <div class="flex items-center gap-4">
                <!-- Language Switcher Dropdown -->
                <div class="relative" x-data="{ langOpen: false }">
                    <button @click="langOpen = !langOpen"
                        class="flex items-center text-slate-300 hover:text-white space-x-1 px-3 py-1">
                        <span class="uppercase text-sm">@lang('words.Language')</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="langOpen" @click.outside="langOpen = false"
                        class="absolute right-0 mt-2 w-16 bg-white rounded-md shadow-lg py-1 border border-slate-100">
                        <a href="set/lang/en" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">EN</a>
                        <a href="set/lang/ckb" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">KU</a>
                    </div>
                </div>

                @auth
                    <!-- Existing Profile Dropdown -->
                    <div class="relative grid place-items-center" x-data="{ open: false }">
                        <button type="button"
                            class="flex items-center space-x-2 px-3 py-1 rounded-full hover:bg-slate-700 focus:outline-none focus:bg-slate-700"
                            @click="open = !open">
                            <div class="w-8 h-8 rounded-full overflow-hidden border-2 border-slate-500">
                                @if (auth()->user()->picture)
                                    <img src="{{ asset('storage/' . auth()->user()->picture) }}"
                                        alt="{{ auth()->user()->user_name }}'s profile picture"
                                        class="w-full h-full object-cover">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->user_name) }}&background=random&size=128"
                                        alt="Default avatar" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <span
                                class="text-white text-sm font-medium hidden sm:block">{{ auth()->user()->user_name }}</span>
                        </button>

                        <div class="bg-white shadow-lg absolute top-10 right-0 rounded-lg overflow-hidden font-light"
                            x-show="open" @click.outside="open = false">
                            <a href="{{ route('dashboard') }}"
                                class="block hover:bg-slate-100 pl-4 pr-8 py-2 mb-1">@lang('words.Dashboard')</a>
                            <form action="{{ route('logout') }}" method="post">
                                @csrf
                                <button
                                    class="block w-full text-left hover:bg-slate-100 pl-4 pr-8 py-2">@lang('words.Logout')</button>
                            </form>
                        </div>
                    </div>
                @endauth

                @guest
                    <div class="flex items-center gap-4">
                        <a href="{{ route('register') }}" class="nav-link">@lang('words.Register')</a>
                        <a href="{{ route('login') }}" class="nav-link">@lang('words.Login')</a>
                    </div>
                @endguest
            </div>
        </nav>
    </header>

    <main class="py-8 px-4 mx-auto max-w-screen">
        {{ $slot }}
    </main>

</body>

</html>
