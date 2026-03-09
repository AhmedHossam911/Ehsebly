<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="antialiased light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>Ehsebly - Split Bills with Friends</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800,900&display=swap" rel="stylesheet" />
    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Outfit', 'sans-serif'] },
                    colors: {
                        brand: { 50: '#ecfdf5', 100: '#d1fae5', 400: '#34d399', 500: '#10b981', 600: '#059669', 900: '#064e3b' },
                        accent: { 500: '#6366f1', 600: '#4f46e5' }
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'float-delayed': 'float 6s ease-in-out 3s infinite',
                        'blob': 'blob 7s infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
                        },
                        blob: {
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                            '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' },
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans text-gray-900 bg-[#fafafa] dark:bg-[#0f172a] dark:text-gray-100 overflow-x-hidden selection:bg-brand-500 selection:text-white transition-colors duration-300">

    <!-- Background Blobs / Vectors -->
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 -left-4 w-72 h-72 bg-brand-400 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob dark:opacity-20 dark:mix-blend-screen"></div>
        <div class="absolute top-0 -right-4 w-72 h-72 bg-accent-400 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000 dark:opacity-20 dark:mix-blend-screen"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000 dark:opacity-20 dark:mix-blend-screen"></div>
        
        <!-- Abstract Vector SVG -->
        <svg class="absolute right-0 top-1/2 transform -translate-y-1/2 opacity-10 dark:opacity-5 text-brand-600 w-full md:w-1/2 h-auto" viewBox="0 0 100 100" preserveAspectRatio="none">
            <path d="M0,50 Q25,25 50,50 T100,50 V100 H0 Z" fill="currentColor"/>
            <path d="M0,70 Q25,45 50,70 T100,70 V100 H0 Z" fill="currentColor" fill-opacity="0.5"/>
        </svg>
    </div>

    <div class="relative z-10 flex flex-col min-h-screen">
        <!-- Navbar -->
        <nav class="w-full px-6 py-4 flex justify-between items-center bg-white/50 dark:bg-[#0f172a]/50 backdrop-blur-xl border-b border-gray-200/50 dark:border-gray-800/50 fixed top-0 z-50">
            <a href="{{ url('/') }}" class="flex items-center space-x-2 group">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-brand-500 to-accent-500 flex items-center justify-center shadow-lg shadow-brand-500/30 transform group-hover:-rotate-6 transition-transform cursor-pointer">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <span class="text-2xl font-black tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-300">Ehsebly</span>
            </a>
            
            <div class="flex items-center space-x-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="font-bold text-gray-700 hover:text-brand-600 dark:text-gray-300 dark:hover:text-brand-400 transition-colors">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="font-bold text-gray-700 hover:text-brand-600 dark:text-gray-300 dark:hover:text-brand-400 transition-colors">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-full bg-gray-900 text-white dark:bg-white dark:text-gray-900 font-bold hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">Get Started</a>
                        @endif
                    @endauth
                @endif
            </div>
        </nav>

        <!-- Hero Section -->
        <main class="flex-grow flex items-center justify-center px-4 sm:px-6 lg:px-8 pt-24 pb-12">
            <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                
                <!-- Hero Text -->
                <div class="text-center lg:text-left space-y-8 z-10">
                    <div class="inline-flex items-center px-3 py-1 rounded-full bg-brand-50 dark:bg-brand-500/10 border border-brand-200 dark:border-brand-500/20 text-brand-600 dark:text-brand-400 text-sm font-semibold mb-2 shadow-sm animate-fade-in-up">
                        <span class="flex h-2 w-2 rounded-full bg-brand-500 mr-2 animate-pulse"></span>
                        The smarter way to split
                    </div>
                    
                    <h1 class="text-5xl sm:text-6xl md:text-7xl font-black tracking-tighter text-gray-900 dark:text-white leading-[1.1] animate-fade-in-up shadow-sm" style="animation-delay: 0.1s;">
                        Settle up without <br/><span class="bg-clip-text text-transparent bg-gradient-to-r from-brand-500 via-accent-500 to-purple-500">the awkwardness.</span>
                    </h1>
                    
                    <p class="mt-6 text-lg sm:text-xlg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto lg:mx-0 font-medium animate-fade-in-up leading-relaxed" style="animation-delay: 0.2s;">
                        Ehsebly (احسبلي) automatically calculates who owes who after outings, trips, and dinners. Connects directly with InstaPay for instant settlements.
                    </p>
                    
                    <div class="mt-8 flex flex-col sm:flex-row justify-center lg:justify-start gap-4 animate-fade-in-up" style="animation-delay: 0.3s;">
                        <a href="{{ route('register') }}" class="group relative px-8 py-4 rounded-full bg-gradient-to-r from-brand-500 to-accent-500 text-white font-bold text-lg hover:shadow-2xl hover:shadow-brand-500/30 transform hover:-translate-y-1 transition-all duration-300 overflow-hidden flex items-center justify-center">
                            <span class="relative z-10 flex items-center">Create Free Account <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg></span>
                            <div class="absolute inset-0 h-full w-full bg-gradient-to-r from-accent-500 to-brand-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-0"></div>
                        </a>
                        <a href="#how-it-works" class="px-8 py-4 rounded-full bg-white dark:bg-gray-800 text-gray-900 dark:text-white font-bold text-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300">
                            See how it works
                        </a>
                    </div>

                    <div class="mt-10 flex items-center justify-center lg:justify-start space-x-4 animate-fade-in-up" style="animation-delay: 0.4s;">
                        <div class="flex -space-x-3">
                            <img class="w-10 h-10 rounded-full border-2 border-white dark:border-gray-900" src="https://ui-avatars.com/api/?name=A&background=random&color=fff" alt="User">
                            <img class="w-10 h-10 rounded-full border-2 border-white dark:border-gray-900" src="https://ui-avatars.com/api/?name=M&background=random&color=fff" alt="User">
                            <img class="w-10 h-10 rounded-full border-2 border-white dark:border-gray-900" src="https://ui-avatars.com/api/?name=S&background=random&color=fff" alt="User">
                            <img class="w-10 h-10 rounded-full border-2 border-white dark:border-gray-900" src="https://ui-avatars.com/api/?name=O&background=random&color=fff" alt="User">
                            <div class="w-10 h-10 rounded-full border-2 border-white dark:border-gray-900 bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-xs font-bold text-gray-600 dark:text-gray-300">+2k</div>
                        </div>
                        <p class="text-sm font-semibold text-gray-600 dark:text-gray-400">Trusted by 2,000+ friends in Egypt</p>
                    </div>
                </div>

                <!-- Hero Vector Illustrator (Glass effect mockups) -->
                <div class="relative w-full max-w-lg mx-auto lg:max-w-none flex justify-center lg:justify-end perspective-1000">
                    
                    <!-- Main Card -->
                    <div class="relative w-[320px] h-[600px] bg-white/40 dark:bg-gray-900/40 backdrop-blur-2xl rounded-[3rem] border border-white/60 dark:border-gray-700/50 shadow-2xl p-6 flex flex-col animate-float z-20">
                        <div class="w-20 h-1.5 bg-gray-300 dark:bg-gray-700 mx-auto rounded-full mb-8"></div>
                        
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h3 class="font-bold text-gray-900 dark:text-white text-lg">Total Balance</h3>
                                <p class="text-gray-500 font-medium text-sm">You are owed</p>
                            </div>
                            <div class="w-12 h-12 bg-gradient-to-br from-brand-400 to-accent-500 rounded-2xl flex items-center justify-center text-white shadow-lg">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                        </div>
                        <h2 class="text-4xl font-black text-gray-900 dark:text-white tracking-tight mb-8">1,450 <span class="text-lg text-gray-400">EGP</span></h2>
                        
                        <!-- Floating list items (Vectors) -->
                        <div class="space-y-4 flex-grow relative">
                            <!-- Item 1 -->
                            <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex items-center">
                                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-500 font-bold mr-3 shadow-inner">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                                </div>
                                <div class="flex-grow">
                                    <p class="font-bold text-gray-900 dark:text-white text-sm">Dinner at Mince</p>
                                    <p class="text-xs text-gray-500 font-medium">Omar paid</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-black text-red-500">-240 EGP</p>
                                </div>
                            </div>

                            <!-- Item 2 -->
                            <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex items-center">
                                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-500 font-bold mr-3 shadow-inner">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" /></svg>
                                </div>
                                <div class="flex-grow">
                                    <p class="font-bold text-gray-900 dark:text-white text-sm">Sahel Tickets</p>
                                    <p class="text-xs text-gray-500 font-medium">You paid</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-black text-brand-500">+800 EGP</p>
                                </div>
                            </div>
                        </div>

                        <!-- Settle Button -->
                        <div class="mt-auto w-full bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-bold rounded-2xl py-4 text-center cursor-pointer shadow-lg transform hover:scale-[1.02] transition-transform">
                            Settle Up Now
                        </div>
                    </div>

                    <!-- Floating Card Behind (Vector decoration) -->
                    <div class="absolute -right-12 top-20 w-64 h-48 bg-white/60 dark:bg-gray-800/60 backdrop-blur-xl rounded-[2rem] border border-white dark:border-gray-700 shadow-xl p-5 animate-float-delayed z-10 transform rotate-6 hidden sm:block">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center pb-0.5"><span class="text-2xl">📸</span></div>
                            <h4 class="font-bold text-gray-900 dark:text-white">Smart OCR</h4>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-300 font-medium mb-3">Snap a photo of the receipt to automatically extract items.</p>
                        <div class="w-full h-8 bg-gray-100 dark:bg-gray-700 rounded-lg animate-pulse"></div>
                    </div>

                </div>
            </div>
        </main>

        <!-- Features Section -->
        <section id="how-it-works" class="py-24 relative z-20 bg-white/60 dark:bg-[#0f172a]/80 backdrop-blur-3xl border-y border-gray-200/50 dark:border-gray-800/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-brand-600 dark:text-brand-400 font-bold tracking-wide uppercase text-sm mb-3">How it works</h2>
                    <h3 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white">Everything you need to <br/>split seamlessly.</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                    <!-- Feature 1 -->
                     <div class="bg-gray-50/50 dark:bg-gray-800/40 rounded-[2.5rem] p-8 border border-gray-100 dark:border-gray-700/50 hover:shadow-2xl hover:shadow-brand-500/10 transition-all duration-300 group">
                        <div class="w-16 h-16 rounded-2xl bg-brand-100 dark:bg-brand-500/20 text-brand-600 dark:text-brand-400 flex items-center justify-center mb-6 transform group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        </div>
                        <h4 class="text-2xl font-bold text-gray-900 dark:text-white mb-3 tracking-tight">Create Events</h4>
                        <p class="text-gray-600 dark:text-gray-400 font-medium leading-relaxed">Create a group for your trip or dinner and invite your friends. Everyone can see who paid for what in real time.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="bg-gray-50/50 dark:bg-gray-800/40 rounded-[2.5rem] p-8 border border-gray-100 dark:border-gray-700/50 hover:shadow-2xl hover:shadow-accent-500/10 transition-all duration-300 group">
                        <div class="w-16 h-16 rounded-2xl bg-accent-100 dark:bg-accent-500/20 text-accent-600 dark:text-accent-400 flex items-center justify-center mb-6 transform group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                        </div>
                        <h4 class="text-2xl font-bold text-gray-900 dark:text-white mb-3 tracking-tight">Track Expenses</h4>
                        <p class="text-gray-600 dark:text-gray-400 font-medium leading-relaxed">Add expenses manually or use our smart OCR to scan receipts. We'll automatically split the costs equally or exactly.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="bg-gray-50/50 dark:bg-gray-800/40 rounded-[2.5rem] p-8 border border-gray-100 dark:border-gray-700/50 hover:shadow-2xl hover:shadow-purple-500/10 transition-all duration-300 group">
                        <div class="w-16 h-16 rounded-2xl bg-purple-100 dark:bg-purple-500/20 text-purple-600 dark:text-purple-400 flex items-center justify-center mb-6 transform group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </div>
                        <h4 class="text-2xl font-bold text-gray-900 dark:text-white mb-3 tracking-tight">Settle Instantly</h4>
                        <p class="text-gray-600 dark:text-gray-400 font-medium leading-relaxed">Our smart algorithm calculates the minimum number of transactions needed. Settle up seamlessly via InstaPay integration.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-24 relative z-20 overflow-hidden">
            <div class="max-w-5xl mx-auto px-6 relative z-10 text-center">
                <h2 class="text-4xl sm:text-5xl font-black text-gray-900 dark:text-white mb-6">Ready to stop doing math at dinner?</h2>
                <p class="text-xl text-gray-600 dark:text-gray-400 font-medium max-w-2xl mx-auto mb-10">Join thousands of people using Ehsebly to keep their friendships intact and their wallets balanced.</p>
                <a href="{{ route('register') }}" class="inline-flex px-10 py-5 rounded-full bg-gray-900 text-white dark:bg-white dark:text-gray-900 font-bold text-xl shadow-2xl hover:scale-105 transition-transform">
                    Get Started for Free
                </a>
            </div>
        </section>

        <!-- Policy Modals Container using AlpineJS -->
        <div x-data="{ policiesOpen: false, activePolicy: '' }" @open-policy.window="policiesOpen = true; activePolicy = $event.detail">
            
            <!-- Policy Modal -->
            <div x-show="policiesOpen" 
                class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                style="display: none;">
                
                <div @click.away="policiesOpen = false" 
                    class="bg-white dark:bg-gray-800 rounded-[2rem] w-full max-w-2xl max-h-[85vh] overflow-hidden shadow-2xl flex flex-col"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-y-8 scale-95">
                    
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-900/50">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white" x-text="activePolicy === 'privacy' ? 'Privacy Policy' : 'Terms of Service'"></h3>
                        <button @click="policiesOpen = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 focus:outline-none bg-gray-200/50 dark:bg-gray-700/50 rounded-full p-2">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                    
                    <div class="p-6 overflow-y-auto font-medium text-gray-600 dark:text-gray-300 space-y-4 text-sm leading-relaxed" x-show="activePolicy === 'privacy'">
                        <p><strong>1. Information Collection:</strong> We collect information you provide directly to us when you create an account, such as your name and email. We temporarily store receipt images exclusively for OCR parsing and immediately discard them in accordance with strict security standards.</p>
                        <p><strong>2. Use of Information:</strong> The information is used to provide, maintain, and improve our services to you, track your expenses, and calculate debt distributions between your selected friends.</p>
                        <p><strong>3. Data Sharing:</strong> We do not sell, trade, or otherwise transfer to outside parties your Personally Identifiable Information. This does not include trusted third parties who assist us in operating our application, so long as those parties agree to keep this information confidential.</p>
                        <p><strong>4. Security:</strong> We implement a variety of security measures to maintain the safety of your personal information when you enter, submit, or access your personal information.</p>
                    </div>

                    <div class="p-6 overflow-y-auto font-medium text-gray-600 dark:text-gray-300 space-y-4 text-sm leading-relaxed" x-show="activePolicy === 'terms'">
                        <p><strong>1. Acceptance of Terms:</strong> By accessing and using Ehsebly, you accept and agree to be bound by the terms and provision of this agreement.</p>
                        <p><strong>2. Description of Service:</strong> Ehsebly is an independent financial tracking utility for splitting bills among peers. We do not act as a bank, financial institution, or payment processor. Settlements recorded via the application or mapped to InstaPay are strictly user-directed.</p>
                        <p><strong>3. User Responsibilities:</strong> You are responsible for maintaining the confidentiality of your account and password. You are solely responsible for all activities that occur under your account.</p>
                        <p><strong>4. Limitation of Liability:</strong> In no event shall Ehsebly or its creators be liable for any direct, indirect, incidental, special, or consequential damages resulting from the use or inability to use the service.</p>
                    </div>
                    
                    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 text-right bg-gray-50/50 dark:bg-gray-900/50">
                        <button @click="policiesOpen = false" class="px-6 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-bold rounded-xl text-sm hover:shadow-lg transform hover:-translate-y-0.5 transition-all">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
