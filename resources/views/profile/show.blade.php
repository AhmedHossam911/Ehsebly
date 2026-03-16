<x-app-layout>
    <div class="py-10 px-4 max-w-7xl mx-auto sm:px-6 lg:px-12 xl:px-16 space-y-10 w-full mb-10">
        
        <!-- Action Bar -->
        <div class="flex justify-between items-center mb-6">
            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('dashboard') }}" class="text-sm font-bold text-gray-600 dark:text-gray-400 hover:text-brand-600 dark:hover:text-brand-400 flex items-center group bg-white dark:bg-gray-800 px-4 py-2 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-all">
                <svg class="w-4 h-4 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back
            </a>
            
            @if(auth()->id() !== $user->id)
                @php
                    $isFriend = auth()->user()->friends->contains($user->id);
                    $sentRequest = auth()->user()->friendRequestsSent()->where('receiver_id', $user->id)->first();
                    $receivedRequest = auth()->user()->friendRequestsReceived()->where('sender_id', $user->id)->first();
                @endphp
                
                @if($isFriend)
                    <span class="px-4 py-2 bg-brand-100 text-brand-800 dark:bg-brand-900/30 dark:text-brand-400 rounded-xl text-sm font-bold flex items-center shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Friends
                    </span>
                @elseif($sentRequest)
                    <span class="px-4 py-2 bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 rounded-xl text-sm font-bold flex items-center shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Request Sent
                    </span>
                @elseif($receivedRequest)
                    <div class="flex space-x-2">
                        <form action="{{ route('friend-requests.accept', $receivedRequest) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-brand-600 text-white rounded-xl text-sm font-bold hover:bg-brand-700 transition-colors shadow-sm">Accept Request</button>
                        </form>
                    </div>
                @else
                    <form action="{{ route('friend-requests.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="friend_uid" value="{{ $user->uid }}">
                        <button type="submit" class="px-4 py-2 bg-gray-900 dark:bg-white text-white dark:text-gray-900 rounded-xl text-sm font-bold hover:shadow-lg transition-all flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                            Add Friend
                        </button>
                    </form>
                @endif
            @endif
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-xl shadow-gray-200/50 dark:shadow-none overflow-hidden border border-gray-100 dark:border-gray-700 relative">
            
            <!-- Cover/Gradient Background -->
            <div class="h-48 w-full bg-gradient-to-r from-brand-500 via-accent-500 to-purple-600 relative overflow-hidden">
                <svg class="absolute inset-0 w-full h-full opacity-20 pointer-events-none mix-blend-overlay" preserveAspectRatio="none" viewBox="0 0 100 100">
                    <path d="M0,50 Q25,30 50,50 T100,50 V100 H0 Z" fill="white" />
                    <path d="M0,80 Q25,60 50,80 T100,80 V100 H0 Z" fill="white" />
                </svg>
            </div>

            <div class="px-8 pb-12">
                <!-- Avatar Area -->
                <div class="relative -mt-20 mb-6 flex flex-col md:flex-row md:items-end justify-between items-center z-10">
                    <div class="flex flex-col items-center md:items-start">
                        <div class="h-40 w-40 rounded-full border-8 border-white dark:border-gray-800 overflow-hidden bg-white shadow-xl">
                            <img src="{{ $user->getAvatarUrl() }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                        </div>
                    </div>
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-4">
                    
                    <div class="md:col-span-2 space-y-6 text-center md:text-left">
                        <div>
                            <h1 class="text-4xl font-black text-gray-900 dark:text-white tracking-tight flex flex-col md:flex-row md:items-center gap-2">
                                {{ $user->name }}
                                <span class="bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 text-sm px-3 py-1 rounded-full font-bold self-center md:self-auto block w-max mx-auto md:mx-0">ID: {{ $user->uid }}</span>
                            </h1>
                            <p class="text-gray-500 dark:text-gray-400 mt-2 font-medium">Joined {{ $user->created_at->format('F Y') }}</p>
                        </div>

                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">About</h3>
                            <p class="text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-gray-900/50 p-4 rounded-2xl border border-gray-100 dark:border-gray-700/50">
                                {{ $user->bio ?? 'This user hasn\'t added a bio yet.' }}
                            </p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-700 pb-2 text-center md:text-left">Connect</h3>
                        
                        <div class="flex flex-col space-y-3">
                            @if($user->whatsapp)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->whatsapp) }}" target="_blank" class="flex items-center p-3 rounded-xl bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/40 text-green-700 dark:text-green-400 transition-colors group">
                                    <svg class="h-6 w-6 mr-3 transform group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M11.99 2C6.47 2 2 6.48 2 12c0 2.22.72 4.27 1.94 5.92L2.57 22l4.19-1.37A9.957 9.957 0 0011.99 22c5.52 0 10-4.48 10-10S17.51 2 11.99 2zm5.41 14.15c-.24.67-1.4 1.28-1.92 1.35-.45.06-.99.12-2.88-.61-2.28-.88-3.76-3.19-3.87-3.35-.12-.15-.92-1.23-.92-2.35 0-1.11.58-1.66.79-1.89.21-.22.45-.28.6-.28.15 0 .3 0 .44.02.16.02.37-.06.57.43.21.49.71 1.73.77 1.85.06.12.1.26.02.43-.08.16-.12.26-.24.39-.12.13-.26.29-.36.38-.1.09-.21.19-.08.41.13.22.58.96 1.25 1.55.86.76 1.58 1 1.8 1.11.22.11.35.09.47-.05.13-.13.56-.64.71-.86.15-.22.3-.18.5-.11.2.07 1.27.6 1.49.71.22.11.37.16.42.25.06.09.06.52-.18 1.19z"></path>
                                    </svg>
                                    <span class="font-bold">WhatsApp</span>
                                </a>
                            @endif

                            @if($user->instagram)
                                <a href="https://instagram.com/{{ ltrim($user->instagram, '@') }}" target="_blank" class="flex items-center p-3 rounded-xl bg-pink-50 dark:bg-pink-900/20 hover:bg-pink-100 dark:hover:bg-pink-900/40 text-pink-700 dark:text-pink-400 transition-colors group">
                                    <svg class="h-6 w-6 mr-3 transform group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                                    </svg>
                                    <span class="font-bold">Instagram</span>
                                </a>
                            @endif

                            @if($user->facebook)
                                <a href="{{ filter_var($user->facebook, FILTER_VALIDATE_URL) ? $user->facebook : 'https://facebook.com/' . $user->facebook }}" target="_blank" class="flex items-center p-3 rounded-xl bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/40 text-blue-700 dark:text-blue-400 transition-colors group">
                                    <svg class="h-6 w-6 mr-3 transform group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/>
                                    </svg>
                                    <span class="font-bold">Facebook</span>
                                </a>
                            @endif

                             @if($user->tiktok)
                                <a href="{{ filter_var($user->tiktok, FILTER_VALIDATE_URL) ? $user->tiktok : 'https://tiktok.com/@' . ltrim($user->tiktok, '@') }}" target="_blank" class="flex items-center p-3 rounded-xl bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-900 dark:text-gray-100 transition-colors group">
                                    <svg class="h-6 w-6 mr-3 transform group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 448 512">
                                        <path d="M448,209.91a210.06,210.06,0,0,1-122.77-39.25V349.38A162.55,162.55,0,1,1,185,188.31V278.2a74.62,74.62,0,1,0,52.23,71.18V0l88,0a121.18,121.18,0,0,0,1.86,22.17h0A122.18,122.18,0,0,0,381,102.39a121.43,121.43,0,0,0,67,20.14Z"/>
                                    </svg>
                                    <span class="font-bold">TikTok</span>
                                </a>
                            @endif

                            @if(!$user->whatsapp && !$user->instagram && !$user->facebook && !$user->tiktok)
                                <p class="text-sm text-gray-500 dark:text-gray-400 italic text-center md:text-left">No social links provided.</p>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</x-app-layout>
