<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'rating' => 0, // Nota de 0 a 5
    'count' => 0, // Número de avaliações
    'size' => 'sm', // sm, md, lg
    'showCount' => true
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'rating' => 0, // Nota de 0 a 5
    'count' => 0, // Número de avaliações
    'size' => 'sm', // sm, md, lg
    'showCount' => true
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $sizeClasses = [
        'sm' => 'text-sm',
        'md' => 'text-base',
        'lg' => 'text-lg'
    ];
    
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['sm'];
    $fullStars = floor($rating);
    $hasHalfStar = ($rating - $fullStars) >= 0.5;
    $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
?>

<div class="flex items-center gap-2 <?php echo e($sizeClass); ?>" <?php echo e($attributes); ?>>
    <div class="flex items-center gap-0.5">
        
        <?php for($i = 0; $i < $fullStars; $i++): ?>
            <i class="bi bi-star-fill text-yellow-400"></i>
        <?php endfor; ?>
        
        
        <?php if($hasHalfStar): ?>
            <i class="bi bi-star-half text-yellow-400"></i>
        <?php endif; ?>
        
        
        <?php for($i = 0; $i < $emptyStars; $i++): ?>
            <i class="bi bi-star text-yellow-400"></i>
        <?php endfor; ?>
    </div>
    
    <?php if($showCount && $count > 0): ?>
        <span class="text-gray-500 text-xs">
            (<?php echo e($count); ?>)
        </span>
    <?php endif; ?>
    
    <?php if($rating > 0 && !$showCount): ?>
        <span class="text-gray-600 text-xs font-medium">
            <?php echo e(number_format($rating, 1)); ?>

        </span>
    <?php endif; ?>
</div>

<?php /**PATH C:\xampp\htdocs\resources\views/components/product-rating.blade.php ENDPATH**/ ?>