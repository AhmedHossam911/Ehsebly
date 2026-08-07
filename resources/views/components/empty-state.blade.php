@props(['title', 'description' => null, 'color' => 'brand'])

<div {{ $attributes->merge(['class' => 'text-center py-16 px-6 bg-white dark:bg-gray-800/50 rounded-[2rem] border-2 border-dashed border-gray-200 dark:border-gray-700 relative overflow-hidden']) }}>
    <div class="absolute inset-0 bg-{{ $color }}-50 dark:bg-{{ $color }}-900/10 opacity-50"></div>
    <div class="relative z-10 flex flex-col items-center">
        <div class="w-20 h-20 bg-{{ $color }}-100 dark:bg-gray-800 rounded-3xl flex items-center justify-center text-{{ $color }}-500 mb-6 shadow-inner">
            {{ $icon }}
        </div>
        <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-2 tracking-tight">{{ $title }}</h3>
        @if ($description)
            <p class="text-gray-500 dark:text-gray-400 text-center max-w-sm font-medium">{{ $description }}</p>
        @endif
        @isset($action)
            <div class="mt-6">{{ $action }}</div>
        @endisset
    </div>
</div>
