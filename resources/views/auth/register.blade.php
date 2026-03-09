<x-guest-layout>
    <div class="h-full flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-white dark:bg-gray-800 p-8 sm:p-10 rounded-[2rem] shadow-2xl border border-gray-100 dark:border-gray-700 relative overflow-hidden">
            
            <div class="absolute -top-24 -left-24 w-48 h-48 bg-brand-500/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-24 -right-24 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl"></div>

            <div class="relative z-10">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Create Account</h2>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Join Ehsebly and start settling bills instantly.</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <div>
                        <x-input-label for="name" :value="__('Full Name')" class="font-bold ml-1 mb-1" />
                        <x-text-input id="name" class="block mt-1 w-full font-medium" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2 ml-1" />
                    </div>

                    <div>
                        <x-input-label for="email" :value="__('Email Address')" class="font-bold ml-1 mb-1" />
                        <x-text-input id="email" class="block mt-1 w-full font-medium" type="email" name="email" :value="old('email')" required autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2 ml-1" />
                    </div>

                    <div>
                        <x-input-label for="password" :value="__('Password')" class="font-bold ml-1 mb-1" />
                        <x-text-input id="password" class="block mt-1 w-full font-medium" type="password" name="password" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2 ml-1" />
                    </div>

                    <div>
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="font-bold ml-1 mb-1" />
                        <x-text-input id="password_confirmation" class="block mt-1 w-full font-medium" type="password" name="password_confirmation" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 ml-1" />
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-2xl shadow-lg text-sm font-bold text-white bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-700 hover:to-indigo-700 transition transform hover:-translate-y-0.5">
                            {{ __('Register') }}
                        </button>
                    </div>
                </form>

                <p class="mt-8 text-center text-sm text-gray-600 dark:text-gray-400 font-medium">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="font-bold text-brand-600 hover:text-brand-500 dark:text-brand-400 hover:underline">
                        Log in
                    </a>
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
