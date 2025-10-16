<?php $__env->startSection('title', 'Favoritos'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Meus Favoritos</h1>
        <button id="clear-favorites" class="text-sm text-red-600 hover:text-red-700">Limpar favoritos</button>
    </div>

    <div id="favorites-loading" class="hidden text-center py-20">
        <?php if (isset($component)) { $__componentOriginal5c29929acf227acd7c5fa56a39e71fcc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c29929acf227acd7c5fa56a39e71fcc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.loading-spinner','data' => ['size' => 'lg','text' => 'Carregando favoritos...']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('loading-spinner'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'lg','text' => 'Carregando favoritos...']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c29929acf227acd7c5fa56a39e71fcc)): ?>
<?php $attributes = $__attributesOriginal5c29929acf227acd7c5fa56a39e71fcc; ?>
<?php unset($__attributesOriginal5c29929acf227acd7c5fa56a39e71fcc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c29929acf227acd7c5fa56a39e71fcc)): ?>
<?php $component = $__componentOriginal5c29929acf227acd7c5fa56a39e71fcc; ?>
<?php unset($__componentOriginal5c29929acf227acd7c5fa56a39e71fcc); ?>
<?php endif; ?>
    </div>

    <div id="favorites-empty" class="hidden text-center py-20">
        <i class="bi bi-heart text-5xl text-gray-300"></i>
        <p class="mt-4 text-gray-600">Você ainda não adicionou produtos aos favoritos.</p>
        <a href="<?php echo e(route('store.index')); ?>" class="mt-6 inline-block px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700">Explorar produtos</a>
    </div>

    <div id="favorites-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8"></div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const grid = document.getElementById('favorites-grid');
    const emptyState = document.getElementById('favorites-empty');
    const loadingState = document.getElementById('favorites-loading');
    const clearBtn = document.getElementById('clear-favorites');

    function getFavorites() {
        try {
            const stored = localStorage.getItem('favorites');
            return stored ? JSON.parse(stored) : [];
        } catch (error) {
            return [];
        }
    }

    function setFavorites(favorites) {
        localStorage.setItem('favorites', JSON.stringify(favorites));
        window.dispatchEvent(new CustomEvent('favoritesUpdated', {
            detail: { count: favorites.length, favorites: favorites }
        }));
    }

    function removeFavorite(id) {
        const favorites = getFavorites().filter(fav => fav.id !== id);
        setFavorites(favorites);
        render();
    }

    function fetchProducts(favorites) {
        const ids = favorites.map(fav => fav.id).join(',');
        
        if (!ids) {
            return Promise.resolve([]);
        }
        
        const url = `<?php echo e(route('api.favorites')); ?>?ids=${encodeURIComponent(ids)}`;
        
        return fetch(url)
            .then(r => r.json())
            .then(data => data.products || [])
            .catch(() => []);
    }

    function productCard(p) {
        const url = `<?php echo e(url('/produto')); ?>/${p.slug}`;
        
        return `
        <div class="relative group w-full sm:max-w-[300px] sm:mx-auto flex flex-col">
            <!-- Product Image -->
            <div class="relative w-full aspect-square mb-4 flex-shrink-0 overflow-hidden rounded-md group">
                <a href="${url}" class="block w-full h-full transition-transform duration-300 group-hover:scale-105">
                    <img src="<?php echo e(url('storage')); ?>/${p.image_url}" 
                     alt="${p.name} - Comunicação Visual Laser Link"
                     class="w-full h-full object-cover bg-white transition-transform duration-300"
                     onerror="this.src='<?php echo e(url('images/general/callback-image.svg')); ?>'; this.classList.remove('object-cover'); this.classList.add('object-contain', 'bg-gray-100');"
                />
                </a>
                
                <!-- Badges -->
                <div class="absolute left-3 top-3 flex flex-col gap-2 z-10">
                    ${p.is_new ? `
                    <span class="px-2 py-1 rounded-md bg-blue-500 text-white text-xs font-semibold uppercase shadow-md w-fit">Novo</span>
                    ` : ''}
                    
                    ${p.is_on_sale ? `
                    <span class="px-2 py-1 rounded-md bg-red-500 text-white text-xs font-semibold shadow-md w-fit">
                        -${p.discount_percentage}%
                    </span>
                    ` : ''}
                    
                    ${p.is_featured ? `
                    <span class="px-2 py-1 rounded-md bg-amber-600 text-white text-xs font-semibold uppercase shadow-md w-fit">Destaque</span>
                    ` : ''}
                </div>
                
                <!-- Favorite Button (Remove) -->
                <button data-remove="${p.id}" 
                        class="absolute right-3 top-3 w-10 h-10 rounded-md bg-white hover:bg-red-50 flex items-center justify-center transition-all shadow-sm z-10"
                        title="Remover dos favoritos">
                    <i class="bi bi-heart-fill text-red-500 text-xl transition-all duration-200"></i>
                </button>
            </div>
            
            <!-- Product Info -->
            <div class="flex flex-col flex-grow space-y-2">
                <!-- Title -->
                <a href="${url}" 
                   class="text-base font-medium capitalize hover:underline line-clamp-2 flex items-start text-[#272343]">
                    ${p.name}
                </a>
                
                <!-- Price and Rating -->
                <div class="flex items-center justify-between gap-2 min-h-[2rem]">
                    <div class="flex items-center gap-2">
                        ${p.auto_calculate_price ? `
                            <p class="text-lg font-semibold text-green-600">
                                <i class="bi bi-calculator mr-1"></i>
                                A partir de R$ ${(p.min_price || 0).toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2})}
                            </p>
                        ` : p.sale_price ? `
                            <p class="text-lg font-semibold text-[#272343]">
                                R$ ${p.sale_price.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2})}
                            </p>
                            ${p.price > p.sale_price ? `
                            <p class="text-sm text-[#9a9caa] line-through">
                                R$ ${p.price.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2})}
                            </p>
                            ` : ''}
                        ` : p.price ? `
                            <p class="text-lg font-semibold text-[#272343]">
                                R$ ${p.price.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2})}
                            </p>
                        ` : `
                            <p class="text-lg font-semibold text-gray-400">
                                Preço sob consulta
                            </p>
                        `}
                    </div>
                    
                    <!-- Rating Stars -->
                    <div class="flex items-center gap-0.5 text-base">
                        ${[1, 2, 3, 4, 5].map(i => {
                            const rating = p.rating_average || 0;
                            const isHalf = i - 0.5 <= rating && i > Math.floor(rating);
                            const isFull = i <= Math.floor(rating);
                            const iconClass = isFull ? 'bi-star-fill' : (isHalf ? 'bi-star-half' : 'bi-star');
                            return `<i class="bi ${iconClass} text-yellow-400"></i>`;
                        }).join('')}
                    </div>
                </div>
                
                <!-- View Product Button -->
                <a href="${url}" 
                   class="w-full py-2 rounded-lg text-sm font-medium bg-gray-900 hover:bg-black text-white transition-colors duration-200 flex items-center justify-center gap-2 mt-auto">
                    <i class="bi bi-eye text-base"></i>
                    <span>Ver Produto</span>
                </a>
            </div>
        </div>`;
    }

    function attachRemoveHandlers() {
        grid.querySelectorAll('button[data-remove]').forEach(btn => {
            btn.addEventListener('click', () => removeFavorite(parseInt(btn.getAttribute('data-remove'))));
        });
    }

    function render() {
        const favorites = getFavorites();
        
        // Esconder tudo primeiro
        emptyState.classList.add('hidden');
        loadingState.classList.add('hidden');
        grid.innerHTML = '';
        
        if (!favorites.length) {
            emptyState.classList.remove('hidden');
            clearBtn.classList.add('hidden');
            return;
        }
        
        // Mostrar loading
        loadingState.classList.remove('hidden');
        clearBtn.classList.remove('hidden');
        
        fetchProducts(favorites).then(products => {
            loadingState.classList.add('hidden');
            
            // Manter ordem conforme favoritos
            const productById = new Map(products.map(p => [p.id, p]));
            const ordered = favorites
                .map(fav => productById.get(fav.id))
                .filter(Boolean);
            
            if (ordered.length === 0) {
                emptyState.classList.remove('hidden');
                clearBtn.classList.add('hidden');
            } else {
                grid.innerHTML = ordered.map(productCard).join('');
                attachRemoveHandlers();
            }
        }).catch(() => {
            loadingState.classList.add('hidden');
            emptyState.classList.remove('hidden');
        });
    }

    clearBtn.addEventListener('click', function () {
        if (confirm('Tem certeza que deseja limpar todos os favoritos?')) {
            setFavorites([]);
            render();
        }
    });

    // Atualizar quando favoritos mudarem em outra aba/página
    window.addEventListener('favoritesUpdated', function() {
        render();
    });

    // Atualizar quando storage mudar (outras abas)
    window.addEventListener('storage', function(e) {
        if (e.key === 'favorites') {
            render();
        }
    });

    render();
});
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.store', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\resources\views/store/favorites.blade.php ENDPATH**/ ?>