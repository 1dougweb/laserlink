<?php $__env->startSection('title', $product->name . ' - Laser Link'); ?>

<?php $__env->startPush('schema'); ?>
<?php
    $allImages = [];
    if($product->featured_image) {
        $allImages[] = url('images/' . $product->featured_image);
    }
    if($product->gallery_images && is_array($product->gallery_images)) {
        foreach($product->gallery_images as $img) {
            $allImages[] = url('images/' . $img);
        }
    }
    
    $schemaProduct = [
        '@context' => 'https://schema.org/',
        '@type' => 'Product',
        'name' => $product->name,
        'image' => $allImages,
        'description' => $product->short_description ?? Str::limit(strip_tags($product->description), 160),
        'brand' => [
            '@type' => 'Brand',
            'name' => 'Laser Link'
        ],
        'offers' => [
            '@type' => 'Offer',
            'url' => route('store.product', $product->slug),
            'priceCurrency' => 'BRL',
            'price' => $product->final_price,
            'priceValidUntil' => now()->addMonths(3)->format('Y-m-d'),
            'availability' => $product->stock_quantity > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
            'itemCondition' => 'https://schema.org/NewCondition',
            'seller' => [
                '@type' => 'Organization',
                'name' => 'Laser Link'
            ]
        ]
    ];
    
    if ($product->sku) {
        $schemaProduct['sku'] = $product->sku;
    }
    
    if (($product->rating_count ?? 0) > 0) {
        $schemaProduct['aggregateRating'] = [
            '@type' => 'AggregateRating',
            'ratingValue' => $product->rating_average ?? 5,
            'reviewCount' => $product->rating_count
        ];
    }
?>

<!-- Schema.org Product -->
<script type="application/ld+json">
<?php echo json_encode($schemaProduct, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); ?>

</script>

<?php
    $schemaBreadcrumb = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => 'Início',
                'item' => route('store.index')
            ],
            [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => $product->category->name,
                'item' => route('store.category', $product->category->slug)
            ],
            [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => $product->name
            ]
        ]
    ];
?>

<!-- Schema.org BreadcrumbList -->
<script type="application/ld+json">
<?php echo json_encode($schemaBreadcrumb, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); ?>

