<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-900 dark:text-white leading-tight tracking-tight">
            {{ __('Friend Requests') }}
        </h2>
    </x-slot>

    <div class="py-8 px-4 max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8 mb-10">

        <x-page-header title="Friend Requests" description="Connect with friends to easily split bills together.">
            <x-slot name="action">
                <a href="{{ route('friends.index') }}"
                    class="text-sm font-bold text-gray-600 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 flex items-center group bg-white dark:bg-gray-800 px-4 py-2 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-all">
                    <svg class="w-4 h-4 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Friends
                </a>
            </x-slot>
        </x-page-header>

        <!-- Send Request -->
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 sm:p-8">
            <h3 class="text-xl font-black text-gray-900 dark:text-white mb-1 tracking-tight">Add a New Friend</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Enter their phone number or unique ID to send a
                request.</p>

            <form action="{{ route('friend-requests.store') }}" method="POST"
                class="flex flex-col sm:flex-row gap-3">
                @csrf
                <div class="flex-grow">
                    <x-text-input id="identifier" name="identifier" type="text" class="block w-full"
                        placeholder="Phone Number or UID (e.g. AB12CD)" value="{{ old('identifier') }}" required />
                    <x-input-error class="mt-2" :messages="$errors->get('identifier')" />
                </div>
                <x-primary-button class="sm:h-[46px] whitespace-nowrap">
                    {{ __('Send Request') }}
                </x-primary-button>
            </form>
        </div>

        <!-- Incoming Requests -->
        <div>
            <div class="flex items-center justify-between px-1 mb-4">
                <h3 class="text-xl md:text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">Incoming
                    Requests</h3>
                <span
                    class="px-3 py-1 bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400 rounded-full text-xs font-bold tracking-wide shadow-sm">{{ $requests->count() }}
                    Pending</span>
            </div>

            <div class="space-y-4">
                @forelse ($requests as $request)
                    <div
                        class="flex items-center justify-between p-5 bg-white dark:bg-gray-800 rounded-[1.5rem] shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-all duration-300">
                        <div class="flex items-center space-x-4">
                            <div
                                class="h-12 w-12 rounded-full bg-gradient-to-tr from-purple-500 to-indigo-500 p-0.5 shadow-sm">
                                <div
                                    class="h-full w-full bg-white dark:bg-gray-900 rounded-full flex items-center justify-center font-bold text-purple-600 dark:text-purple-400">
                                    {{ substr($request->sender->name, 0, 1) }}
                                </div>
                            </div>
                            <div>
                                <p class="font-bold text-gray-900 dark:text-white">{{ $request->sender->name }}</p>
                                <p class="text-xs font-mono text-gray-500 dark:text-gray-400">{{ $request->sender->uid }}</p>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <form action="{{ route('friend-requests.accept', $request) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="px-4 py-2 bg-brand-600 text-white rounded-xl text-sm font-bold hover:bg-brand-700 shadow-sm transition-colors">
                                    Accept
                                </button>
                            </form>
                            <form action="{{ route('friend-requests.reject', $request) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="px-4 py-2 bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200 rounded-xl text-sm font-bold hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                    Reject
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <x-empty-state title="No Pending Requests" color="purple"
                        description="When someone sends you a friend request, it'll show up here.">
                        <x-slot name="icon">
                            <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </x-slot>
                    </x-empty-state>
                @endforelse
            </div>
        </div>

    </div>
</x-app-layout>
