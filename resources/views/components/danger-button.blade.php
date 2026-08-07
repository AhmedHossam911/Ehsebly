<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-5 py-2.5 bg-red-600 border border-transparent rounded-xl font-bold text-sm text-white shadow-sm shadow-red-500/20 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 disabled:opacity-50 disabled:pointer-events-none transition-colors duration-150']) }}>
    {{ $slot }}
</button>
