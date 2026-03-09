<x-app-layout>
    <div class="py-10 px-4 max-w-7xl mx-auto sm:px-6 lg:px-12 xl:px-16 space-y-10 w-full mb-10">

        <!-- Header & Profile Area -->
        <div class="flex justify-between items-center bg-transparent relative z-10">
            <div>
                <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Welcome
                    back, <span>{{ explode(' ', auth()->user()->name)[0] }}
                    </span></p>
            </div>
        </div>

        <!-- Main Grid Layout for Desktop vs Mobile -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 xl:gap-12 w-full">

            <!-- Left Column: Focal Area (Balance & Events) -->
            <div class="lg:col-span-8 space-y-10">
                <!-- Main Balance Card with Vectors -->
                @php
                    $transactions = auth()->user()->walletTransactions()->get();
                    $balance = $transactions->reduce(function ($carry, $tx) {
                        return $tx->type === 'income' ? $carry + $tx->amount : $carry - $tx->amount;
                    }, 0);
                @endphp
                <div
                    class="relative bg-[#0f172a] dark:bg-gray-900 rounded-[2.5rem] p-8 md:p-12 text-white shadow-2xl shadow-gray-900/20 overflow-hidden transform hover:-translate-y-1 transition-transform duration-300 group w-full">

                    <!-- Abstract Vector Background Graphics -->
                    <div
                        class="absolute -top-24 -right-12 w-64 h-64 bg-gradient-to-br from-brand-400 to-emerald-600 rounded-full mix-blend-screen opacity-20 filter blur-3xl group-hover:opacity-40 transition-opacity duration-700">
                    </div>
                    <div
                        class="absolute -bottom-24 -left-12 w-64 h-64 bg-gradient-to-tr from-accent-500 to-purple-600 rounded-full mix-blend-screen opacity-20 filter blur-3xl group-hover:opacity-40 transition-opacity duration-700">
                    </div>

                    <!-- Elegant SVG Vector Pattern -->
                    <svg class="absolute inset-0 w-full h-full opacity-10 pointer-events-none" viewBox="0 0 100 100"
                        preserveAspectRatio="none">
                        <path d="M0,50 Q25,30 50,50 T100,50 V100 H0 Z" fill="url(#grad1)" />
                        <path d="M0,80 Q25,60 50,80 T100,80 V100 H0 Z" fill="url(#grad2)" />
                        <defs>
                            <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="0%">
                                <stop offset="0%" style="stop-color:#34d399;stop-opacity:1" />
                                <stop offset="100%" style="stop-color:#4f46e5;stop-opacity:1" />
                            </linearGradient>
                            <linearGradient id="grad2" x1="0%" y1="0%" x2="100%" y2="0%">
                                <stop offset="0%" style="stop-color:#6366f1;stop-opacity:1" />
                                <stop offset="100%" style="stop-color:#10b981;stop-opacity:1" />
                            </linearGradient>
                        </defs>
                    </svg>

                    <div class="relative z-10 flex flex-col h-full justify-between min-h-[160px]">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-gray-300 font-medium text-sm tracking-wide flex items-center">
                                    <svg class="w-4 h-4 mr-1.5 text-brand-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    Total Balance
                                </p>
                                <span
                                    class="px-2.5 py-1 bg-white/10 rounded-full text-xs font-semibold backdrop-blur-md border border-white/20">Active</span>
                            </div>
                            <h2 class="text-5xl font-black tracking-tighter text-white drop-shadow-lg mb-8">
                                {{ number_format($balance, 2) }} <span
                                    class="text-xl font-bold text-gray-400">EGP</span></h2>
                        </div>

                        <div class="grid grid-cols-2 gap-4 pt-6 border-t border-white/10">
                            <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-4 border border-white/5">
                                <p class="text-xs text-gray-400 font-medium mb-1 uppercase tracking-wider">You Owe</p>
                                <p class="text-xl font-bold text-white">0.00 EGP</p>
                            </div>
                            <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-4 border border-white/5">
                                <p class="text-xs text-gray-400 font-medium mb-1 uppercase tracking-wider">You are Owed
                                </p>
                                <p class="text-xl font-bold text-brand-400 drop-shadow">0.00 EGP</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Events Section -->
                <div>
                    <div
                        class="flex justify-between items-end mb-6 px-1 border-b border-gray-200/50 dark:border-gray-800/50 pb-4">
                        <h3 class="text-xl md:text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                            Recent
                            Outings</h3>
                        <a href="{{ route('events.index') }}"
                            class="text-sm font-bold text-brand-600 dark:text-brand-400 hover:text-brand-700 flex items-center group">
                            View All
                            <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-6 pt-4">
                        @php
                            $recentEvents = \App\Models\Event::where('creator_id', auth()->id())
                                ->orWhereHas('participants', fn($q) => $q->where('user_id', auth()->id()))
                                ->latest()
                                ->take(3)
                                ->get();
                        @endphp

                        @forelse($recentEvents as $index => $event)
                            @php
                                $colors = [
                                    'text-brand-500 bg-brand-50 dark:bg-brand-500/10',
                                    'text-accent-500 bg-accent-50 dark:bg-accent-500/10',
                                    'text-purple-500 bg-purple-50 dark:bg-purple-500/10',
                                ];
                                $colorClass = $colors[$index % 3];
                            @endphp
                            <a href="{{ route('events.show', $event) }}"
                                class="group block relative bg-white dark:bg-gray-800 rounded-3xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">

                                <!-- Very subtle background vector for standard items -->
                                <svg class="absolute right-0 top-0 h-full opacity-[0.03] dark:opacity-[0.02] transform translate-x-1/4"
                                    viewBox="0 0 100 100" preserveAspectRatio="none">
                                    <circle cx="50" cy="50" r="40" fill="currentColor" />
                                </svg>

                                <div class="flex items-center relative z-10">
                                    <div
                                        class="h-14 w-14 rounded-2xl flex items-center justify-center mr-5 shadow-inner {{ $colorClass }}">
                                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div class="flex-grow">
                                        <h4
                                            class="font-bold text-gray-900 dark:text-white text-lg tracking-tight group-hover:text-brand-600 dark:group-hover:text-brand-400 transition-colors">
                                            {{ $event->name }}</h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">
                                            {{ $event->date ? \Carbon\Carbon::parse($event->date)->format('M d, Y') : 'Ongoing' }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <div
                                            class="w-8 h-8 rounded-full bg-gray-50 dark:bg-gray-700 flex items-center justify-center text-gray-400 group-hover:bg-brand-500 group-hover:text-white transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div
                                class="p-8 bg-white dark:bg-gray-800 rounded-3xl border border-dashed border-gray-300 dark:border-gray-700 text-center relative overflow-hidden lg:col-span-2 xl:col-span-3">
                                <div class="absolute inset-0 bg-brand-50 dark:bg-brand-900/10 opacity-50"></div>
                                <div class="relative z-10 flex flex-col items-center">
                                    <div
                                        class="w-16 h-16 bg-brand-100 dark:bg-gray-700 rounded-full flex items-center justify-center text-brand-500 mb-4">
                                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                    </div>
                                    <p class="text-gray-900 dark:text-white font-bold text-lg mb-1">No recent outings.
                                    </p>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm mb-4">Start by creating an event
                                        with
                                        your friends.</p>
                                    <a href="{{ route('events.create') }}"
                                        class="px-6 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-bold rounded-full text-sm hover:shadow-lg transform hover:-translate-y-0.5 transition-all">Create
                                        an event</a>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- Right Column: Action Hub -->
            <div class="lg:col-span-4 space-y-8">
                <!-- Quick Actions with Minimalist Vectors -->
                <div
                    class="bg-white/50 dark:bg-gray-900/50 backdrop-blur-md rounded-[2.5rem] p-6 sm:p-8 border border-gray-200/50 dark:border-gray-800/50 shadow-sm">
                    <h3
                        class="text-xl md:text-2xl font-extrabold text-gray-900 dark:text-white mb-6 px-1 tracking-tight">
                        Quick Actions</h3>

                    <div class="grid grid-cols-2 gap-4 sm:gap-6">
                        <!-- Action 1 -->
                        <a href="{{ route('events.create') }}" class="flex flex-col items-center group">
                            <div
                                class="relative h-20 w-full sm:h-24 bg-white dark:bg-gray-800 rounded-[1.5rem] shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-center transform group-hover:-translate-y-1 group-hover:shadow-xl transition-all duration-300 overflow-hidden">
                                <div
                                    class="absolute inset-0 bg-brand-500 opacity-0 group-hover:opacity-10 transition-opacity">
                                </div>
                                <svg class="h-8 w-8 text-brand-500 transform group-hover:scale-110 transition-transform"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <span class="text-sm font-bold text-gray-700 dark:text-gray-300 mt-3 text-center">New
                                Event</span>
                        </a>

                        <!-- Action 2 -->
                        <a href="{{ route('friends.index') }}" class="flex flex-col items-center group">
                            <div
                                class="relative h-20 w-full sm:h-24 bg-white dark:bg-gray-800 rounded-[1.5rem] shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-center transform group-hover:-translate-y-1 group-hover:shadow-xl transition-all duration-300 overflow-hidden">
                                <div
                                    class="absolute inset-0 bg-purple-500 opacity-0 group-hover:opacity-10 transition-opacity">
                                </div>
                                <svg class="h-8 w-8 text-purple-500 transform group-hover:scale-110 transition-transform"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <span
                                class="text-sm font-bold text-gray-700 dark:text-gray-300 mt-3 text-center">Friends</span>
                        </a>

                        <!-- Action 3 -->
                        <a href="{{ route('wallet.index') }}" class="flex flex-col items-center group col-span-2">
                            <div
                                class="relative h-16 w-full bg-white dark:bg-gray-800 rounded-[1.5rem] shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-center transform group-hover:-translate-y-1 group-hover:shadow-xl transition-all duration-300 overflow-hidden space-x-3">
                                <div
                                    class="absolute inset-0 bg-orange-500 opacity-0 group-hover:opacity-10 transition-opacity">
                                </div>
                                <svg class="h-6 w-6 text-orange-500 transform group-hover:scale-110 transition-transform"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                                <span class="text-sm font-bold text-gray-700 dark:text-gray-300">Wallet</span>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Call to Action Banner (Optional Space Filler) -->
                <div
                    class="hidden lg:block relative bg-gradient-to-br from-brand-600 to-accent-600 rounded-[2.5rem] p-8 text-white shadow-xl overflow-hidden group">
                    <svg class="absolute top-0 right-0 w-32 h-32 text-white opacity-10 transform translate-x-1/3 -translate-y-1/3"
                        viewBox="0 0 100 100" preserveAspectRatio="none">
                        <circle cx="50" cy="50" r="40" fill="currentColor" />
                    </svg>
                    <div class="relative z-10">
                        <h4 class="text-xl font-bold mb-2">Split the bill smoothly</h4>
                        <p class="text-sm text-brand-100 mb-6">Create your first event and invite friends to share
                            expenses effortlessly.</p>
                        <a href="{{ route('events.create') }}"
                            class="inline-block bg-white text-brand-600 font-bold px-5 py-2.5 rounded-xl text-sm hover:shadow-lg hover:-translate-y-0.5 transition-all">Start
                            Now</a>
                    </div>
                </div>
            </div>

        </div>

    </div>
</x-app-layout>
