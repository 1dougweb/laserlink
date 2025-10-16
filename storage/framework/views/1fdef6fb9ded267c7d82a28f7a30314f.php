<?php
    $menuEnabled = \App\Models\Setting::get('store_bottom_menu_enabled', true);
    $menuStyle = \App\Models\Setting::get('store_menu_style', 'modern');
    $menuPosition = \App\Models\Setting::get('store_menu_position', 'bottom');
    $menuItems = \App\Models\StoreMenuItem::active()->ordered()->get();
    $currentPath = request()->path();
    $currentRoute = request()->route() ? request()->route()->getName() : null;
    
    // Função para verificar se o link está ativo
    function isMenuItemActive($item, $currentPath, $currentRoute) {
        // Se for link externo, nunca está ativo
        if ($item->is_external) {
            return false;
        }
        
        $itemUrl = trim($item->url, '/');
        $currentPath = trim($currentPath, '/');
        
        // Se o item aponta para a raiz e estamos na raiz
        if (empty($itemUrl) && empty($currentPath)) {
            return true;
        }
        
        // Se o item aponta para a raiz mas não estamos na raiz
        if (empty($itemUrl) && !empty($currentPath)) {
            return false;
        }
        
        // Verificar se a rota atual corresponde
        $routeMappings = [
            '/' => 'store.index',
            'categorias' => 'store.category',
            'favoritos' => 'store.favorites',
            'carrinho' => 'store.cart',
            'checkout' => 'store.checkout'
        ];
        
        if (isset($routeMappings[$itemUrl]) && $routeMappings[$itemUrl] === $currentRoute) {
            return true;
        }
        
        // Verificar se o path atual começa com o path do item
        if (!empty($itemUrl) && str_starts_with($currentPath, $itemUrl)) {
            return true;
        }
        
        return false;
    }
?>

<?php if($menuEnabled && $menuItems->count() > 0): ?>
    <div class="bg-black border-t border-gray-700 shadow-lg"
         id="store-bottom-menu">
        <div class="max-w-7xl mx-auto px-4 py-2">
            <div class="flex items-center gap-6 overflow-x-auto scrollbar-hide">
                <?php $__currentLoopData = $menuItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $isActive = isMenuItemActive($item, $currentPath, $currentRoute);
                    ?>
                    <a href="<?php echo e($item->is_external ? $item->url : url($item->url)); ?>" 
                       class="flex items-center space-x-2 px-4 py-2 rounded-lg transition-colors duration-200 whitespace-nowrap flex-shrink-0 <?php echo e($isActive ? 'text-red-500 bg-[#222222]' : 'text-white hover:text-red-500 hover:bg-[#222222]'); ?>"
                       <?php echo e($item->is_external ? 'target="_blank" rel="noopener noreferrer"' : ''); ?>>
                        
                        <i class="<?php echo e($item->icon ?? 'bi bi-link'); ?> text-lg"></i>
                        <span class="text-sm font-medium"><?php echo e($item->name); ?></span>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

<?php endif; ?>

<style>
/* Esconder barra de scroll no menu inferior */
.scrollbar-hide {
    -ms-overflow-style: none;  /* Internet Explorer 10+ */
    scrollbar-width: none;  /* Firefox */
}
.scrollbar-hide::-webkit-scrollbar { 
    display: none;  /* Safari and Chrome */
}

/* Melhorar a experiência de scroll */
.scrollbar-hide {
    scroll-behavior: smooth;
    -webkit-overflow-scrolling: touch;
}
</style>
<?php /**PATH C:\xampp\htdocs\resources\views/components/store-bottom-menu.blade.php ENDPATH**/ ?>