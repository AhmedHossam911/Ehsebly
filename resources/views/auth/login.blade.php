<x-guest-layout>
    <div class="h-full w-full flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div
            class="w-full max-w-md md:max-w-2xl lg:max-w-4xl xl:max-w-5xl space-y-8 bg-white dark:bg-gray-800 p-8 sm:p-12 lg:p-16 rounded-[2.5rem] shadow-2xl border border-gray-100 dark:border-gray-700 relative overflow-hidden">

            <!-- Auth background decorative -->
            <div class="absolute -top-24 -right-24 w-48 h-48 bg-brand-500/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl"></div>

            <div class="relative z-10 flex flex-col">
                <!-- Theme Toggle Button (Edge-to-Edge Header) -->
                <button @click="darkMode = !darkMode; localStorage.setItem('theme', darkMode ? 'dark' : 'light')"
                    class="-mt-8 sm:-mt-12 lg:-mt-16 -mx-8 sm:-mx-12 lg:-mx-16 mb-8 px-8 sm:px-12 lg:px-16 py-4 flex items-center justify-between bg-gray-50/50 hover:bg-gray-100/80 dark:bg-gray-900/20 dark:hover:bg-gray-900/40 backdrop-blur-xl border-b border-gray-100 dark:border-gray-700/50 transition-all duration-300 focus:outline-none group z-20">
                    <span
                        class="text-xs font-black tracking-widest uppercase text-gray-500 dark:text-gray-400 group-hover:text-brand-600 dark:group-hover:text-brand-400 transition-colors"
                        x-text="darkMode ? 'Switch to Light Mode' : 'Switch to Dark Mode'"></span>
                    <div class="flex items-center justify-center transition-transform group-hover:scale-110">
                        <svg x-show="!darkMode"
                            class="w-5 h-5 text-gray-700 group-hover:text-brand-600 transition-colors" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                        <svg x-show="darkMode"
                            class="w-5 h-5 text-yellow-400 group-hover:text-brand-400 transition-colors"
                            style="display: none;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </button>

                <!-- Branding Logo -->
                <a href="{{ url('/') }}"
                    class="flex items-center justify-center space-x-3 group mb-8 hover:opacity-80 transition-opacity">
                    <div
                        class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-brand-500 to-accent-500 flex items-center justify-center shadow-lg shadow-brand-500/30 transform group-hover:-rotate-12 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span
                        class="text-3xl font-black tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-300">Ehsebly</span>
                </a>

                <div class="text-center mb-10">
                    <h2 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Welcome Back</h2>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Sign in to settle your debts and track
                        expenses.</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="email" value="{{ __('Email Address') }}" class="font-bold ml-1" />
                        <x-text-input id="email" class="block mt-1 w-full font-medium" type="email" name="email"
                            :value="old('email')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2 ml-1" />
                    </div>

                    <div x-data="{ show: false }">
                        <div class="flex items-center justify-between ml-1 mb-1">
                            <x-input-label for="password" value="{{ __('Password') }}" class="font-bold mb-0" />
                            <!-- Forgot Password Link hid temporarily until SMTP is setup -->
                        </div>
                        <div class="relative">
                            <x-text-input id="password" class="block mt-1 w-full font-medium pr-10" ::type="show ? 'text' : 'password'"
                                name="password" required autocomplete="current-password" />
                            <button type="button" @click="show = !show" tabindex="-1"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none">
                                <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="show" style="display:none;" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 ml-1" />
                    </div>

                    <div class="block">
                        <label for="remember_me" class="inline-flex items-center ml-1">
                            <input id="remember_me" type="checkbox"
                                class="rounded border-gray-300 text-brand-600 shadow-sm focus:ring-brand-500 dark:focus:ring-brand-600 dark:focus:ring-offset-gray-800"
                                name="remember">
                            <span
                                class="ms-2 text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                        </label>
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                            class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-2xl shadow-lg text-sm font-bold text-white bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transform transition hover:-translate-y-0.5">
                            {{ __('Log in') }}
                        </button>
                    </div>
                </form>

                <p class="mt-8 text-center text-sm text-gray-600 dark:text-gray-400 font-medium">
                    Don't have an account?
                    <a href="{{ route('register') }}"
                        class="font-bold text-brand-600 hover:text-brand-500 dark:text-brand-400 hover:underline">
                        Create one now
                    </a>
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
