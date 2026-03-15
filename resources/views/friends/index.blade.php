<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-900 dark:text-white leading-tight tracking-tight">
            {{ __('Friends') }}
        </h2>
    </x-slot>

    <div class="py-8 px-4 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8 mb-10">
        
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 relative z-10">
            <div>
                <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Your Friends List</h1>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">Manage connections to easily split expenses.</p>
            </div>
            <a href="{{ route('friend-requests.index') }}" class="group relative inline-flex items-center justify-center px-6 py-3 font-bold text-white transition-all duration-200 bg-purple-600 border border-transparent rounded-2xl hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 shadow-lg shadow-purple-500/30 overflow-hidden transform hover:-translate-y-0.5">
                <div class="absolute inset-0 w-full h-full -mt-1 rounded-lg opacity-30 bg-gradient-to-b from-transparent via-transparent to-black"></div>
                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                <span class="relative">Friend Requests</span>
            </a>
        </div>


        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 relative z-10">
            @forelse($friends as $friend)
                <div x-data="{ openRemoveModal: false }" class="group relative bg-white dark:bg-gray-800 rounded-[2rem] p-6 shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col items-center hover:shadow-2xl hover:-translate-y-1.5 transition-all duration-300 overflow-hidden text-center">
                    
                    <!-- Decorative Background Vector -->
                    <svg class="absolute -left-6 -bottom-6 w-32 h-32 opacity-[0.03] dark:opacity-[0.02] text-current transform group-hover:scale-110 transition-transform duration-500 pointer-events-none" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="40" fill="currentColor"/>
                    </svg>

                    <!-- Avatar -->
                    <a href="{{ route('profile.show', $friend->uid) }}" class="relative h-20 w-20 rounded-full bg-gradient-to-tr from-purple-500 to-indigo-500 p-1 mb-4 shadow-md group-hover:shadow-lg transition-shadow block z-10">
                        <div class="h-full w-full bg-white dark:bg-gray-900 rounded-full flex items-center justify-center overflow-hidden border border-white dark:border-gray-800">
                            @if($friend->avatar)
                                <img src="{{ asset('storage/' . $friend->avatar) }}" alt="Avatar" class="h-full w-full object-cover">
                            @else
                                <span class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-br from-purple-500 to-indigo-600">{{ substr($friend->name, 0, 1) }}</span>
                            @endif
                        </div>
                    </a>
                    
                    <a href="{{ route('profile.show', $friend->uid) }}" class="z-10 relative">
                        <h3 class="text-xl font-black text-gray-900 dark:text-white tracking-tight leading-tight group-hover:text-purple-500 dark:group-hover:text-purple-400 transition-colors">{{ $friend->name }}</h3>
                    </a>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-6 font-mono bg-gray-50 dark:bg-gray-700/50 px-3 py-1 rounded-full mt-2 border border-gray-200 dark:border-gray-600 z-10 relative">ID: {{ $friend->uid }}</p>
                    
                    <div class="w-full flex space-x-2 mt-auto relative z-10">
                        <button @click="openRemoveModal = true" type="button" class="w-full px-4 py-3 bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400 rounded-2xl text-sm font-bold hover:bg-red-100 dark:hover:bg-red-500/20 transition-colors border border-red-100 dark:border-red-900/50">
                            Remove Friend
                        </button>
                    </div>

                    <!-- Confirmation Modal -->
                    <div x-show="openRemoveModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" x-transition.opacity>
                        <div @click.away="openRemoveModal = false" class="bg-white dark:bg-gray-800 rounded-3xl p-6 sm:p-8 max-w-sm w-full mx-4 shadow-2xl transform transition-all" x-show="openRemoveModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                            
                            <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center text-red-600 dark:text-red-400 mb-6 mx-auto shadow-sm">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zm11-2h-4v-2h4v2z"/></svg>
                            </div>
                            
                            <h3 class="text-xl font-black text-gray-900 dark:text-white mb-2 tracking-tight text-center">Remove Friend?</h3>
                            <p class="text-gray-500 dark:text-gray-400 text-center mb-8 font-medium">Are you sure you want to remove <strong class="text-gray-700 dark:text-gray-300">{{ $friend->name }}</strong> from your friends list?</p>
                            
                            <div class="flex flex-col sm:flex-row gap-3">
                                <button @click="openRemoveModal = false" type="button" class="w-full px-5 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold rounded-2xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                    Cancel
                                </button>
                                <form action="{{ route('friends.destroy', $friend) }}" method="POST" class="w-full">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full px-5 py-3 bg-red-600 text-white font-bold rounded-2xl hover:bg-red-700 shadow-md shadow-red-500/30 transition-all">
                                        Yes, Remove
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            @empty
                <div class="col-span-full py-16 flex flex-col items-center justify-center bg-transparent border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-[2.5rem] relative overflow-hidden group">
                    <div class="absolute inset-0 bg-purple-50 dark:bg-purple-900/10 opacity-50"></div>
                    <div class="relative z-10 flex flex-col items-center">
                        <div class="w-20 h-20 bg-purple-100 dark:bg-gray-800 rounded-3xl flex items-center justify-center text-purple-500 mb-6 shadow-inner transform group-hover:scale-105 transition-transform">
                            <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        </div>
                        <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-2 tracking-tight">No Friends Yet</h3>
                        <p class="text-gray-500 dark:text-gray-400 text-center max-w-sm font-medium">Add friends to easily split bills without asking for their InstaPay details every time.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
