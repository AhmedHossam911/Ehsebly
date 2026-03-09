<footer
    class="mt-auto py-8 text-center text-sm text-gray-500 dark:text-gray-400 border-t border-gray-200/50 dark:border-gray-800/50 relative z-50 w-full {{ $attributes->get('class') }}">
    <div class="max-w-7xl mx-auto flex flex-col items-center justify-center px-6">
        <p class="font-medium mb-4">
            &copy; {{ date('Y') }} <strong>Ehsebly</strong>. All rights reserved for <a
                href="https://github.com/a511r">Ahmed Hossam</a>.
        </p>
        <div class="flex space-x-6 text-sm font-medium">
            <a href="{{ route('privacy') }}" class="hover:text-brand-500 transition-colors">Privacy</a>
            <a href="{{ route('terms') }}" class="hover:text-brand-500 transition-colors">Terms</a>
            <a href="{{ route('contact') }}" class="hover:text-brand-500 transition-colors">Contact</a>
        </div>
    </div>
</footer>
