<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> {{ env('APP_NAME') }} </title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css" integrity="sha512-Cv93isEugYPAeDxPMAvkH0XCBsZRpEDcXeL3nwUbYwNEKh7RRe6FTKiVXRZBStQFOxK+L3iP8Nw9M2r8MhXoGA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    @stack('head')
</head>

<body class="text-gray-800 bg-white">

    <header class="sticky top-0 z-50 bg-white shadow-md" x-data="{ mobileMenuOpen: false }">
        <nav class="container flex items-center justify-between px-6 py-3 mx-auto">
            <!-- Left side: Logo -->
            <a href="{{ route('home') }}" class="flex items-center text-xl font-bold text-blue-600">
                <img src="{{ asset('images/logo.png') }}" alt="ORS Logo" class="w-auto h-8 mr-2"> ORS
            </a>

            <!-- Center: Desktop Navigation Links -->
            <div class="items-center hidden space-x-6 md:flex">
                <a href="{{ route('home') }}"
                    class="px-3 py-2 text-sm font-medium text-gray-600 rounded-md hover:text-blue-600">@lang('words.Home')</a>
                <a href="#"
                    class="px-3 py-2 text-sm font-medium text-gray-600 rounded-md hover:text-blue-600">@lang('words.About')</a>
                <a href="{{ route('contact') }}"
                    class="px-3 py-2 text-sm font-medium text-gray-600 rounded-md hover:text-blue-600">@lang('words.Feedback')</a>
                {{-- Add other navigation links here if needed --}}
            </div>

            <!-- Right side: Auth, Language, Mobile Menu Button -->
            <div class="flex items-center gap-4">
                <!-- Desktop Auth Links -->
                @guest
                    <div class="items-center hidden gap-2 md:flex">
                        <a href="{{ route('login') }}"
                            class="px-3 py-2 text-sm font-medium text-gray-600 rounded-md hover:text-blue-600">@lang('words.Login')</a>
                        <a href="{{ route('register') }}"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">@lang('words.Register')</a>
                    </div>
                @endguest

                <!-- Language Switcher Dropdown (Desktop and Mobile) -->
                <!-- This will be duplicated into the mobile menu below, or we can make this one work for both -->
                <div class="relative hidden md:block" x-data="{ langOpen: false }">
                    <button @click="langOpen = !langOpen"
                        class="flex items-center px-3 py-1 space-x-1 text-gray-600 rounded hover:text-blue-600 focus:outline-none hover:bg-gray-100">
                        @if (app()->getLocale() == 'en')
                            <img src="{{ asset('images/flags/ENG.PNG') }}" alt="ENG Flag" class="w-5 h-auto">
                            <span class="hidden ml-1 sm:inline">@lang('words.ENG')</span>
                        @elseif (app()->getLocale() == 'ckb')   
                            <img src="{{ asset('images/flags/KRD.svg') }}" alt="KRD Flag" class="w-5 h-auto">
                            <span class="hidden ml-1 sm:inline">@lang('words.KRD')</span>
                        @else
                            <i class="fa-solid fa-language"></i>
                        @endif
                        <i class="ml-1 fas fa-chevron-down fa-xs"></i>
                    </button>
                    <div x-show="langOpen" @click.outside="langOpen = false"
                        class="absolute right-0 z-20 py-1 mt-2 bg-white border border-gray-200 rounded-md shadow-lg min-w-max"
                        style="display: none;">
                        <a href="{{ url('set/lang/en') }}"
                            class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ app()->getLocale() == 'en' ? 'bg-gray-100 font-semibold' : '' }}">
                            <img src="{{ asset('images/flags/ENG.PNG') }}" alt="ENG Flag" class="w-5 h-auto mr-2"> @lang('words.ENG')
                        </a>
                        <a href="{{ url('set/lang/ckb') }}"
                            class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ app()->getLocale() == 'ckb' ? 'bg-gray-100 font-semibold' : '' }}">
                            <img src="{{ asset('images/flags/KRD.svg') }}" alt="KRD Flag" class="w-5 h-auto mr-2"> @lang('words.KRD')
                        </a>
                    </div>
                </div>

                @auth
                    <!-- Notification Icon with Dropdown -->
                        <div class="relative" x-data="notificationsComponent()" x-init="init()">
                            <button @click="toggleDropdown()" type="button" title="Notifications"
                                class="relative p-2 text-gray-600 rounded-full hover:text-blue-600 focus:outline-none hover:bg-gray-100">
                                <span class="sr-only"> @lang('words.View notifications')</span>
                                <i class="text-xl fas fa-bell"></i>
                                <template x-if="unreadCount > 0">
                                    <span x-text="unreadCount > 9 ? '9+' : unreadCount"
                                        class="absolute flex items-center justify-center w-4 h-4 text-xs text-white bg-red-500 border-white rounded-full -top-1 -right-1"></span>
                                </template>
                            </button>

                            <div x-show="notifOpen" @click.outside="notifOpen = false"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 z-10 mt-2 overflow-y-auto bg-white border border-gray-200 rounded-md shadow-lg w-80 max-h-96">
                                <div class="flex items-center justify-between p-3 border-b border-gray-200">
                                    <h3 class="text-sm font-semibold text-gray-700"> @lang('words.Notifications')</h3>
                                    <button x-show="notifications.length > 0" @click="markAllRead()"
                                        class="text-xs text-blue-600 hover:underline">@lang('words.Clear All')</button>
                                </div>
                                <div class="divide-y divide-gray-100">
                                    <template x-if="loading">
                                        <p class="p-4 text-sm text-center text-gray-500">@lang('words.Loading...')</p>
                                    </template>
                                    <template x-if="!loading && notifications.length === 0">
                                        <p class="p-4 text-sm text-center text-gray-500">@lang('words.No new notifications.')</p>
                                    </template>
                                    <template x-for="notification in notifications" :key="notification.id">
                                        <a :href="notification.link" @click.prevent="handleNotificationClick(notification)"
                                            class="block p-3 cursor-pointer hover:bg-gray-50"
                                            :class="{ 'opacity-60': notification.read_at }">
                                            <p class="text-sm"
                                                :class="notification.read_at ? 'font-normal text-gray-600' :
                                                    'font-medium text-gray-800'"
                                                x-text="notification.message"></p>
                                            <p class="text-xs text-gray-400" x-text="timeAgo(notification.created_at)"></p>
                                        </a>
                                    </template>
                                </div>
                                <div x-show="!loading && (notifications.length > 0 || unreadCount > 0)"
                                    class="p-2 text-center border-t border-gray-200">
                                    {{-- You might want a dedicated page for all notifications e.g. /notifications --}}
                                    <a href="#" class="text-sm text-blue-600 hover:underline">@lang('words.View all notifications')</a>
                                </div>
                            </div>
                        </div>

                        <!-- Profile Dropdown -->
                        <div class="relative grid place-items-center" x-data="{ open: false }">
                            <button type="button"
                                class="flex items-center px-3 py-1 space-x-2 rounded-full hover:bg-gray-100 focus:outline-none"
                                @click="open = !open">
                                <div class="w-8 h-8 overflow-hidden border-2 border-gray-300 rounded-full">
                                    @if (auth()->user()->picture)
                                        <img src="{{ asset('storage/' . auth()->user()->picture) }}"
                                            alt="{{ auth()->user()->user_name }}'s profile picture"
                                            class="object-cover w-full h-full">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->user_name) }}&background=random&size=128&color=ffffff"
                                            alt="Default avatar" class="object-cover w-full h-full">
                                    @endif
                                </div>
                                <span
                                    class="hidden text-sm font-medium text-gray-700 sm:block">{{ auth()->user()->user_name }}</span>
                            </button>

                            <div class="absolute right-0 z-10 w-56 overflow-hidden font-light bg-white border border-gray-200 rounded-lg shadow-lg top-10"
                                x-show="open" @click.outside="open = false">


                                <a href="{{ route('profile.show') }}"
                                    class="block py-2 pl-4 pr-8 hover:bg-slate-100">@lang('words.Profile')</a>

                                <!-- Houses Dropdown -->
                                {{-- <div x-data="{ housesOpen: false }">
                                    <button @click="housesOpen = !housesOpen"
                                        class="flex items-center justify-between w-full py-2 pl-4 pr-8 text-left hover:bg-slate-100">
                                        <span>@lang('words.Houses')</span>
                                        <svg class="w-4 h-4 transition-transform duration-200 transform"
                                            :class="{ 'rotate-180': housesOpen }" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                    <div x-show="housesOpen" x-transition class="pl-8">
                                        <a href="{{ route('Show.house.add') }}"
                                            class="block py-2 pl-4 pr-8 text-sm text-gray-700 hover:bg-slate-100">@lang('words.Add House')</a>
                                        
                                        <a href="{{ route('my.houses') }}"
                                            class="block py-2 pl-4 pr-8 text-sm text-gray-700 hover:bg-slate-100">@lang('words.My Houses')</a>
                                        
                                        <a href="{{ route('my.bookings') }}"
                                            class="block py-2 pl-4 pr-8 text-sm text-gray-700 hover:bg-slate-100">@lang('words.Booking Lists')</a>

                                        <a href="{{ route('bookings.sent') }}"
                                            class="block py-2 pl-4 pr-8 text-sm text-gray-700 hover:bg-slate-100">@lang('words.Bookings sent')</a>
                                    </div>
                                </div> --}}
                                <!-- End Houses Dropdown -->

                                <a href="{{ route('dashboard') }}"
                                    class="block py-2 pl-4 pr-8 hover:bg-slate-100">@lang('words.Dashboard')</a>


                                <form action="{{ route('logout') }}" method="post">
                                    @csrf
                                    <button
                                        class="block w-full py-2 pl-4 pr-8 text-sm text-left text-gray-700 hover:bg-gray-100">@lang('words.Logout')</button>
                                </form>
                            </div>
                        </div>
                    @endauth

                    <!-- Mobile Menu Button -->
                    <div class="md:hidden">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" type="button"
                            class="inline-flex items-center justify-center p-2 text-gray-600 rounded-md hover:text-blue-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500"
                            aria-controls="mobile-menu" aria-expanded="false">
                            <span class="sr-only">@lang('words.Open main menu')</span>
                            <i class="text-xl fas" :class="{ 'fa-bars': !mobileMenuOpen, 'fa-times': mobileMenuOpen }"></i>
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Mobile menu, show/hide based on menu state. -->
        <div class="md:hidden" id="mobile-menu" x-show="mobileMenuOpen" @click.outside="mobileMenuOpen = false"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            style="display: none;"> {{-- Alpine controls visibility --}}
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="{{ route('home') }}"
                    class="block px-3 py-2 text-base font-medium text-gray-700 rounded-md hover:bg-gray-50 hover:text-blue-600">@lang('words.Home')</a>
                <a href="#"
                    class="block px-3 py-2 text-base font-medium text-gray-700 rounded-md hover:bg-gray-50 hover:text-blue-600">@lang('words.About')</a>
                <a href="{{ route('contact') }}"
                    class="block px-3 py-2 text-base font-medium text-gray-700 rounded-md hover:bg-gray-50 hover:text-blue-600">@lang('Feedback')</a>

                @guest
                    <a href="{{ route('login') }}"
                        class="block px-3 py-2 text-base font-medium text-gray-700 rounded-md hover:bg-gray-50 hover:text-blue-600">@lang('words.Login')</a>
                    <a href="{{ route('register') }}"
                        class="block px-3 py-2 text-base font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">@lang('words.Register')</a>
                @endguest
            </div>
            <!-- Language switcher for mobile -->
            <div class="px-5 pt-2 pb-3 border-t border-gray-200">
                <div class="text-sm font-medium text-gray-500 flex items-center">
                    <i class="fas fa-globe mr-2"></i> <span class="sr-only">@lang('words.Language')</span>
                </div>
                <div class="mt-1 space-y-1" x-data="{ langOpenMobile: false }">
                     <button @click="langOpenMobile = !langOpenMobile"
                        class="flex items-center justify-between w-full px-3 py-2 text-base font-medium text-gray-700 rounded-md hover:bg-gray-50 hover:text-blue-600">
                        <span>
                            @if (app()->getLocale() == 'en')
                                <img src="{{ asset('images/flags/ENG.PNG') }}" alt="ENG Flag" class="inline w-5 h-auto mr-2">
                                <span>ENG</span>
                            @elseif (app()->getLocale() == 'ckb')
                                <img src="{{ asset('images/flags/KRD.svg') }}" alt="KRD Flag" class="inline w-5 h-auto mr-2">
                                <span>KRD</span>
                            @else
                                <i class="fa-solid fa-language"></i>
                            @endif
                        </span>
                        <i class="ml-1 fas fa-chevron-down fa-xs" :class="{'rotate-180': langOpenMobile}"></i>
                    </button>
                    <div x-show="langOpenMobile" class="pl-3 mt-1 space-y-1 border-l border-gray-200">
                        <a href="{{ url('set/lang/en') }}"
                            class="flex items-center w-full px-3 py-2 text-sm text-gray-700 rounded-md hover:bg-gray-100 {{ app()->getLocale() == 'en' ? 'bg-gray-100 font-semibold' : '' }}">
                            <img src="{{ asset('images/flags/ENG.PNG') }}" alt="ENG Flag" class="w-5 h-auto mr-2">  @lang('words.KRD')
                        </a>
                        <a href="{{ url('set/lang/ckb') }}"                                                
                            class="flex items-center w-full px-3 py-2 text-sm text-gray-700 rounded-md hover:bg-gray-100 {{ app()->getLocale() == 'ckb' ? 'bg-gray-100 font-semibold' : '' }}">
                            <img src="{{ asset('images/flags/KRD.svg') }}" alt="KRD Flag" class="w-5 h-auto mr-2">  @lang('words.ENG')
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main>
        {{ $slot }}
    </main>


    <footer class="py-12 bg-slate-800 text-slate-300">
        <div class="container grid grid-cols-1 gap-8 px-6 mx-auto md:grid-cols-3">
            <!-- ORS Info -->
            <div>
                <h3 class="mb-4 text-lg font-semibold text-white">ORS</h3>
                <p class="text-sm">
                  @lang('words.Finding your perfect home has never been easier. Browse our wide selection of properties and find the one that suits your needs.')  
                </p>
            </div>

            <!-- Navigation Links -->
            <div>
                <h3 class="mb-4 text-lg font-semibold text-white">@lang('words.NAVIGATION')</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}" class="hover:text-blue-400">@lang('words.Home')</a></li>
                    <li><a href="#" class="hover:text-blue-400">@lang('words.About')</a></li>
                    {{-- Add other footer navigation links here --}}
                </ul>
            </div>

            <!-- Legal Links -->
            <div>
                <h3 class="mb-4 text-lg font-semibold text-white">@lang('words.LEGAL')</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-blue-400">@lang('words.Privacy Policy')</a></li>
                    <li><a href="#" class="hover:text-blue-400">@lang('words.Terms of Service')</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-blue-400">@lang('words.Contact Us')</a></li>
                </ul>
            </div>
        </div>
        <div class="container px-6 pt-8 mx-auto mt-8 text-sm text-center border-t border-slate-600">
            &copy; {{ date('Y') }} ORS. All Rights Reserved.
        </div>
    </footer>

    @stack('scripts')
    @auth
        <script>
            function notificationsComponent() {
                return {
                    notifOpen: false,
                    notifications: [],
                    unreadCount: 0,
                    loading: true,
                    init() {
                        this.fetchNotifications();
                        // Optional: Poll for new notifications every 60 seconds
                        // setInterval(() => {
                        //     if (document.visibilityState === 'visible') { // Only fetch if tab is active
                        //        this.fetchNotifications(false); // false to not show loading indicator for background refresh
                        //     }
                        // }, 60000);
                    },
                    fetchNotifications(showLoading = true) {
                        if (showLoading) this.loading = true;
                        fetch('{{ route('notifications.data') }}', {
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                this.notifications = data.notifications;
                                this.unreadCount = data.unread_count;
                                if (showLoading) this.loading = false;
                            })
                            .catch(error => {
                                console.error('Error fetching notifications:', error);
                                if (showLoading) this.loading = false;
                            });
                    },
                    toggleDropdown() {
                        this.notifOpen = !this.notifOpen;
                        if (this.notifOpen) {
                            // Fetch fresh notifications when dropdown is opened
                            this.fetchNotifications();
                        }
                    },
                    async handleNotificationClick(notification) {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        try {
                            // Only mark as read if it's currently unread
                            if (!notification.read_at) {
                                await fetch(`/notifications/${notification.id}/mark-as-read`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': csrfToken,
                                        'Accept': 'application/json',
                                    },
                                });
                                // Refresh notifications from server to get updated read_at status and unread_count
                                this.fetchNotifications(false);
                            }
                            // Navigate after marking as read (or if already read)
                            if (notification.link && notification.link !== '#') {
                                window.location.href = notification.link;
                            }
                        } catch (error) {
                            console.error('Error marking notification as read:', error);
                        }
                        this.notifOpen = false; // Close dropdown after click
                    },
                    async markAllRead() {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        try {
                            await fetch('{{ route('notifications.markAllAsRead') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json',
                                },
                            });
                            // Clear the list visually and update count
                            this.notifications = [];
                            this.unreadCount = 0;
                        } catch (error) {
                            console.error('Error marking all notifications as read:', error);
                        }
                    },
                    timeAgo(timestamp) { // Simple time ago function
                        const now = new Date();
                        const past = new Date(timestamp);
                        const msPerMinute = 60 * 1000;
                        const msPerHour = msPerMinute * 60;
                        const msPerDay = msPerHour * 24;
                        const elapsed = now - past;

                        if (elapsed < msPerMinute) return Math.round(elapsed / 1000) + 's ago';
                        else if (elapsed < msPerHour) return Math.round(elapsed / msPerMinute) + 'm ago';
                        else if (elapsed < msPerDay) return Math.round(elapsed / msPerHour) + 'h ago';
                        else return Math.round(elapsed / msPerDay) + 'd ago';
                    }
                }
            }
        </script>
    @endauth
</body>

</html>
