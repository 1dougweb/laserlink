<?php $__env->startSection('title', 'Editar Produto - Laser Link'); ?>
<?php $__env->startSection('page-title', 'Editar Produto'); ?>

<?php $__env->startPush('head'); ?>
<!-- Desabilitar cache para página de edição de produtos -->
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6" x-data="productManager()">
    <form method="POST" action="<?php echo e(route('admin.products.update', $product)); ?>" enctype="multipart/form-data" id="productForm">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        
        <div class="flex gap-6">
            <!-- Conteúdo Principal -->
            <div class="flex-1">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nome do Produto *
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="<?php echo e(old('name', $product->name)); ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           required>
                    <?php $__errorArgs = ['name'];
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
                    <label for="sku" class="block text-sm font-medium text-gray-700 mb-2">
                        SKU
                    </label>
                    <input type="text" 
                           id="sku" 
                           name="sku" 
                           value="<?php echo e(old('sku', $product->sku)); ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary <?php $__errorArgs = ['sku'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['sku'];
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
                
                <div class="md:col-span-2">
                    <label for="short_description" class="block text-sm font-medium text-gray-700 mb-2">
                        Descrição Curta
                    </label>
                    <textarea id="short_description" 
                              name="short_description" 
                              rows="2"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary <?php $__errorArgs = ['short_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('short_description', $product->short_description)); ?></textarea>
                    <?php $__errorArgs = ['short_description'];
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
                
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Descrição Completa
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="10"
                              class="quill-editor"><?php echo e(old('description', $product->description)); ?></textarea>
                    <?php $__errorArgs = ['description'];
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
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                        Preço *
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">R$</span>
                        <input type="text" 
                               id="price" 
                               name="price" 
                               value="<?php echo e(old('price') ? old('price') : ($product->price ? number_format((float)$product->price, 2, ',', '.') : '')); ?>"
                               placeholder="0,00"
                               class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               required>
                    </div>
                    <?php $__errorArgs = ['price'];
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
                    <label for="sale_price" class="block text-sm font-medium text-gray-700 mb-2">
                        Preço de Promoção
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">R$</span>
                        <input type="text" 
                               id="sale_price" 
                               name="sale_price" 
                               value="<?php echo e(old('sale_price') ? old('sale_price') : ($product->sale_price ? number_format((float)$product->sale_price, 2, ',', '.') : '')); ?>"
                               placeholder="0,00"
                               class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary <?php $__errorArgs = ['sale_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    </div>
                    <?php $__errorArgs = ['sale_price'];
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
                    <label for="stock_quantity" class="block text-sm font-medium text-gray-700 mb-2">
                        Estoque
                    </label>
                    <input type="number" 
                           id="stock_quantity" 
                           name="stock_quantity" 
                           min="0"
                           value="<?php echo e(old('stock_quantity', $product->stock_quantity)); ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary <?php $__errorArgs = ['stock_quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['stock_quantity'];
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
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                        Ordem de Exibição
                    </label>
                    <input type="number" 
                           id="sort_order" 
                           name="sort_order" 
                           value="<?php echo e(old('sort_order', $product->sort_order)); ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary <?php $__errorArgs = ['sort_order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['sort_order'];
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
                
                <!-- Campos SEO -->
                <div class="md:col-span-2">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center mb-4 space-between">
                            <i class="bi bi-search text-blue-600 text-lg mr-2"></i>
                            <h3 class="text-lg font-semibold text-gray-900">SEO & Otimização</h3>
                            <button type="button" 
                                    @click="toggleSeoPreview()"
                                    class="ml-auto px-3 py-1 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition-colors">
                                <i class="bi bi-eye mr-1"></i>
                                <span x-text="showSeoPreview ? 'Ocultar Preview' : 'Ver Preview'"></span>
                            </button>
                        </div>
                        
                        <div class="space-y-4">
                            <!-- Meta Title -->
                            <div>
                                <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">
                                    Meta Title <span class="text-red-500">*</span>
                                    <span class="text-xs text-gray-500">(Máx: 60 caracteres)</span>
                    </label>
                                <input type="text" 
                                       id="meta_title" 
                                       name="meta_title" 
                                       x-model="seoData.meta_title"
                                       @input="updateSeoPreview()"
                                       maxlength="60"
                                       value="<?php echo e(old('meta_title', $product->meta_title)); ?>"
                                       placeholder="Título otimizado para SEO"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary <?php $__errorArgs = ['meta_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <div class="flex justify-between text-xs mt-1">
                                    <span class="text-gray-500">Usado no Google</span>
                                    <span :class="seoData.meta_title.length > 60 ? 'text-red-500' : 'text-gray-500'" 
                                          x-text="seoData.meta_title.length + '/60'"></span>
                                </div>
                                <?php $__errorArgs = ['meta_title'];
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

                            <!-- Meta Description -->
                            <div>
                                <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">
                                    Meta Description <span class="text-red-500">*</span>
                                    <span class="text-xs text-gray-500">(Máx: 160 caracteres)</span>
                                </label>
                                <textarea id="meta_description" 
                                          name="meta_description" 
                                          x-model="seoData.meta_description"
                                          @input="updateSeoPreview()"
                                          maxlength="160"
                                          rows="3"
                                          placeholder="Descrição que aparece no Google"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary <?php $__errorArgs = ['meta_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('meta_description', $product->meta_description)); ?></textarea>
                                <div class="flex justify-between text-xs mt-1">
                                    <span class="text-gray-500">Usado no Google</span>
                                    <span :class="seoData.meta_description.length > 160 ? 'text-red-500' : 'text-gray-500'" 
                                          x-text="seoData.meta_description.length + '/160'"></span>
                                    </div>
                                <?php $__errorArgs = ['meta_description'];
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

                            <!-- Meta Keywords -->
                            <div x-data="keywordsTags()">
                                <label for="meta_keywords_input" class="block text-sm font-medium text-gray-700 mb-2">
                                    Meta Keywords
                                    <span class="text-xs text-gray-500">(Para LLMs e busca interna)</span>
                                </label>
                                
                                <!-- Tags Display -->
                                <div class="mb-2 min-h-[42px] p-2 rounded-md bg-gray-50 flex flex-wrap gap-2 items-center border border-gray-200">
                                    <template x-for="(tag, index) in tags" :key="index">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-700 border border-gray-300 shadow-sm hover:bg-gray-200 transition-colors">
                                            <span x-text="tag"></span>
                                            <button type="button" 
                                                    @click="removeTag(index)"
                                                    class="ml-2 hover:bg-gray-400 w-6 h-6 rounded-full pt-0.4 px-0.4 focus:outline-none">
                                                <i class="bi bi-x text-lg leading-none"></i>
                                            </button>
                                        </span>
                                    </template>
                                    <input type="text" 
                                           x-model="newTag"
                                           @keydown.enter.prevent="addTag()"
                                           @keydown.comma.prevent="addTag()"
                                           @input="updateHiddenInput()"
                                           placeholder="Digite e pressione Enter ou vírgula"
                                           style="border: none !important; outline: none !important; box-shadow: none !important;"
                                           class="flex-1 min-w-[200px] bg-transparent text-sm">
                                </div>
                                
                                <!-- Hidden input for form submission -->
                                <input type="hidden" 
                                       id="meta_keywords" 
                                       name="meta_keywords" 
                                       x-model="hiddenValue"
                                       value="<?php echo e(old('meta_keywords', $product->meta_keywords)); ?>">
                                
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="bi bi-info-circle mr-1"></i>
                                    Digite uma palavra-chave e pressione Enter ou vírgula para adicionar. Clique no × para remover.
                                </p>
                                <?php $__errorArgs = ['meta_keywords'];
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
                        </div>

                        <!-- Preview do Google -->
                        <div x-show="showSeoPreview" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             class="mt-6 p-4 bg-white border border-gray-200 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-700 mb-3 flex items-center">
                                <i class="bi bi-google text-blue-600 mr-2"></i>
                                Como aparecerá no Google
                            </h4>
                            
                            <div class="space-y-2">
                                <!-- URL -->
                                <div class="text-green-700 text-sm">
                                    <?php echo e(url('/')); ?>/produto/<span x-text="seoData.slug || '<?php echo e($product->slug ?? 'nome-do-produto'); ?>'"></span>
                                </div>
                                
                                <!-- Title -->
                                <div class="text-blue-600 text-lg font-medium leading-tight">
                                    <span x-text="seoData.meta_title || '<?php echo e($product->meta_title ?: $product->name ?: 'Título do produto aparecerá aqui'); ?>'"></span>
                                </div>
                                
                                <!-- Description -->
                                <div class="text-gray-600 text-sm leading-relaxed">
                                    <span x-text="seoData.meta_description || '<?php echo e($product->meta_description ?: $product->short_description ?: 'Descrição do produto aparecerá aqui'); ?>'"></span>
                                </div>
                                
                                <!-- Keywords para LLMs -->
                                <div class="mt-2 p-2 bg-gray-50 rounded">
                                    <div class="text-xs text-gray-600 mb-2"><strong>Para LLMs e busca interna:</strong></div>
                                    <div class="flex flex-wrap gap-1.5">
                                        <template x-if="seoData.meta_keywords">
                                            <template x-for="keyword in (seoData.meta_keywords || '<?php echo e($product->meta_keywords); ?>').split(',').map(k => k.trim()).filter(k => k)" :key="keyword">
                                                <span class="inline-block px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full text-xs font-medium" x-text="keyword"></span>
                                            </template>
                                        </template>
                                        <template x-if="!seoData.meta_keywords && !'<?php echo e($product->meta_keywords); ?>'">
                                            <span class="text-xs text-gray-400 italic">Nenhuma palavra-chave definida</span>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="md:col-span-2">
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" 
                                   id="is_active" 
                                   name="is_active" 
                                   value="1"
                                   <?php echo e(old('is_active', $product->is_active) ? 'checked' : ''); ?>

                                   class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                Produto ativo
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="hidden" name="is_featured" value="0">
                            <input type="checkbox" 
                                   id="is_featured" 
                                   name="is_featured" 
                                   value="1"
                                   <?php echo e(old('is_featured', $product->is_featured) ? 'checked' : ''); ?>

                                   class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                            <label for="is_featured" class="ml-2 block text-sm text-gray-900">
                                Produto em destaque
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Campos Customizados Dinâmicos -->
            <div id="custom-fields-container" class="mt-8 pt-6 border-t border-gray-200" style="display: none;">
                <h4 class="text-lg font-medium text-gray-900 mb-4">Especificações do Produto</h4>
                <div id="custom-fields" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Campos serão inseridos aqui dinamicamente -->
            </div>
            </div>
        </div>
        
                <!-- Botões --><!-- Botões -->
        <div class="flex justify-between items-center mt-4">
            <div class="flex space-x-3">
                <button type="button" 
                        onclick="openExtraFieldsModal()"
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors flex items-center">
                    <i class="bi bi-gear mr-2"></i>
                    Gerenciar Campos Extras
                </button>
            </div>
            
            <div class="flex space-x-3">
                <a href="<?php echo e(route('admin.products')); ?>" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors">
                    Atualizar Produto
                </button>
            </div>
        </div>
    </form>
            </div>

            
            
            <!-- Sidebar Lateral -->
            <div class="w-80 space-y-6">
                    <!-- Imagem Destacada -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Imagem Destacada</h3>
                    <div class="space-y-4">
                        <!-- Preview da imagem destacada -->
                        <div x-show="featuredImage" class="relative">
                            <img :src="featuredImage" alt="Imagem destacada" class="w-full h-48 object-cover rounded-lg border border-gray-200">
                            <button type="button" 
                                    @click.prevent.stop="removeFeaturedImage()" 
                                    class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition-colors">
                                <i class="bi bi-x text-sm"></i>
                            </button>
                        </div>
                        
                        <!-- Upload de imagem destacada -->
                        <div x-show="!featuredImage" class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                            <i class="bi bi-image text-4xl text-gray-400 mb-2"></i>
                            <p class="text-sm text-gray-500 mb-3">Nenhuma imagem selecionada</p>
                            <button type="button" 
                                    onclick="openFileManagerproductFeaturedImageManager()"
                                    class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors">
                                <i class="bi bi-folder mr-2"></i>
                                Selecionar Imagem
                            </button>
                        </div>
                        
                        <!-- Input hidden para imagem destacada -->
                        <input type="hidden" name="featured_image" x-model="featuredImagePath">
                        
                        <!-- Inputs hidden para valores numéricos dos preços -->
                        <input type="hidden" name="price_numeric" id="price_numeric" value="">
                        <input type="hidden" name="sale_price_numeric" id="sale_price_numeric" value="">
                    </div>
                </div>
                
                <!-- Galeria de Imagens -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Galeria de Imagens</h3>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <!-- Grid de imagens da galeria -->
                        <template x-if="galleryImages.length > 0">
                            <div class="grid grid-cols-3 gap-4"
                                 @dragover.prevent
                                 @drop.prevent="handleDrop($event)">
                                <template x-for="(image, index) in galleryImages" :key="`${image.path}-${index}`">
                                    <div class="relative group cursor-move"
                                         :draggable="true"
                                         @dragstart="handleDragStart($event, index)"
                                         @dragend="handleDragEnd($event)"
                                         :class="draggedIndex === index ? 'opacity-50 scale-95' : ''">
                                        
                                        
                                        <img :src="image.preview || image.url" 
                                             :alt="image.name" 
                                             class="w-full aspect-square object-cover rounded-lg border border-gray-200">
                                        
                                        <!-- Overlay com ações -->
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 rounded-lg transition-all duration-200 flex items-center justify-center">
                                            <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex space-x-2">
                                    <button type="button" 
                                            @click.prevent.stop="removeGalleryImage(index)"
                                                        class="w-8 h-8 bg-red-500 text-white rounded-full hover:bg-red-600 transition-colors flex items-center justify-center">
                                                    <i class="bi bi-trash text-xs"></i>
                                    </button>
                                            </div>
                                        </div>
                                        
                                        
                                        <!-- Indicador de posição -->
                                        <div class="absolute bottom-1 right-1 bg-black bg-opacity-50 text-white text-xs px-1 py-0.5 rounded">
                                            <span x-text="index + 1"></span>
                                        </div>
                                </div>
                            </template>
                        </div>
                        </template>
                        
                        <!-- Estado vazio -->
                        <template x-if="galleryImages.length === 0">
                            <div class="text-center py-8">
                                <i class="bi bi-images text-4xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500 text-sm">Nenhuma imagem na galeria</p>
                            </div>
                        </template>
                        
                        <!-- Botão para adicionar imagens -->
                        <button type="button" 
                                onclick="openFileManagerproductGalleryManager()"
                                class="w-full border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors">
                            <i class="bi bi-plus-circle text-3xl text-gray-400 mb-2"></i>
                            <p class="text-sm text-gray-500 font-medium">Adicionar Imagens à Galeria</p>
                            <p class="text-xs text-gray-400 mt-1">Clique para selecionar do gerenciador</p>
                        </button>
                        
                        <!-- Inputs hidden para galeria -->
                        <template x-for="(image, index) in galleryImages" :key="index">
                            <input type="hidden" :name="`gallery_images[${index}]`" :value="image.path">
                        </template>
                    </div>
                </div>
                
                <!-- Categoria -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Categorias</h3>
                    <div class="space-y-3">
                        <!-- Lista de categorias com checkboxes -->
                        <div class="max-h-48 overflow-y-auto space-y-2" id="categories-list">
                            <?php
                                // Garante que $product->categories existe e é uma coleção antes de usar pluck
                                $productCategories = isset($product) && isset($product->categories) && is_iterable($product->categories)
                                    ? collect($product->categories)->pluck('id')->toArray()
                                    : [];
                                    
                                // Se $product->category_id existe (produto antigo), adicionar ao array
                                if (isset($product->category_id) && !in_array($product->category_id, $productCategories)) {
                                    $productCategories[] = $product->category_id;
                                }
                                
                                $oldCategories = old('category_id', $productCategories);
                                if (!is_array($oldCategories)) {
                                    $oldCategories = [$oldCategories];
                                }
                            ?>
                            <?php if(isset($categories) && count($categories)): ?>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label class="flex items-center space-x-3 p-2 hover:bg-gray-50 rounded-lg cursor-pointer">
                                        <input type="checkbox" 
                                               name="category_id[]" 
                                               value="<?php echo e($category->id); ?>"
                                               <?php echo e(in_array($category->id, $oldCategories) ? 'checked' : ''); ?>

                                               class="h-4 w-4 text-primary focus:ring-primary border-gray-300">
                                        <span class="text-sm text-gray-900"><?php echo e($category->name); ?></span>
                                    </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <p class="text-gray-500 text-sm">Nenhuma categoria disponível</p>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Botão para criar nova categoria -->
                        <button type="button" 
                                @click="showNewCategoryModal = true"
                                class="w-full border-2 border-dashed border-gray-300 rounded-lg p-3 text-center hover:border-gray-400 transition-colors">
                            <i class="bi bi-plus-circle text-lg text-gray-400 mb-1"></i>
                            <p class="text-sm text-gray-500">Nova Categoria</p>
                        </button>
                    </div>
                </div>
                
                <!-- Botão Gemini AI -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">IA Assistente</h3>
                    <button type="button" 
                            @click="generateDescriptionWithAI()"
                            :disabled="aiLoading"
                            class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg p-3 hover:from-blue-600 hover:to-purple-700 disabled:opacity-50 transition-all">
                        <i class="bi bi-robot mr-2" x-show="!aiLoading"></i>
                        <i class="bi bi-arrow-clockwise animate-spin mr-2" x-show="aiLoading"></i>
                        <span x-text="aiLoading ? 'Gerando...' : 'Gerar Descrição com IA'"></span>
                    </button>
                    <p class="text-xs text-gray-500 mt-2">Use a IA para gerar uma descrição baseada no título do produto</p>
                </div>
                
                
            </div>
        </div>
        
        
    
    <!-- File Manager Modal -->
    <div x-show="showFileManager" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50"
         @click="showFileManager = false">
        <div class="bg-white rounded-2xl shadow-2xl w-11/12 h-5/6 mx-4 flex flex-col max-w-6xl"
             @click.stop>
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-t-2xl">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                        <i class="bi bi-folder text-white text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900" x-text="fileManagerType === 'featured' ? 'Selecionar Imagem Destacada' : 'Selecionar Imagens da Galeria'"></h3>
                        <p class="text-sm text-gray-600" x-text="fileManagerType === 'featured' ? 'Escolha uma imagem para destacar o produto' : 'Escolha múltiplas imagens para a galeria'"></p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <template x-if="fileManagerType === 'gallery'">
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-600" x-text="`${selectedFiles.length} selecionadas`"></span>
                            <button @click="selectAllFiles()" 
                                    class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition-colors">
                                Selecionar Todas
                            </button>
                            <button @click="clearSelection()" 
                                    class="px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors">
                                Limpar
                            </button>
                        </div>
                    </template>
                    <button @click="showFileManager = false" 
                            class="w-10 h-10 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center transition-colors">
                        <i class="bi bi-x text-gray-600 text-lg"></i>
                    </button>
                </div>
            </div>
            
            <!-- File Manager Content -->
            <div class="flex-1 p-6 overflow-hidden">
                <!-- Loading -->
                <div x-show="fileManagerLoading" class="flex items-center justify-center h-full">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4 mx-auto">
                            <i class="bi bi-arrow-clockwise animate-spin text-2xl text-blue-600"></i>
                        </div>
                        <p class="text-gray-600 font-medium">Carregando imagens...</p>
                    </div>
                </div>
                
                <!-- File Grid -->
                <div x-show="!fileManagerLoading" class="h-full overflow-y-auto">
                    <template x-if="fileManagerItems.length === 0">
                        <div class="text-center py-12">
                            <i class="bi bi-folder-x text-6xl text-gray-400 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum arquivo encontrado</h3>
                            <p class="text-gray-500">Faça upload de algumas imagens para começar.</p>
                        </div>
                    </template>
                    
                    <template x-if="fileManagerItems.length > 0">
                        <div>
                            <template x-for="(folder, folderName) in groupedFiles" :key="folderName">
                                <div class="mb-6">
                                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                        <i class="bi bi-folder text-yellow-500 mr-2"></i>
                                        <span x-text="folderName"></span>
                                        <span class="ml-2 text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded-full" x-text="folder.length"></span>
                                    </h4>
                                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                                        <template x-for="file in folder" :key="file.name">
                                            <div class="relative group cursor-pointer rounded-xl p-4 transition-all duration-300 h-32 bg-gray-50 border-2 border-gray-200 hover:bg-gray-100 hover:border-gray-300 hover:shadow-lg"
                                                 :class="fileManagerType === 'gallery' && selectedFiles.some(f => f.path === file.path) ? 'border-blue-500 bg-blue-50' : ''"
                                                 @click="fileManagerType === 'gallery' ? toggleFileSelection(file) : selectFile(file)">
                                                <!-- Checkbox para galeria -->
                                                <template x-if="fileManagerType === 'gallery'">
                                                    <div class="absolute top-2 right-2">
                                                        <input type="checkbox" 
                                                               :checked="selectedFiles.some(f => f.path === file.path)"
                                                               class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500">
                                                    </div>
                                                </template>
                                                
                                                <div class="text-center flex flex-col items-center justify-center h-full">
                                                    <i class="text-4xl mb-3" :class="getFileIcon(file.extension)"></i>
                                                    <p class="text-sm font-bold text-gray-900 truncate px-2" :title="file.name" x-text="file.name.length > 15 ? file.name.substring(0, 15) + '...' : file.name"></p>
                                                    <p class="text-xs text-gray-700 font-semibold truncate px-2" x-text="file.extension ? file.extension.toUpperCase() : ''"></p>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="flex justify-between items-center p-6 border-t border-gray-200 bg-gray-50 rounded-b-2xl">
                <template x-if="fileManagerType === 'gallery'">
                    <div class="text-sm text-gray-600">
                        <span x-text="`${selectedFiles.length} imagens selecionadas`"></span>
                    </div>
                </template>
                <template x-if="fileManagerType === 'featured'">
                    <div></div>
                </template>
                
                <div class="flex space-x-3">
                    <button @click="showFileManager = false"
                            class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors font-medium">
                        Cancelar
                    </button>
                    <template x-if="fileManagerType === 'gallery'">
                        <button @click="confirmGallerySelection()"
                                :disabled="selectedFiles.length === 0"
                                :class="selectedFiles.length === 0 ? 'opacity-50 cursor-not-allowed' : ''"
                                class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                            Adicionar <span x-text="selectedFiles.length"></span> Imagens
                        </button>
                    </template>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Nova Categoria -->
    <div x-show="showNewCategoryModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50"
         @click="showNewCategoryModal = false">
        <div @click.stop
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="bg-white rounded-xl shadow-2xl p-6 w-96 mx-4">
            <h3 class="text-lg font-semibold mb-4 text-gray-900">Nova Categoria</h3>
            <form @submit.prevent="addNewCategoryToList()">
                <input type="text" 
                       x-model="newCategoryName"
                       placeholder="Nome da categoria"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
                       required>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" 
                            @click="showNewCategoryModal = false; newCategoryName = ''"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" 
                            :disabled="creatingCategory"
                            class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-red-700 disabled:opacity-50 transition-colors">
                        <i class="bi bi-arrow-clockwise animate-spin mr-2" x-show="creatingCategory"></i>
                        <span x-text="creatingCategory ? 'Criando...' : 'Criar'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Campos Extras -->
<div id="extraFieldsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Header do Modal -->
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">
                    Gerenciar Campos Extras - <?php echo e($product->name); ?>

                </h3>
                <button onclick="closeExtraFieldsModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="bi bi-x-lg text-xl"></i>
                </button>
            </div>

            <!-- Conteúdo do Modal -->
            <div id="modalContent">
                <div class="text-center gap-4">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary mx-auto"></div>
                    <p class="mt-2 text-gray-600">Carregando campos extras...</p>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Modal do File Manager para Campos Extras -->
<div id="optionFileManagerModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-4 mx-auto p-0 border w-11/12 max-w-6xl shadow-lg rounded-2xl bg-white">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b border-gray-200 bg-white rounded-t-2xl">
            <div>
                <h3 class="text-xl font-semibold text-gray-900">Selecionar Imagem</h3>
                <p class="text-sm text-gray-600">Escolha uma imagem para a opção</p>
            </div>
            <button onclick="closeOptionFileManager()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="bi bi-x text-gray-600 text-lg"></i>
            </button>
        </div>
        
        <!-- File Manager Content -->
        <div class="flex-1 p-6 overflow-hidden">
            <!-- Breadcrumb Navigation -->
            <div class="mb-4">
                <div id="breadcrumbContainer" class="flex items-center space-x-2 text-sm text-gray-600 bg-gray-50 rounded-lg p-3">
                    <button onclick="navigateToFolder('')" 
                            class="hover:text-blue-600 flex items-center gap-1 transition-colors">
                        <i class="bi bi-house"></i>
                        <span>Raiz</span>
                    </button>
                </div>
            </div>
            
            <!-- Toolbar -->
            <div class="mb-4 flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
                <!-- Busca -->
                <div class="flex-1 max-w-md">
                    <div class="relative">
                        <input type="text" 
                               id="imageSearchInput"
                               placeholder="Buscar imagens..."
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>
                
                <!-- Ações -->
                <div class="flex gap-2">
                    <!-- Botão Upload -->
                    <button onclick="openUploadModal()" 
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors flex items-center gap-2">
                        <i class="bi bi-upload"></i>
                        <span class="hidden sm:inline">Upload</span>
                    </button>
                    
                    <!-- Botão Atualizar -->
                    <button onclick="loadCurrentDirectory()" 
                            class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                </div>
            </div>
            
            <!-- Loading -->
            <div id="optionFileManagerLoading" class="flex items-center justify-center h-full">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4 mx-auto">
                        <i class="bi bi-image text-2xl text-blue-600"></i>
                    </div>
                    <p class="text-gray-600 font-medium">Carregando imagens...</p>
                </div>
            </div>
            
            <!-- File Grid -->
            <div id="optionFileManagerGrid" class="hidden h-full overflow-y-auto">
                <!-- Pastas -->
                <div id="foldersSection" class="mb-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="bi bi-folder text-blue-500 mr-2"></i>
                        Pastas
                    </h4>
                    <div id="foldersGrid" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        <!-- Será preenchido via JavaScript -->
                    </div>
                </div>
                
                <!-- Arquivos -->
                <div id="filesSection" class="mb-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="bi bi-file-image text-green-500 mr-2"></i>
                        Imagens
                    </h4>
                    <div id="filesGrid" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        <!-- Será preenchido via JavaScript -->
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="flex justify-between items-center p-6 border-t border-gray-200 bg-gray-50 rounded-b-2xl">
            <div class="text-sm text-gray-600">
                <i class="bi bi-info-circle mr-1"></i>
                Clique em uma imagem para selecionar
            </div>
            <button onclick="closeOptionFileManager()" 
                    class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                Cancelar
            </button>
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div id="uploadModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-60">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">📤 Upload de Imagem</h3>
                <button onclick="closeUploadModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="bi bi-x-lg text-xl"></i>
                </button>
            </div>
            
            <form id="uploadForm" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pasta de Destino</label>
                    <select name="directory" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Raiz</option>
                        <option value="products">products</option>
                        <option value="banners">banners</option>
                        <option value="categories">categories</option>
                        <option value="logos">logos</option>
                        <option value="general">general</option>
                    </select>
                </div>
                
                <div id="uploadContent">
                    <div id="uploadArea" 
                         class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors cursor-pointer"
                         onclick="document.getElementById('imageFileInput').click()">
                        <i class="bi bi-cloud-upload text-4xl text-gray-400 mb-4"></i>
                        <p class="text-gray-600 mb-2">Clique ou arraste uma imagem aqui</p>
                        <p class="text-sm text-gray-500">PNG, JPG, GIF até 5MB</p>
                        <input type="file" 
                               id="imageFileInput" 
                               name="file" 
                               accept="image/*" 
                               class="hidden"
                               onchange="handleFileSelect(event)">
                    </div>
                </div>
                
                <div id="uploadProgress" class="hidden">
                    <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                        <div id="progressBar" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>
                    <p class="text-sm text-gray-600">Enviando arquivo...</p>
                </div>
                
                <div id="uploadSuccess" class="hidden text-center">
                    <i class="bi bi-check-circle text-green-500 text-4xl mb-2"></i>
                    <p class="text-green-600 font-medium">Upload realizado com sucesso!</p>
                </div>
                
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" 
                            onclick="closeUploadModal()"
                            class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" 
                            id="uploadButton"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                        Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Configurações do Campo -->

<style>
/* Estilos mínimos para drag-and-drop - igual à view extra-fields */
.drag-handle {
    cursor: move;
}
</style>


<script>
// Configurações globais
window.categoriesData = <?php echo json_encode($categories); ?>;
window.existingCustomAttributes = <?php echo json_encode($product->custom_attributes ?? []); ?>;
window.fileManagerRoute = '<?php echo e(route("admin.admin.file-manager.index")); ?>';
window.categoryStoreRoute = '<?php echo e(route("admin.categories.store")); ?>';
window.generateDescriptionRoute = '<?php echo e(route("admin.products.generate-description")); ?>';
window.productFeaturedImage = <?php echo json_encode($product->featured_image ? url('images/' . $product->featured_image) : null); ?>;
window.productFeaturedImagePath = <?php echo json_encode($product->featured_image ?? ''); ?>;
window.productGalleryImages = <?php echo json_encode(
    collect($product->gallery_images ?? [])->map(function($image) {
        return [
            'path' => $image,
            'url' => url('images/' . $image),
            'preview' => url('images/' . $image),
            'name' => basename($image)
        ];
    })->values()->toArray()
); ?>;
window.files = <?php echo json_encode($files ?? [], 15, 512) ?>;

// Keywords Tags Management
function keywordsTags() {
    return {
        tags: [],
        newTag: '',
        hiddenValue: '',
        
        init() {
            // Initialize from hidden input value
            const initialValue = document.getElementById('meta_keywords').value;
            if (initialValue) {
                this.tags = initialValue.split(',').map(tag => tag.trim()).filter(tag => tag);
                this.updateHiddenInput();
            }
            
            // Listen for AI-generated keywords
            const self = this;
            const keywordsInput = document.getElementById('meta_keywords');
            if (keywordsInput) {
                keywordsInput.addEventListener('keywords-updated', (event) => {
                    const keywords = event.detail.keywords;
                    if (keywords) {
                        self.tags = keywords.split(',').map(tag => tag.trim()).filter(tag => tag);
                        self.hiddenValue = keywords;
                    }
                });
                
                // Watch for value changes in the hidden input
                const observer = new MutationObserver((mutations) => {
                    mutations.forEach((mutation) => {
                        if (mutation.type === 'attributes' && mutation.attributeName === 'value') {
                            const newValue = keywordsInput.value;
                            if (newValue && newValue !== self.hiddenValue) {
                                self.tags = newValue.split(',').map(tag => tag.trim()).filter(tag => tag);
                                self.hiddenValue = newValue;
                            }
                        }
                    });
                });
                
                observer.observe(keywordsInput, { attributes: true });
                
                // Also watch for direct value changes
                setInterval(() => {
                    const currentValue = keywordsInput.value;
                    if (currentValue && currentValue !== self.hiddenValue && !self.newTag) {
                        self.tags = currentValue.split(',').map(tag => tag.trim()).filter(tag => tag);
                        self.hiddenValue = currentValue;
                    }
                }, 500);
            }
            
            // Watch for external updates (from AI generation)
            this.$watch('hiddenValue', (value) => {
                if (value && !this.newTag) {
                    this.tags = value.split(',').map(tag => tag.trim()).filter(tag => tag);
                }
            });
        },
        
        addTag() {
            const tag = this.newTag.trim();
            if (tag && !this.tags.includes(tag)) {
                this.tags.push(tag);
                this.updateHiddenInput();
                this.updateSeoData();
            }
            this.newTag = '';
        },
        
        removeTag(index) {
            this.tags.splice(index, 1);
            this.updateHiddenInput();
            this.updateSeoData();
        },
        
        updateHiddenInput() {
            this.hiddenValue = this.tags.join(', ');
        },
        
        updateSeoData() {
            // Update the parent component's seoData if it exists
            if (window.productManager && window.productManager.seoData) {
                window.productManager.seoData.meta_keywords = this.hiddenValue;
            }
        }
    }
}
</script>
<script src="<?php echo e(asset('js/product-manager.js')); ?>"></script>

<script>
// Função para inicializar drag-and-drop das opções
function initOptionsSortable() {
    const optionsContainer = document.querySelector('[x-ref="optionsContainer"]');
    if (optionsContainer && typeof Sortable !== 'undefined') {
        new Sortable(optionsContainer, {
            animation: 150,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            handle: '.cursor-move',
            onEnd: function(evt) {
                // Atualizar a ordem das opções no Alpine.js
                const alpineComponent = Alpine.$data(document.querySelector('[x-data]'));
                if (alpineComponent && alpineComponent.selectedField) {
                    const options = alpineComponent.selectedField.options;
                    const item = options[evt.oldIndex];
                    options.splice(evt.oldIndex, 1);
                    options.splice(evt.newIndex, 0, item);
                }
            }
        });
    }
}

// Função Alpine.js para gerenciar campos extras
function extraFieldsManager() {
    return {
        showFieldSettings: false,
        selectedField: null,
        reorderMode: false,
        currentView: 'list',
        
        async editField(fieldId, fieldName, fieldType, isRequired, sortOrder) {
            this.selectedField = {
                id: fieldId,
                name: fieldName,
                type: fieldType,
                isRequired: isRequired,
                sortOrder: sortOrder,
                options: []
            };
            
            // Carregar opções do campo
            await this.loadFieldOptions(fieldId);
            this.currentView = 'settings';
            
            // Aguardar o DOM ser atualizado e inicializar o sortable
            this.$nextTick(() => {
                setTimeout(() => {
                    if (typeof initOptionsSortable === 'function') {
                        initOptionsSortable();
                    }
                }, 100);
            });
        },
        
        async loadFieldOptions(fieldId) {
            try {
                const response = await fetch(`/admin/extra-fields/${fieldId}/options`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    this.selectedField.options = data.options || [];
                } else {
                    console.error('Erro ao carregar opções:', response.status);
                    this.selectedField.options = [];
                }
            } catch (error) {
                console.error('Erro ao carregar opções:', error);
                this.selectedField.options = [];
            }
        },
        
        addOption() {
            this.selectedField.options.push({
                value: '',
                label: '',
                price: '0,00',
                price_type: 'fixed',
                is_active: true
            });
        },
        
        removeOption(index) {
            this.selectedField.options.splice(index, 1);
        },
        
        formatPrice(event) {
            let value = event.target.value;
            // Remover tudo que não for número ou vírgula
            value = value.replace(/[^\d,]/g, '');
            
            // Garantir que há apenas uma vírgula
            const parts = value.split(',');
            if (parts.length > 2) {
                value = parts[0] + ',' + parts.slice(1).join('');
            }
            
            event.target.value = value;
        },
        
        async saveFieldOptions() {
            if (!this.selectedField) return;
            
            try {
                // Converter preços de formato brasileiro para decimal
                const options = this.selectedField.options.map(option => ({
                    ...option,
                    price: parseFloat(option.price.replace(',', '.')) || 0
                }));
                
                const response = await fetch(`/admin/extra-fields/${this.selectedField.id}/save-options`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ options })
                });
                
                if (response.ok) {
                    const data = await response.json();
                    showNotification('✅ Opções salvas com sucesso!', 'success');
                    this.currentView = 'list';
                    this.selectedField = null;
                    loadExtraFieldsContent();
                } else {
                    showNotification('❌ Erro ao salvar opções', 'error');
                }
            } catch (error) {
                console.error('Erro ao salvar opções:', error);
                showNotification('❌ Erro ao salvar opções', 'error');
            }
        }
    };
}

// Funções do Modal de Campos Extras
function openExtraFieldsModal() {
    const modal = document.getElementById('extraFieldsModal');
    if (modal) {
    modal.classList.remove('hidden');
    loadExtraFieldsContent();
    }
}

function closeExtraFieldsModal() {
    const modal = document.getElementById('extraFieldsModal');
    modal.classList.add('hidden');
}

// Função principal do Alpine.js para gerenciar campos extras
window.extraFieldsData = function() {
    return {
        showFieldSettings: false,
        selectedField: null,
        reorderMode: false,
        currentView: 'list',
        
        openFieldSettings(fieldId, fieldName, fieldType) {
            this.selectedField = {
                id: fieldId,
                name: fieldName,
                type: fieldType,
                options: []
            };
            
            // Carregar opções salvas do produto via AJAX
            this.loadProductFieldOptions(fieldId);
            
            this.currentView = 'settings';
        },
        
        loadProductFieldOptions(fieldId) {
            const url = '/admin/products/<?php echo e($product->id); ?>/extra-fields/' + fieldId + '/options';
            
            fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.options && data.options.length > 0) {
                    this.selectedField.options = data.options;
                } else {
                    this.loadFieldOptions(fieldId);
                }
            })
            .catch(error => {
                console.error('❌ Erro ao carregar opções do produto:', error);
                this.loadFieldOptions(fieldId);
            });
        },
        
        loadFieldOptions(fieldId) {
            const url = '/admin/extra-fields/' + fieldId + '/options';
            fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                this.selectedField.options = data.options || [];
            })
            .catch(error => {
                console.error('Erro ao carregar opções:', error);
                this.selectedField.options = [];
            });
        },
        
        addOption() {
            if (!this.selectedField) return;
            const newOption = {
                value: '',
                label: '',
                price: '0,00',
                price_type: 'fixed',
                is_active: true
            };
            
            // Adicionar campos específicos baseado no tipo do campo
            if (this.selectedField.type === 'image') {
                newOption.image_url = '';
            } else if (this.selectedField.type === 'color') {
                newOption.color_hex = '#000000';
            }
            
            this.selectedField.options.push(newOption);
        },
        
        // Método helper para atualizar imagem de uma opção
        updateOptionImage(index, imagePath) {
            if (!this.selectedField || !this.selectedField.options[index]) {
                console.error('❌ Opção não encontrada');
                return;
            }
            this.selectedField.options[index].image_url = imagePath;
        },
        
        removeOption(index) {
            if (!this.selectedField) return;
            this.selectedField.options.splice(index, 1);
        },
        
        formatPrice(event) {
            let value = event.target.value;
            value = value.replace(/[^\d,]/g, '');
            const parts = value.split(',');
            if (parts.length > 2) {
                value = parts[0] + ',' + parts.slice(1).join('');
            }
            event.target.value = value;
        },
        
        saveFieldOptions() {
            
            if (!this.selectedField) {
                console.error('❌ selectedField é null!');
                return;
            }
            
            
            const options = this.selectedField.options.map(option => {
                let price = option.price.toString().replace(',', '.');
                const baseOption = {
                    value: option.value,
                    label: option.label,
                    price: parseFloat(price) || 0,
                    price_type: option.price_type,
                    is_active: option.is_active
                };
                
                // Adicionar campos específicos se existirem
                if (option.image_url !== undefined) {
                    baseOption.image_url = option.image_url;
                }
                if (option.color_hex !== undefined) {
                    baseOption.color_hex = option.color_hex;
                }
                
                return baseOption;
            });
            
            const url = '/admin/products/<?php echo e($product->id); ?>/extra-fields/' + this.selectedField.id + '/settings';
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ 
                    field_settings: {
                        custom_options: options
                    }
                })
            })
            .then(response => {
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    if (typeof showNotification === 'function') {
                        showNotification('Configurações salvas com sucesso!', 'success');
                    } else {
                        console.error('❌ showNotification não é uma função!');
                        alert('✅ Configurações salvas com sucesso!');
                    }
                } else {
                    showNotification('Erro ao salvar configurações', 'error');
                }
            })
            .catch(error => {
                console.error('❌ Erro ao salvar configurações:', error);
                showNotification('❌ Erro ao salvar configurações', 'error');
            });
        }
    };
}

