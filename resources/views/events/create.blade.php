<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create New Event') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-3xl border border-gray-100 dark:border-gray-700">
                <div class="p-8 sm:p-10">
                    <div class="mb-8 text-center">
                        <div
                            class="inline-flex justify-center items-center w-16 h-16 rounded-full bg-brand-50 text-brand-600 dark:bg-brand-900/30 dark:text-brand-400 mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-black text-gray-900 dark:text-white">Start a New Memory</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Create an event to start splitting
                            bills and tracking expenses with your friends.</p>
                    </div>

                    <form method="POST" action="{{ route('events.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <label for="name"
                                class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('Event Title') }}</label>
                            <input id="name"
                                class="block w-full rounded-2xl border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:border-brand-500 focus:ring-brand-500 shadow-sm px-4 py-3 text-base"
                                type="text" name="name" value="{{ old('name') }}" required autofocus
                                placeholder="e.g. Red Sea Trip, Dinner at Mince..." />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <label for="date"
                                class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('Date (Optional)') }}</label>
                            <input id="date"
                                class="block w-full rounded-2xl border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:border-brand-500 focus:ring-brand-500 shadow-sm px-4 py-3 text-base text-gray-500 dark:text-gray-400"
                                type="date" name="date" value="{{ old('date') }}" />
                            <x-input-error :messages="$errors->get('date')" class="mt-2" />
                        </div>

                        <div>
                            <label for="budget"
                                class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('Budget (Optional)') }}</label>
                            <input id="budget"
                                class="block w-full rounded-2xl border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:border-brand-500 focus:ring-brand-500 shadow-sm px-4 py-3 text-base"
                                type="number" step="0.01" min="0.01" name="budget" value="{{ old('budget') }}"
                                placeholder="e.g. 5000" />
                            <x-input-error :messages="$errors->get('budget')" class="mt-2" />
                        </div>

                        <div>
                            <label for="currency"
                                class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('Currency') }}</label>
                            <select id="currency" name="currency"
                                class="block w-full rounded-2xl border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:border-brand-500 focus:ring-brand-500 shadow-sm px-4 py-3 text-base">
                                @foreach (\App\Models\Event::SUPPORTED_CURRENCIES as $currency)
                                    <option value="{{ $currency }}" {{ old('currency', 'EGP') === $currency ? 'selected' : '' }}>{{ $currency }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('currency')" class="mt-2" />
                        </div>

                        <div
                            class="flex items-center justify-end pt-6 mt-6 border-t border-gray-100 dark:border-gray-700">
                            <a class="text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-lg px-4 py-2 transition-colors mr-3"
                                href="{{ route('events.index') }}">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit"
                                class="px-6 py-3 bg-brand-600 text-white rounded-xl font-bold shadow-md hover:bg-brand-700 hover:-translate-y-0.5 transition-all">
                                {{ __('Create Event') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
