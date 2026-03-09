<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="antialiased light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Ehsebly Auth</title>

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
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans text-gray-900 bg-[#fafafa] dark:bg-[#0f172a] dark:text-gray-100 overflow-x-hidden min-h-screen">
    
    <!-- Abstract Vector Background -->
    <div class="fixed inset-0 z-0 pointer-events-none opacity-20 dark:opacity-10 mix-blend-multiply dark:mix-blend-screen">
        <svg class="absolute top-0 right-0 w-full h-full text-brand-500" viewBox="0 0 100 100" preserveAspectRatio="none">
            <path d="M100,0 L100,100 C50,80 50,20 0,0 Z" fill="currentColor"/>
        </svg>
    </div>

    <!-- Content Slot -->
    <div class="relative z-10 w-full min-h-screen flex flex-col items-center justify-center">
        <a href="{{ url('/') }}" class="absolute top-8 left-8 flex items-center space-x-2 group hover:opacity-80 transition-opacity">
            <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-brand-500 to-accent-500 flex items-center justify-center shadow-lg shadow-brand-500/30 transform group-hover:rotate-12 transition-transform">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <span class="text-xl font-black tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-300">Ehsebly</span>
        </a>

        <div class="w-full max-w-md flex-grow flex items-center justify-center relative z-20">
            {{ $slot }}
        </div>

        <!-- Footer Component -->
        <x-footer class="mt-8" />
    </div>

</body>
</html>