function loadExtraFieldsContent() {
    const modalContent = document.getElementById('modalContent');
    
    if (!modalContent) {
        return;
    }
    
    // Mostrar loading
    modalContent.innerHTML = `
        <div class="text-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary mx-auto"></div>
            <p class="mt-2 text-gray-600">Carregando campos extras...</p>
        </div>
    `;
    
    // Carregar conteúdo via AJAX
    fetch(`<?php echo e(route('admin.products.extra-fields', $product)); ?>`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text();
        })
        .then(html => {
            modalContent.innerHTML = html;
            
            // Reinicializar Alpine.js no conteúdo carregado
            if (window.Alpine) {
                window.Alpine.initTree(modalContent);
            }
            
            // Inicializar drag-and-drop após o DOM estar pronto
            setTimeout(() => {
                const container = document.getElementById('associated-fields');
                if (container) {
                    initializeDragAndDrop();
                }
            }, 300);
            
            initializeModalEvents();
        })
        .catch(error => {
            console.error('Erro ao carregar campos extras:', error);
            modalContent.innerHTML = `
                <div class="text-center py-8">
                    <div class="text-red-500 text-lg mb-2">Erro ao carregar campos extras</div>
                    <p class="text-gray-600 mb-4">${error.message}</p>
                    <button onclick="loadExtraFieldsContent()" 
                            class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg transition-colors">
                        Tentar novamente
                    </button>
                </div>
            `;
        });
}

