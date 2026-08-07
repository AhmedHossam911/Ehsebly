<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{
    darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
}" x-bind:class="{ 'dark': darkMode }"
    class="antialiased">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Ehsebly Auth</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Tailwind & Alpine JS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif']
                    },
                    colors: {
                        brand: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            900: '#064e3b'
                        },
                        accent: {
                            500: '#6366f1',
                            600: '#4f46e5'
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="font-sans text-gray-900 bg-[#fafafa] dark:bg-[#0f172a] dark:text-gray-100 overflow-x-hidden min-h-screen">

    <!-- Abstract Vector Background -->
    <div
        class="fixed inset-0 z-0 pointer-events-none opacity-20 dark:opacity-10 mix-blend-multiply dark:mix-blend-screen">
        <svg class="absolute top-0 right-0 w-full h-full text-brand-500" viewBox="0 0 100 100"
            preserveAspectRatio="none">
            <path d="M100,0 L100,100 C50,80 50,20 0,0 Z" fill="currentColor" />
        </svg>
    </div>

    <!-- Content Slot -->
    <div class="relative z-10 w-full min-h-screen flex flex-col items-center justify-center">

        <div class="w-full flex-grow flex items-center justify-center relative z-20">
            {{ $slot }}
        </div>

        <!-- Footer Component -->
        <x-footer class="mt-8" />
    </div>

    <!-- Global Form Loading Spinner -->
    <script>
        document.addEventListener('submit', function(e) {
            if (e.defaultPrevented) return;

            const form = e.target;
            if (!form || form.tagName !== 'FORM') return;

            if (form.hasAttribute('data-submitting')) {
                e.preventDefault();
                return;
            }

            form.setAttribute('data-submitting', 'true');
            const btn = form.querySelector('button[type="submit"]');
            
            if (btn) {
                const w = btn.offsetWidth;
                if (w > 0) btn.style.width = w + 'px';
                
                // Use pointer-events-none instead of disabled to avoid breaking form submission in some browsers
                btn.classList.add('opacity-80', 'cursor-not-allowed', 'pointer-events-none', 'flex', 'items-center', 'justify-center', 'transition-all');
                btn.innerHTML = `<svg class="animate-spin h-5 w-5 text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>`;
            }
        });
    </script>
</body>

</html>
