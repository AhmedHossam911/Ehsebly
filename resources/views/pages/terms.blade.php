<x-guest-layout>
    <div class="h-full flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div
            class="max-w-3xl w-full space-y-8 bg-white dark:bg-gray-800 p-8 sm:p-12 rounded-[2.5rem] shadow-2xl border border-gray-100 dark:border-gray-700 relative overflow-hidden">

            <div class="absolute -top-24 -left-24 w-48 h-48 bg-purple-500/10 rounded-full blur-3xl pointer-events-none">
            </div>
            <div
                class="absolute -bottom-24 -right-24 w-48 h-48 bg-accent-500/10 rounded-full blur-3xl pointer-events-none">
            </div>

            <div class="relative z-10 prose dark:prose-invert prose-brand max-w-none">
                <div class="text-center mb-10">
                    <h1 class="text-4xl font-black text-gray-900 dark:text-white tracking-tight mb-2">Terms of Service
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wider">Last
                        updated: {{ date('F d, Y') }}</p>
                </div>

                <h3>1. Acceptance of Terms</h3>
                <p>By accessing and using Ehsebly, you accept and agree to be bound by the terms and provision of this
                    agreement. Furthermore, when using these respective services, you shall be subject to any posted
                    guidelines or rules applicable to such services.</p>

                <h3>2. Description of Service</h3>
                <p>Ehsebly provides a platform for friends and groups to track shared expenses and calculate
                    settlements. We are not a financial institution, bank, or payment processor. Any integrations with
                    third-party apps like InstaPay are strictly for redirection and convenience.</p>

                <h3>3. User Responsibilities</h3>
                <p>You are responsible for maintaining the confidentiality of your account and password and for
                    restricting access to your computer. You agree to accept responsibility for all activities that
                    occur under your account or password.</p>

                <h3>4. Dispute Resolution</h3>
                <p>Ehsebly is not responsible for settling financial disputes between users. The app simply calculates
                    math based on user input. All actual financial transactions happen off-platform.</p>

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