// Flag para evitar múltiplas inicializações
let dragAndDropInitialized = false;

// Variável global para armazenar a instância do Sortable
let sortableInstance = null;

// Função para inicializar drag-and-drop com SortableJS
function initializeDragAndDrop() {
    const container = document.getElementById('associated-fields');
    
    if (!container) {
        console.error('❌ Container não encontrado!');
        return;
    }
    
    
    if (typeof Sortable === 'undefined') {
        console.error('❌ Sortable não definido!');
        return;
    }
    
    
    // Destruir sortable existente
    if (sortableInstance) {
        sortableInstance.destroy();
        sortableInstance = null;
    }
    
    // Verificar handles
    const handles = container.querySelectorAll('.field-drag-handle');
    
    // Criar novo sortable
    sortableInstance = new Sortable(container, {
        animation: 250,
        handle: '.field-drag-handle',
        ghostClass: 'sortable-ghost',
        dragClass: 'sortable-drag',
        chosenClass: 'sortable-chosen',
        
        onStart: function(evt) {
        },
        
        onEnd: function(evt) {
            if (evt.oldIndex !== evt.newIndex) {
                updateFieldOrder();
            }
        }
    });
    
}

// Função para atualizar ordem dos campos no servidor
async function updateFieldOrder() {
    const container = document.getElementById('associated-fields');
    if (!container) {
        return;
    }
    
    const fieldIds = Array.from(container.querySelectorAll('[data-field-id]'))
        .map(el => parseInt(el.dataset.fieldId));
    
    try {
        const response = await fetch(`<?php echo e(route('admin.products.extra-fields.reorder', $product)); ?>`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ order: fieldIds })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification('✅ ' + (data.message || 'Ordem dos campos atualizada!'), 'success');
            
            // Atualizar números de ordem visualmente
            container.querySelectorAll('[data-field-id]').forEach((el, index) => {
                const orderSpan = el.querySelector('[data-order-number]');
                if (orderSpan) {
                    orderSpan.textContent = index + 1;
                }
                el.dataset.sortOrder = index + 1;
            });
        } else {
            showNotification('❌ ' + (data.message || 'Erro ao atualizar ordem'), 'error');
            // Recarregar para reverter
            loadExtraFieldsContent();
        }
    } catch (error) {
        console.error('❌ Erro ao salvar ordem:', error);
        showNotification('❌ Erro ao salvar ordem dos campos', 'error');
        // Recarregar para reverter
        loadExtraFieldsContent();
    }
}

