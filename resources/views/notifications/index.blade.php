<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Notifications') }}
        </h2>
    </x-slot>

    <div class="py-6 px-4 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <div class="flex justify-between items-center bg-transparent mb-6 mt-4">
            <div>
                <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Updates</h1>
                <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 mt-1 uppercase tracking-wider">Activity
                    from your groups and friends</p>
            </div>
        </div>

        <div class="space-y-4">
            @forelse($notifications as $notif)
                @php
                    $isUnread = mb_strlen($notif->read_at) === 0;
                    $isExpense = str_contains($notif->type, 'Expense');
                    $isSettlement = str_contains($notif->type, 'Settlement');
                    $isInvite = str_contains($notif->type, 'Event');
                @endphp
                <div
                    class="group relative flex items-start p-5 bg-white dark:bg-gray-800 rounded-3xl shadow-sm border {{ $isUnread ? 'border-brand-200 dark:border-brand-800/50 hover:shadow-lg hover:-translate-y-1' : 'border-gray-100 dark:border-gray-700/50 opacity-80 hover:opacity-100' }} transition-all duration-300">

                    @if ($isUnread)
                        <div
                            class="absolute -left-1 top-1/2 -translate-y-1/2 w-2 h-10 bg-brand-500 rounded-r-lg shadow-[0_0_10px_rgba(16,185,129,0.5)]">
                        </div>
                    @endif

                    <div
                        class="h-14 w-14 rounded-2xl flex-shrink-0 flex items-center justify-center shadow-inner {{ $isUnread ? 'bg-gradient-to-br from-brand-50 to-brand-100 dark:from-brand-900/40 dark:to-brand-800/20 text-brand-600 dark:text-brand-400 border border-brand-200 dark:border-brand-700/50' : 'bg-gray-50 dark:bg-gray-900 text-gray-400 border border-gray-100 dark:border-gray-800' }}">
                        @if ($isExpense)
                            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        @elseif($isSettlement)
                            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        @else
                            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        @endif
                    </div>

                    <div class="ml-5 flex-grow">
                        <p
                            class="text-lg font-bold text-gray-900 dark:text-white tracking-tight group-hover:text-brand-600 dark:group-hover:text-brand-400 transition-colors">
                            {{ $notif->data['title'] ?? 'System Update' }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 leading-relaxed">
                            {{ $notif->data['message'] ?? 'You have a new activity.' }}</p>
                        <div class="flex items-center mt-3">
                            <span
                                class="text-xs font-semibold text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-900 px-2 py-1 rounded-md">{{ $notif->created_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    @if ($isUnread)
                        <div class="ml-4 flex-shrink-0">
                            <form action="{{ route('notifications.read', $notif) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="h-10 w-10 rounded-full bg-brand-50 hover:bg-brand-500 text-brand-500 hover:text-white dark:bg-brand-900/30 dark:hover:bg-brand-600 dark:text-brand-400 flex items-center justify-center transition-all duration-300 transform hover:scale-110 shadow-sm"
                                    title="Mark as Read">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @empty
                <x-empty-state title="All caught up!"
                    description="You don't have any new notifications to review right now.">
                    <x-slot name="icon">
                        <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </x-slot>
                </x-empty-state>
            @endforelse
        </div>

    </div>
</x-app-layout>
