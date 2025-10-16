

<?php $__env->startSection('title', 'Erro interno do servidor - 500 | ' . config('app.name')); ?>

<?php $__env->startSection('meta_description', 'Ocorreu um erro interno no servidor. Nossa equipe foi notificada e está trabalhando para resolver o problema.'); ?>

<?php $__env->startSection('content'); ?>

<div class="min-h-screen bg-gradient-to-br from-red-50 to-gray-100 flex items-center justify-center px-4 py-12">
    <div class="max-w-2xl w-full text-center">
        <!-- Ilustração do erro -->
        <div class="mb-8">
            <div class="relative inline-block">
                <div class="text-9xl font-black text-red-200 select-none">500</div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <i class="bi bi-exclamation-triangle text-6xl text-red-600 opacity-50 animate-pulse"></i>
                </div>
            </div>
        </div>

        <!-- Mensagem -->
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
            Ops! Algo deu errado
        </h1>
        
        <p class="text-lg text-gray-600 mb-8 max-w-md mx-auto">
            Encontramos um problema técnico. Nossa equipe já foi notificada e está trabalhando para resolver. 
            Por favor, tente novamente em alguns instantes.
        </p>

        <!-- Botões de ação -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
            <button onclick="window.location.reload()" 
                    class="inline-flex items-center justify-center px-8 py-3.5 bg-primary hover:bg-red-700 text-white font-semibold rounded-lg transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <i class="bi bi-arrow-clockwise mr-2"></i>
                Tentar Novamente
            </button>
            
            <a href="<?php echo e(url('/')); ?>" 
               class="inline-flex items-center justify-center px-8 py-3.5 bg-white border-2 border-gray-300 hover:border-primary text-gray-700 hover:text-primary font-semibold rounded-lg transition-all shadow-sm hover:shadow-md">
                <i class="bi bi-house-door mr-2"></i>
                Voltar ao Início
            </a>
        </div>

        <!-- Informações de suporte -->
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="bi bi-headset mr-2"></i>
                Precisa de ajuda imediata?
            </h2>
            <p class="text-gray-600 mb-4">
                Nossa equipe de suporte está pronta para ajudar você.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <?php
                    $whatsapp = \App\Models\Setting::get('whatsapp');
                    $email = \App\Models\Setting::get('email');
                    $phone = \App\Models\Setting::get('phone');
                ?>
                
                <?php if($whatsapp): ?>
                    <a href="https://wa.me/<?php echo e(preg_replace('/[^0-9]/', '', $whatsapp)); ?>" 
                       target="_blank"
                       class="inline-flex items-center px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                        <i class="bi bi-whatsapp mr-2"></i>
                        WhatsApp
                    </a>
                <?php endif; ?>
                
                <?php if($email): ?>
                    <a href="mailto:<?php echo e($email); ?>" 
                       class="inline-flex items-center px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                        <i class="bi bi-envelope mr-2"></i>
                        E-mail
                    </a>
                <?php endif; ?>
                
                <?php if($phone): ?>
                    <a href="tel:<?php echo e(preg_replace('/[^0-9]/', '', $phone)); ?>" 
                       class="inline-flex items-center px-6 py-2.5 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors">
                        <i class="bi bi-telephone mr-2"></i>
                        Telefone
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Código do erro (em desenvolvimento) -->
        <?php if(config('app.debug')): ?>
            <div class="mt-6 text-xs text-gray-400">
                Error ID: <?php echo e(Str::random(8)); ?> | Time: <?php echo e(now()->format('Y-m-d H:i:s')); ?>

            </div>
        <?php endif; ?>
    </div>
</div>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.store', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\resources\views/errors/500.blade.php ENDPATH**/ ?>