// Função para adicionar campo via AJAX
async function addFieldToProduct(fieldId, fieldName) {
    try {
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        formData.append('extra_field_id', fieldId);
        
        const response = await fetch(`<?php echo e(route('admin.products.extra-fields.store', $product)); ?>`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification('Campo adicionado com sucesso!', 'success');
            loadExtraFieldsContent(); // Recarregar conteúdo
        } else {
            showNotification(data.message || 'Erro ao adicionar campo', 'error');
        }
    } catch (error) {
        console.error('Erro ao adicionar campo:', error);
        showNotification('Erro ao adicionar campo: ' + error.message, 'error');
    }
}

// Função para remover campo do produto
async function removeFieldFromProduct(fieldId, fieldName) {
    if (confirm(`Tem certeza que deseja remover o campo "${fieldName}"?`)) {
        try {
        // Construir URL dinamicamente
        const url = `/admin/products/<?php echo e($product->id); ?>/extra-fields/${fieldId}`;
        
        const response = await fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
                showNotification('Campo removido com sucesso!', 'success');
                loadExtraFieldsContent(); // Recarregar conteúdo
        } else {
            showNotification(data.message || 'Erro ao remover campo', 'error');
        }
    } catch (error) {
        console.error('Erro ao remover campo:', error);
        showNotification('Erro ao remover campo: ' + error.message, 'error');
    }
}
}

