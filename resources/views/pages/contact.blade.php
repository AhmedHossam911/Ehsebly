<x-guest-layout>
    <div class="h-full flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div
            class="max-w-2xl w-full space-y-8 bg-white dark:bg-gray-800 p-8 sm:p-12 rounded-[2.5rem] shadow-2xl border border-gray-100 dark:border-gray-700 relative overflow-hidden">

            <div class="absolute -top-24 -left-24 w-48 h-48 bg-orange-500/10 rounded-full blur-3xl pointer-events-none">
            </div>
            <div
                class="absolute -bottom-24 -right-24 w-48 h-48 bg-brand-500/10 rounded-full blur-3xl pointer-events-none">
            </div>

            <div class="relative z-10 text-center">
                <div
                    class="w-20 h-20 mx-auto bg-brand-50 dark:bg-brand-500/10 rounded-full flex items-center justify-center text-brand-500 mb-6 shadow-inner">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>

                <h1 class="text-4xl font-black text-gray-900 dark:text-white tracking-tight mb-4">Contact Us</h1>
                <p class="text-lg text-gray-500 dark:text-gray-400 font-medium mb-8">We'd love to hear from you. Please
                    reach out with any questions, feedback, or support requests.</p>

                <div
                    class="bg-gray-50 dark:bg-gray-900/50 rounded-2xl p-6 border border-gray-100 dark:border-gray-800 mb-8 inline-block">
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-1">Email Support</p>
                    <a href="mailto:support@ehsebly.com"
                        class="text-2xl font-bold text-brand-600 dark:text-brand-400 hover:text-brand-700 dark:hover:text-brand-300 transition-colors">support@ehsebly.com</a>
                </div>

                <div class="mt-8 text-center pt-8 border-t border-gray-100 dark:border-gray-800">
                    <a href="{{ route('login') }}"
                        class="font-bold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white transition-colors">
                        Return to Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
