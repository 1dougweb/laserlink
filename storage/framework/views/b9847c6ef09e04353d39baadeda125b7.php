<?php $__env->startSection('title', 'Produtos'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Produtos</h1>
        <a href="<?php echo e(route('admin.products.create')); ?>" 
           class="bg-primary hover:opacity-90 text-white px-4 py-2 rounded-lg transition-all shadow-sm hover:shadow-md">
            + Novo Produto
        </a>
    </div>

    <!-- Filtros de Pesquisa -->
    <div class="bg-white rounded-lg shadow mb-6" x-data="{ showFilters: <?php echo e(request()->hasAny(['search', 'category_id', 'status', 'featured', 'price_min', 'price_max']) ? 'true' : 'false'); ?> }">
        <div class="p-4 border-b border-gray-200">
            <button @click="showFilters = !showFilters" 
                    class="flex items-center justify-between w-full text-left">
                <div class="flex items-center space-x-2">
                    <i class="bi bi-funnel text-gray-600"></i>
                    <span class="font-medium text-gray-900">Filtros de Pesquisa</span>
                    <?php if(request()->hasAny(['search', 'category_id', 'status', 'featured', 'price_min', 'price_max'])): ?>
                        <span class="bg-primary bg-opacity-10 text-primary text-xs font-medium px-2.5 py-0.5 rounded-full">
                            Ativos
                        </span>
                    <?php endif; ?>
                </div>
                <i class="bi transition-transform duration-200" 
                   :class="showFilters ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
            </button>
        </div>

        <form method="GET" action="<?php echo e(route('admin.products')); ?>" x-show="showFilters" x-cloak>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Pesquisa por Nome/SKU -->
                <div class="lg:col-span-2">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="bi bi-search mr-1"></i>
                        Buscar por Nome ou SKU
                    </label>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="<?php echo e(request('search')); ?>"
                           placeholder="Digite o nome ou SKU..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>

                <!-- Categoria -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="bi bi-folder mr-1"></i>
                        Categoria
                    </label>
                    <select id="category_id" 
                            name="category_id" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">Todas</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->id); ?>" <?php echo e(request('category_id') == $category->id ? 'selected' : ''); ?>>
                                <?php echo e($category->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="bi bi-toggle-on mr-1"></i>
                        Status
                    </label>
                    <select id="status" 
                            name="status" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">Todos</option>
                        <option value="active" <?php echo e(request('status') === 'active' ? 'selected' : ''); ?>>Ativo</option>
                        <option value="inactive" <?php echo e(request('status') === 'inactive' ? 'selected' : ''); ?>>Inativo</option>
                    </select>
                </div>

                <!-- Preço Mínimo -->
                <div>
                    <label for="price_min" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="bi bi-currency-dollar mr-1"></i>
                        Preço Mínimo
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">R$</span>
                        <input type="text" 
                               id="price_min" 
                               name="price_min_display" 
                               value="<?php echo e(request('price_min') ? number_format((float)request('price_min'), 2, ',', '.') : ''); ?>"
                               placeholder="0,00"
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        <input type="hidden" id="price_min_value" name="price_min" value="<?php echo e(request('price_min')); ?>">
                    </div>
                </div>

                <!-- Preço Máximo -->
                <div>
                    <label for="price_max" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="bi bi-currency-dollar mr-1"></i>
                        Preço Máximo
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">R$</span>
                        <input type="text" 
                               id="price_max" 
                               name="price_max_display" 
                               value="<?php echo e(request('price_max') ? number_format((float)request('price_max'), 2, ',', '.') : ''); ?>"
                               placeholder="0,00"
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        <input type="hidden" id="price_max_value" name="price_max" value="<?php echo e(request('price_max')); ?>">
                    </div>
                </div>

                <!-- Destaque -->
                <div>
                    <label for="featured" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="bi bi-star mr-1"></i>
                        Destaque
                    </label>
                    <select id="featured" 
                            name="featured" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">Todos</option>
                        <option value="1" <?php echo e(request('featured') === '1' ? 'selected' : ''); ?>>Sim</option>
                        <option value="0" <?php echo e(request('featured') === '0' ? 'selected' : ''); ?>>Não</option>
                    </select>
                </div>

                <!-- Ordenação -->
                <div>
                    <label for="sort_by" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="bi bi-sort-down mr-1"></i>
                        Ordenar por
                    </label>
                    <select id="sort_by" 
                            name="sort_by" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="created_at" <?php echo e(request('sort_by', 'created_at') === 'created_at' ? 'selected' : ''); ?>>Data de Criação</option>
                        <option value="name" <?php echo e(request('sort_by') === 'name' ? 'selected' : ''); ?>>Nome</option>
                        <option value="price" <?php echo e(request('sort_by') === 'price' ? 'selected' : ''); ?>>Preço</option>
                        <option value="stock_quantity" <?php echo e(request('sort_by') === 'stock_quantity' ? 'selected' : ''); ?>>Estoque</option>
                    </select>
                </div>
            </div>

            <!-- Botões de Ação -->
            <div class="px-6 pb-6 flex items-center justify-between">
                <a href="<?php echo e(route('admin.products')); ?>" 
                   class="text-sm text-gray-600 hover:text-gray-900 flex items-center">
                    <i class="bi bi-x-circle mr-1"></i>
                    Limpar Filtros
                </a>
                <div class="flex space-x-3">
                    <button type="submit" 
                            class="bg-primary hover:opacity-90 text-white px-6 py-2 rounded-lg transition-all shadow-sm hover:shadow-md flex items-center">
                        <i class="bi bi-search mr-2"></i>
                        Filtrar
                    </button>
                </div>
            </div>
        </form>
    </div>

    <?php if($products->count() > 0): ?>
        <!-- Contador de Resultados -->
        <div class="mb-4 flex items-center justify-between">
            <div class="text-sm text-gray-600">
                Mostrando <span class="font-semibold"><?php echo e($products->firstItem()); ?></span> 
                a <span class="font-semibold"><?php echo e($products->lastItem()); ?></span> 
                de <span class="font-semibold"><?php echo e($products->total()); ?></span> produtos
            </div>
            <?php if(request()->hasAny(['search', 'category_id', 'status', 'featured', 'price_min', 'price_max'])): ?>
                <div class="text-sm text-primary">
                    <i class="bi bi-info-circle mr-1"></i>
                    Resultados filtrados
                </div>
            <?php endif; ?>
        </div>

        <!-- Desktop/Tablet: Tabela com Scroll Horizontal -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <!-- Wrapper com scroll horizontal -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-gray-50 z-10">
                                Produto
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Categoria
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Preço
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estoque
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky right-0 bg-gray-50 z-10">
                                Ações
                            </th>
                        </tr>
                    </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap sticky left-0 bg-white z-10 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center min-w-[250px]">
                                    <?php if($product->first_image): ?>
                                        <img class="h-10 w-10 rounded-lg object-cover mr-4 flex-shrink-0" 
                                             src="<?php echo e($product->first_image); ?>" 
                                             alt="<?php echo e($product->name); ?>"
                                             onerror="this.src='<?php echo e(url('images/general/callback-image.svg')); ?>'">
                                    <?php else: ?>
                                        <img class="h-10 w-10 rounded-lg object-contain mr-4 flex-shrink-0 bg-gray-100 p-1" 
                                             src="<?php echo e(url('images/general/callback-image.svg')); ?>" 
                                             alt="<?php echo e($product->name); ?>">
                                    <?php endif; ?>
                                    <div class="min-w-0 flex-1">
                                        <div class="text-sm font-medium text-gray-900 truncate"><?php echo e($product->name); ?></div>
                                        <div class="text-sm text-gray-500">SKU: <?php echo e($product->sku); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900"><?php echo e($product->category->name ?? 'Sem categoria'); ?></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    R$ <?php echo e(number_format($product->final_price, 2, ',', '.')); ?>

                                    <?php if($product->is_on_sale): ?>
                                        <span class="text-xs text-red-600 line-through">
                                            R$ <?php echo e(number_format($product->price, 2, ',', '.')); ?>

                                        </span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900"><?php echo e($product->stock_quantity); ?></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                        <?php echo e($product->is_active ? 'Ativo' : 'Inativo'); ?>

                                    </span>
                                    <?php if($product->is_featured): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Destaque
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium sticky right-0 bg-white z-10 shadow-[-4px_0_6px_-1px_rgba(0,0,0,0.1)]">
                                <div class="flex items-center space-x-2 justify-end">
                                    <a href="<?php echo e(route('admin.products.show', $product)); ?>" 
                                       class="text-blue-600 hover:text-blue-900 p-2 rounded hover:bg-blue-50 transition-all" 
                                       title="Ver detalhes">
                                        <i class="bi bi-eye text-lg"></i>
                                    </a>
                                    <a href="<?php echo e(route('admin.products.edit', $product)); ?>" 
                                       class="text-indigo-600 hover:text-indigo-900 p-2 rounded hover:bg-indigo-50 transition-all" 
                                       title="Editar">
                                        <i class="bi bi-pencil text-lg"></i>
                                    </a>
                                    <form method="POST" action="<?php echo e(route('admin.products.toggle-status', $product)); ?>" class="inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PATCH'); ?>
                                        <button type="submit" 
                                                class="text-yellow-600 hover:text-yellow-900 p-2 rounded hover:bg-yellow-50 transition-all" 
                                                title="<?php echo e($product->is_active ? 'Desativar' : 'Ativar'); ?>">
                                            <i class="bi <?php echo e($product->is_active ? 'bi-toggle-on' : 'bi-toggle-off'); ?> text-lg"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="<?php echo e(route('admin.products.delete', $product)); ?>" 
                                          class="inline" 
                                          onsubmit="return confirm('Tem certeza que deseja excluir este produto?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900 p-2 rounded hover:bg-red-50 transition-all" 
                                                title="Excluir">
                                            <i class="bi bi-trash text-lg"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            </div>
            
            <!-- Indicador de Scroll (apenas mobile/tablet) -->
            <div class="lg:hidden p-3 bg-gray-50 border-t border-gray-200 text-center">
                <div class="flex items-center justify-center text-sm text-gray-500">
                    <i class="bi bi-arrow-left-right mr-2"></i>
                    <span>Deslize para ver mais colunas</span>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <?php echo e($products->links()); ?>

        </div>
    <?php else: ?>
        <div class="text-center py-12">
            <div class="text-gray-500 text-lg mb-4">Nenhum produto encontrado</div>
            <a href="<?php echo e(route('admin.products.create')); ?>" 
               class="inline-block bg-primary hover:opacity-90 text-white px-4 py-2 rounded-lg transition-all shadow-sm hover:shadow-md">
                Criar primeiro produto
            </a>
        </div>
    <?php endif; ?>
</div>

<style>
/* Scroll horizontal suave */
.overflow-x-auto {
    -webkit-overflow-scrolling: touch;
    scrollbar-width: thin;
    scrollbar-color: #EE0000 #f3f4f6;
}

.overflow-x-auto::-webkit-scrollbar {
    height: 8px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: #f3f4f6;
    border-radius: 4px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #EE0000;
    border-radius: 4px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #cc0000;
}

/* Sombra nas colunas sticky para indicar scroll */
.sticky.left-0 {
    box-shadow: 2px 0 4px -2px rgba(0, 0, 0, 0.1);
}

.sticky.right-0 {
    box-shadow: -2px 0 4px -2px rgba(0, 0, 0, 0.1);
}

/* Hover nas linhas mantém bg nas colunas sticky */
tr:hover .sticky {
    background-color: #f9fafb !important;
}
</style>

<script>
// Função robusta para formatar moeda brasileira (R$ 1.234,56)
function formatCurrencyBRL(value) {
    if (typeof value === 'number') value = value.toString();
    if (!value) return '0,00';

    // Remove tudo que não é dígito
    let numeric = value.replace(/\D/g, '');
    if (!numeric) return '0,00';

    // Remove zeros à esquerda
    numeric = numeric.replace(/^0+/, '') || '0';

    // Garante pelo menos 3 dígitos para centavos
    while (numeric.length < 3) numeric = '0' + numeric;

    // Insere vírgula para centavos
    let cents = numeric.slice(-2);
    let integer = numeric.slice(0, -2);

    // Adiciona pontos de milhar
    integer = integer.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

    return integer + ',' + cents;
}

// Converte valor formatado para float (ex: "1.234,56" => 1234.56)
function parseCurrencyBRL(value) {
    if (!value) return '';
    // Remove tudo exceto dígitos e vírgula
    value = value.replace(/[^\d,]/g, '');
    // Substitui vírgula por ponto para decimal
    value = value.replace(',', '.');
    return value;
}

// Detectar scroll horizontal e adicionar indicadores visuais
document.addEventListener('DOMContentLoaded', function() {
    const scrollContainer = document.querySelector('.overflow-x-auto');
    
    if (scrollContainer) {
        function updateScrollIndicators() {
            const isAtStart = scrollContainer.scrollLeft === 0;
            const isAtEnd = scrollContainer.scrollLeft + scrollContainer.clientWidth >= scrollContainer.scrollWidth - 1;
            
            // Adicionar/remover sombra nas colunas sticky
            const leftSticky = scrollContainer.querySelectorAll('.sticky.left-0');
            const rightSticky = scrollContainer.querySelectorAll('.sticky.right-0');
            
            leftSticky.forEach(el => {
                if (isAtStart) {
                    el.style.boxShadow = 'none';
                } else {
                    el.style.boxShadow = '2px 0 4px -2px rgba(0, 0, 0, 0.2)';
                }
            });
            
            rightSticky.forEach(el => {
                if (isAtEnd) {
                    el.style.boxShadow = 'none';
                } else {
                    el.style.boxShadow = '-4px 0 6px -1px rgba(0, 0, 0, 0.2)';
                }
            });
        }
        
        scrollContainer.addEventListener('scroll', updateScrollIndicators);
        window.addEventListener('resize', updateScrollIndicators);
        updateScrollIndicators();
    }
});

// Aplica máscara de moeda nos campos de preço
document.addEventListener('DOMContentLoaded', function() {
    const priceFields = ['price_min', 'price_max'];

    priceFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        const hiddenField = document.getElementById(fieldId + '_value');
        
        if (field && hiddenField) {
            // Formatar valor inicial se existir
            if (field.value) {
                field.value = formatCurrencyBRL(field.value);
            }

            // Aplicar máscara ao digitar
            field.addEventListener('input', function(e) {
                const formatted = formatCurrencyBRL(e.target.value);
                e.target.value = formatted;
                
                // Atualizar campo hidden com valor numérico
                hiddenField.value = parseCurrencyBRL(formatted);
                
                // Manter cursor no final
                setTimeout(() => {
                    e.target.setSelectionRange(e.target.value.length, e.target.value.length);
                }, 0);
            });

            // Atualizar campo hidden ao perder foco
            field.addEventListener('blur', function(e) {
                const formatted = formatCurrencyBRL(e.target.value);
                e.target.value = formatted;
                hiddenField.value = parseCurrencyBRL(formatted);
            });
        }
    });
});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\resources\views/admin/products/index.blade.php ENDPATH**/ ?>