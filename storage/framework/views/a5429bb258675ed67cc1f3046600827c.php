

<?php $__env->startSection('title', 'Páginas'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Páginas</h1>
        <a href="<?php echo e(route('admin.pages.create')); ?>" 
           class="bg-primary hover:opacity-90 text-white px-4 py-2 rounded-lg transition-all shadow-sm hover:shadow-md">
            + Nova Página
        </a>
    </div>

    <!-- Mensagens de Sucesso -->
    <?php if(session('success')): ?>
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="bi bi-check-circle text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700"><?php echo e(session('success')); ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Filtros de Pesquisa -->
    <div class="bg-white rounded-lg shadow mb-6" x-data="{ showFilters: <?php echo e(request()->hasAny(['search', 'status']) ? 'true' : 'false'); ?> }">
        <div class="p-4 border-b border-gray-200">
            <button @click="showFilters = !showFilters" 
                    class="flex items-center justify-between w-full text-left">
                <div class="flex items-center space-x-2">
                    <i class="bi bi-funnel text-gray-600"></i>
                    <span class="font-medium text-gray-900">Filtros de Pesquisa</span>
                    <?php if(request()->hasAny(['search', 'status'])): ?>
                        <span class="bg-primary bg-opacity-10 text-primary text-xs font-medium px-2.5 py-0.5 rounded-full">
                            Ativos
                        </span>
                    <?php endif; ?>
                </div>
                <i class="bi transition-transform duration-200" 
                   :class="showFilters ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
            </button>
        </div>

        <form method="GET" action="<?php echo e(route('admin.pages.index')); ?>" x-show="showFilters" x-cloak>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Pesquisa -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="bi bi-search mr-1"></i>
                        Buscar por Título ou Slug
                    </label>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="<?php echo e(request('search')); ?>"
                           placeholder="Digite o título ou slug..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
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
                        <option value="">Todas</option>
                        <option value="active" <?php echo e(request('status') === 'active' ? 'selected' : ''); ?>>Ativas</option>
                        <option value="inactive" <?php echo e(request('status') === 'inactive' ? 'selected' : ''); ?>>Inativas</option>
                    </select>
                </div>
            </div>
            
            <div class="px-6 pb-4 flex justify-end space-x-3">
                <a href="<?php echo e(route('admin.pages.index')); ?>" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="bi bi-arrow-counterclockwise mr-1"></i>
                    Limpar
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-primary text-white rounded-lg hover:opacity-90 transition-colors">
                    <i class="bi bi-search mr-1"></i>
                    Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Tabela de Páginas -->
    <?php if($pages->isEmpty()): ?>
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <i class="bi bi-file-earmark-text text-gray-300 text-6xl mb-4"></i>
            <p class="text-lg text-gray-500">Nenhuma página encontrada.</p>
            <p class="text-sm text-gray-400 mt-2">Crie sua primeira página para começar!</p>
            <a href="<?php echo e(route('admin.pages.create')); ?>" 
               class="mt-4 inline-block bg-primary text-white px-6 py-2 rounded-lg hover:opacity-90 transition-colors">
                Criar Primeira Página
            </a>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Título
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Slug
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Data de Criação
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ações
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__currentLoopData = $pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    <?php echo e($page->title); ?>

                                </div>
                                <?php if($page->meta_description): ?>
                                    <div class="text-sm text-gray-500">
                                        <?php echo e(Str::limit($page->meta_description, 80)); ?>

                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <code class="bg-gray-100 px-2 py-1 rounded text-xs"><?php echo e($page->slug); ?></code>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium rounded-full <?php echo e($page->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'); ?>">
                                    <i class="bi <?php echo e($page->is_active ? 'bi-check-circle' : 'bi-x-circle'); ?> mr-1"></i>
                                    <?php echo e($page->is_active ? 'Ativa' : 'Inativa'); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo e($page->created_at->format('d/m/Y H:i')); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    <?php if($page->is_active): ?>
                                        <a href="<?php echo e(route('page.show', $page->slug)); ?>" 
                                           target="_blank"
                                           class="text-blue-600 hover:text-blue-900"
                                           title="Ver Página">
                                            <i class="bi bi-box-arrow-up-right"></i>
                                        </a>
                                    <?php endif; ?>
                                    <a href="<?php echo e(route('admin.pages.edit', $page)); ?>" 
                                       class="text-primary hover:text-red-700"
                                       title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="<?php echo e(route('admin.pages.destroy', $page)); ?>" 
                                          method="POST" 
                                          onsubmit="return confirm('Tem certeza que deseja deletar esta página?')"
                                          class="inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900"
                                                title="Deletar">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <!-- Paginação -->
        <div class="mt-4">
            <?php echo e($pages->links()); ?>

        </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\resources\views/admin/pages/index.blade.php ENDPATH**/ ?>