// Função para editar configurações do campo
function editFieldSettings(fieldId, fieldName, fieldType, isRequired, sortOrder) {
    showNotification('Funcionalidade de edição em desenvolvimento', 'info');
}

// Função showNotification agora é global no layout.blade.php

function initializeModalEvents() {
    // Adicionar event listeners para os botões do modal
    const addFieldForms = document.querySelectorAll('form[action*="extra-fields"]');
    addFieldForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            handleModalFormSubmit(this);
        });
    });
    
    // Inicializar drag-and-drop com delay para garantir que o DOM esteja pronto
    setTimeout(() => {
        initializeDragAndDrop();
    }, 200);
}

function handleModalFormSubmit(form) {
    const formData = new FormData(form);
    const action = form.action;
    const method = form.querySelector('input[name="_method"]')?.value || 'POST';
    
    fetch(action, {
        method: method,
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
            return response.json();
    })
    .then(data => {
        if (data.success) {
            loadExtraFieldsContent();
            updateFieldsCount();
            showNotification(data.message || 'Operação realizada com sucesso!', 'success');
        } else {
            showNotification(data.message || 'Erro ao processar solicitação', 'error');
        }
    })
    .catch(error => {
        console.error('Erro no formulário:', error);
        showNotification('Erro ao processar solicitação: ' + error.message, 'error');
    });
}

