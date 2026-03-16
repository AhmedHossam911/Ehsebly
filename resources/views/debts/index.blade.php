<x-app-layout>
    <div class="py-10 px-4 max-w-7xl mx-auto sm:px-6 lg:px-12 xl:px-16 space-y-10 w-full mb-10">

        <!-- Header Area -->
        <div class="flex justify-between items-center bg-transparent relative z-10 mb-8">
            <div>
                <h2 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">Your Debts</h2>
                <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 mt-2">Manage everything you owe and are owed</p>
            </div>
            <div>
                <a href="{{ route('dashboard') }}" class="text-sm font-bold text-gray-600 dark:text-gray-400 hover:text-brand-600 dark:hover:text-brand-400 flex items-center group bg-white dark:bg-gray-800 px-4 py-2 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-all">
                    <svg class="w-4 h-4 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Dashboard
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 xl:gap-12 w-full">

            <!-- Left Column: To Pay (You Owe) -->
            <div class="space-y-6">
                <div class="flex items-center justify-between px-1 mb-4 border-b border-gray-200/50 dark:border-gray-800/50 pb-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-red-50 dark:bg-red-500/10 flex items-center justify-center text-red-500 mr-4">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                            </svg>
                        </div>
                        <h3 class="text-xl md:text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">To Pay (عليك)</h3>
                    </div>
                    <span class="px-3 py-1 bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 rounded-full text-xs font-bold tracking-wide shadow-sm">{{ $payables->count() }} Debts</span>
                </div>

                <div class="space-y-4">
                    @forelse($payables as $payable)
                        <div class="group relative bg-white dark:bg-gray-800 rounded-[1.5rem] p-5 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden flex items-center justify-between">
                            <div class="flex items-center relative z-10 w-full">
                                <a href="{{ route('profile.show', $payable->toParticipant->user->uid) }}" class="flex-shrink-0">
                                    <img src="{{ $payable->toParticipant->user ? $payable->toParticipant->user->getAvatarUrl() : 'https://ui-avatars.com/api/?name='.urlencode($payable->toParticipant->guest_name).'&background=random' }}" alt="Avatar" class="h-12 w-12 rounded-full object-cover border-2 border-white dark:border-gray-700 shadow-md mr-4 hover:opacity-80 transition-opacity">
                                </a>
                                
                                <div class="flex-grow">
                                    <a href="{{ route('profile.show', $payable->toParticipant->user->uid) }}" class="hover:underline">
                                        <h4 class="font-bold text-gray-900 dark:text-white text-base tracking-tight">{{ $payable->toParticipant->user->name ?? $payable->toParticipant->guest_name }}</h4>
                                    </a>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Event: {{ $payable->event->name }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-black text-red-500">{{ number_format($payable->amount, 2) }} <span class="text-xs font-bold text-gray-400">EGP</span></p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 bg-gray-50 dark:bg-gray-800/50 rounded-[1.5rem] border border-dashed border-gray-300 dark:border-gray-700 text-center flex flex-col items-center justify-center">
                            <div class="w-16 h-16 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center text-gray-400 dark:text-gray-500 mb-4 shadow-sm border border-gray-100 dark:border-gray-700">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <p class="text-gray-900 dark:text-white font-bold mb-1">You're all settled up!</p>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">You don't owe anyone right now.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Right Column: To Receive (You are Owed) -->
            <div class="space-y-6">
                <div class="flex items-center justify-between px-1 mb-4 border-b border-gray-200/50 dark:border-gray-800/50 pb-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-brand-50 dark:bg-brand-500/10 flex items-center justify-center text-brand-500 mr-4">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                            </svg>
                        </div>
                        <h3 class="text-xl md:text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">To Receive (ليك)</h3>
                    </div>
                    <span class="px-3 py-1 bg-brand-100 text-brand-800 dark:bg-brand-900/30 dark:text-brand-400 rounded-full text-xs font-bold tracking-wide shadow-sm">{{ $receivables->count() }} Debts</span>
                </div>

                <div class="space-y-4">
                    @forelse($receivables as $receivable)
                        <div class="group relative bg-white dark:bg-gray-800 rounded-[1.5rem] p-5 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden flex items-center justify-between">
                            <div class="flex items-center relative z-10 w-full">
                                <a href="{{ route('profile.show', $receivable->fromParticipant->user->uid) }}" class="flex-shrink-0">
                                    <img src="{{ $receivable->fromParticipant->user ? $receivable->fromParticipant->user->getAvatarUrl() : 'https://ui-avatars.com/api/?name='.urlencode($receivable->fromParticipant->guest_name).'&background=random' }}" alt="Avatar" class="h-12 w-12 rounded-full object-cover border-2 border-white dark:border-gray-700 shadow-md mr-4 hover:opacity-80 transition-opacity">
                                </a>                                
                                <div class="flex-grow">
                                    <a href="{{ route('profile.show', $receivable->fromParticipant->user->uid) }}" class="hover:underline">
                                        <h4 class="font-bold text-gray-900 dark:text-white text-base tracking-tight">{{ $receivable->fromParticipant->user->name ?? $receivable->fromParticipant->guest_name }}</h4>
                                    </a>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Event: {{ $receivable->event->name }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-black text-brand-500">{{ number_format($receivable->amount, 2) }} <span class="text-xs font-bold text-gray-400">EGP</span></p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 bg-gray-50 dark:bg-gray-800/50 rounded-[1.5rem] border border-dashed border-gray-300 dark:border-gray-700 text-center flex flex-col items-center justify-center">
                            <div class="w-16 h-16 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center text-gray-400 dark:text-gray-500 mb-4 shadow-sm border border-gray-100 dark:border-gray-700">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="text-gray-900 dark:text-white font-bold mb-1">Nobody owes you.</p>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Your friends don't owe you anything right now.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>
</x-app-layout>
