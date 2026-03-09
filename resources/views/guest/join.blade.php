<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">You're invited to</h2>
        <p class="text-3xl font-extrabold text-indigo-600 dark:text-indigo-400">{{ $event->name }}</p>
        <p class="text-gray-500 dark:text-gray-400 mt-2">{{ $event->date ? \Carbon\Carbon::parse($event->date)->format('M d, Y') : 'Date TBD' }}</p>
        <p class="text-sm mt-1 text-gray-500">Organized by <span class="font-semibold">{{ $event->creator->name }}</span></p>
    </div>

    <form method="POST" action="{{ route('guest.join', $event->guest_token) }}">
        @csrf
        
        <div>
            <x-input-label for="guest_name" value="Enter your name to join" />
            <x-text-input id="guest_name" class="block mt-1 w-full rounded-xl" type="text" name="guest_name" :value="old('guest_name')" required autofocus placeholder="Your Name" />
            <x-input-error :messages="$errors->get('guest_name')" class="mt-2" />
        </div>

        <div class="mt-8">
            <x-primary-button class="w-full justify-center rounded-full py-3 text-lg">
                {{ __('Join Event') }}
            </x-primary-button>
        </div>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Already have an account? 
                <a href="{{ route('login') }}" class="font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-500">Log in</a>
            </p>
        </div>
    </form>
</x-guest-layout>
