<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-5 py-2.5 bg-brand-600 border border-transparent rounded-xl font-bold text-sm text-white shadow-sm shadow-brand-500/20 hover:bg-brand-700 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 active:translate-y-0 disabled:opacity-50 disabled:pointer-events-none transition-all duration-150']) }}>
    {{ $slot }}
</button>