</script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Hide scrollbar but keep functionality - Works on all browsers */
    .hide-scrollbar {
        -ms-overflow-style: none !important;  /* IE and Edge */
        scrollbar-width: none !important;     /* Firefox */
        overflow: -moz-scrollbars-none !important;
        margin: 0 !important;
    }
    
    .hide-scrollbar::-webkit-scrollbar {
        display: none !important;  /* Chrome, Safari, Opera */
        width: 0 !important;
        height: 0 !important;
        background: transparent !important;
        margin: 0 !important;
    }
    
    /* Store Notifications */
    .store-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        min-width: 320px;
        max-width: 500px;
        padding: 16px 20px;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        z-index: 999999 !important;
        transform: translateX(520px);
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        backdrop-filter: blur(10px);
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }
    
    .store-notification.show {
        transform: translateX(0);
        opacity: 1;
    }
    
    .store-notification.success {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        border: 1px solid #047857;
    }
    
    .store-notification.error {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        border: 1px solid #b91c1c;
    }
    
    .store-notification.warning {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        border: 1px solid #b45309;
    }
    
    .store-notification-content {
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }
    
    .store-notification-icon {
        flex-shrink: 0;
        width: 20px;
        height: 20px;
        margin-top: 2px;
    }
    
    .store-notification-text {
        flex: 1;
        font-weight: 500;
        line-height: 1.5;
    }
    
    .hide-scrollbar::-webkit-scrollbar-track {
        display: none !important;
    }
    
    .hide-scrollbar::-webkit-scrollbar-thumb {
        display: none !important;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div x-data="productPage()">
    <!-- Breadcrumb -->
    <nav class="bg-gray-50 py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <ol class="flex items-center space-x-2 text-sm text-gray-500">
                <li><a href="<?php echo e(route('store.index')); ?>" class="hover:text-primary transition-colors">Início</a></li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 mx-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <a href="<?php echo e(route('store.category', $product->category->slug)); ?>" class="hover:text-primary transition-colors"><?php echo e($product->category->name); ?></a>
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 mx-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-gray-900 font-medium"><?php echo e($product->name); ?></span>
                </li>
            </ol>
        </div>
    </nav>

    <!-- Product Details Section -->
    <section class="py-8 md:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
                <!-- Product Images -->
                <div class="flex flex-col lg:flex-row gap-4">
                    <?php
                        $allImages = [];
                        if($product->featured_image) {
                            $allImages[] = $product->featured_image;
                        }
                        if($product->gallery_images && is_array($product->gallery_images)) {
                            $allImages = array_merge($allImages, $product->gallery_images);
                        }
                        if(empty($allImages)) {
                            $allImages[] = 'general/callback-image.svg';
                        }
                    ?>
                    
                    <!-- Desktop Layout: Thumbnails on the left -->
                    <?php if(count($allImages) > 1): ?>
                    <div class="hidden lg:block relative flex flex-col w-24 flex-shrink-0">
                        <!-- Scroll Up Arrow -->
                        <?php if(count($allImages) > 5): ?>
                        <button @click="scrollThumbnails('up')"
                                x-show="thumbnailScrollPosition > 0"
                                class="absolute top-0 left-0 right-0 z-20 bg-white/95 hover:bg-white shadow-md rounded-t-lg p-1.5 flex items-center justify-center transition-all"
                                style="display: none;">
                            <svg class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                            </svg>
                        </button>
                        <?php endif; ?>
                        
                        <!-- Thumbnails Container - Desktop -->
                        <div x-ref="thumbnailsContainer" 
                             class="flex flex-col gap-3 overflow-y-scroll scroll-smooth <?php echo e(count($allImages) > 5 ? 'max-h-[470px]' : ''); ?>"
                             @scroll="updateThumbnailScrollPosition()"
                             style="-ms-overflow-style: none; scrollbar-width: none; <?php echo e(count($allImages) > 5 ? '' : 'max-height: fit-content;'); ?>">
                            <?php $__currentLoopData = $allImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden cursor-pointer border transition-all duration-200 shadow-sm hover:shadow-md flex-shrink-0 w-24"
                                     :class="currentImageIndex === <?php echo e($index); ?> ? 'border-gray-400' : 'border-gray-200 hover:border-gray-400'"
                                     @click="changeImage(<?php echo e($index); ?>)">
                                    <img src="<?php echo e(url('images/' . $image)); ?>" 
                                         alt="<?php echo e($product->name); ?> - <?php echo e($product->category->name); ?> - Imagem <?php echo e($index + 1); ?>" 
                                         class="w-full h-full object-cover"
                                         onerror="this.src='<?php echo e(url('images/general/callback-image.svg')); ?>'; this.classList.remove('object-cover'); this.classList.add('object-contain');">
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        
                        <!-- Scroll Down Arrow -->
                        <?php if(count($allImages) > 5): ?>
                        <button @click="scrollThumbnails('down')"
                                x-show="thumbnailScrollPosition < thumbnailMaxScroll"
                                class="absolute bottom-0 left-0 right-0 z-20 bg-white/95 hover:bg-white shadow-md rounded-b-lg p-1.5 flex items-center justify-center transition-all"
                                style="display: none;">
                            <svg class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <!-- Main Image -->
                    <div class="flex-1 w-full aspect-square lg:w-[470px] lg:h-[470px] bg-white rounded-2xl overflow-hidden shadow-lg relative">
                        <!-- Favorite Button - Positioned over image -->
                        <button @click.stop="toggleFavorite(<?php echo e($product->id); ?>)" 
                                class="absolute top-4 right-4 z-30 w-12 h-12 rounded-full bg-white/90 backdrop-blur-sm hover:bg-white shadow-lg flex items-center justify-center transition-all duration-300 hover:scale-110"
                                :class="{'bg-red-50': isFavorite}">
                            <svg class="w-6 h-6 transition-all duration-300" 
                                 :class="isFavorite ? 'fill-red-500 text-red-500' : 'text-gray-600'" 
                                 fill="none" 
                                 stroke="currentColor" 
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </button>
                        
                        <div class="w-full h-full relative overflow-hidden">
                            <?php $__currentLoopData = $allImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div x-show="currentImageIndex === <?php echo e($index); ?>"
                                     x-transition:enter="transition-all ease-out duration-500"
                                     x-transition:enter-start="transform translate-x-full opacity-0"
                                     x-transition:enter-end="transform translate-x-0 opacity-100"
                                     x-transition:leave="transition-all ease-in duration-300"
                                     x-transition:leave-start="transform translate-x-0 opacity-100"
                                     x-transition:leave-end="transform -translate-x-full opacity-0"
                                     class="absolute inset-0 w-full h-full cursor-pointer"
                                     @click="openImageModal('<?php echo e(url('images/' . $image)); ?>')"
                                     style="display: none;">
                                    <img src="<?php echo e(url('images/' . $image)); ?>" 
                                         alt="<?php echo e($product->name); ?> - <?php echo e($product->category->name); ?> - Foto detalhada <?php echo e($index + 1); ?> - Laser Link" 
                                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                                         onerror="this.src='<?php echo e(url('images/general/callback-image.svg')); ?>'; this.classList.remove('object-cover'); this.classList.add('object-contain');">
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        
                        <!-- Navigation Arrows (if multiple images) -->
                        <?php if(count($allImages) > 1): ?>
                        <div class="absolute top-1/2 left-0 right-0 -translate-y-1/2 flex justify-between px-4 pointer-events-none">
                            <button @click.stop="previousImage()"
                                    class="pointer-events-auto w-10 h-10 md:w-12 md:h-12 rounded-full bg-white/90 hover:bg-white shadow-lg flex items-center justify-center transition-all duration-200 hover:scale-110">
                                <svg class="w-5 h-5 md:w-6 md:h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                            <button @click.stop="nextImage()"
                                    class="pointer-events-auto w-10 h-10 md:w-12 md:h-12 rounded-full bg-white/90 hover:bg-white shadow-lg flex items-center justify-center transition-all duration-200 hover:scale-110">
                                <svg class="w-5 h-5 md:w-6 md:h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Image Counter -->
                        <div class="absolute bottom-4 right-4 bg-black/60 text-white px-3 py-1.5 rounded-full text-sm font-medium backdrop-blur-sm">
                            <span x-text="currentImageIndex + 1"></span> / <?php echo e(count($allImages)); ?>

                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Mobile/Tablet Layout: Thumbnails below -->
                    <?php if(count($allImages) > 1): ?>
                    <div class="lg:hidden mt-4">
                        <div class="relative">
                            <!-- Scroll Left Arrow -->
                            <?php if(count($allImages) > 4): ?>
                            <button @click="scrollThumbnailsHorizontal('left')"
                                    x-show="thumbnailScrollPosition > 0"
                                    class="absolute left-0 top-1/2 -translate-y-1/2 z-20 bg-white/95 hover:bg-white shadow-md rounded-l-lg p-2 flex items-center justify-center transition-all -ml-2"
                                    style="display: none;">
                                <svg class="w-4 h-4 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                            <?php endif; ?>
                            
                            <!-- Thumbnails Container - Mobile/Tablet Horizontal -->
                            <div x-ref="thumbnailsContainerMobile" 
                                 class="flex gap-3 overflow-x-scroll scroll-smooth px-2"
                                 @scroll="updateThumbnailScrollPositionHorizontal()"
                                 style="-ms-overflow-style: none; scrollbar-width: none;">
                                <?php $__currentLoopData = $allImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden cursor-pointer border transition-all duration-200 shadow-sm hover:shadow-md flex-shrink-0 w-16 sm:w-20"
                                         :class="currentImageIndex === <?php echo e($index); ?> ? 'border-gray-400' : 'border-gray-200 hover:border-gray-400'"
                                         @click="changeImage(<?php echo e($index); ?>)">
                                        <img src="<?php echo e(url('images/' . $image)); ?>" 
                                             alt="<?php echo e($product->name); ?> - Miniatura <?php echo e($index + 1); ?>" 
                                             class="w-full h-full object-cover"
                                             onerror="this.src='<?php echo e(url('images/general/callback-image.svg')); ?>'; this.classList.remove('object-cover'); this.classList.add('object-contain');">
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            
                            <!-- Scroll Right Arrow -->
                            <?php if(count($allImages) > 4): ?>
                            <button @click="scrollThumbnailsHorizontal('right')"
                                    x-show="thumbnailScrollPosition < thumbnailMaxScroll"
                                    class="absolute right-0 top-1/2 -translate-y-1/2 z-20 bg-white/95 hover:bg-white shadow-md rounded-r-lg p-2 flex items-center justify-center transition-all -mr-2"
                                    style="display: none;">
                                <svg class="w-4 h-4 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Product Info -->
                <div class="space-y-6">
                    <!-- Product Header -->
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <?php if($product->is_new): ?>
                                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-1 rounded-full">Novo</span>
                            <?php endif; ?>
                            <?php if($product->is_featured): ?>
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-1 rounded-full">Destaque</span>
                            <?php endif; ?>
                            <?php if($product->is_on_sale): ?>
                                <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-1 rounded-full">Promoção</span>
                            <?php endif; ?>
                        </div>
                        
                        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3"><?php echo e($product->name); ?></h1>
                        
                        <div class="flex items-center gap-3 mb-4">
                            <span 
                               class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-primary/10 text-primary hover:bg-primary/20 transition-colors">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                <?php echo e($product->category->name); ?>

                    </span>
                        
                            <?php if($product->sku): ?>
                                <span class="text-sm text-gray-500">SKU: <span class="font-mono"><?php echo e($product->sku); ?></span></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Price Section -->
                    <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6 shadow-md">
                        <?php if($product->sale_price && $product->sale_price > 0): ?>
                            <div class="space-y-2">
                                <div class="flex items-baseline gap-3">
                                <span class="text-4xl font-bold text-green-600">R$ <?php echo e(number_format($product->sale_price, 2, ',', '.')); ?></span>
                                    <span class="text-xl text-gray-500 line-through">R$ <?php echo e(number_format($product->price, 2, ',', '.')); ?></span>
                                </div>
                                <div class="inline-flex items-center bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path>
                                    </svg>
                                    Economia de <?php echo e($product->discount_percentage); ?>%
                                </div>
                            </div>
                        <?php elseif($product->auto_calculate_price): ?>
                            <div class="space-y-2">
                                <div class="flex items-baseline gap-3">
                                    <span class="text-4xl font-bold text-primary">R$ <span x-text="formatPrice(totalPrice)"></span></span>
                                    <span class="text-gray-600">à vista</span>
                                </div>
                                <div x-show="extraCost > 0" class="text-sm text-green-600 font-medium">
                                    (+R$ <span x-text="formatPrice(extraCost)"></span> extras)
                                </div>
                                <div class="text-sm text-gray-500">
                                    <i class="bi bi-calculator mr-1"></i>
                                    Preço calculado conforme personalização
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="space-y-2">
                                <div class="flex items-baseline gap-3">
                                    <span class="text-4xl font-bold text-primary">R$ <span x-text="formatPrice(totalPrice)"></span></span>
                                    <span class="text-gray-600">à vista</span>
                                </div>
                                <div x-show="extraCost > 0" class="text-sm text-green-600 font-medium">
                                    (+R$ <span x-text="formatPrice(extraCost)"></span> extras)
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-sm text-gray-600">
                            <i class="bi bi-truck text-green-600"></i>
                                O prazo de entrega é de até <span class="font-semibold">5 dias úteis</span>
                            </p>
            </div>
            
                        <!-- Extra Fields / Product Attributes -->
                        <?php if($extraFields && $extraFields->count() > 0): ?>
                            <div class="mt-4 pt-4 border-t border-gray-200 space-y-4">
                                <?php $__currentLoopData = $extraFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        // Verificar se há opções customizadas no produto
                                        $fieldSettings = json_decode($field->pivot->field_settings ?? '{}', true);
                                        $customOptions = $fieldSettings['custom_options'] ?? null;
                                        
                                        if ($customOptions && count($customOptions) > 0) {
                                            // Normalizar opções customizadas para garantir que price seja float
                                            $optionsToShow = array_map(function($option) {
                                                return [
                                                    'value' => $option['value'] ?? '',
                                                    'label' => $option['label'] ?? '',
                                                    'image_url' => $option['image_url'] ?? null,
                                                    'color_hex' => $option['color_hex'] ?? null,
                                                    'price' => (float) ($option['price'] ?? 0),
                                                    'price_type' => $option['price_type'] ?? 'fixed'
                                                ];
                                            }, $customOptions);
                                        } else {
                                            // Usar opções gerais do campo
                                            $optionsToShow = $field->options->map(function($option) {
                                                return [
                                                    'value' => $option->value,
                                                    'label' => $option->label,
                                                    'image_url' => $option->image_url ?? null,
                                                    'color_hex' => $option->color_hex ?? null,
                                                    'price' => (float) ($option->price ?? 0),
                                                    'price_type' => $option->price_type ?? 'fixed'
                                                ];
                                            })->toArray();
                                        }
                                    ?>
                                    
                                    <?php if(count($optionsToShow) > 0): ?>
                                        <div class="extra-field-container">
                                            <label class="block text-sm font-semibold text-gray-900 mb-3">
                                                <?php echo e($field->name); ?>

                                                <?php if($field->pivot->is_required): ?>
                                                    <span class="text-red-500">*</span>
                                                <?php endif; ?>
                                            </label>
                                            
                                            <?php if($field->type === 'select'): ?>
                                                <!-- Dropdown Select -->
                                                <select name="extra_fields[<?php echo e($field->slug); ?>]" 
                                                        x-model="selectedOptions['<?php echo e($field->slug); ?>']"
                                                        @change="calculateTotalPrice()"
                                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all bg-white">
                                                    <option value="">Selecione uma opção</option>
                                                    <?php $__currentLoopData = $optionsToShow; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($option['value']); ?>" 
                                                                data-price="<?php echo e($option['price'] ?? 0); ?>"
                                                                data-price-type="<?php echo e($option['price_type'] ?? 'fixed'); ?>">
                                                            <?php echo e($option['label']); ?>

                                                            <?php if(isset($option['price']) && $option['price'] > 0): ?>
                                                                <?php if(isset($option['price_type']) && $option['price_type'] === 'percentage'): ?>
                                                                    (+<?php echo e(number_format($option['price'], 0)); ?>%)
                                                                <?php else: ?>
                                                                    (+ R$ <?php echo e(number_format($option['price'], 2, ',', '.')); ?>)
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                                
                                            <?php elseif($field->type === 'radio'): ?>
                                                <!-- Radio Buttons - Grid Layout -->
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                    <?php $__currentLoopData = $optionsToShow; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <label class="relative flex items-center p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 group"
                                                               :class="selectedOptions['<?php echo e($field->slug); ?>'] === '<?php echo e($option['value']); ?>' 
                                                                   ? 'border-primary bg-primary/5 shadow-md' 
                                                                   : 'border-gray-200 hover:border-gray-300 hover:shadow-sm bg-white'">
                                                            <input type="radio" 
                                                                   name="extra_fields[<?php echo e($field->slug); ?>]" 
                                                                   value="<?php echo e($option['value']); ?>"
                                                                   x-model="selectedOptions['<?php echo e($field->slug); ?>']"
                                                                   @change="calculateTotalPrice()"
                                                                   class="sr-only">
                                                            
                                                            <!-- Radio Icon Verde -->
                                                            <div class="flex-shrink-0">
                                                                <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-all"
                                                                     :class="selectedOptions['<?php echo e($field->slug); ?>'] === '<?php echo e($option['value']); ?>' 
                                                                         ? 'border-green-500 bg-green-500' 
                                                                         : 'border-gray-300 group-hover:border-gray-400 bg-white'">
                                                                    <i x-show="selectedOptions['<?php echo e($field->slug); ?>'] === '<?php echo e($option['value']); ?>'" 
                                                                       class="bi bi-check-lg text-white font-bold"></i>
                                                                </div>
                                                            </div>
                                                            
                                                            <!-- Content -->
                                                            <div class="ml-3 flex-1 min-w-0 flex items-center justify-between gap-2">
                                                                <div class="flex flex-col">
                                                                    <span class="font-medium text-gray-900 text-sm"><?php echo e($option['label']); ?></span>
                                                                    <?php if(isset($option['description']) && $option['description']): ?>
                                                                        <p class="mt-0.5 text-xs text-gray-500"><?php echo e($option['description']); ?></p>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <?php if(isset($option['price']) && $option['price'] > 0): ?>
                                                                    <span class="flex-shrink-0 text-sm font-bold text-primary whitespace-nowrap">
                                                                        <?php if(isset($option['price_type']) && $option['price_type'] === 'percentage'): ?>
                                                                            +<?php echo e(number_format($option['price'], 0)); ?>%
                                                                        <?php else: ?>
                                                                            +R$ <?php echo e(number_format($option['price'], 2, ',', '.')); ?>

                                                                        <?php endif; ?>
                                                                    </span>
                                                                <?php endif; ?>
                                                            </div>
                                                        </label>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>

                                            <?php elseif($field->type === 'checkbox'): ?>
                                                <!-- Checkboxes -->
                                                <div class="space-y-2">
                                                    <?php $__currentLoopData = $optionsToShow; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors"
                                                               :class="selectedOptions['<?php echo e($field->slug); ?>'] && selectedOptions['<?php echo e($field->slug); ?>'].includes('<?php echo e($option['value']); ?>') ? 'border-primary bg-primary/5' : ''">
                                                            <input type="checkbox" 
                                                                   value="<?php echo e($option['value']); ?>"
                                                                   x-model="selectedOptions['<?php echo e($field->slug); ?>']"
                                                                   @change="calculateTotalPrice()"
                                                                   class="sr-only">
                                                            
                                                            <!-- Checkbox Icon Verde -->
                                                            <div class="flex-shrink-0">
                                                                <div class="w-6 h-6 rounded border-2 flex items-center justify-center transition-all"
                                                                     :class="selectedOptions['<?php echo e($field->slug); ?>'] && selectedOptions['<?php echo e($field->slug); ?>'].includes('<?php echo e($option['value']); ?>') 
                                                                         ? 'border-green-500 bg-green-500' 
                                                                         : 'border-gray-300'">
                                                                    <i x-show="selectedOptions['<?php echo e($field->slug); ?>'] && selectedOptions['<?php echo e($field->slug); ?>'].includes('<?php echo e($option['value']); ?>')" 
                                                                       class="bi bi-check-lg text-white font-bold"></i>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="ml-3 flex-1 flex items-center justify-between gap-2">
                                                                <span class="font-medium text-gray-900"><?php echo e($option['label']); ?></span>
                                                                <?php if(isset($option['price']) && $option['price'] > 0): ?>
                                                                    <span class="flex-shrink-0 text-sm font-semibold text-primary whitespace-nowrap">
                                                                        <?php if(isset($option['price_type']) && $option['price_type'] === 'percentage'): ?>
                                                                            +<?php echo e(number_format($option['price'], 0)); ?>%
                                                                        <?php else: ?>
                                                                            +R$ <?php echo e(number_format($option['price'], 2, ',', '.')); ?>

                                                                        <?php endif; ?>
                                                                    </span>
                                                                <?php endif; ?>
                                                            </div>
                                                        </label>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>

                                            <?php elseif($field->type === 'image' || $field->type === 'color'): ?>
                                                <!-- Seleção Visual por Imagens/Cores -->
                                                <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 gap-3">
                                                    <?php $__currentLoopData = $optionsToShow; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <div class="relative">
                                                            <input type="radio"
                                                                   name="extra_fields[<?php echo e($field->slug); ?>]"
                                                                   value="<?php echo e($option['value']); ?>"
                                                                   id="option_<?php echo e($field->slug); ?>_<?php echo e($loop->index); ?>"
                                                                   x-model="selectedOptions['<?php echo e($field->slug); ?>']"
                                                                   @change="calculateTotalPrice()"
                                                                   class="sr-only">
                                                            
                                                            <label for="option_<?php echo e($field->slug); ?>_<?php echo e($loop->index); ?>"
                                                                   class="block cursor-pointer group">
                                                                <!-- Thumbnail -->
                                                                <div class="relative w-full aspect-square rounded-lg overflow-hidden border-2 transition-all duration-200"
                                                                     :class="selectedOptions['<?php echo e($field->slug); ?>'] === '<?php echo e($option['value']); ?>'
                                                                         ? 'border-primary shadow-lg scale-105'
                                                                         : 'border-gray-300 hover:border-gray-400 hover:shadow-md'">
                                                                    
                                                                    <?php if(isset($option['image_url']) && $option['image_url']): ?>
                                                                        <!-- Imagem -->
                                                                        <img src="<?php echo e(url('images/' . $option['image_url'])); ?>"
                                                                             alt="<?php echo e($option['label']); ?>"
                                                                             class="w-full h-full object-cover"
                                                                             onerror="this.src='<?php echo e(url('images/general/callback-image.svg')); ?>'">
                                                                    <?php elseif(isset($option['color_hex']) && $option['color_hex']): ?>
                                                                        <!-- Cor Sólida -->
                                                                        <div class="w-full h-full" 
                                                                             style="background-color: <?php echo e($option['color_hex']); ?>"></div>
                                                                    <?php else: ?>
                                                                        <!-- Fallback: Texto -->
                                                                        <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-600 text-xs font-medium p-1 text-center leading-tight">
                                                                            <?php echo e($option['label']); ?>

                                                                        </div>
                                                                    <?php endif; ?>
                                                                    
                                                                    <!-- Check Verde Redondo Quando Selecionado -->
                                                                    <div x-show="selectedOptions['<?php echo e($field->slug); ?>'] === '<?php echo e($option['value']); ?>'"
                                                                         class="absolute inset-0 flex items-center justify-center">
                                                                        <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                                                                            <i class="bi bi-check-lg text-white text-sm font-bold"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>

                                            <?php else: ?>
                                                <!-- Fallback para outros tipos -->
                                                <div class="flex flex-wrap gap-2">
                                                    <?php $__currentLoopData = $optionsToShow; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <span class="inline-flex items-center px-3 py-1.5 rounded-md text-sm border border-gray-300 text-gray-700 bg-white">
                                                            <?php echo e($option['label']); ?>

                                                            <?php if(isset($option['price']) && $option['price'] > 0): ?>
                                                                <?php if(isset($option['price_type']) && $option['price_type'] === 'percentage'): ?>
                                                                    <span class="ml-1.5 font-semibold text-primary">+<?php echo e(number_format($option['price'], 0)); ?>%</span>
                                                                <?php else: ?>
                                                                    <span class="ml-1.5 font-semibold text-primary">+R$ <?php echo e(number_format($option['price'], 2, ',', '.')); ?></span>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                        </span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                            <?php endif; ?>
                                    </div>
                                    <?php else: ?>
                                        <div class="extra-field-container">
                                            <label class="block text-sm font-semibold text-gray-900 mb-2">
                                                <?php echo e($field->name); ?>

                                                <?php if($field->pivot->is_required): ?>
                                                    <span class="text-red-500">*</span>
                            <?php endif; ?>
                                            </label>
                                            <p class="text-sm text-gray-500 italic">Nenhuma opção disponível</p>
                                </div>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>
                        </div>

                    <!-- Short Description -->
                    <?php if($product->short_description): ?>
                        <div class="product-description text-gray-600">
                            <p><?php echo e($product->short_description); ?></p>
                    </div>
                    <?php endif; ?>

                    <!-- Stock Information -->
                    <?php if($product->track_stock): ?>
                        <div class="flex items-center gap-2 text-sm">
                            <?php if($product->stock_quantity > 10): ?>
                                <span class="flex items-center text-green-600">
                                    <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                    Em estoque
                                </span>
                            <?php elseif($product->stock_quantity > 0): ?>
                                <span class="flex items-center text-orange-600">
                                    <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    Últimas <?php echo e($product->stock_quantity); ?> unidades
                                </span>
                            <?php else: ?>
                                <span class="flex items-center text-red-600">
                                    <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    Fora de estoque
                                </span>
                            <?php endif; ?>
                                </div>
                    <?php endif; ?>

                    <!-- Quick Actions -->
                    <div class="flex gap-3 items-center">
                        <!-- Quantity Selector -->
                        <div class="flex items-center bg-gray-100 border border-gray-200 rounded-lg overflow-hidden bg-white p-1 shadow-b-md">
                            <!-- Display da quantidade à esquerda -->
                            <div class="w-12 h-10 flex items-center justify-center bg-gray-100 rounded-md mx-1">
                                <span x-text="quantity" class="text-lg font-semibold text-gray-800"></span>
                            </div>
                            
                            <!-- Botões +/- empilhados à direita -->
                            <div class="flex flex-col gap-1">
                                <button @click="increaseQuantity()" 
                                        :disabled="quantity >= 99"
                                        class="px-3 py-1.5 bg-gray-300 hover:bg-gray-400 disabled:bg-gray-200 disabled:text-gray-400 disabled:cursor-not-allowed transition-colors rounded-md shadow-b-sm">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </button>
                                <button @click="decreaseQuantity()" 
                                        :disabled="quantity <= 1"
                                        class="px-3 py-1.5 bg-gray-300 hover:bg-gray-400 disabled:bg-gray-200 disabled:text-gray-400 disabled:cursor-not-allowed transition-colors rounded-md shadow-b-sm">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Add to Cart Button -->
                        <button @click="submitCustomization()" 
                                @mouseenter="if(inCart) removing = true"
                                @mouseleave="removing = false"
                                class="flex-1 px-6 py-3.5 rounded-xl transition-all duration-300 font-semibold text-lg shadow-lg hover:shadow-xl flex items-center justify-center gap-2"
                                :class="inCart && !removing ? 'bg-green-500 hover:bg-green-600 text-white' : inCart && removing ? 'bg-red-500 hover:bg-red-600 text-white' : 'bg-primary hover:bg-red-700 text-white'">
                            
                            <!-- Static state - Adicionar -->
                            <template x-if="!adding && !inCart">
                                <div class="flex items-center gap-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <span>Adicionar ao Carrinho</span>
                                </div>
                            </template>
                            
                            <!-- Loading before adding -->
                            <template x-if="adding">
                                <div class="flex items-center gap-2">
                                    <svg class="w-6 h-6 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    <span>Adicionando...</span>
                                </div>
                            </template>
                            
                            <!-- Added to cart -->
                            <template x-if="!adding && inCart && !removing">
                                <div class="flex items-center gap-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Produto Adicionado</span>
                                </div>
                            </template>
                            
                            <!-- Remove on hover -->
                            <template x-if="!adding && inCart && removing">
                                <div class="flex items-center gap-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    <span>Remover do Carrinho</span>
                                </div>
                            </template>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Product Tabs: Description & Reviews -->
    <section class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div x-data="{ activeTab: 'description' }">
                <!-- Tabs Navigation -->
                <div class="flex border-b border-gray-200 bg-white rounded-t-2xl shadow-sm">
                    <button @click="activeTab = 'description'"
                            class="flex-1 py-4 px-6 text-center font-semibold transition-colors duration-200"
                            :class="activeTab === 'description' ? 'text-primary border-b-2 border-primary bg-red-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'">
                        <i class="bi bi-file-text mr-2"></i>
                        Descrição
                    </button>
                    <button @click="activeTab = 'reviews'"
                            class="flex-1 py-4 px-6 text-center font-semibold transition-colors duration-200"
                            :class="activeTab === 'reviews' ? 'text-primary border-b-2 border-primary bg-red-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'">
                        <i class="bi bi-star mr-2"></i>
                        Avaliações (<?php echo e($reviewsStats['total']); ?>)
                    </button>
                </div>

                <!-- Tab Content -->
                <div class="bg-white rounded-b-2xl shadow-lg p-8">
                    <!-- Description Tab -->
                    <div x-show="activeTab === 'description'" x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform translate-y-4"
                         x-transition:enter-end="opacity-100 transform translate-y-0">
                        <?php if($product->description): ?>
                            <div class="product-description text-gray-600 prose prose-sm max-w-none">
                                <?php echo $product->description; ?>

                            </div>
                        <?php else: ?>
                            <p class="text-gray-500 text-center py-8">Nenhuma descrição disponível para este produto.</p>
                        <?php endif; ?>
                    </div>

                    <!-- Reviews Tab -->
                    <div x-show="activeTab === 'reviews'" x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform translate-y-4"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         style="display: none;">
                        <?php echo $__env->make('store.partials.product-reviews', ['product' => $product, 'reviewsStats' => $reviewsStats], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Product Features -->
    <section class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Por que escolher este produto?</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-lg transition-shadow text-center">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Qualidade Premium</h3>
                        <p class="text-gray-600 text-sm">Materiais de alta qualidade e durabilidade garantida</p>
                    </div>
                    
                    <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-lg transition-shadow text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Entrega Rápida</h3>
                        <p class="text-gray-600 text-sm">Produção e envio em até 5 dias úteis</p>
                    </div>
                    
                    <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-lg transition-shadow text-center">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Suporte Dedicado</h3>
                        <p class="text-gray-600 text-sm">Atendimento especializado para suas dúvidas</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Products -->
    <?php if($relatedProducts->count() > 0): ?>
    <section class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-8 text-[#272343]">Produtos Relacionados</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 sm:gap-6">
                <?php $__currentLoopData = $relatedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $relatedProduct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="relative group w-full sm:max-w-[300px] sm:mx-auto flex flex-col bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow"
                     x-data="{
                        relProduct: <?php echo e(json_encode([
                            'id' => $relatedProduct->id,
                            'name' => $relatedProduct->name,
                            'slug' => $relatedProduct->slug,
                            'final_price' => (float) $relatedProduct->final_price,
                            'original_price' => $relatedProduct->is_on_sale && $relatedProduct->price ? (float) $relatedProduct->price : null,
                            'featured_image' => $relatedProduct->featured_image ? url('images/' . $relatedProduct->featured_image) : null,
                            'is_new' => (bool) $relatedProduct->is_new,
                            'is_on_sale' => (bool) $relatedProduct->is_on_sale,
                            'is_featured' => (bool) $relatedProduct->is_featured,
                            'discount_percentage' => $relatedProduct->discount_percentage ?? 0,
                        ])); ?>,
                        adding: false, 
                        inCart: false, 
                        removing: false,
                        init() {
                            const cart = JSON.parse(localStorage.getItem('cart') || '[]');
                            this.inCart = cart.some(item => item.id === this.relProduct.id);
                        },
                        
                        isFavorite(productId) {
                            if (!window.favoritesManager) return false;
                            return window.favoritesManager.isFavorite(productId);
                        },
                        handleCartClick() {
                            if (!this.inCart) {
                                this.adding = true;
                                
                                let cart = JSON.parse(localStorage.getItem('cart') || '[]');
                                const existingItem = cart.find(item => item.id === this.relProduct.id);
                                
                                if (existingItem) {
                                    existingItem.quantity += 1;
                                } else {
                                    cart.push({
                                        id: this.relProduct.id,
                                        name: this.relProduct.name,
                                        slug: this.relProduct.slug,
                                        price: this.relProduct.final_price,
                                        image: this.relProduct.featured_image || '<?php echo e(url('images/general/callback-image.svg')); ?>',
                                        quantity: 1
                                    });
                                }
                                
                                localStorage.setItem('cart', JSON.stringify(cart));
                                window.dispatchEvent(new Event('cartUpdated'));
                                
                                setTimeout(() => {
                                    this.adding = false;
                                    this.inCart = true;
                                }, 800);
                            } else if (this.removing) {
                                this.adding = true;
                                let cart = JSON.parse(localStorage.getItem('cart') || '[]');
                                cart = cart.filter(item => item.id !== this.relProduct.id);
                                localStorage.setItem('cart', JSON.stringify(cart));
                                window.dispatchEvent(new Event('cartUpdated'));
                                
                                setTimeout(() => {
                                    this.adding = false;
                                    this.inCart = false;
                                    this.removing = false;
                                }, 500);
                            }
                        }
                     }"
                     x-init="window.addEventListener('cartUpdated', () => {
                        const cart = JSON.parse(localStorage.getItem('cart') || '[]');
                        inCart = cart.some(item => item.id === relProduct.id);
                     })">
                    
                    <!-- Product Image -->
                    <div class="relative w-full aspect-square mb-4 flex-shrink-0 overflow-hidden">
                        <?php if($relatedProduct->featured_image): ?>
                            <img src="<?php echo e(url('images/' . $relatedProduct->featured_image)); ?>" 
                                 alt="<?php echo e($relatedProduct->name); ?> - <?php echo e($relatedProduct->category->name); ?> - Laser Link" 
                                 class="w-full h-full object-cover bg-white"
                                 onerror="this.src='<?php echo e(url('images/general/callback-image.svg')); ?>'; this.classList.add('object-contain', 'bg-gray-100');">
                        <?php else: ?>
                            <img src="<?php echo e(url('images/general/callback-image.svg')); ?>" 
                                 alt="<?php echo e($relatedProduct->name); ?> - Produto relacionado Laser Link"
                                 class="w-full h-full object-contain bg-gray-100">
                        <?php endif; ?>
                        
                        <!-- Badges -->
                        <div class="absolute left-3 top-3 flex flex-col gap-2 z-10">
                            <?php if($relatedProduct->is_new): ?>
                            <span class="px-2 py-1 rounded-md bg-blue-500 text-white text-xs font-semibold uppercase shadow-md w-fit">Novo</span>
                            <?php endif; ?>
                            
                            <?php if($relatedProduct->is_on_sale && $relatedProduct->price): ?>
                            <span class="px-2 py-1 rounded-md bg-red-500 text-white text-xs font-semibold shadow-md w-fit">
                                -<?php echo e($relatedProduct->discount_percentage); ?>%
                            </span>
                        <?php endif; ?>
                            
                            <?php if($relatedProduct->is_featured): ?>
                            <span class="px-2 py-1 rounded-md bg-amber-600 text-white text-xs font-semibold uppercase shadow-md w-fit">Destaque</span>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Favorite Button -->
                        <button @click.prevent="
                                if (window.favoritesManager) {
                                    window.favoritesManager.toggleFavorite(relProduct);
                                    updateFavIcon();
                                }
                            " 
                            x-data="{
                                updateFavIcon() {
                                    if (window.favoritesManager) {
                                        const icon = $el.querySelector('i');
                                        const isFav = window.favoritesManager.isFavorite(relProduct.id);
                                        icon.className = isFav 
                                            ? 'bi bi-heart-fill text-red-500 text-xl transition-all duration-200' 
                                            : 'bi bi-heart text-gray-700 text-xl transition-all duration-200';
                                    }
                                }
                            }"
                            x-init="
                                // Atualizar estado inicial
                                setTimeout(() => updateFavIcon(), 100);
                                
                                // Escutar mudanças
                                window.addEventListener('favoritesUpdated', () => updateFavIcon());
                            "
                            class="absolute right-3 top-3 w-10 h-10 rounded-md bg-white hover:bg-gray-50 flex items-center justify-center transition-all shadow-sm z-10">
                            <i class="bi bi-heart text-gray-700 text-xl transition-all duration-200"></i>
                        </button>
                    </div>
                    
                    <!-- Product Info -->
                    <div class="flex flex-col flex-grow space-y-2 p-4">
                        <!-- Title -->
                            <a href="<?php echo e(route('store.product', $relatedProduct->slug)); ?>" 
                           class="text-base font-medium capitalize hover:underline line-clamp-2 flex items-start text-[#272343]">
                            <?php echo e($relatedProduct->name); ?>

                        </a>
                        
                        <!-- Price and Rating -->
                        <div class="flex items-center justify-between gap-2 min-h-[2rem]">
                            <div class="flex items-center gap-2">
                                <p class="text-lg font-semibold text-[#272343]">
                                    R$ <?php echo e(number_format($relatedProduct->final_price, 2, ',', '.')); ?>

                                </p>
                                <?php if($relatedProduct->is_on_sale && $relatedProduct->price): ?>
                                <p class="text-sm text-[#9a9caa] line-through">
                                    R$ <?php echo e(number_format($relatedProduct->price, 2, ',', '.')); ?>

                                </p>
                                <?php endif; ?>
                        </div>
                            
                            <?php if (isset($component)) { $__componentOriginal716f57506f031c4bc2c687c3d4a6b958 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal716f57506f031c4bc2c687c3d4a6b958 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.product-rating','data' => ['rating' => $relatedProduct->rating_average ?? 0,'count' => $relatedProduct->rating_count ?? 0,'showCount' => false,'size' => 'md']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('product-rating'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['rating' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($relatedProduct->rating_average ?? 0),'count' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($relatedProduct->rating_count ?? 0),'showCount' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'size' => 'md']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal716f57506f031c4bc2c687c3d4a6b958)): ?>
<?php $attributes = $__attributesOriginal716f57506f031c4bc2c687c3d4a6b958; ?>
<?php unset($__attributesOriginal716f57506f031c4bc2c687c3d4a6b958); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal716f57506f031c4bc2c687c3d4a6b958)): ?>
<?php $component = $__componentOriginal716f57506f031c4bc2c687c3d4a6b958; ?>
<?php unset($__componentOriginal716f57506f031c4bc2c687c3d4a6b958); ?>
<?php endif; ?>
                        </div>
                        
                        <!-- Add to Cart Button Full Width -->
                        <button @click="handleCartClick()"
                                @mouseenter="if(inCart) removing = true"
                                @mouseleave="removing = false"
                                class="w-full py-2 rounded-lg text-sm font-medium transition-all flex items-center justify-center gap-2 mt-auto"
                                :class="inCart && !removing ? 'bg-green-500 text-white transition-colors duration-200' : inCart && removing ? 'bg-red-500 text-white transition-colors duration-200' : 'bg-gray-900 hover:bg-black text-white transition-colors duration-200'">
                            <!-- Static state - Adicionar -->
                            <template x-if="!adding && !inCart">
                                <div class="flex items-center gap-2">
                                    <i class="bi bi-cart-plus text-base"></i>
                                    <span>Adicionar ao Carrinho</span>
                                </div>
                            </template>
                            
                            <!-- Loading before adding -->
                            <template x-if="adding">
                                <div class="flex items-center gap-2">
                                    <i class="bi bi-arrow-clockwise text-base animate-spin"></i>
                                    <span>Adicionando...</span>
                                </div>
                            </template>
                            
                            <!-- Added to cart -->
                            <template x-if="!adding && inCart && !removing">
                                <div class="flex items-center gap-2">
                                    <i class="bi bi-cart-check-fill text-base"></i>
                                    <span>Produto Adicionado</span>
                                </div>
                            </template>
                            
                            <!-- Remove on hover -->
                            <template x-if="!adding && inCart && removing">
                                <div class="flex items-center gap-2">
                                    <i class="bi bi-cart-x-fill text-base"></i>
                                    <span>Remover do Carrinho</span>
                                </div>
                            </template>
                        </button>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Image Modal -->
    <div x-show="imageModalOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-black bg-opacity-75 transition-opacity" @click="closeImageModal()"></div>
            
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-4xl w-full overflow-hidden">
                <button @click="closeImageModal()" 
                        class="absolute top-4 right-4 z-10 bg-white rounded-full p-2 hover:bg-gray-100 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    </button>
                <img :src="modalImageSrc" alt="Product Image" class="w-full h-auto">
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function productPage() {
    return {
        // Image gallery
        currentImageIndex: 0,
        totalImages: <?php echo e(count($allImages)); ?>,
        imageModalOpen: false,
        modalImageSrc: '',
        thumbnailScrollPosition: 0,
        thumbnailMaxScroll: 0,
        
        // Favorites
        isFavorite: false,
        
        // Cart states
        adding: false,
        inCart: false,
        removing: false,
        
        // Customization
        customization: {},
        selectedOptions: {},
        quantity: 1,
        basePrice: <?php echo e($product->auto_calculate_price ? ($product->min_price ?? 0) : ($product->final_price ?? 0)); ?>,
        extraCost: 0,
        totalPrice: <?php echo e($product->auto_calculate_price ? ($product->min_price ?? 0) : ($product->final_price ?? 0)); ?>,
        
        init() {
            // Initialize customization fields and selectedOptions
            <?php if($extraFields && $extraFields->count() > 0): ?>
                <?php $__currentLoopData = $extraFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($field->type === 'checkbox'): ?>
                        this.customization['<?php echo e($field->slug); ?>'] = [];
                        this.selectedOptions['<?php echo e($field->slug); ?>'] = [];
                    <?php else: ?>
                        this.customization['<?php echo e($field->slug); ?>'] = '';
                        this.selectedOptions['<?php echo e($field->slug); ?>'] = '';
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
            
            // Check if product is in favorites
            this.checkFavorite();
            
            // Check if product is already in cart
            const cart = JSON.parse(localStorage.getItem('cart') || '[]');
            this.inCart = cart.some(item => item.product_id === <?php echo e($product->id); ?>);
            
            // Update pricing on init
            this.updatePricing();
            
            // Watch for changes in selectedOptions
            this.$watch('selectedOptions', () => {
                this.calculateTotalPrice();
            }, { deep: true });
            
            // Listen for favorites updates
            window.addEventListener('favoritesUpdated', () => {
                this.syncFavorites();
                this.isFavorite = this.favorites.includes(<?php echo e($product->id); ?>);
            });
            
            // Keyboard navigation
            document.addEventListener('keydown', (e) => {
                if (this.imageModalOpen) return;
                if (e.key === 'ArrowLeft') this.previousImage();
                if (e.key === 'ArrowRight') this.nextImage();
            });
            
            // Initialize thumbnail scroll
            this.$nextTick(() => {
                this.updateThumbnailScrollPosition();
                this.updateThumbnailScrollPositionHorizontal();
            });
            
            // Registrar visualização do produto
            this.registerProductView();
        },
        
        changeImage(index) {
            this.currentImageIndex = index;
            
            // Scroll para desktop (vertical)
            this.scrollThumbnailIntoView(index);
            
            // Scroll para mobile/tablet (horizontal)
            this.scrollThumbnailIntoViewHorizontal(index);
        },
        
        nextImage() {
            this.currentImageIndex = (this.currentImageIndex + 1) % this.totalImages;
            this.scrollThumbnailIntoView(this.currentImageIndex);
            this.scrollThumbnailIntoViewHorizontal(this.currentImageIndex);
        },
        
        previousImage() {
            this.currentImageIndex = this.currentImageIndex === 0 ? this.totalImages - 1 : this.currentImageIndex - 1;
            this.scrollThumbnailIntoView(this.currentImageIndex);
            this.scrollThumbnailIntoViewHorizontal(this.currentImageIndex);
        },
        
        scrollThumbnails(direction) {
            const container = this.$refs.thumbnailsContainer;
            if (!container) return;
            
            const scrollAmount = 120; // Aproximadamente a altura de uma thumbnail + gap
            
            if (direction === 'up') {
                container.scrollBy({ top: -scrollAmount, behavior: 'smooth' });
            } else {
                container.scrollBy({ top: scrollAmount, behavior: 'smooth' });
            }
        },
        
        updateThumbnailScrollPosition() {
            const container = this.$refs.thumbnailsContainer;
            if (!container) return;
            
            this.thumbnailScrollPosition = container.scrollTop;
            this.thumbnailMaxScroll = container.scrollHeight - container.clientHeight;
        },
        
        scrollThumbnailIntoView(index) {
            const container = this.$refs.thumbnailsContainer;
            if (!container) return;
            
            const thumbnails = container.children;
            if (thumbnails[index]) {
                thumbnails[index].scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'nearest' 
                });
            }
        },
        
        // Funções para scroll horizontal no mobile/tablet
        scrollThumbnailsHorizontal(direction) {
            const container = this.$refs.thumbnailsContainerMobile;
            if (!container) return;
            
            const scrollAmount = 80; // Largura de uma thumbnail + gap
            
            if (direction === 'left') {
                container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
            } else {
                container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
            }
        },
        
        updateThumbnailScrollPositionHorizontal() {
            const container = this.$refs.thumbnailsContainerMobile;
            if (!container) return;
            
            this.thumbnailScrollPosition = container.scrollLeft;
            this.thumbnailMaxScroll = container.scrollWidth - container.clientWidth;
        },
        
        scrollThumbnailIntoViewHorizontal(index) {
            const container = this.$refs.thumbnailsContainerMobile;
            if (!container) return;
            
            const thumbnails = container.children;
            if (thumbnails[index]) {
                thumbnails[index].scrollIntoView({ 
                    behavior: 'smooth', 
                    inline: 'center',
                    block: 'nearest'
                });
            }
        },
        
        openImageModal(src) {
            this.modalImageSrc = src;
            this.imageModalOpen = true;
            document.body.style.overflow = 'hidden';
        },
        
        closeImageModal() {
            this.imageModalOpen = false;
            document.body.style.overflow = 'auto';
        },
        
        favorites: [],
        
        syncFavorites() {
            if (window.favoritesManager) {
                const favs = window.favoritesManager.getAllFavorites();
                this.favorites = favs.map(f => f.id);
            }
        },
        
        checkFavorite() {
            this.syncFavorites();
            this.isFavorite = this.favorites.includes(<?php echo e($product->id); ?>);
        },
        
        isFavoriteProduct(productId) {
            return this.favorites.includes(productId);
        },
        
        toggleFavorite(productId) {
            if (!window.favoritesManager) {
                return;
            }
            
            // Se for o produto principal
            if (productId === <?php echo e($product->id); ?>) {
                const productData = {
                    id: <?php echo e($product->id); ?>,
                    name: '<?php echo e($product->name); ?>',
                    slug: '<?php echo e($product->slug); ?>',
                    price: <?php echo e($product->final_price); ?>,
                    image_url: '<?php echo e($product->featured_image ? url('images/' . $product->featured_image) : url('images/general/callback-image.svg')); ?>'
                };
                
                window.favoritesManager.toggleFavorite(productData);
                this.syncFavorites();
                this.isFavorite = this.favorites.includes(productId);
                return;
            }
            
            // Para produtos relacionados
            const productCard = event.target.closest('[x-data]');
            if (!productCard) return;
            
            const alpineData = Alpine.$data(productCard);
            if (!alpineData || !alpineData.relProduct) return;
            
            const product = alpineData.relProduct;
            const productData = {
                id: product.id,
                name: product.name,
                slug: product.slug,
                price: product.final_price,
                image_url: product.featured_image || '<?php echo e(url('images/general/callback-image.svg')); ?>'
            };
            
            window.favoritesManager.toggleFavorite(productData);
            this.syncFavorites();
        },
        
        increaseQuantity() {
            <?php if($product->track_stock && $product->stock_quantity > 0): ?>
                if (this.quantity < <?php echo e($product->stock_quantity); ?> && this.quantity < 99) {
                    this.quantity++;
                    this.calculateTotalPrice();
                }
            <?php else: ?>
                if (this.quantity < 99) {
                    this.quantity++;
                    this.calculateTotalPrice();
                }
            <?php endif; ?>
        },
        
        decreaseQuantity() {
            if (this.quantity > 1) {
                this.quantity--;
                this.calculateTotalPrice();
            }
        },
        
        calculateTotalPrice() {
            let extraCost = 0;
            
            // Calcular custo baseado nas opções selecionadas
            Object.keys(this.selectedOptions).forEach(fieldSlug => {
                const selectedValue = this.selectedOptions[fieldSlug];
                
                if (Array.isArray(selectedValue)) {
                    // Checkbox - múltiplas seleções
                    selectedValue.forEach(value => {
                        const option = this.getOptionByValue(fieldSlug, value);
                        if (option && option.price > 0) {
                            if (option.price_type === 'percentage') {
                                const percentCost = (this.basePrice * option.price) / 100;
                                extraCost += percentCost;
                            } else {
                                extraCost += parseFloat(option.price);
                            }
                        }
                    });
                } else if (selectedValue) {
                    // Select/Radio - seleção única
                    const option = this.getOptionByValue(fieldSlug, selectedValue);
                    if (option && option.price > 0) {
                        if (option.price_type === 'percentage') {
                            const percentCost = (this.basePrice * option.price) / 100;
                            extraCost += percentCost;
                        } else {
                            extraCost += parseFloat(option.price);
                        }
                    }
                }
            });
            
            // Atualizar valores de forma reativa
            this.extraCost = parseFloat(extraCost.toFixed(2));
            this.totalPrice = parseFloat(((this.basePrice + this.extraCost) * this.quantity).toFixed(2));
            
            // Forçar atualização da interface
            this.$nextTick(() => {
                // Interface atualizada
            });
        },
        
        getOptionByValue(fieldSlug, value) {
            // Buscar opção nas configurações dos campos extras
            <?php if($extraFields && $extraFields->count() > 0): ?>
                <?php $__currentLoopData = $extraFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    if ('<?php echo e($field->slug); ?>' === fieldSlug) {
                        <?php
                            $fieldSettings = json_decode($field->pivot->field_settings ?? '{}', true);
                            $customOptions = $fieldSettings['custom_options'] ?? null;
                            
                            if ($customOptions && count($customOptions) > 0) {
                                // Normalizar opções customizadas para garantir que price seja float
                                $optionsToShow = array_map(function($option) {
                                    return [
                                        'value' => $option['value'] ?? '',
                                        'label' => $option['label'] ?? '',
                                        'image_url' => $option['image_url'] ?? null,
                                        'color_hex' => $option['color_hex'] ?? null,
                                        'price' => (float) ($option['price'] ?? 0),
                                        'price_type' => $option['price_type'] ?? 'fixed'
                                    ];
                                }, $customOptions);
                } else {
                                $optionsToShow = $field->options->map(function($option) {
                                    return [
                                        'value' => $option->value,
                                        'label' => $option->label,
                                        'image_url' => $option->image_url ?? null,
                                        'color_hex' => $option->color_hex ?? null,
                                        'price' => (float) ($option->price ?? 0),
                                        'price_type' => $option->price_type ?? 'fixed'
                                    ];
                                })->toArray();
                            }
                        ?>
                        
                        const options_<?php echo e($field->id); ?> = <?php echo json_encode($optionsToShow, 15, 512) ?>;
                        const found = options_<?php echo e($field->id); ?>.find(opt => opt.value === value);
                        return found;
                    }
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
            return null;
        },
        
        updatePricing() {
            this.calculateTotalPrice();
        },

        formatPrice(price) {
            return parseFloat(price || 0).toFixed(2).replace('.', ',');
        },

        async addToCart() {
            // Add product to cart
            const cartData = {
                product_id: <?php echo e($product->id); ?>,
                quantity: 1,
                customization: {},
                price: this.basePrice
            };
            
            // TODO: Implement cart API call
            alert('Produto adicionado ao carrinho!');
        },
        
        async submitCustomization() {
            // Se já está no carrinho e está removendo, remover
            if (this.inCart && this.removing) {
                this.removing = false;
                
                try {
                    let cart = JSON.parse(localStorage.getItem('cart') || '[]');
                    
                    // Remover todos os itens deste produto (incluindo customizados)
                    cart = cart.filter(item => item.product_id !== <?php echo e($product->id); ?>);
                    
                    localStorage.setItem('cart', JSON.stringify(cart));
                    window.dispatchEvent(new Event('cartUpdated'));
                    
                    this.inCart = false;
                    this.showSuccessMessage('🗑️ Produto removido do carrinho!');
                } catch (error) {
                    this.showErrorMessage('Erro ao remover produto do carrinho.');
                }
                return;
            }
            
            // Se já está no carrinho, não fazer nada
            if (this.inCart) return;
            
            // Validate required fields
            let isValid = true;
            <?php $__currentLoopData = $extraFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($field->pivot->is_required): ?>
                    if (!this.customization['<?php echo e($field->slug); ?>'] || this.customization['<?php echo e($field->slug); ?>'].length === 0) {
                        alert('Por favor, preencha o campo: <?php echo e($field->name); ?>');
                        isValid = false;
                        return;
                    }
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            
            if (!isValid) return;
            
            // Validar campos obrigatórios
            if (!this.validateRequiredFields()) {
                return;
            }
            
            // Mostrar estado de loading
            this.adding = true;
            
            // Add customized product to cart
            // Calcular preço unitário (sem multiplicar pela quantidade)
            const unitPrice = this.basePrice + this.extraCost;
            
            const cartData = {
                product_id: <?php echo e($product->id); ?>,
                quantity: this.quantity,
                customization: this.selectedOptions,
                extra_cost: this.extraCost,
                unit_price: unitPrice,
                total_price: unitPrice * this.quantity,
                base_price: this.basePrice
            };

            try {
                // Simular delay para mostrar loading
                await new Promise(resolve => setTimeout(resolve, 800));
                
                // Usar o mesmo sistema dos produtos relacionados
                let cart = JSON.parse(localStorage.getItem('cart') || '[]');
                
                // Criar ID único para o item customizado
                const customItemId = `custom_${cartData.product_id}_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`;
                
                // Adicionar item ao carrinho
                cart.push({
                    id: customItemId,
                    product_id: cartData.product_id,
                    name: cartData.product_name || '<?php echo e($product->name); ?>',
                    slug: cartData.product_slug || '<?php echo e($product->slug); ?>',
                    price: cartData.unit_price,
                    image: cartData.product_image || '<?php echo e($product->featured_image ? url('images/' . $product->featured_image) : url('images/general/callback-image.svg')); ?>',
                    quantity: cartData.quantity,
                    customization: cartData.customization,
                    extra_cost: cartData.extra_cost,
                    base_price: cartData.base_price,
                    unit_price: cartData.unit_price,
                    total_price: cartData.total_price,
                    added_at: new Date().toISOString()
                });
                
                localStorage.setItem('cart', JSON.stringify(cart));
                window.dispatchEvent(new Event('cartUpdated'));
                
                // Atualizar estados
                this.adding = false;
                this.inCart = true;
                
                this.showSuccessMessage('✅ Produto adicionado ao carrinho com sucesso!');
                
                // Opcional: Enviar dados para API para validação/backup
                try {
                    await fetch('/api/adicionar-carrinho', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(cartData)
                    });
                } catch (apiError) {
                    // Erro na API (dados salvos localmente)
                }
                
            } catch (error) {
                this.adding = false;
                this.showErrorMessage('Erro ao adicionar produto ao carrinho. Tente novamente.');
            }
        },
        
        validateRequiredFields() {
            <?php if($extraFields && $extraFields->count() > 0): ?>
                <?php $__currentLoopData = $extraFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($field->pivot->is_required): ?>
                        if (!this.selectedOptions['<?php echo e($field->slug); ?>'] || 
                            (Array.isArray(this.selectedOptions['<?php echo e($field->slug); ?>']) && this.selectedOptions['<?php echo e($field->slug); ?>'].length === 0)) {
                            this.showErrorMessage('Por favor, selecione uma opção para: <?php echo e($field->name); ?>');
                            return false;
                        }
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
            return true;
        },
        
        showNotification(message, type = 'success') {
            // Remover notificações anteriores
            document.querySelectorAll('.store-notification').forEach(n => n.remove());
            
            const notification = document.createElement('div');
            notification.className = `store-notification ${type}`;
            
            let icon = '';
            switch(type) {
                case 'success':
                    icon = '<svg class="store-notification-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>';
                    break;
                case 'error':
                    icon = '<svg class="store-notification-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>';
                    break;
                case 'warning':
                    icon = '<svg class="store-notification-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>';
                    break;
            }
            
            notification.innerHTML = `
                <div class="store-notification-content">
                    ${icon}
                    <div class="store-notification-text">${message}</div>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Animar entrada
            setTimeout(() => {
                notification.classList.add('show');
            }, 10);
            
            // Auto-hide após 4 segundos
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 4000);
        },
        
        showSuccessMessage(message) {
            this.showNotification(message, 'success');
        },
        
        showErrorMessage(message) {
            this.showNotification(message, 'error');
        },
        
        registerProductView() {
            // Registrar produto visualizado para a seção "Produtos Vistos Recentemente"
            const productData = {
                id: <?php echo e($product->id); ?>,
                name: '<?php echo e(addslashes($product->name)); ?>',
                slug: '<?php echo e($product->slug); ?>',
                price: <?php echo e($product->final_price); ?>,
                image: '<?php echo e($product->featured_image ? url('images/' . $product->featured_image) : url('images/general/callback-image.svg')); ?>'
            };
            
            // Disparar evento para outras páginas ouvirem
            window.dispatchEvent(new CustomEvent('productViewed', { 
                detail: productData 
            }));
            
            // Também salvar diretamente no localStorage
            let recentProducts = JSON.parse(localStorage.getItem('recentlyViewed') || '[]');
            
            // Remover se já existir
            recentProducts = recentProducts.filter(p => p.id !== productData.id);
            
            // Adicionar no início
            recentProducts.unshift({
                ...productData,
                viewed_at: new Date().toISOString()
            });
            
            // Limitar a 4 produtos
            if (recentProducts.length > 4) {
                recentProducts = recentProducts.slice(0, 4);
            }
            
            localStorage.setItem('recentlyViewed', JSON.stringify(recentProducts));
        }
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.store', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\resources\views/store/product.blade.php ENDPATH**/ ?>