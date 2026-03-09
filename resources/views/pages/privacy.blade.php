<x-guest-layout>
    <div class="h-full flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div
            class="max-w-3xl w-full space-y-8 bg-white dark:bg-gray-800 p-8 sm:p-12 rounded-[2.5rem] shadow-2xl border border-gray-100 dark:border-gray-700 relative overflow-hidden">

            <div class="absolute -top-24 -left-24 w-48 h-48 bg-brand-500/10 rounded-full blur-3xl pointer-events-none">
            </div>
            <div
                class="absolute -bottom-24 -right-24 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none">
            </div>

            <div class="relative z-10 prose dark:prose-invert prose-brand max-w-none">
                <div class="text-center mb-10">
                    <h1 class="text-4xl font-black text-gray-900 dark:text-white tracking-tight mb-2">Privacy Policy</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wider">Last
                        updated: {{ date('F d, Y') }}</p>
                </div>

                <h3>1. Information We Collect</h3>
                <p>We collect information you provide directly to us when you create an account, update your profile,
                    use the interactive features of our services, participate in contests or promotions, request
                    customer support or otherwise communicate with us.</p>

                <h3>2. How We Use Your Information</h3>
                <p>We use the information we collect to provide, maintain, and improve our services, such as to
                    facilitate expense sharing, process transactions (including via InstaPay), and personalize your
                    experience.</p>

                <h3>3. Sharing of Information</h3>
                <p>We may share your information with other users of the app when you form groups or split expenses. We
                    do not sell your personal data to third parties.</p>

                <h3>4. Data Security</h3>
                <p>We take reasonable measures to help protect information about you from loss, theft, misuse and
                    unauthorized access, disclosure, alteration and destruction.</p>

                <div class="mt-12 text-center pt-8 border-t border-gray-100 dark:border-gray-800">
                    <a href="{{ route('login') }}"
                        class="font-bold text-brand-600 hover:text-brand-500 dark:text-brand-400 hover:underline">
                        Return to Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
