<?php $__env->startSection('title', 'Dashboard - Laser Link'); ?>
<?php $__env->startSection('page-title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6 relative overflow-hidden">
            <div class="flex items-center">
                <div class="selo rounded-full bg-blue-100 text-blue-600">
                    <i class="bi bi-box text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total de Produtos</p>
                    <p class="text-2xl font-semibold text-gray-900"><?php echo e($stats['total_products']); ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6 relative overflow-hidden">
            <div class="flex items-center">
                <div class="selo rounded-full bg-green-100 text-green-600">
                    <i class="bi bi-folder text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Categorias</p>
                    <p class="text-2xl font-semibold text-gray-900"><?php echo e($stats['total_categories']); ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6 relative overflow-hidden">
            <!-- Indicador de crescimento na borda -->
            <?php
                $growthPercent = abs($stats['orders_growth']);
                $isPositive = $stats['orders_growth'] >= 0;
                $barColor = $isPositive ? 'bg-green-500' : 'bg-red-500';
            ?>
            <div class="absolute left-0 top-0 bottom-0 w-1 <?php echo e($barColor); ?>" style="height: <?php echo e(min($growthPercent, 100)); ?>%;"></div>
            
            <div class="flex items-center">
                <div class="selo rounded-full bg-yellow-100 text-yellow-600">
                    <i class="bi bi-cart-check text-2xl"></i>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-medium text-gray-600">Total de Pedidos</p>
                    <p class="text-2xl font-semibold text-gray-900"><?php echo e($stats['total_orders']); ?></p>
                    <div class="flex items-center mt-1">
                        <i class="bi bi-<?php echo e($isPositive ? 'arrow-up' : 'arrow-down'); ?> text-xs <?php echo e($isPositive ? 'text-green-600' : 'text-red-600'); ?> mr-1"></i>
                        <span class="text-xs font-medium <?php echo e($isPositive ? 'text-green-600' : 'text-red-600'); ?>">
                            <?php echo e(number_format($growthPercent, 1)); ?>% vs mês anterior
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6 relative overflow-hidden">
            <div class="flex items-center">
                <div class="selo rounded-full bg-red-100 text-red-600">
                    <i class="bi bi-clock text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pedidos Pendentes</p>
                    <p class="text-2xl font-semibold text-gray-900"><?php echo e($stats['pending_orders']); ?></p>
                </div>
            </div>
        </div>
        
    </div>
    
    <!-- Revenue Card -->
    <div class="bg-white rounded-lg shadow p-6 relative overflow-hidden">
        <!-- Indicador de crescimento na borda -->
        <?php
            $revenueGrowthPercent = abs($stats['revenue_growth']);
            $isRevenuePositive = $stats['revenue_growth'] >= 0;
            $revenueBarColor = $isRevenuePositive ? 'bg-green-500' : 'bg-red-500';
        ?>
        <div class="absolute left-0 top-0 bottom-0 w-1.5 <?php echo e($revenueBarColor); ?>" style="height: <?php echo e(min($revenueGrowthPercent, 100)); ?>%;"></div>
        
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h3 class="text-lg font-medium text-gray-900">Receita Estimada</h3>
                <p class="text-3xl font-bold text-primary">R$ <?php echo e(number_format($stats['total_revenue'] ?? 0, 2, ',', '.')); ?></p>
                <div class="flex items-center mt-2">
                    <i class="bi bi-<?php echo e($isRevenuePositive ? 'arrow-up' : 'arrow-down'); ?> text-sm <?php echo e($isRevenuePositive ? 'text-green-600' : 'text-red-600'); ?> mr-1"></i>
                    <span class="text-sm font-medium <?php echo e($isRevenuePositive ? 'text-green-600' : 'text-red-600'); ?>">
                        <?php echo e(number_format($revenueGrowthPercent, 1)); ?>% vs mês anterior
                    </span>
                    <span class="text-xs text-gray-500 ml-2">
                        (R$ <?php echo e(number_format($stats['current_month_revenue'] ?? 0, 2, ',', '.')); ?> este mês)
                    </span>
                </div>
            </div>
            <div class="selo rounded-full bg-primary text-white">
                <i class="bi bi-currency-dollar text-2xl"></i>
            </div>
        </div>
    </div>
    
    <!-- Recent Orders -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Pedidos Recentes</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pedido</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            #<?php echo e($order->order_number); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <?php echo e($order->customer_name); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo e($order->status_color); ?>">
                                <?php echo e($order->status_label); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            R$ <?php echo e(number_format($order->total, 2, ',', '.')); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo e($order->created_at->format('d/m/Y H:i')); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="<?php echo e(route('admin.orders.show', $order)); ?>" class="text-primary hover:text-red-700">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Nenhum pedido encontrado
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="<?php echo e(route('admin.products.create')); ?>" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="selo rounded-full bg-blue-100 text-blue-600">
                    <i class="bi bi-plus text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Novo Produto</h3>
                    <p class="text-sm text-gray-500">Adicionar um novo produto ao catálogo</p>
                </div>
            </div>
        </a>
        
        <a href="<?php echo e(route('admin.categories.create')); ?>" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="selo rounded-full bg-green-100 text-green-600">
                    <i class="bi bi-folder-plus text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Nova Categoria</h3>
                    <p class="text-sm text-gray-500">Criar uma nova categoria de produtos</p>
                </div>
            </div>
        </a>
        
        <a href="<?php echo e(route('admin.orders')); ?>" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="selo rounded-full bg-yellow-100 text-yellow-600">
                    <i class="bi bi-cart-check text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Gerenciar Pedidos</h3>
                    <p class="text-sm text-gray-500">Visualizar e gerenciar todos os pedidos</p>
                </div>
            </div>
        </a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>