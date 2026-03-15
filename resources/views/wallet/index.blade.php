<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-900 dark:text-white leading-tight tracking-tight">
            {{ __('Personal Wallet') }}
        </h2>
    </x-slot>

    <div class="py-8 px-4 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8 mb-10">
        
        <!-- Header area (Desktop mostly, mobile handled by app-layout title) -->
        <div class="hidden md:block relative z-10 mb-6">
            <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Your Wallet</h1>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">Track your personal income and expenses.</p>
        </div>



        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 relative z-10">
            <!-- Left Column: Balance & Add Transaction -->
            <div class="space-y-8">
                
                <!-- Premium Balance Card -->
                <div class="relative bg-[#0f172a] dark:bg-gray-900 rounded-[2.5rem] p-8 text-white shadow-2xl shadow-gray-900/20 overflow-hidden group">
                    <!-- Abstract Vector Background Graphics -->
                    <div class="absolute -top-24 -right-12 w-64 h-64 bg-gradient-to-br from-indigo-400 to-purple-600 rounded-full mix-blend-screen opacity-30 filter blur-3xl group-hover:opacity-50 transition-opacity duration-700"></div>
                    <div class="absolute -bottom-24 -left-12 w-64 h-64 bg-gradient-to-tr from-brand-500 to-accent-600 rounded-full mix-blend-screen opacity-20 filter blur-3xl group-hover:opacity-40 transition-opacity duration-700"></div>
                    
                    <svg class="absolute inset-0 w-full h-full opacity-10 pointer-events-none" viewBox="0 0 100 100" preserveAspectRatio="none">
                        <path d="M0,50 Q25,30 50,50 T100,50 V100 H0 Z" fill="currentColor"/>
                    </svg>

                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-4">
                            <p class="text-indigo-100 font-medium text-sm flex items-center">
                                <svg class="w-4 h-4 mr-1.5 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                                Available Balance
                            </p>
                        </div>
                        <h2 class="text-5xl font-black tracking-tighter text-white drop-shadow-lg">{{ number_format($balance, 2) }} <span class="text-xl font-bold text-indigo-200">EGP</span></h2>
                    </div>
                </div>

                <!-- Add Transaction Form -->
                <div class="bg-white dark:bg-gray-800 rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-700 p-8">
                    <h3 class="text-xl font-black text-gray-900 dark:text-white mb-6 tracking-tight">Add Transaction</h3>
                    <form method="POST" action="{{ route('wallet.transactions.store') }}" class="space-y-5">
                        @csrf
                        <div class="grid grid-cols-2 gap-5">
                            <div>
                                <x-input-label for="type" value="Type" class="font-bold mb-1" />
                                <div class="relative">
                                    <select name="type" id="type" class="appearance-none block w-full px-4 py-3 bg-gray-50 border-gray-200 dark:bg-gray-900/50 dark:border-gray-700/50 focus:bg-white dark:focus:bg-gray-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-gray-900 dark:text-gray-100 rounded-xl shadow-sm font-semibold transition-colors outline-none cursor-pointer">
                                        <option value="expense">Expense (-)</option>
                                        <option value="income">Income (+)</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </div>
                                </div>
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
                        </div>
                        
                        <div>
                            <x-input-label for="category" value="Category" class="font-bold mb-1" />
                            <x-text-input id="category" type="text" name="category" class="block w-full bg-gray-50 border-gray-200 focus:bg-white dark:bg-gray-900/50 dark:border-gray-700/50 dark:focus:bg-gray-800" placeholder="e.g. Groceries, Salary, Coffee" />
                        </div>

                        <div>
                            <x-input-label for="date" value="Date" class="font-bold mb-1" />
                            <x-text-input id="date" type="date" name="date" class="block w-full bg-gray-50 border-gray-200 focus:bg-white dark:bg-gray-900/50 dark:border-gray-700/50 dark:focus:bg-gray-800 font-medium cursor-pointer" value="{{ date('Y-m-d') }}" required />
                        </div>

                        <div>
                            <x-input-label for="notes" value="Notes (Optional)" class="font-bold mb-1" />
                            <textarea id="notes" name="notes" rows="2" class="block w-full px-4 py-3 bg-gray-50 border-gray-200 dark:bg-gray-900/50 dark:border-gray-700/50 focus:bg-white dark:focus:bg-gray-800 text-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl shadow-sm transition-colors outline-none resize-none placeholder-gray-400" placeholder="Any details..."></textarea>
                        </div>

                        <button class="w-full flex justify-center py-4 px-4 border border-transparent rounded-xl shadow-lg shadow-indigo-500/30 text-sm font-bold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transform transition-all hover:-translate-y-0.5 mt-2">
                            Save Transaction
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right Column: Transactions List -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-700 p-6 sm:p-8 h-full">
                    <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-100 dark:border-gray-700/50">
                        <h3 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">Recent Activity</h3>
                        <div class="text-sm font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/30 px-3 py-1.5 rounded-full">
                            {{ count($transactions) }} {{ Str::plural('Transaction', count($transactions)) }}
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        @forelse($transactions as $tx)
                            <div class="group flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900/40 rounded-2xl hover:bg-white dark:hover:bg-gray-800 hover:shadow-md border border-transparent hover:border-gray-200 dark:hover:border-gray-700 transition-all duration-300 transform hover:-translate-y-1 cursor-default">
                                <div class="flex items-center space-x-4">
                                    <div class="h-14 w-14 rounded-2xl flex items-center justify-center shadow-inner {{ $tx->type === 'income' ? 'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400' : 'bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-300' }}">
                                        @if($tx->type === 'income')
                                            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                                            </svg>
                                        @else
                                            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900 dark:text-white text-lg tracking-tight">{{ $tx->category ?: 'Uncategorized' }}</p>
                                        <div class="flex items-center space-x-2 text-xs font-medium text-gray-500 mt-0.5">
                                            <span>{{ \Carbon\Carbon::parse($tx->date)->format('M d, Y') }}</span>
                                            @if($tx->notes)
                                                <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-600"></span>
                                                <span class="truncate max-w-[120px] sm:max-w-xs">{{ $tx->notes }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-xl font-black tracking-tight {{ $tx->type === 'income' ? 'text-green-600 dark:text-green-400' : 'text-gray-900 dark:text-white' }}">
                                        {{ $tx->type === 'income' ? '+' : '-' }}{{ number_format($tx->amount, 2) }}
                                    </p>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">EGP</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-16 flex flex-col items-center">
                                <div class="w-20 h-20 bg-gray-100 dark:bg-gray-800 rounded-3xl flex items-center justify-center text-gray-400 mb-6 shadow-inner">
                                    <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                </div>
                                <h4 class="text-xl font-black text-gray-900 dark:text-white mb-2">No Transactions Yet</h4>
                                <p class="text-gray-500 font-medium max-w-sm">Start tracking your personal finances by adding your first transaction.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
