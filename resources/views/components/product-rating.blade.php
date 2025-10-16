@props([
    'rating' => 0, // Nota de 0 a 5
    'count' => 0, // Número de avaliações
    'size' => 'sm', // sm, md, lg
    'showCount' => true
])

@php
    $sizeClasses = [
        'sm' => 'text-sm',
        'md' => 'text-base',
        'lg' => 'text-lg'
    ];
    
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['sm'];
    $fullStars = floor($rating);
    $hasHalfStar = ($rating - $fullStars) >= 0.5;
    $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
@endphp

<div class="flex items-center gap-2 {{ $sizeClass }}" {{ $attributes }}>
    <div class="flex items-center gap-0.5">
        {{-- Estrelas cheias --}}
        @for($i = 0; $i < $fullStars; $i++)
            <i class="bi bi-star-fill text-yellow-400"></i>
        @endfor
        
        {{-- Meia estrela --}}
        @if($hasHalfStar)
            <i class="bi bi-star-half text-yellow-400"></i>
        @endif
        
        {{-- Estrelas vazias --}}
        @for($i = 0; $i < $emptyStars; $i++)
            <i class="bi bi-star text-yellow-400"></i>
        @endfor
    </div>
    
    @if($showCount && $count > 0)
        <span class="text-gray-500 text-xs">
            ({{ $count }})
        </span>
    @endif
    
    @if($rating > 0 && !$showCount)
        <span class="text-gray-600 text-xs font-medium">
            {{ number_format($rating, 1) }}
        </span>
    @endif
</div>

