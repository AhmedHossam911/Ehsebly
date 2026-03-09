<x-guest-layout>
    <div class="h-full flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-white dark:bg-gray-800 p-8 sm:p-10 rounded-[2rem] shadow-2xl border border-gray-100 dark:border-gray-700 relative overflow-hidden">
            
            <!-- Auth background decorative -->
            <div class="absolute -top-24 -right-24 w-48 h-48 bg-brand-500/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl"></div>

            <div class="relative z-10">
                <div class="text-center mb-10">
                    <h2 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Welcome Back</h2>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Sign in to settle your debts and track expenses.</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="email" value="{{ __('Email Address') }}" class="font-bold ml-1" />
                        <x-text-input id="email" class="block mt-1 w-full font-medium" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2 ml-1" />
                    </div>

                    <div>
                        <div class="flex items-center justify-between ml-1 mb-1">
                            <x-input-label for="password" value="{{ __('Password') }}" class="font-bold mb-0" />
                            @if (Route::has('password.request'))
                                <a class="text-xs font-semibold text-brand-600 hover:text-brand-500 dark:text-brand-400" href="{{ route('password.request') }}">
                                    {{ __('Forgot password?') }}
                                </a>
                            @endif
                        </div>
                        <x-text-input id="password" class="block mt-1 w-full font-medium" type="password" name="password" required autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2 ml-1" />
                    </div>

                    <div class="block">
                        <label for="remember_me" class="inline-flex items-center ml-1">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-brand-600 shadow-sm focus:ring-brand-500 dark:focus:ring-brand-600 dark:focus:ring-offset-gray-800" name="remember">
                            <span class="ms-2 text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                        </label>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-2xl shadow-lg text-sm font-bold text-white bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transform transition hover:-translate-y-0.5">
                            {{ __('Log in') }}
                        </button>
                    </div>
                </form>

                <p class="mt-8 text-center text-sm text-gray-600 dark:text-gray-400 font-medium">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="font-bold text-brand-600 hover:text-brand-500 dark:text-brand-400 hover:underline">
                        Create one now
                    </a>
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
