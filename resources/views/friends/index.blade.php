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

        @if (session('status'))
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded-2xl relative shadow-sm font-medium" role="alert">
                <span class="block sm:inline">{{ session('status') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 relative z-10">
            @forelse($friends as $friend)
                <div class="group relative bg-white dark:bg-gray-800 rounded-[2rem] p-6 shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col items-center hover:shadow-2xl hover:-translate-y-1.5 transition-all duration-300 overflow-hidden text-center">
                    
                    <!-- Decorative Background Vector -->
                    <svg class="absolute -left-6 -bottom-6 w-32 h-32 opacity-[0.03] dark:opacity-[0.02] text-current transform group-hover:scale-110 transition-transform duration-500" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="40" fill="currentColor"/>
                    </svg>

                    <!-- Avatar -->
                    <div class="relative h-20 w-20 rounded-full bg-gradient-to-tr from-purple-500 to-indigo-500 p-1 mb-4 shadow-md group-hover:shadow-lg transition-shadow">
                        <div class="h-full w-full bg-white dark:bg-gray-900 rounded-full flex items-center justify-center overflow-hidden border border-white dark:border-gray-800">
                            @if($friend->avatar)
                                <img src="{{ asset('storage/' . $friend->avatar) }}" alt="Avatar" class="h-full w-full object-cover">
                            @else
                                <span class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-br from-purple-500 to-indigo-600">{{ substr($friend->name, 0, 1) }}</span>
                            @endif
                        </div>
                    </div>
                    
                    <h3 class="text-xl font-black text-gray-900 dark:text-white tracking-tight leading-tight group-hover:text-purple-500 dark:group-hover:text-purple-400 transition-colors">{{ $friend->name }}</h3>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-6 font-mono bg-gray-50 dark:bg-gray-700/50 px-3 py-1 rounded-full mt-2 border border-gray-200 dark:border-gray-600">ID: {{ $friend->uid }}</p>
                    
                    <div class="w-full flex space-x-2 mt-auto relative z-10">
                        <form action="{{ route('friends.destroy', $friend) }}" method="POST" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full px-4 py-3 bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400 rounded-2xl text-sm font-bold hover:bg-red-100 dark:hover:bg-red-500/20 transition-colors border border-red-100 dark:border-red-900/50">
                                Remove Friend
                            </button>
                        </form>
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
