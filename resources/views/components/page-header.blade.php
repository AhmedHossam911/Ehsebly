@props(['title', 'description' => null])

<div {{ $attributes->merge(['class' => 'flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 relative z-10']) }}>
    <div>
        <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">{{ $title }}</h1>
        @if ($description)
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">{{ $description }}</p>
        @endif
    </div>
    @isset($action)
        <div>{{ $action }}</div>
    @endisset
</div>
