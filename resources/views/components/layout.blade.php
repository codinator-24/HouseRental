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
                    <button type="button" class="round-btn" @click="open = !open">
                        <img src="https://picsum.photos/200" alt="">
                    </button>

                    {{-- Dropdown menu --}}
                    <div class="bg-white shadow-lg absolute top-10 right-0 rounded-lg overflow-hidden font-light"
                        x-show="open" @click.outside="open = false">
                        {{-- Username --}}
                        <p class="username">{{ auth()->user()->fullName }}</p>
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
