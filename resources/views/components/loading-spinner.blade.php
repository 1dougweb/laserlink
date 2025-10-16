{{-- Loading Spinner Component --}}
@props([
    'size' => 'md', // sm, md, lg, xl
    'color' => 'red', // red, white, gray
    'text' => ''
])

@php
    $sizeClasses = [
        'sm' => 'w-4 h-4',
        'md' => 'w-8 h-8',
        'lg' => 'w-12 h-12',
        'xl' => 'w-16 h-16'
    ];
    
    $colorClasses = [
        'red' => 'border-red-600',
        'white' => 'border-white',
        'gray' => 'border-gray-600'
    ];
    
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
    $colorClass = $colorClasses[$color] ?? $colorClasses['red'];
@endphp

<div class="flex flex-col items-center justify-center space-y-2" {{ $attributes }}>
    <div class="relative {{ $sizeClass }}">
        <div class="absolute inset-0 rounded-full border-4 border-gray-200"></div>
        <div class="absolute inset-0 rounded-full border-4 {{ $colorClass }} border-t-transparent animate-spin"></div>
    </div>
    
    @if($text)
        <p class="text-sm text-gray-600 font-medium">{{ $text }}</p>
    @endif
    
    {{ $slot }}
</div>

<style>
@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}
</style>