function updateFieldsCount() {
    const associatedFields = document.querySelectorAll('#associated-fields [data-field-id]');
    const count = associatedFields.length;
    
    const button = document.querySelector('[onclick*="openExtraFieldsModal"]');
    if (button) {
        const currentText = button.textContent;
        const newText = currentText.replace(/\(\d+\)/, `(${count})`);
        button.textContent = newText;
    }
}

// Fechar modal ao clicar fora dele
document.getElementById('extraFieldsModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeExtraFieldsModal();
    }
});



// Função Alpine.js para gerenciar campos extras

// Dados do produto já definidos no topo do arquivo (linhas 834-845)

// Função para formatar moeda brasileira
function formatCurrency(value) {
    // Remove tudo que não é dígito
    const numericValue = value.replace(/\D/g, '');
    
    // Converte para centavos
    const cents = parseInt(numericValue) || 0;
    
    // Formata como número brasileiro (sem símbolo da moeda)
    return new Intl.NumberFormat('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(cents / 100);
}

// Função para converter valor formatado para número
function parseCurrency(value) {
    // Remove símbolos e converte para número
    return parseFloat(value.replace(/[^\d,]/g, '').replace(',', '.')) || 0;
}

// Aplicar máscara de moeda nos campos
document.addEventListener('DOMContentLoaded', function() {
    const priceFields = ['price', 'sale_price'];
    
    priceFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            // Formatar valor inicial se existir
            if (field.value) {
                field.value = formatCurrency(field.value);
            }
            
            // Aplicar máscara no input
            field.addEventListener('input', function(e) {
                const cursorPosition = e.target.selectionStart;
                const oldValue = e.target.value;
                const newValue = formatCurrency(e.target.value);
                
                e.target.value = newValue;
                
                // Ajustar posição do cursor
                const newCursorPosition = cursorPosition + (newValue.length - oldValue.length);
                e.target.setSelectionRange(newCursorPosition, newCursorPosition);
            });
            
            // Converter para número antes do envio
            field.addEventListener('blur', function(e) {
                const numericValue = parseCurrency(e.target.value);
                e.target.setAttribute('data-numeric-value', numericValue);
                
                // Atualizar campo hidden correspondente
                const hiddenField = document.getElementById(fieldId + '_numeric');
                if (hiddenField) {
                    hiddenField.value = numericValue;
                }
            });
            
            // Também atualizar no evento input para capturar mudanças em tempo real
            field.addEventListener('input', function(e) {
                const numericValue = parseCurrency(e.target.value);
                const hiddenField = document.getElementById(fieldId + '_numeric');
                if (hiddenField) {
                    hiddenField.value = numericValue;
                }
            });
        }
    });

    // Interceptar submit do formulário para converter preços
    const form = document.getElementById('productForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Converter preços de vírgula para ponto antes de enviar
            const priceField = document.getElementById('price');
            const salePriceField = document.getElementById('sale_price');
            
            if (priceField && priceField.value) {
                // Remove pontos de milhar e converte vírgula para ponto
                const priceValue = priceField.value.replace(/\./g, '').replace(',', '.');
                priceField.value = priceValue;
            }
            
            if (salePriceField && salePriceField.value) {
                // Remove pontos de milhar e converte vírgula para ponto
                const salePriceValue = salePriceField.value.replace(/\./g, '').replace(',', '.');
                salePriceField.value = salePriceValue;
            }
        });
    }
});

// File Manager para Campos Extras - Mesmo código do options.blade.php
let currentOptionIndex = null;
let fileManagerData = {
    items: [],
    folders: [],
    files: [],
    loading: false,
    currentPath: '',
    breadcrumb: []
};

