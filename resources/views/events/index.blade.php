<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-900 dark:text-white leading-tight tracking-tight">
            {{ __('Events & Outings') }}
        </h2>
    </x-slot>

    <div class="py-8 px-4 w-full max-w-[95%] mx-auto sm:px-6 lg:px-8 space-y-8 mb-10">

        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 relative z-10">
            <div>
                <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Your Events</h1>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">Manage outings, trips, and shared
                    expenses.</p>
            </div>
            <a href="{{ route('events.create') }}"
                class="group relative inline-flex items-center justify-center px-6 py-3 font-bold text-white transition-all duration-200 bg-brand-500 border border-transparent rounded-2xl hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 shadow-lg shadow-brand-500/30 overflow-hidden transform hover:-translate-y-0.5">
                <div
                    class="absolute inset-0 w-full h-full -mt-1 rounded-lg opacity-30 bg-gradient-to-b from-transparent via-transparent to-black">
                </div>
                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span class="relative">New Event</span>
            </a>
        </div>

        @if (session('status'))
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded-2xl relative shadow-sm font-medium"
                role="alert">
                <span class="block sm:inline">{{ session('status') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5 lg:gap-6 relative z-10 w-full">
            @forelse($events as $index => $event)
                @php
                    $colors = [
                        ['bg' => 'bg-brand-50 dark:bg-brand-500/10', 'text' => 'text-brand-500'],
                        ['bg' => 'bg-accent-50 dark:bg-accent-500/10', 'text' => 'text-accent-500'],
                        ['bg' => 'bg-purple-50 dark:bg-purple-500/10', 'text' => 'text-purple-500'],
                        ['bg' => 'bg-orange-50 dark:bg-orange-500/10', 'text' => 'text-orange-500'],
                    ];
                    $theme = $colors[$index % count($colors)];
                @endphp
                <a href="{{ route('events.show', $event) }}"
                    class="group block relative bg-white dark:bg-gray-800/80 backdrop-blur-xl rounded-[2.5rem] p-7 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-gray-100/80 dark:border-gray-700/50 hover:shadow-[0_20px_40px_rgb(0,0,0,0.08)] hover:-translate-y-2 transition-all duration-500 overflow-hidden">

                    <!-- Decorative Gradient Glow (Top Right) -->
                    <div
                        class="absolute -right-10 -top-10 w-40 h-40 {{ $theme['bg'] }} rounded-full blur-3xl opacity-50 group-hover:opacity-80 transition-opacity duration-500">
                    </div>

                    <!-- Decorative Background Vector -->
                    <svg class="absolute -right-4 -top-4 w-32 h-32 opacity-[0.02] dark:opacity-[0.02] text-current transform group-hover:scale-110 group-hover:-rotate-12 transition-all duration-700 ease-out"
                        viewBox="0 0 100 100">
                        <path d="M50,0 C80,0 100,20 100,50 C100,80 80,100 50,100 C20,100 0,80 0,50 C0,20 20,0 50,0 Z"
                            fill="currentColor" />
                    </svg>

                    <div class="relative z-10 flex flex-col h-full">
                        <div class="flex justify-between items-start mb-8">
                            <div
                                class="h-16 w-16 rounded-[1.25rem] {{ $theme['bg'] }} {{ $theme['text'] }} flex items-center justify-center shadow-inner transform group-hover:scale-110 group-hover:rotate-6 transition-all duration-500 ease-out border border-white/50 dark:border-gray-700/50">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>

                            <!-- Participants Pills -->
                            <div class="flex items-center space-x-2">
                                <div
                                    class="bg-gray-50/80 dark:bg-gray-700/50 backdrop-blur-md text-gray-700 dark:text-gray-200 text-xs font-bold px-3 py-1.5 rounded-full shadow-sm ring-1 ring-gray-900/5 dark:ring-white/10 flex items-center">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    {{ $event->participants_count }}
                                </div>
                            </div>
                        </div>

                        <div class="flex-grow">
                            <h3
                                class="text-2xl font-black text-gray-900 dark:text-white tracking-tight leading-tight mb-2 group-hover:text-brand-500 dark:group-hover:text-brand-400 transition-colors">
                                {{ $event->name }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium flex items-center">
                                <svg class="w-4 h-4 mr-1.5 text-gray-400 group-hover:text-brand-400 transition-colors"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                {{ $event->date ? \Carbon\Carbon::parse($event->date)->format('M d, Y') : 'Date TBD' }}
                            </p>
                        </div>

                        <div
                            class="mt-8 pt-5 border-t border-gray-100 dark:border-gray-700/50 flex items-center justify-between">
                            <div
                                class="flex items-center text-sm font-bold {{ $event->creator_id === auth()->id() ? 'text-brand-600 dark:text-brand-400' : 'text-gray-500 dark:text-gray-400' }}">
                                @if ($event->creator_id === auth()->id())
                                    <span
                                        class="flex items-center bg-brand-50 dark:bg-brand-500/10 px-2.5 py-1 rounded-lg">
                                        <span class="w-1.5 h-1.5 rounded-full bg-brand-500 mr-1.5 animate-pulse"></span>
                                        Organizer
                                    </span>
                                @else
                                    <span
                                        class="flex items-center bg-gray-50 dark:bg-gray-700/50 px-2.5 py-1 rounded-lg">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400 mr-1.5"></span> Participant
                                    </span>
                                @endif
                            </div>

                            <!-- Forward Button Indicator -->
                            <div
                                class="w-10 h-10 rounded-2xl bg-white dark:bg-gray-800 border-2 border-gray-50 dark:border-gray-700 flex items-center justify-center text-gray-400 group-hover:bg-brand-500 group-hover:border-brand-500 group-hover:text-white transform group-hover:translate-x-1 transition-all duration-300 shadow-sm">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div
                    class="col-span-full py-16 flex flex-col items-center justify-center bg-transparent border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-[2.5rem] relative overflow-hidden group">
                    <div class="absolute inset-0 bg-brand-50 dark:bg-brand-900/10 opacity-50"></div>
                    <div class="relative z-10 flex flex-col items-center">
                        <div
                            class="w-20 h-20 bg-brand-100 dark:bg-gray-800 rounded-3xl flex items-center justify-center text-brand-500 mb-6 shadow-inner transform group-hover:scale-105 transition-transform">
                            <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-2 tracking-tight">No Events Yet
                        </h3>
                        <p class="text-gray-500 dark:text-gray-400 text-center max-w-sm mb-8 font-medium">Create your
                            first event to start logging expenses and effortlessly settling debts with friends.</p>
                        <a href="{{ route('events.create') }}"
                            class="px-8 py-3.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-bold rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all">
                            Create First Event
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
