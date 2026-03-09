<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Notifications') }}
        </h2>
    </x-slot>

    <div class="py-6 px-4 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        <div class="flex justify-between items-center bg-transparent mb-2">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">Updates</h1>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">Activity from your groups and friends</p>
            </div>
        </div>

        <div class="space-y-4">
            @forelse($notifications as $notif)
                <div class="flex items-start p-5 bg-white dark:bg-gray-800 rounded-[1.5rem] shadow-sm border {{ $notif->read_at ? 'border-gray-100 dark:border-gray-700 opacity-80' : 'border-brand-200 dark:border-brand-800 shadow-md transform hover:-translate-y-0.5' }} transition">
                    <div class="h-12 w-12 rounded-full flex-shrink-0 flex items-center justify-center {{ $notif->read_at ? 'bg-gray-100 dark:bg-gray-700 text-gray-500' : 'bg-brand-100 dark:bg-brand-900/40 text-brand-600 dark:text-brand-400' }}">
                        @if($notif->type === 'expense')
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                        @elseif($notif->type === 'settlement')
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        @else
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                        @endif
                    </div>
                    
                    <div class="ml-4 flex-grow">
                        <!-- We decode JSON data assuming it has a 'message' or 'title' -->
                        @php $data = json_decode($notif->data, true); @endphp
                        <p class="font-bold text-gray-900 dark:text-white">{{ $data['title'] ?? 'New Update' }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $data['message'] ?? 'You have a new activity.' }}</p>
                        <p class="text-xs text-gray-500 mt-2">{{ $notif->created_at->diffForHumans() }}</p>
                    </div>

                    @if(!$notif->read_at)
                    <div class="ml-2">
                        <form action="{{ route('notifications.read', $notif) }}" method="POST">
                            @csrf
                            <button type="submit" class="h-8 w-8 rounded-full bg-brand-50 hover:bg-brand-100 dark:bg-brand-900/30 dark:hover:bg-brand-900/50 flex items-center justify-center text-brand-600 dark:text-brand-400 transition" title="Mark as Read">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-[2rem] border border-gray-100 dark:border-gray-700 shadow-sm">
                    <div class="h-20 w-20 mx-auto bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mb-4">
                        <svg class="h-10 w-10 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">All caught up!</h3>
                    <p class="text-gray-500 dark:text-gray-400">You don't have any new notifications.</p>
                </div>
            @endforelse
        </div>
        
    </div>
</x-app-layout>
