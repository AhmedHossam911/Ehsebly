<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-900 dark:text-white leading-tight tracking-tight">
            {{ __('Recurring Payments') }}
        </h2>
    </x-slot>

    <div class="py-8 px-4 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8 mb-10">

        <x-page-header title="Recurring Payments" class="hidden md:flex mb-6"
            description="Rent, subscriptions, and utilities that get logged to your wallet automatically when they're due." />

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 relative z-10">
            <!-- Left Column: Add Recurring Payment -->
            <div class="space-y-8">
                <div class="bg-white dark:bg-gray-800 rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-700 p-8">
                    <h3 class="text-xl font-black text-gray-900 dark:text-white mb-6 tracking-tight">Add Recurring Payment</h3>
                    <form method="POST" action="{{ route('recurring-payments.store') }}" class="space-y-5">
                        @csrf
                        <div>
                            <x-input-label for="title" value="Title" class="font-bold mb-1" />
                            <x-text-input id="title" type="text" name="title" class="block w-full bg-gray-50 border-gray-200 focus:bg-white dark:bg-gray-900/50 dark:border-gray-700/50 dark:focus:bg-gray-800" required placeholder="e.g. Rent, Netflix, Electricity" />
                        </div>

                        <div>
                            <x-input-label for="amount" value="Amount" class="font-bold mb-1" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span class="text-gray-500 font-bold text-sm">£</span>
                                </div>
                                <x-text-input id="amount" type="number" step="0.01" name="amount" class="pl-8 block w-full bg-gray-50 border-gray-200 focus:bg-white dark:bg-gray-900/50 dark:border-gray-700/50 dark:focus:bg-gray-800 text-lg font-bold" required placeholder="0.00" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="due_date" value="Next Due Date" class="font-bold mb-1" />
                            <x-text-input id="due_date" type="date" name="due_date" class="block w-full bg-gray-50 border-gray-200 focus:bg-white dark:bg-gray-900/50 dark:border-gray-700/50 dark:focus:bg-gray-800 font-medium cursor-pointer" value="{{ date('Y-m-d') }}" required />
                        </div>

                        <div>
                            <x-input-label for="recurrence_type" value="Repeats" class="font-bold mb-1" />
                            <div class="relative">
                                <select name="recurrence_type" id="recurrence_type" class="appearance-none block w-full px-4 py-3 bg-gray-50 border-gray-200 dark:bg-gray-900/50 dark:border-gray-700/50 focus:bg-white dark:focus:bg-gray-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-gray-900 dark:text-gray-100 rounded-xl shadow-sm font-semibold transition-colors outline-none cursor-pointer">
                                    <option value="monthly">Monthly</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="daily">Daily</option>
                                    <option value="yearly">Yearly</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </div>
                            </div>
                        </div>

                        <button class="w-full flex justify-center py-4 px-4 border border-transparent rounded-xl shadow-lg shadow-indigo-500/30 text-sm font-bold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transform transition-all hover:-translate-y-0.5 mt-2">
                            Save Recurring Payment
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right Column: List -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-700 p-6 sm:p-8 h-full">
                    <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-100 dark:border-gray-700/50">
                        <h3 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">Scheduled</h3>
                        <div class="text-sm font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/30 px-3 py-1.5 rounded-full">
                            {{ count($recurringPayments) }} {{ Str::plural('Payment', count($recurringPayments)) }}
                        </div>
                    </div>

                    <div class="space-y-4">
                        @forelse($recurringPayments as $payment)
                            <div class="group flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900/40 rounded-2xl hover:bg-white dark:hover:bg-gray-800 hover:shadow-md border border-transparent hover:border-gray-200 dark:hover:border-gray-700 transition-all duration-300">
                                <div class="flex items-center space-x-4">
                                    <div class="h-14 w-14 rounded-2xl flex items-center justify-center shadow-inner bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900 dark:text-white text-lg tracking-tight">{{ $payment->title }}</p>
                                        <div class="flex items-center space-x-2 text-xs font-medium text-gray-500 mt-0.5">
                                            <span>Next: {{ \Carbon\Carbon::parse($payment->due_date)->format('M d, Y') }}</span>
                                            <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-600"></span>
                                            <span class="capitalize">{{ $payment->recurrence_type }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <div class="text-right">
                                        <p class="text-xl font-black tracking-tight text-gray-900 dark:text-white">
                                            {{ number_format($payment->amount, 2) }}</p>
                                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">EGP</p>
                                    </div>
                                    <form action="{{ route('recurring-payments.destroy', $payment) }}" method="POST" onsubmit="return confirm('Delete this recurring payment?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 p-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition" title="Delete Recurring Payment">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <x-empty-state title="No Recurring Payments" color="gray"
                                description="Add rent, subscriptions, or utility bills to have them logged to your wallet automatically when due.">
                                <x-slot name="icon">
                                    <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </x-slot>
                            </x-empty-state>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