function openFileManagerForOption(optionIndex) {
    currentOptionIndex = optionIndex;
    const modal = document.getElementById('optionFileManagerModal');
    modal.classList.remove('hidden');
    loadFileManagerImages();
}

function closeOptionFileManager() {
    const modal = document.getElementById('optionFileManagerModal');
    modal.classList.add('hidden');
    currentOptionIndex = null;
}

async function loadFileManagerImages() {
    const loading = document.getElementById('optionFileManagerLoading');
    const grid = document.getElementById('optionFileManagerGrid');
    
    if (!loading || !grid) {
        console.error('❌ Elementos do modal não encontrados!');
        return;
    }
    
    loading.classList.remove('hidden');
    grid.classList.add('hidden');
    
    fileManagerData.currentPath = '';
    await loadCurrentDirectory();
}

async function loadCurrentDirectory() {
    
    const loading = document.getElementById('optionFileManagerLoading');
    const grid = document.getElementById('optionFileManagerGrid');
    
    if (!loading || !grid) {
        console.error('❌ Elementos do modal não encontrados!');
        console.error('❌ loading:', loading);
        console.error('❌ grid:', grid);
        return;
    }
    
    loading.classList.remove('hidden');
    grid.classList.add('hidden');
    
    try {
        let url = window.fileManagerRoute;
        
        if (fileManagerData.currentPath && fileManagerData.currentPath.trim() !== '') {
            url += `?directory=${encodeURIComponent(fileManagerData.currentPath)}`;
        } else {
        }
        
        
        const response = await fetch(url);
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        const data = await response.json();
        
        if (data.items) {
            fileManagerData.folders = data.items.filter(item => item.type === 'directory');
            fileManagerData.files = data.items.filter(item => 
                item.type === 'file' && isImageFile(item.name)
            );
            
        } else {
            console.warn('⚠️ Nenhum item retornado na resposta');
            fileManagerData.folders = [];
            fileManagerData.files = [];
        }
        
        updateBreadcrumb();
        
        displayFileManagerContent();
    } catch (error) {
        console.error('❌ Erro ao carregar diretório:', error);
        console.error('❌ Stack trace:', error.stack);
        alert('Erro ao carregar diretório: ' + error.message);
    }
}

function updateBreadcrumb() {
    const parts = fileManagerData.currentPath ? fileManagerData.currentPath.split('/').filter(p => p) : [];
    fileManagerData.breadcrumb = parts.map((part, index) => ({
        name: part,
        path: parts.slice(0, index + 1).join('/')
    }));
}

function displayFileManagerContent() {
    
    const grid = document.getElementById('optionFileManagerGrid');
    const loading = document.getElementById('optionFileManagerLoading');
    
    if (!grid || !loading) {
        console.error('❌ Elementos principais do modal não encontrados!');
        console.error('❌ grid:', grid);
        console.error('❌ loading:', loading);
        return;
    }
    
    loading.classList.add('hidden');
    grid.classList.remove('hidden');
    
    const foldersSection = document.getElementById('foldersSection');
    const filesSection = document.getElementById('filesSection');
    const foldersGrid = document.getElementById('foldersGrid');
    const filesGrid = document.getElementById('filesGrid');
    
    
    updateBreadcrumbDOM();
    
    if (foldersSection) {
        const showFolders = fileManagerData.folders.length > 0;
        foldersSection.style.display = showFolders ? 'block' : 'none';
    }
    if (filesSection) {
        const showFiles = fileManagerData.files.length > 0;
        filesSection.style.display = showFiles ? 'block' : 'none';
    }
    
    if (fileManagerData.folders.length > 0 && foldersGrid) {
        foldersGrid.innerHTML = fileManagerData.folders.map(folder => `
            <div class="relative group cursor-pointer rounded-lg border-2 border-gray-200 hover:border-blue-500 hover:shadow-lg transition-all p-4 bg-white"
                 onclick="navigateToFolder('${folder.path}')"
                 title="Entrar em ${folder.name}">
                <div class="text-center">
                    <i class="bi bi-folder text-4xl text-blue-500 mb-3"></i>
                    <p class="text-sm font-medium text-gray-900 truncate">${folder.name}</p>
                    <p class="text-xs text-gray-500 mt-1">Pasta</p>
                </div>
            </div>
        `).join('');
    }
    
    if (fileManagerData.files.length > 0 && filesGrid) {
        filesGrid.innerHTML = fileManagerData.files.map(file => {
            const fileSize = formatFileSize(file.size);
            const fileExtension = file.name.split('.').pop().toUpperCase();
            
            return `
                <div class="relative group cursor-pointer rounded-lg overflow-hidden border-2 border-gray-200 hover:border-green-500 hover:shadow-lg transition-all aspect-square bg-gray-50"
                     onclick="selectImageForOption('${file.path}')"
                     title="${file.name} (${fileSize})">
                    <img src="${file.url}" 
                         alt="${file.name}"
                         class="w-full h-full object-cover"
                         loading="lazy"
                         onerror="this.src='/images/general/callback-image.svg'">
                    
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all flex items-center justify-center">
                        <i class="bi bi-check-circle-fill text-white text-3xl opacity-0 group-hover:opacity-100 transition-opacity"></i>
                    </div>
                    
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-2">
                        <p class="text-white text-xs font-medium truncate mb-1">${file.name}</p>
                        <div class="flex justify-between items-center text-xs text-gray-300">
                            <span>${fileExtension}</span>
                            <span>${fileSize}</span>
                        </div>
                    </div>
                    
                    <div class="absolute top-2 right-2 bg-black/60 text-white text-xs px-2 py-1 rounded-full">
                        ${fileExtension}
                    </div>
                </div>
            `;
        }).join('');
    }
    
    if (fileManagerData.folders.length === 0 && fileManagerData.files.length === 0) {
        grid.innerHTML = `
            <div class="text-center py-12">
                <i class="bi bi-folder-x text-6xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Pasta vazia</h3>
                <p class="text-gray-500">Esta pasta não contém arquivos ou subpastas.</p>
            </div>
        `;
    }
    
}

function updateBreadcrumbDOM() {
    const breadcrumbContainer = document.getElementById('breadcrumbContainer');
    if (!breadcrumbContainer) return;
    
    let breadcrumbHTML = `
        <button onclick="navigateToFolder('')" 
                class="hover:text-blue-600 flex items-center gap-1 transition-colors">
            <i class="bi bi-house"></i>
            <span>Raiz</span>
        </button>
    `;
    
    fileManagerData.breadcrumb.forEach(part => {
        breadcrumbHTML += `
            <div class="flex items-center space-x-2">
                <i class="bi bi-chevron-right text-gray-400"></i>
                <button onclick="navigateToFolder('${part.path}')" 
                        class="hover:text-blue-600 transition-colors">
                    ${part.name}
                </button>
            </div>
        `;
    });
    
    breadcrumbContainer.innerHTML = breadcrumbHTML;
}

function navigateToFolder(path) {
    fileManagerData.currentPath = path;
    loadCurrentDirectory();
}

function selectImageForOption(imagePath) {
    if (currentOptionIndex === null) {
        console.error('❌ currentOptionIndex é null');
        return;
    }
    
    
    // Limpar o caminho da imagem (remover 'public/' se existir)
    const cleanPath = imagePath.startsWith('public/') ? imagePath.replace('public/', '') : imagePath;
    
    // Verificar se Alpine está disponível
    if (typeof Alpine === 'undefined') {
        console.error('❌ Alpine.js não está disponível globalmente');
        return;
    }
    
    // Tentar encontrar o componente Alpine.js dentro do modal
    const modalContent = document.getElementById('modalContent');
    if (!modalContent) {
        console.error('❌ modalContent não encontrado');
        return;
    }
    
    const alpineEl = modalContent.querySelector('[x-data]');
    if (!alpineEl) {
        console.error('❌ Elemento Alpine.js não encontrado dentro do modalContent');
        return;
    }
    
    
    const alpineComponent = Alpine.$data(alpineEl);
    if (!alpineComponent) {
        console.error('❌ Componente Alpine.js não encontrado');
        return;
    }
    
    
    // Usar o método helper do Alpine.js
    if (typeof alpineComponent.updateOptionImage === 'function') {
        alpineComponent.updateOptionImage(currentOptionIndex, cleanPath);
    } else {
        // Fallback: atualizar diretamente
        if (alpineComponent.selectedField && alpineComponent.selectedField.options) {
            
            if (alpineComponent.selectedField.options[currentOptionIndex]) {
                alpineComponent.selectedField.options[currentOptionIndex].image_url = cleanPath;
            } else {
                console.error('❌ Opção no index ' + currentOptionIndex + ' não existe');
            }
        } else {
        }
    }
    
    closeOptionFileManager();
}

