
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'size' => 'md', // sm, md, lg, xl
    'color' => 'red', // red, white, gray
    'text' => ''
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
    'size' => 'md', // sm, md, lg, xl
    'color' => 'red', // red, white, gray
    'text' => ''
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
?>

<div class="flex flex-col items-center justify-center space-y-2" <?php echo e($attributes); ?>>
    <div class="relative <?php echo e($sizeClass); ?>">
        <div class="absolute inset-0 rounded-full border-4 border-gray-200"></div>
        <div class="absolute inset-0 rounded-full border-4 <?php echo e($colorClass); ?> border-t-transparent animate-spin"></div>
    </div>
    
    <?php if($text): ?>
        <p class="text-sm text-gray-600 font-medium"><?php echo e($text); ?></p>
    <?php endif; ?>
    
    <?php echo e($slot); ?>

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

<?php /**PATH C:\xampp\htdocs\resources\views/components/loading-spinner.blade.php ENDPATH**/ ?>