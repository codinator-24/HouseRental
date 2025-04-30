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
        <nav>
            <a href="{{ route('home') }}" class="nav-link">Home</a>

            @auth
                <div class="relative grid place-items-center" x-data="{ open: false }">
                    {{-- Dropdown menu button --}}
                    <button type="button" class="flex items-center space-x-2 px-3 py-1 rounded-full hover:bg-slate-700 focus:outline-none focus:bg-slate-700" @click="open = !open">
                        {{-- Image container --}}
                        <div class="w-8 h-8 rounded-full overflow-hidden border-2 border-slate-500">
                            @if(auth()->user()->picture)
                                <img src="{{ asset('storage/' . auth()->user()->picture) }}" alt="{{ auth()->user()->user_name }}'s profile picture" class="w-full h-full object-cover">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->user_name) }}&background=random&size=128" alt="Default avatar" class="w-full h-full object-cover"> {{-- Default avatar using ui-avatars --}}
                            @endif
                        </div>
                        <span class="text-white text-sm font-medium hidden sm:block">{{ auth()->user()->user_name }}</span> {{-- Username, hidden on small screens --}}
                    </button>

                    {{-- Dropdown menu --}}
                    <div class="bg-white shadow-lg absolute top-10 right-0 rounded-lg overflow-hidden font-light"
                        x-show="open" @click.outside="open = false">
                        {{-- Username --}}
                        <a href="{{ route('dashboard') }}"
                            class="block hover:bg-slate-100 pl-4 pr-8 py-2 mb-1">Dashboard</a>

                        {{-- Logout --}}
                        <form action="{{ route('logout') }}" method="post">
                            @csrf
                            <button class="block w-full text-left hover:bg-slate-100 pl-4 pr-8 py-2">Logout</button>
                        </form>
                    </div>
                </div>
            @endauth

            @guest
                <div class="flex items-center gap-4">
                    <a href="{{ route('register') }}" class="nav-link">Register</a>
                    <a href="{{ route('login') }}" class="nav-link">Login</a>
                </div>
            @endguest
        </nav>
    </header>

    <main class="py-8 px-4 mx-auto max-w-screen">
        {{ $slot }}
    </main>

</body>

</html>