function isImageFile(filename) {
    const imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'];
    const extension = filename.split('.').pop().toLowerCase();
    return imageExtensions.includes(extension);
}

function formatFileSize(bytes) {
    if (!bytes) return '0 B';
    const k = 1024;
    const sizes = ['B', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
}

// Upload Modal Functions
function openUploadModal() {
    const modal = document.getElementById('uploadModal');
    modal.classList.remove('hidden');
    resetUploadModal();
}

function closeUploadModal() {
    const modal = document.getElementById('uploadModal');
    modal.classList.add('hidden');
    resetUploadModal();
}

function resetUploadModal() {
    document.getElementById('uploadForm').reset();
    document.getElementById('uploadContent').classList.remove('hidden');
    document.getElementById('uploadProgress').classList.add('hidden');
    document.getElementById('uploadSuccess').classList.add('hidden');
    document.getElementById('progressBar').style.width = '0%';
    document.getElementById('uploadButton').disabled = false;
}

function handleFileSelect(event) {
    const file = event.target.files[0];
    if (file) {
        
        if (!file.type.startsWith('image/')) {
            alert('Por favor, selecione apenas arquivos de imagem.');
            event.target.value = '';
            return;
        }
        
        if (file.size > 5 * 1024 * 1024) {
            alert('O arquivo deve ter no máximo 5MB.');
            event.target.value = '';
            return;
        }
        
        showFilePreview(file);
    }
}

function showFilePreview(file) {
    const reader = new FileReader();
    reader.onload = function(e) {
        const uploadContent = document.getElementById('uploadContent');
        uploadContent.innerHTML = `
            <img src="${e.target.result}" alt="Preview" class="w-32 h-32 object-cover rounded-lg mx-auto mb-4">
            <p class="text-gray-600 font-medium">${file.name}</p>
            <p class="text-sm text-gray-500">${formatFileSize(file.size)}</p>
        `;
    };
    reader.readAsDataURL(file);
}

async function handleUpload(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const fileInput = document.getElementById('imageFileInput');
    
    if (!fileInput.files[0]) {
        alert('Por favor, selecione um arquivo para upload.');
        return;
    }
    
    document.getElementById('uploadContent').classList.add('hidden');
    document.getElementById('uploadProgress').classList.remove('hidden');
    document.getElementById('uploadButton').disabled = true;
    
    try {
        const response = await fetch('<?php echo e(route("admin.admin.file-manager.upload")); ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        const result = await response.json();
        
        if (result.success) {
            document.getElementById('uploadProgress').classList.add('hidden');
            document.getElementById('uploadSuccess').classList.remove('hidden');
            
            setTimeout(() => {
                loadCurrentDirectory();
                closeUploadModal();
            }, 1000);
        } else {
            throw new Error(result.message || 'Erro no upload');
        }
    } catch (error) {
        console.error('❌ Erro no upload:', error);
        alert('Erro no upload: ' + error.message);
        resetUploadModal();
    }
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('imageSearchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            if (fileManagerData.items.length > 0) {
                displayFileManagerContent();
            }
        });
    }
    
    const uploadForm = document.getElementById('uploadForm');
    if (uploadForm) {
        uploadForm.addEventListener('submit', handleUpload);
    }
    
    const uploadArea = document.getElementById('uploadArea');
    if (uploadArea) {
        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            uploadArea.classList.add('border-green-500', 'bg-green-50');
        });
        
        uploadArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('border-green-500', 'bg-green-50');
        });
        
        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('border-green-500', 'bg-green-50');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                document.getElementById('imageFileInput').files = files;
                handleFileSelect({ target: { files: files } });
            }
        });
    }
});

</script>

<!-- Componente File Manager para Imagem Destacada -->
<?php if (isset($component)) { $__componentOriginalf0b87ed0128a26fa6e98183f0145fdc5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf0b87ed0128a26fa6e98183f0145fdc5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.file-manager-modal','data' => ['modalId' => 'productFeaturedImageManager','title' => 'Selecionar Imagem Destacada','onSelectCallback' => 'selectProductFeaturedImage']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('file-manager-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['modal-id' => 'productFeaturedImageManager','title' => 'Selecionar Imagem Destacada','on-select-callback' => 'selectProductFeaturedImage']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf0b87ed0128a26fa6e98183f0145fdc5)): ?>
<?php $attributes = $__attributesOriginalf0b87ed0128a26fa6e98183f0145fdc5; ?>
<?php unset($__attributesOriginalf0b87ed0128a26fa6e98183f0145fdc5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf0b87ed0128a26fa6e98183f0145fdc5)): ?>
<?php $component = $__componentOriginalf0b87ed0128a26fa6e98183f0145fdc5; ?>
<?php unset($__componentOriginalf0b87ed0128a26fa6e98183f0145fdc5); ?>
<?php endif; ?>

<!-- Componente File Manager para Galeria -->
<?php if (isset($component)) { $__componentOriginalf0b87ed0128a26fa6e98183f0145fdc5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf0b87ed0128a26fa6e98183f0145fdc5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.file-manager-modal','data' => ['modalId' => 'productGalleryManager','title' => 'Adicionar Imagens à Galeria','onSelectCallback' => 'selectProductGalleryImage']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('file-manager-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['modal-id' => 'productGalleryManager','title' => 'Adicionar Imagens à Galeria','on-select-callback' => 'selectProductGalleryImage']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf0b87ed0128a26fa6e98183f0145fdc5)): ?>
<?php $attributes = $__attributesOriginalf0b87ed0128a26fa6e98183f0145fdc5; ?>
<?php unset($__attributesOriginalf0b87ed0128a26fa6e98183f0145fdc5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf0b87ed0128a26fa6e98183f0145fdc5)): ?>
<?php $component = $__componentOriginalf0b87ed0128a26fa6e98183f0145fdc5; ?>
<?php unset($__componentOriginalf0b87ed0128a26fa6e98183f0145fdc5); ?>
<?php endif; ?>

<script>
// Callbacks para seleção de imagens do produto
function selectProductFeaturedImage(imagePath) {
    const imageUrl = imagePath.startsWith('http') ? imagePath : `<?php echo e(url('images/')); ?>/${imagePath}`;
    
    // Atualizar Alpine.js
    const component = Alpine.$data(document.querySelector('[x-data*="productManager()"]'));
    if (component) {
        component.featuredImage = imageUrl;
        component.featuredImagePath = imagePath;
    }
    
    closeFileManagerproductFeaturedImageManager();
}

function selectProductGalleryImage(imagePath) {
    const imageUrl = imagePath.startsWith('http') ? imagePath : `<?php echo e(url('images/')); ?>/${imagePath}`;
    
    // Atualizar Alpine.js
    const component = Alpine.$data(document.querySelector('[x-data*="productManager()"]'));
    if (component) {
        // Verificar se a imagem já não está na galeria
        const exists = component.galleryImages.some(img => img.path === imagePath);
        if (!exists) {
            component.galleryImages.push({
                url: imageUrl,
                path: imagePath,
                name: imagePath.split('/').pop()
            });
        }
    }
    
    closeFileManagerproductGalleryManager();
}

// Debug: Verificar se Alpine.js está funcionando corretamente
document.addEventListener('DOMContentLoaded', function() {
    console.log('🔍 Verificando Alpine.js...');
    
    // Verificar se Alpine está disponível
    if (typeof Alpine !== 'undefined') {
        console.log('✅ Alpine.js carregado');
        
        // Verificar se o componente productManager está disponível
        const productManagerEl = document.querySelector('[x-data*="productManager"]');
        if (productManagerEl) {
            console.log('✅ Componente productManager encontrado');
            
            // Verificar se o componente foi inicializado
            setTimeout(() => {
                const component = Alpine.$data(productManagerEl);
                if (component) {
                    console.log('✅ Componente productManager inicializado:', {
                        featuredImage: !!component.featuredImage,
                        featuredImagePath: component.featuredImagePath,
                        galleryCount: component.galleryImages?.length || 0
                    });
                } else {
                    console.error('❌ Componente productManager não inicializado');
                }
            }, 1000);
        } else {
            console.error('❌ Elemento com x-data="productManager()" não encontrado');
        }
    } else {
        console.error('❌ Alpine.js não está disponível');
    }
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\resources\views/admin/products/edit.blade.php ENDPATH**/ ?>