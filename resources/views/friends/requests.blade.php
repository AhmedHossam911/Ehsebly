<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Friend Requests') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Send Request UI -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Add a New Friend</h3>
                    


                    <form action="{{ route('friend-requests.store') }}" method="POST" class="flex gap-4">
                        @csrf
                        <div class="flex-grow">
                            <x-text-input id="identifier" name="identifier" type="text" class="block w-full" placeholder="Enter Phone Number or UID (e.g. AB12CD)" value="{{ old('identifier') }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('identifier')" />
                        </div>
                        <x-primary-button>
                            {{ __('Send Request') }}
                        </x-primary-button>
                    </form>
                </div>
            </div>

            <!-- Incoming Requests -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Incoming Requests</h3>
                    
                    <div class="space-y-4">
                        @forelse($requests as $request)
                            <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                                <div class="flex items-center space-x-4">
                                    <div class="h-12 w-12 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-700 dark:text-indigo-300 font-bold">
                                        {{ substr($request->sender->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $request->sender->name }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $request->sender->uid }}</p>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <form action="{{ route('friend-requests.accept', $request) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 transition">Accept</button>
                                    </form>
                                    <form action="{{ route('friend-requests.reject', $request) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200 rounded-lg text-sm font-semibold hover:bg-gray-300 dark:hover:bg-gray-600 transition">Reject</button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 dark:text-gray-400 py-4 text-center">No pending friend requests.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
