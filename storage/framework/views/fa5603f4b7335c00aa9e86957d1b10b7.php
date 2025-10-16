

<?php $__env->startSection('title', 'Nova Página - Laser Link'); ?>
<?php $__env->startSection('page-title', 'Nova Página'); ?>

<?php $__env->startSection('content'); ?>

<!-- Notificações de erro -->
<?php if($errors->any()): ?>
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">Há <?php echo e(count($errors)); ?> erro(s) no formulário:</h3>
                <div class="mt-2 text-sm text-red-700">
                    <ul class="list-disc list-inside space-y-1">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="space-y-6" x-data="pageManager()">
    <form method="POST" action="<?php echo e(route('admin.pages.store')); ?>" id="pageForm">
        <?php echo csrf_field(); ?>
        
        <div class="flex gap-6">
            <!-- Conteúdo Principal -->
            <div class="flex-1">
                <!-- Informações Básicas -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informações da Página</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Título da Página *
                            </label>
                            <input type="text" 
                                   id="title" 
                                   name="title" 
                                   value="<?php echo e(old('title')); ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   required
                                   @input="generateSlug($event.target.value); updateMetaTitle($event.target.value)">
                            <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                                Slug (URL amigável)
                            </label>
                            <div class="flex items-center">
                                <span class="text-sm text-gray-500 mr-2"><?php echo e(url('/')); ?>/</span>
                                <input type="text" 
                                       id="slug" 
                                       name="slug" 
                                       x-model="slug"
                                       value="<?php echo e(old('slug')); ?>"
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Gerado automaticamente se deixado em branco</p>
                            <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                                Conteúdo da Página *
                            </label>
                            <textarea id="content" 
                                      name="content" 
                                      rows="20"
                                      class="quill-editor"><?php echo e(old('content')); ?></textarea>
                            <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <p class="text-xs text-gray-500 mt-2">
                                <i class="bi bi-info-circle mr-1"></i>
                                O HTML será renderizado na página pública.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- SEO (Opcional) -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="bi bi-search mr-2"></i>SEO (Opcional)
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">
                                Meta Title
                            </label>
                            <input type="text" 
                                   id="meta_title" 
                                   name="meta_title" 
                                   x-model="metaTitle"
                                   value="<?php echo e(old('meta_title')); ?>"
                                   maxlength="60"
                                   placeholder="Título otimizado para SEO"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            <p class="text-xs text-gray-500 mt-1">Máximo de 60 caracteres (<span x-text="metaTitle.length"></span>/60)</p>
                        </div>

                        <div>
                            <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">
                                Meta Description
                            </label>
                            <textarea id="meta_description" 
                                      name="meta_description" 
                                      x-model="metaDescription"
                                      rows="2"
                                      maxlength="160"
                                      placeholder="Descrição da página para mecanismos de busca"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"><?php echo e(old('meta_description')); ?></textarea>
                            <p class="text-xs text-gray-500 mt-1">Máximo de 160 caracteres (<span x-text="metaDescription.length"></span>/160)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="w-80 space-y-6">
                <!-- Publicação -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Publicação</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" 
                                       name="is_active" 
                                       value="1"
                                       x-model="isActive"
                                       checked
                                       class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                <span class="ml-2 text-sm font-medium text-gray-700">
                                    Página Ativa
                                </span>
                            </label>
                            <p class="text-xs text-gray-500 mt-1">
                                Páginas ativas ficam visíveis publicamente
                            </p>
                        </div>

                        <div class="pt-4 border-t border-gray-200">
                            <button type="submit" 
                                    class="w-full bg-primary hover:opacity-90 text-white font-medium py-2 px-4 rounded-lg transition-all shadow-sm hover:shadow-md">
                                <i class="bi bi-check-circle mr-2"></i>
                                Criar Página
                            </button>
                        </div>

                        <div>
                            <a href="<?php echo e(route('admin.pages.index')); ?>" 
                               class="block w-full text-center border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors">
                                <i class="bi bi-x-circle mr-2"></i>
                                Cancelar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Informações -->
                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                    <h4 class="text-sm font-semibold text-blue-900 mb-2 flex items-center">
                        <i class="bi bi-info-circle mr-2"></i>
                        Dica
                    </h4>
                    <p class="text-xs text-blue-800">
                        Você pode usar o editor para formatar o texto, adicionar imagens, links e muito mais. O conteúdo HTML será renderizado na página pública.
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    function pageManager() {
        return {
            slug: '<?php echo e(old('slug')); ?>',
            metaTitle: '<?php echo e(old('meta_title')); ?>',
            metaDescription: '<?php echo e(old('meta_description')); ?>',
            isActive: <?php echo e(old('is_active', 'true')); ?>,
            
            generateSlug(title) {
                if (!this.slug || this.slug === '') {
                    this.slug = title
                        .toLowerCase()
                        .normalize('NFD')
                        .replace(/[\u0300-\u036f]/g, '')
                        .replace(/[^\w\s-]/g, '')
                        .replace(/\s+/g, '-')
                        .replace(/--+/g, '-')
                        .trim();
                }
            },
            
            updateMetaTitle(title) {
                if (!this.metaTitle || this.metaTitle === '') {
                    this.metaTitle = title;
                }
            }
        }
    }
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\resources\views/admin/pages/create.blade.php ENDPATH**/ ?>