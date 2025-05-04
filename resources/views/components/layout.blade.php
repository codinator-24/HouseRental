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

<body class="bg-white text-gray-800">

    <header class="bg-white shadow-md sticky top-0 z-50">
        <nav class="container mx-auto px-6 py-3 flex items-center justify-between">
            <!-- Left side: Logo -->
            <a href="{{ route('home') }}" class="text-xl font-bold text-blue-600">HouseRental</a>

            <!-- Center: Navigation Links -->
            <div class="hidden md:flex items-center space-x-6">
                <a href="{{ route('home') }}"
                    class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">@lang('words.Home')</a>
                <a href="#"
                    class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">@lang('words.About')</a>
                {{-- Add other navigation links here if needed --}}
            </div>

            <!-- Right side: Auth, Language -->
            <div class="flex items-center gap-4">
                @guest
                    <div class="hidden md:flex items-center gap-2">
                        <a href="{{ route('login') }}"
                            class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">@lang('words.Login')</a>
                        <a href="{{ route('register') }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">@lang('words.Register')</a>
                    </div>
                @endguest

                <!-- Language Switcher Dropdown -->
                <div class="relative" x-data="{ langOpen: false }">
                    <button @click="langOpen = !langOpen"
                        class="flex items-center text-gray-600 hover:text-blue-600 space-x-1 px-3 py-1 focus:outline-none">
                        {{-- Simple Globe Icon --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                        </svg>
                        {{-- <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg> --}}
                    </button>

                    <div x-show="langOpen" @click.outside="langOpen = false"
                        class="absolute right-0 mt-2 w-20 bg-white rounded-md shadow-lg py-1 border border-gray-200 z-10">
                        <a href="set/lang/en" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">EN</a>
                        <a href="set/lang/ckb" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">KU</a>
                    </div>
                </div>

                @auth
                    <!-- Profile Dropdown -->
                    <div class="relative grid place-items-center" x-data="{ open: false }">
                        <button type="button"
                            class="flex items-center space-x-2 px-3 py-1 rounded-full hover:bg-gray-100 focus:outline-none"
                            @click="open = !open">
                            <div class="w-8 h-8 rounded-full overflow-hidden border-2 border-gray-300">
                                @if (auth()->user()->picture)
                                    <img src="{{ asset('storage/' . auth()->user()->picture) }}"
                                        alt="{{ auth()->user()->user_name }}'s profile picture"
                                        class="w-full h-full object-cover">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->user_name) }}&background=random&size=128&color=ffffff"
                                        alt="Default avatar" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <span
                                class="text-gray-700 text-sm font-medium hidden sm:block">{{ auth()->user()->user_name }}</span>
                        </button>

                        <div class="bg-white shadow-lg absolute top-10 right-0 rounded-lg overflow-hidden font-light z-10 border border-gray-200"
                            x-show="open" @click.outside="open = false">


                            <a href="{{ route('profile.show') }}"
                                class="block hover:bg-slate-100 pl-4 pr-8 py-2 mb-1">Profile</a>

                            <a href="{{ route('Show.house.add') }}"
                                class="block hover:bg-slate-100 pl-4 pr-8 py-2 mb-1">Add House</a>

                            <a href="{{ route('dashboard') }}"
                                class="block hover:bg-slate-100 pl-4 pr-8 py-2 mb-1">@lang('words.Dashboard')</a>


                            <form action="{{ route('logout') }}" method="post">
                                @csrf
                                <button
                                    class="block w-full text-left hover:bg-gray-100 pl-4 pr-8 py-2 text-sm text-gray-700">@lang('words.Logout')</button>
                            </form>
                        </div>
                    </div>
                @endauth

                <!-- Mobile Menu Button (optional) -->
                <div class="md:hidden">
                    {{-- Add a button here to toggle a mobile menu if needed --}}
                </div>
            </div>
        </nav>
    </header>

    <main>
        {{ $slot }}
    </main>


    <footer class="bg-gray-900 text-gray-300 py-12">
        <div class="container mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- HouseRental Info -->
            <div>
                <h3 class="text-lg font-semibold text-white mb-4">HouseRental</h3>
                <p class="text-sm">
                    Finding your perfect home has never been easier. Browse our wide selection of properties and find
                    the one that suits your needs.
                </p>
            </div>

            <!-- Navigation Links -->
            <div>
                <h3 class="text-lg font-semibold text-white mb-4">NAVIGATION</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}" class="hover:text-white">Home</a></li>
                    <li><a href="#" class="hover:text-white">About</a></li>
                    {{-- Add other footer navigation links here --}}
                </ul>
            </div>

            <!-- Legal Links -->
            <div>
                <h3 class="text-lg font-semibold text-white mb-4">LEGAL</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-white">Privacy Policy</a></li>
                    <li><a href="#" class="hover:text-white">Terms of Service</a></li>
                    <li><a href="#" class="hover:text-white">Contact Us</a></li>
                </ul>
            </div>

            <!-- Placeholder for potential 4th column -->
            <div>
                {{-- You can add social media links or other content here --}}
            </div>
        </div>
        <div class="container mx-auto px-6 mt-8 pt-8 border-t border-gray-700 text-center text-sm">
            &copy; {{ date('Y') }} HouseRental. All Rights Reserved.
        </div>
    </footer>

</body>

</html>
