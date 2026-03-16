<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{
    darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
    sidebarExpanded: localStorage.getItem('sidebar') !== 'false',
    toggleTheme() {
        this.darkMode = !this.darkMode;
        localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
    },
    toggleSidebar() {
        this.sidebarExpanded = !this.sidebarExpanded;
        localStorage.setItem('sidebar', this.sidebarExpanded);
    }
}" x-bind:class="{ 'dark': darkMode }"
    class="antialiased">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Ehsebly App</title>

    <!-- Fonts (Outfit) -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Tailwind & Alpine Config -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif']
                    },
                    colors: {
                        brand: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            900: '#064e3b'
                        },
                        accent: {
                            500: '#6366f1',
                            600: '#4f46e5'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        body {
            -webkit-tap-highlight-color: transparent;
        }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body
    class="font-sans bg-[#fafafa] dark:bg-[#0f172a] text-gray-900 dark:text-gray-100 transition-colors duration-300 overflow-x-hidden selection:bg-brand-500 selection:text-white">

    <!-- Global App Vectors -->
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        <!-- Organic shape top right -->
        <svg class="absolute top-0 right-0 w-[500px] h-[500px] text-brand-500 opacity-[0.03] dark:opacity-[0.02] transform translate-x-1/3 -translate-y-1/3"
            viewBox="0 0 100 100" preserveAspectRatio="none">
            <path d="M50,0 C80,0 100,20 100,50 C100,80 80,100 50,100 C20,100 0,80 0,50 C0,20 20,0 50,0 Z"
                fill="currentColor" />
        </svg>
        <!-- Organic shape bottom left -->
        <svg class="absolute bottom-0 left-0 w-[600px] h-[600px] text-accent-500 opacity-[0.03] dark:opacity-[0.02] transform -translate-x-1/3 translate-y-1/3"
            viewBox="0 0 100 100" preserveAspectRatio="none">
            <path d="M50,0 C80,0 100,30 100,50 C100,70 80,100 50,100 C20,100 0,80 0,50 C0,20 30,0 50,0 Z"
                fill="currentColor" />
        </svg>
    </div>

    <div class="relative z-10 min-h-screen flex flex-col md:flex-row pb-20 md:pb-0">

        <!-- Desktop Sidebar Navigation -->
        <aside
            :class="sidebarExpanded ? 'w-72' : 'w-24'"
            class="hidden md:flex md:flex-col bg-white/80 dark:bg-gray-900/80 backdrop-blur-3xl border-r border-gray-200/50 dark:border-gray-800/50 fixed inset-y-0 left-0 z-40 shadow-2xl transition-all duration-300 ease-in-out ">
            
            <div class="h-24 flex items-center justify-between px-6 border-b border-gray-100 dark:border-gray-800/50 relative">
                <a href="{{ auth()->check() ? route('dashboard') : url('/') }}"
                    class="flex items-center space-x-3 group w-full">
                    <div
                        class="w-10 h-10 shrink-0 rounded-2xl bg-gradient-to-tr from-brand-500 to-accent-500 flex items-center justify-center shadow-lg shadow-brand-500/30 transform group-hover:rotate-12 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span
                        x-show="sidebarExpanded"
                        class="text-2xl font-black tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-300 transition-opacity xl:group-hover:block whitespace-nowrap">Ehsebly</span>
                </a>

                <!-- New Integrated Sidebar Flap Toggle matching the image reference -->
                <button @click.prevent="toggleSidebar()"
                    class="absolute -right-3 top-32 w-3 h-14 bg-white dark:bg-gray-800 border border-l-0 border-gray-200 dark:border-gray-700 rounded-r-md shadow-sm flex items-center justify-center text-gray-400 hover:w-4 hover:-right-4 hover:text-brand-500 transition-all z-50 cursor-pointer focus:outline-none xl:group-hover:flex"
                    title="Toggle Sidebar">
                    <div class="w-0.5 h-4 bg-gray-300 dark:bg-gray-600 rounded-full"></div>
                </button>
            </div>

            <div class="flex-grow py-8 px-4 space-y-2 overflow-y-auto no-scrollbar overflow-x-hidden">
                <p :class="sidebarExpanded ? 'opacity-100' : 'opacity-0 xl:group-hover:opacity-100'" class="px-4 text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-4 whitespace-nowrap transition-opacity duration-300">Menu</p>

                <a href="{{ route('dashboard') }}" title="Dashboard"
                    class="flex items-center px-4 py-3 rounded-2xl transition-all duration-300 overflow-hidden {{ request()->routeIs('dashboard') ? 'bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 font-bold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white font-semibold' }}">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="{{ request()->routeIs('dashboard') ? '2.5' : '2' }}"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    <span :class="sidebarExpanded ? 'opacity-100 delay-100' : 'opacity-0 xl:group-hover:opacity-100'" class="ml-4 whitespace-nowrap transition-opacity duration-200">Dashboard</span>
                </a>

                <a href="{{ route('events.index') }}" title="Events"
                    class="flex items-center px-4 py-3 rounded-2xl transition-all duration-300 overflow-hidden {{ request()->routeIs('events.*') ? 'bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 font-bold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white font-semibold' }}">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="{{ request()->routeIs('events.*') ? '2.5' : '2' }}"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span :class="sidebarExpanded ? 'opacity-100 delay-100' : 'opacity-0 xl:group-hover:opacity-100'" class="ml-4 whitespace-nowrap transition-opacity duration-200">Events</span>
                </a>

                <a href="{{ route('wallet.index') }}" title="Wallet"
                    class="flex items-center px-4 py-3 rounded-2xl transition-all duration-300 overflow-hidden {{ request()->routeIs('wallet.*') ? 'bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 font-bold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white font-semibold' }}">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="{{ request()->routeIs('wallet.*') ? '2.5' : '2' }}"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                        </path>
                    </svg>
                    <span :class="sidebarExpanded ? 'opacity-100 delay-100' : 'opacity-0 xl:group-hover:opacity-100'" class="ml-4 whitespace-nowrap transition-opacity duration-200">Wallet</span>
                </a>

                <a href="{{ route('friends.index') }}" title="Friends"
                    class="flex items-center px-4 py-3 rounded-2xl transition-all duration-300 overflow-hidden {{ request()->routeIs('friends.*') ? 'bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 font-bold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white font-semibold' }}">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="{{ request()->routeIs('friends.*') ? '2.5' : '2' }}"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                    <span :class="sidebarExpanded ? 'opacity-100 delay-100' : 'opacity-0 xl:group-hover:opacity-100'" class="ml-4 whitespace-nowrap transition-opacity duration-200">Friends</span>
                </a>

                <a href="{{ route('notifications.index') }}" title="Notifications"
                    class="flex items-center px-4 py-3 rounded-2xl transition-all duration-300 overflow-hidden {{ request()->routeIs('notifications.*') ? 'bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 font-bold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white font-semibold' }}">
                    <svg class="w-6 h-6 shrink-0 relative" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="{{ request()->routeIs('notifications.*') ? '2.5' : '2' }}"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                        </path>
                        @if (auth()->check() && auth()->user()->unreadNotifications->count() > 0)
                            <circle cx="18" cy="6" r="3" fill="#ef4444"
                                class="absolute shadow-sm shadow-red-500/50" />
                        @endif
                    </svg>
                    <span :class="sidebarExpanded ? 'opacity-100 delay-100' : 'opacity-0 xl:group-hover:opacity-100'" class="ml-4 whitespace-nowrap transition-opacity duration-200">Notifications</span>
                </a>

                <div class="pt-6 mt-4 border-t border-gray-100 dark:border-gray-800/50">
                    <p :class="sidebarExpanded ? 'opacity-100' : 'opacity-0 xl:group-hover:opacity-100'" class="px-4 text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-4 whitespace-nowrap transition-opacity duration-300">
                        Settings</p>
                    <a href="{{ route('profile.edit') }}" title="Profile"
                        class="flex items-center px-4 py-3 rounded-2xl transition-all duration-300 overflow-hidden {{ request()->routeIs('profile.*') ? 'bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 font-bold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white font-semibold' }}">
                        <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="{{ request()->routeIs('profile.*') ? '2.5' : '2' }}"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span :class="sidebarExpanded ? 'opacity-100 delay-100' : 'opacity-0 xl:group-hover:opacity-100'" class="ml-4 whitespace-nowrap transition-opacity duration-200">Profile</span>
                    </a>
                </div>
            </div>

            <!-- Desktop Sidebar Logout Footer -->
            <div class="px-4 py-4 border-t border-gray-100 dark:border-gray-800/50 mb-2">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Log Out"
                        class="w-full flex items-center px-4 py-3 rounded-2xl transition-all duration-300 text-red-600 dark:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 font-semibold focus:outline-none overflow-hidden group/logout">
                        <svg class="w-6 h-6 shrink-0 group-hover/logout:animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                        <span :class="sidebarExpanded ? 'opacity-100 delay-100' : 'opacity-0 xl:group-hover:opacity-100'" class="ml-4 whitespace-nowrap transition-opacity duration-200">Log Out</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Page Content (Pushed Right on Desktop based on Sidebar State) -->
        <main 
            :class="sidebarExpanded ? 'md:ml-72' : 'md:ml-24'"
            class="flex-grow flex flex-col bg-transparent relative z-10 min-h-screen transition-all duration-300 ease-in-out">

            <!-- Global Top Navigation (Desktop & Mobile) -->
            <header
                class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl sticky top-0 z-30 border-b border-gray-200/50 dark:border-gray-800/50 shadow-sm px-4 sm:px-8 h-[4.5rem] flex items-center justify-between transition-colors duration-300">

                <!-- Left: Page Title / Logo -->
                <div class="flex items-center">
                    <div class="md:hidden flex items-center space-x-2 mr-4">
                        <div
                            class="w-8 h-8 rounded-xl bg-gradient-to-tr from-brand-500 to-accent-500 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <a href="{{ auth()->check() ? route('dashboard') : url('/') }}"
                        class="text-xl font-black bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-300 tracking-tight hover:opacity-80 transition-opacity">
                        Ehsebly
                    </a>
                </div>

                <!-- Right: Theme Toggle, Notifications, Profile -->
                <div class="flex items-center space-x-1 sm:space-x-2">

                    <!-- Theme Toggle Button -->
                    <button @click="toggleTheme()"
                        class="w-10 h-10 rounded-full flex items-center justify-center text-gray-400 hover:text-brand-500 hover:bg-brand-50 dark:hover:bg-brand-500/10 transition-colors focus:outline-none">
                        <svg x-show="darkMode" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <svg x-show="!darkMode" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>

                    @auth
                        <!-- Notifications Dropdown -->
                        <div x-data="{ openNotifications: false }" class="relative">
                            <button @click="openNotifications = !openNotifications"
                                @click.away="openNotifications = false"
                                class="relative w-10 h-10 rounded-full flex items-center justify-center text-gray-400 hover:text-brand-500 hover:bg-brand-50 dark:hover:bg-brand-500/10 transition-colors focus:outline-none">
                                <svg class="w-5 h-5 cursor-pointer" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                @if (auth()->user()->unreadNotifications->count() > 0)
                                    <span class="absolute top-2.5 right-2.5 flex h-2.5 w-2.5">
                                        <span
                                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                        <span
                                            class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500 ring-2 ring-white dark:ring-gray-900 border border-white dark:border-gray-900"></span>
                                    </span>
                                @endif
                            </button>

                            <div x-show="openNotifications" x-transition.opacity style="display: none;"
                                class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-2xl shadow-xl shadow-brand-500/10 border border-gray-100 dark:border-gray-700 overflow-hidden z-50">
                                <div
                                    class="px-4 py-3 border-b border-gray-50 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-900/50">
                                    <span class="text-sm font-bold text-gray-900 dark:text-white">Notifications</span>
                                    @if (auth()->user()->unreadNotifications->count() > 0)
                                        <span
                                            class="text-xs font-semibold bg-brand-100 text-brand-700 dark:bg-brand-900/30 dark:text-brand-400 px-2 py-0.5 rounded-full">{{ auth()->user()->unreadNotifications->count() }}
                                            New</span>
                                    @endif
                                </div>
                                <div class="max-h-80 overflow-y-auto no-scrollbar">
                                    @forelse(auth()->user()->unreadNotifications->take(10) as $notification)
                                        <div
                                            class="px-4 py-3 border-b border-gray-50 dark:border-gray-700/50 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition group">
                                            <form action="{{ route('notifications.read', $notification->id) }}"
                                                method="POST" class="flex items-start">
                                                @csrf
                                                <div class="flex-shrink-0 mr-3 mt-1">
                                                    <div
                                                        class="w-8 h-8 rounded-full bg-{{ $notification->data['color'] ?? 'brand-500' }}/10 text-{{ $notification->data['color'] ?? 'brand-500' }} flex items-center justify-center">
                                                        @if (isset($notification->data['icon']) && $notification->data['icon'] === 'receipt')
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                                </path>
                                                            </svg>
                                                        @elseif(isset($notification->data['icon']) && $notification->data['icon'] === 'check-badge')
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                                                                </path>
                                                            </svg>
                                                        @elseif(isset($notification->data['icon']) && $notification->data['icon'] === 'banknotes')
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                                                </path>
                                                            </svg>
                                                        @else
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                                                </path>
                                                            </svg>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="flex-1">
                                                    <button type="submit" class="text-left w-full focus:outline-none">
                                                        <p
                                                            class="text-sm font-bold text-gray-900 dark:text-white line-clamp-1 group-hover:text-brand-600 dark:group-hover:text-brand-400 transition-colors">
                                                            {{ $notification->data['title'] }}</p>
                                                        <p
                                                            class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 line-clamp-2">
                                                            {{ $notification->data['message'] }}</p>
                                                        <p class="text-[10px] text-gray-400 font-semibold mt-1">
                                                            {{ $notification->created_at->diffForHumans() }}</p>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    @empty
                                        <div class="px-4 py-8 text-center flex flex-col items-center">
                                            <div
                                                class="w-12 h-12 bg-gray-50 dark:bg-gray-800 rounded-full flex items-center justify-center mb-2">
                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">You're all
                                                caught up!</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    @endauth

                    @auth
                        <!-- User Profile Dropdown -->
                        <div x-data="{ openProfile: false }" class="relative ml-2 flex-shrink-0">
                            <!-- Avatar Trigger -->
                            <button @click="openProfile = !openProfile" @click.away="openProfile = false"
                                class="relative group focus:outline-none flex">
                                <div
                                    class="absolute -inset-0.5 bg-gradient-to-tr from-brand-400 to-accent-600 rounded-full blur opacity-60 group-hover:opacity-100 transition duration-300">
                                </div>
                                <div
                                    class="relative h-9 w-9 sm:h-10 sm:w-10 rounded-full overflow-hidden bg-white dark:bg-gray-800 p-[2px] shadow-sm transform group-hover:scale-105 transition duration-300">
                                    <div
                                        class="h-full w-full bg-gradient-to-br from-brand-50 to-brand-100 dark:from-gray-800 dark:to-gray-900 rounded-full flex items-center justify-center border border-white dark:border-gray-800">
                                        @if (auth()->user()->avatar)
                                            <img src="{{ auth()->user()->getAvatarUrl() }}" alt="Avatar"
                                                class="h-full w-full object-cover rounded-full">
                                        @else
                                            <span
                                                class="text-sm font-bold bg-clip-text text-transparent bg-gradient-to-br from-brand-500 to-accent-600">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="openProfile" x-transition.opacity style="display: none;"
                                class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-2xl shadow-xl shadow-brand-500/10 border border-gray-100 dark:border-gray-700 overflow-hidden z-50 py-2">

                                <a href="{{ route('profile.edit') }}"
                                    class="flex items-center px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-brand-600 dark:hover:text-brand-400 transition-colors">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Profile Settings
                                </a>

                                <button type="button" 
                                    @click="
                                        navigator.clipboard.writeText('{{ url('/user/' . auth()->user()->uid) }}');
                                        window.dispatchEvent(new CustomEvent('notify', { detail: { type: 'success', message: 'Profile link copied to clipboard!' }}));
                                        openProfile = false;
                                    "
                                    class="w-full flex items-center px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-brand-600 dark:hover:text-brand-400 transition-colors">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6.632l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                    </svg>
                                    Share Account
                                </button>

                                <div class="border-t border-gray-100 dark:border-gray-700/50 my-1"></div>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center px-4 py-3 text-sm font-medium text-red-600 dark:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                            </path>
                                        </svg>
                                        Log Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endauth

                </div>
            </header>

            <div class="flex-grow flex flex-col w-full relative">
                {{ $slot }}
            </div>

            <!-- Footer Component -->
            <x-footer class="hidden md:block px-4 sm:px-8 bg-transparent pb-32 md:pb-8" />
        </main>
    </div>

    <!-- Mobile Bottom Navigation (Glassmorphic) -->
    <nav
        class="fixed bottom-0 w-full bg-white/80 dark:bg-gray-900/80 backdrop-blur-2xl border-t border-gray-200/50 dark:border-gray-800/50 z-50 md:hidden pb-safe shadow-[0_-10px_40px_-15px_rgba(0,0,0,0.1)]">
        <div class="flex justify-around items-center h-[72px] px-2 relative">

            <!-- Home -->
            <a href="{{ route('dashboard') }}"
                class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ request()->routeIs('dashboard') ? 'text-brand-600 dark:text-brand-400' : 'text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300' }} transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="{{ request()->routeIs('dashboard') ? '2.5' : '2' }}"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                    </path>
                </svg>
                <span class="text-[10px] font-bold">Home</span>
            </a>

            <!-- Events -->
            <a href="{{ route('events.index') }}"
                class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ request()->routeIs('events.*') ? 'text-brand-600 dark:text-brand-400' : 'text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300' }} transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="{{ request()->routeIs('events.*') ? '2.5' : '2' }}"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                <span class="text-[10px] font-bold">Events</span>
            </a>

            <!-- New Event FAB (Floating Action Button) -->
            <div class="relative -top-6 flex justify-center w-full">
                <a href="{{ route('events.create') }}"
                    class="bg-gradient-to-tr from-brand-500 to-accent-500 rounded-full p-4 shadow-xl shadow-brand-500/30 text-white transform hover:-translate-y-1 active:scale-95 transition-all duration-300 border-4 border-[#fafafa] dark:border-[#0f172a] flex items-center justify-center">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4">
                        </path>
                    </svg>
                </a>
            </div>

            <!-- Wallet -->
            <a href="{{ route('wallet.index') }}"
                class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ request()->routeIs('wallet.*') ? 'text-brand-600 dark:text-brand-400' : 'text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300' }} transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="{{ request()->routeIs('wallet.*') ? '2.5' : '2' }}"
                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                    </path>
                </svg>
                <span class="text-[10px] font-bold">Wallet</span>
            </a>

            <!-- Friends -->
            <a href="{{ route('friends.index') }}"
                class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ request()->routeIs('friends.*') ? 'text-brand-600 dark:text-brand-400' : 'text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300' }} transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="{{ request()->routeIs('friends.*') ? '2.5' : '2' }}"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
                <span class="text-[10px] font-bold">Friends</span>
            </a>

        </div>
    </nav>

    <!-- Alpine Toaster Notification System -->
    <div x-data="{ 
            notifications: [],
            add(e) {
                this.notifications.push({
                    id: e.timeStamp,
                    type: e.detail.type || 'success',
                    message: e.detail.message,
                });
                setTimeout(() => {
                    this.remove(e.timeStamp);
                }, 4000);
            },
            remove(id) {
                this.notifications = this.notifications.filter(n => n.id !== id);
            }
        }" 
        @notify.window="add($event)"
        class="fixed top-24 left-6 z-[100] flex flex-col items-start space-y-4 pointer-events-none">
        
        <template x-for="notification in notifications" :key="notification.id">
            <div x-show="true" 
                x-transition:enter="transform ease-out duration-300 transition"
                x-transition:enter-start="-translate-x-10 opacity-0"
                x-transition:enter-end="translate-x-0 opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-x-0"
                x-transition:leave-end="opacity-0 -translate-x-10"
                class="pointer-events-auto flex items-start gap-4 p-4 w-[320px] bg-white/90 dark:bg-gray-900/90 backdrop-blur-2xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] border border-gray-100/50 dark:border-gray-800/50 rounded-2xl relative overflow-hidden group">
                
                <!-- Edge color bar -->
                <div class="absolute left-0 top-0 bottom-0 w-1.5" 
                     :class="{'bg-green-500': notification.type === 'success', 'bg-red-500': notification.type === 'error'}"></div>

                <div class="flex-shrink-0 pt-0.5 ml-1">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center"
                         :class="{'bg-green-100 dark:bg-green-500/20 text-green-600 dark:text-green-400': notification.type === 'success', 'bg-red-100 dark:bg-red-500/20 text-red-600 dark:text-red-400': notification.type === 'error'}">
                        <svg x-show="notification.type === 'success'" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                        <svg x-show="notification.type === 'error'" style="display:none;" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                </div>
                
                <div class="flex-1 min-w-0">
                    <p class="text-[13px] font-bold tracking-wide uppercase text-gray-900 dark:text-white" x-text="notification.type === 'success' ? 'Success' : 'Error'"></p>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1 leading-snug font-medium" x-text="notification.message"></p>
                </div>
                
                <button @click="remove(notification.id)" class="flex-shrink-0 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors opacity-0 group-hover:opacity-100 focus:outline-none">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </template>
    </div>

    @if (session('status'))
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.nextTick(() => {
                    window.dispatchEvent(new CustomEvent('notify', {
                        detail: { type: 'success', message: '{{ session('status') }}' }
                    }));
                });
            });
        </script>
    @elseif (session('success'))
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.nextTick(() => {
                    window.dispatchEvent(new CustomEvent('notify', {
                        detail: { type: 'success', message: '{{ session('success') }}' }
                    }));
                });
            });
        </script>
    @elseif (session('error'))
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.nextTick(() => {
                    window.dispatchEvent(new CustomEvent('notify', {
                        detail: { type: 'error', message: '{{ session('error') }}' }
                    }));
                });
            });
        </script>
    @endif

    <!-- Unified Alpine.js script handles theme completely above -->
</body>

</html>
