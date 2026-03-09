<x-guest-layout>
    <div class="h-full w-full flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div
            class="w-full max-w-md md:max-w-2xl lg:max-w-4xl xl:max-w-5xl space-y-6 bg-white dark:bg-gray-800 p-8 sm:p-12 lg:p-16 rounded-[2.5rem] shadow-2xl border border-gray-100 dark:border-gray-700 relative overflow-hidden">

            <div class="absolute -top-24 -left-24 w-48 h-48 bg-brand-500/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-24 -right-24 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl"></div>

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

                <div class="text-center mb-8">
                    <h2 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Create Account</h2>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Join Ehsebly and start settling bills
                        instantly.</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <div>
                        <x-input-label for="name" :value="__('Full Name')" class="font-bold ml-1 mb-1" />
                        <x-text-input id="name" class="block mt-1 w-full font-medium" type="text" name="name"
                            :value="old('name')" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2 ml-1" />
                    </div>

                    <div>
                        <x-input-label for="email" :value="__('Email Address')" class="font-bold ml-1 mb-1" />
                        <x-text-input id="email" class="block mt-1 w-full font-medium" type="email" name="email"
                            :value="old('email')" required autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2 ml-1" />
                    </div>

                    <div>
                        <x-input-label for="password" :value="__('Password')" class="font-bold ml-1 mb-1" />
                        <x-text-input id="password" class="block mt-1 w-full font-medium" type="password"
                            name="password" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2 ml-1" />
                    </div>

                    <div>
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="font-bold ml-1 mb-1" />
                        <x-text-input id="password_confirmation" class="block mt-1 w-full font-medium" type="password"
                            name="password_confirmation" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 ml-1" />
                    </div>

                    <div class="flex items-start mt-4 mb-4">
                        <div class="flex items-center h-5">
                            <input id="terms" name="terms" type="checkbox" required
                                class="w-4 h-4 text-brand-600 bg-gray-100 border-gray-300 rounded focus:ring-brand-500 dark:focus:ring-brand-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 cursor-pointer">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="terms" class="font-medium text-gray-700 dark:text-gray-300">
                                I agree to the
                                <a href="{{ route('terms') }}" target="_blank"
                                    class="text-brand-600 dark:text-brand-400 hover:underline">Terms of Service</a>
                                and
                                <a href="{{ route('privacy') }}" target="_blank"
                                    class="text-brand-600 dark:text-brand-400 hover:underline">Privacy Policy</a>.
                            </label>
                            <x-input-error :messages="$errors->get('terms')" class="mt-1" />
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                            class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-2xl shadow-lg text-sm font-bold text-white bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-700 hover:to-indigo-700 transition transform hover:-translate-y-0.5">
                            {{ __('Register') }}
                        </button>
                    </div>
                </form>

                <p class="mt-8 text-center text-sm text-gray-600 dark:text-gray-400 font-medium">
                    Already have an account?
                    <a href="{{ route('login') }}"
                        class="font-bold text-brand-600 hover:text-brand-500 dark:text-brand-400 hover:underline">
                        Log in
                    </a>
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
