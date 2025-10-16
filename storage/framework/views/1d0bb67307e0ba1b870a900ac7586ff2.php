<?php $__env->startSection('title', 'Loja Virtual - Laser Link'); ?>
<?php $__env->startSection('page-title', 'Configurações da Loja Virtual'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6" x-data="storeSettings()">
    <form method="POST" action="<?php echo e(route('admin.store-settings.update')); ?>" x-init="init()" enctype="multipart/form-data" class="space-y-6">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        


        <!-- Banners da Home -->
        <?php
            // Preparar dados dos banners desktop para Alpine.js
            $banners = json_decode($settings['home_banners'] ?? '[]', true);
            if (!is_array($banners)) {
                // Suporte para banner único antigo
                $oldBanner = $settings['home_banner_image'] ?? null;
                $banners = $oldBanner ? [$oldBanner] : [];
            }
            
            $bannersForAlpine = array_map(function($banner) {
                // Determinar URL correta baseado na localização do arquivo
                if (filter_var($banner, FILTER_VALIDATE_URL)) {
                    // Já é uma URL completa
                    $url = $banner;
                } elseif (strpos($banner, '/') === 0) {
                    // Caminho absoluto
                    $url = $banner;
                } else {
                    // Caminho relativo - assumir que está em public/images/
                    $url = url('images/' . $banner);
                }
                
                return [
                    'url' => $url,
                    'name' => basename($banner),
                    'path' => $banner
                ];
            }, $banners);

            // Preparar dados dos banners mobile para Alpine.js
            $bannersMobile = json_decode($settings['home_banners_mobile'] ?? '[]', true);
            if (!is_array($bannersMobile)) {
                $bannersMobile = [];
            }
            
            $bannersMobileForAlpine = array_map(function($banner) {
                // Determinar URL correta baseado na localização do arquivo
                if (filter_var($banner, FILTER_VALIDATE_URL)) {
                    // Já é uma URL completa
                    $url = $banner;
                } elseif (strpos($banner, '/') === 0) {
                    // Caminho absoluto
                    $url = $banner;
                } else {
                    // Caminho relativo - assumir que está em public/images/
                    $url = url('images/' . $banner);
                }
                
                return [
                    'url' => $url,
                    'name' => basename($banner),
                    'path' => $banner
                ];
            }, $bannersMobile);
        ?>
        
        <div class="bg-white rounded-lg shadow p-6" x-data="bannerManager()">
            <div class="flex items-center mb-6">
                <i class="bi bi-laptop text-primary text-xl mr-3"></i>
                <h3 class="text-lg font-semibold text-gray-900">Banners Desktop (Horizontal)</h3>
                <span class="ml-2 px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">1600x500px recomendado</span>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Lado Esquerdo: Botões e Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Upload de Banners Desktop
                    </label>
                    
                    <!-- Opções de seleção -->
                    <div class="mb-4 flex space-x-3">
                        <button type="button" 
                                onclick="openFileManagerbannerDesktopFileManager()"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="bi bi-folder mr-2"></i>Selecionar do Gerenciador
                        </button>
                        <button type="button" 
                                onclick="document.getElementById('home_banner_images').click()"
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="bi bi-upload mr-2"></i>Upload Novo
                        </button>
                    </div>
                    
                    <!-- Área de Upload -->
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <i class="bi bi-cloud-upload text-gray-400 text-4xl"></i>
                            <div class="flex text-sm text-gray-600">
                                <label for="home_banner_images" class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-red-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary">
                                    <span>Fazer upload</span>
                                    <input type="file" 
                                           id="home_banner_images" 
                                           name="home_banner_images[]" 
                                           accept="image/*"
                                           multiple
                                           class="sr-only"
                                           @change="handleFileUpload($event)">
                                </label>
                                <p class="pl-1">ou arraste e solte</p>
                            </div>
                            <p class="text-xs text-gray-500">JPG, PNG, WEBP, AVIF até 5MB | 1600x500px recomendado</p>
                        </div>
                    </div>
                    
                    <input type="hidden" name="home_banner_images_from_gallery" id="home_banner_images_from_gallery" value="">
                </div>
                
                <!-- Lado Direito: Preview com Slider -->
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-2">Preview:</p>
                    
                    <!-- Preview Container -->
                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 relative">
                        <template x-if="uploadedBanners.length === 0">
                            <div class="text-center text-gray-500 flex flex-col items-center justify-center h-64">
                                <i class="bi bi-images text-6xl mb-4"></i>
                                <p class="font-medium">Nenhum banner selecionado</p>
                                <p class="text-sm mt-1">Adicione banners usando os botões acima</p>
                            </div>
                        </template>
                        
                        <template x-if="uploadedBanners.length > 0">
                            <div class="relative">
                                <!-- Slider de Banners -->
                                <div class="relative overflow-hidden rounded-lg">
                                    <template x-for="(banner, index) in uploadedBanners" :key="index">
                                        <div x-show="currentBannerIndex === index" 
                                             x-transition:enter="transition ease-out duration-300"
                                             x-transition:enter-start="opacity-0 transform translate-x-full"
                                             x-transition:enter-end="opacity-100 transform translate-x-0"
                                             x-transition:leave="transition ease-in duration-300"
                                             x-transition:leave-start="opacity-100 transform translate-x-0"
                                             x-transition:leave-end="opacity-0 transform -translate-x-full"
                                             class="aspect-[16/5]">
                                            <img :src="banner.url" 
                                                 :alt="banner.name" 
                                                 class="w-full h-full object-cover rounded-lg">
                                            
                                            <!-- Botão Remover Banner -->
                                            <button type="button"
                                                    @click="removeBanner(index)"
                                                    class="absolute top-2 right-2 bg-red-500 text-white p-2 rounded-full hover:bg-red-600 transition shadow-lg">
                                                <i class="bi bi-trash text-sm"></i>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                                
                                <!-- Setas de Navegação -->
                                <template x-if="uploadedBanners.length > 1">
                                    <div>
                                        <button type="button"
                                                @click="previousBanner()"
                                                class="absolute left-2 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white p-3 rounded-full shadow-lg transition">
                                            <i class="bi bi-chevron-left text-gray-900 text-xl"></i>
                                        </button>
                                        <button type="button"
                                                @click="nextBanner()"
                                                class="absolute right-2 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white p-3 rounded-full shadow-lg transition">
                                            <i class="bi bi-chevron-right text-gray-900 text-xl"></i>
                                        </button>
                                        
                                        <!-- Indicadores -->
                                        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex space-x-2">
                                            <template x-for="(banner, index) in uploadedBanners" :key="index">
                                                <button type="button"
                                                        @click="currentBannerIndex = index"
                                                        class="w-2 h-2 rounded-full transition-all"
                                                        :class="currentBannerIndex === index ? 'bg-white w-8' : 'bg-white/50 hover:bg-white/80'">
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                                
                                <!-- Contador -->
                                <div class="mt-3 text-center">
                                    <span class="text-xs text-gray-600 bg-white px-3 py-1 rounded-full shadow-sm border border-gray-200">
                                        <i class="bi bi-images mr-1"></i>
                                        <span x-text="uploadedBanners.length"></span> banner(s)
                                    </span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
            
            <!-- Input hidden para armazenar os banners como JSON -->
            <input type="hidden" name="home_banners_json" id="home_banners_json" :value="JSON.stringify(uploadedBanners)">
        </div>

        <!-- Banners Mobile (Vertical) -->
        <div class="bg-white rounded-lg shadow p-6 mt-6" x-data="bannerMobileManager()">
            <div class="flex items-center mb-6">
                <i class="bi bi-phone text-primary text-xl mr-3"></i>
                <h3 class="text-lg font-semibold text-gray-900">Banners Mobile (Vertical)</h3>
                <span class="ml-2 px-2 py-1 text-xs bg-purple-100 text-purple-800 rounded">600x800px recomendado</span>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Lado Esquerdo: Botões e Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Upload de Banners Mobile
                    </label>
                    
                    <!-- Opções de seleção -->
                    <div class="mb-4 flex space-x-3">
                        <button type="button" 
                                onclick="openFileManagerbannerMobileFileManager()"
                                class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                            <i class="bi bi-folder mr-2"></i>Selecionar do Gerenciador
                        </button>
                        <button type="button" 
                                onclick="document.getElementById('home_banner_mobile_images').click()"
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="bi bi-upload mr-2"></i>Upload Novo
                        </button>
                    </div>
                    
                    <!-- Área de Upload -->
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <i class="bi bi-cloud-upload text-gray-400 text-4xl"></i>
                            <div class="flex text-sm text-gray-600">
                                <label for="home_banner_mobile_images" class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-red-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary">
                                    <span>Fazer upload</span>
                                    <input type="file" 
                                           id="home_banner_mobile_images" 
                                           name="home_banner_mobile_images[]" 
                                           accept="image/*"
                                           multiple
                                           class="sr-only"
                                           @change="handleFileUpload($event)">
                                </label>
                                <p class="pl-1">ou arraste e solte</p>
                            </div>
                            <p class="text-xs text-gray-500">JPG, PNG, WEBP, AVIF até 5MB | 600x800px recomendado (vertical)</p>
                        </div>
                    </div>
                    
                    <input type="hidden" name="home_banner_mobile_images_from_gallery" id="home_banner_mobile_images_from_gallery" value="">
                </div>
                
                <!-- Lado Direito: Preview com Slider -->
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-2">Preview (Mobile):</p>
                    
                    <!-- Preview Container -->
                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 relative flex justify-center">
                        <template x-if="uploadedBannersMobile.length === 0">
                            <div class="text-center text-gray-500 flex flex-col items-center justify-center h-full">
                                <i class="bi bi-phone text-6xl mb-4"></i>
                                <p class="font-medium">Nenhum banner mobile selecionado</p>
                                <p class="text-sm mt-1">Adicione banners verticais para mobile</p>
                            </div>
                        </template>
                        
                        <template x-if="uploadedBannersMobile.length > 0">
                            <div class="relative w-full max-w-[300px]">
                                <!-- Slider de Banners Mobile -->
                                <div class="relative overflow-hidden rounded-lg">
                                    <template x-for="(banner, index) in uploadedBannersMobile" :key="index">
                                        <div x-show="currentBannerMobileIndex === index" 
                                             x-transition:enter="transition ease-out duration-300"
                                             x-transition:enter-start="opacity-0 transform translate-x-full"
                                             x-transition:enter-end="opacity-100 transform translate-x-0"
                                             x-transition:leave="transition ease-in duration-300"
                                             x-transition:leave-start="opacity-100 transform translate-x-0"
                                             x-transition:leave-end="opacity-0 transform -translate-x-full"
                                             class="aspect-[3/4]">
                                            <img :src="banner.url" 
                                                 :alt="banner.name" 
                                                 class="w-full h-full object-cover rounded-lg">
                                            
                                            <!-- Botão Remover Banner -->
                                            <button type="button"
                                                    @click="removeBannerMobile(index)"
                                                    class="absolute top-2 right-2 bg-red-500 text-white p-2 rounded-full hover:bg-red-600 transition shadow-lg">
                                                <i class="bi bi-trash text-sm"></i>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                                
                                <!-- Setas de Navegação -->
                                <template x-if="uploadedBannersMobile.length > 1">
                                    <div>
                                        <button type="button"
                                                @click="previousBannerMobile()"
                                                class="absolute left-2 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white p-3 rounded-full shadow-lg transition z-10">
                                            <i class="bi bi-chevron-left text-gray-900 text-xl"></i>
                                        </button>
                                        <button type="button"
                                                @click="nextBannerMobile()"
                                                class="absolute right-2 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white p-3 rounded-full shadow-lg transition z-10">
                                            <i class="bi bi-chevron-right text-gray-900 text-xl"></i>
                                        </button>
                                        
                                        <!-- Indicadores -->
                                        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex space-x-2">
                                            <template x-for="(banner, index) in uploadedBannersMobile" :key="index">
                                                <button type="button"
                                                        @click="currentBannerMobileIndex = index"
                                                        class="w-2 h-2 rounded-full transition-all"
                                                        :class="currentBannerMobileIndex === index ? 'bg-white w-8' : 'bg-white/50 hover:bg-white/80'">
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                                
                                <!-- Contador -->
                                <div class="mt-3 text-center">
                                    <span class="text-xs text-gray-600 bg-white px-3 py-1 rounded-full shadow-sm border border-gray-200">
                                        <i class="bi bi-phone mr-1"></i>
                                        <span x-text="uploadedBannersMobile.length"></span> banner(s) mobile
                                    </span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
            
            <!-- Input hidden para armazenar os banners mobile como JSON -->
            <input type="hidden" name="home_banners_mobile_json" id="home_banners_mobile_json" :value="JSON.stringify(uploadedBannersMobile)">
        </div>

        <!-- Configuração de Categorias da Home -->
        <div class="bg-white rounded-lg shadow p-6" x-data="homeCategories()">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <i class="bi bi-grid-3x3-gap text-primary text-xl mr-3"></i>
                    <h3 class="text-lg font-semibold text-gray-900">Categorias da Página Inicial</h3>
                </div>
                <button type="button" 
                        @click="addCategory()"
                        class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors">
                    <i class="bi bi-plus mr-2"></i>Adicionar Categoria
                </button>
            </div>

            <div class="space-y-4">
                <template x-for="(category, index) in categories" :key="index">
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                            <!-- Imagem da Categoria - PRIMEIRA POSIÇÃO -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Imagem</label>
                                <div class="flex items-center space-x-3">
                                    <!-- Miniatura da imagem -->
                                    <div class="flex-shrink-0">
                                        <div x-show="category.image" class="w-12 h-12 rounded-full overflow-hidden border-2 border-gray-200">
                                            <img :src="getImageUrl(category.image)" 
                                                 alt="Imagem da categoria"
                                                 class="w-full h-full object-cover">
                                        </div>
                                        <div x-show="!category.image" class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center">
                                            <i class="bi bi-image text-gray-400"></i>
                                        </div>
                                    </div>
                                    
                                    <!-- Botões -->
                                    <div class="flex flex-col space-y-1">
                                        <button type="button" 
                                                @click="openImageManager(index)"
                                                class="px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors whitespace-nowrap">
                                            <i class="bi bi-folder2-open mr-1"></i>Selecionar
                                        </button>
                                        <button type="button" 
                                                @click="removeImage(index)"
                                                x-show="category.image"
                                                class="px-3 py-1 text-sm bg-red-600 text-white rounded hover:bg-red-700 transition-colors whitespace-nowrap">
                                            <i class="bi bi-trash mr-1"></i>Remover
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Categoria -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Categoria</label>
                                <select x-model="category.category_id" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                                    <option value="">Selecione uma categoria</option>
                                    <?php $__currentLoopData = $allCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($cat->id); ?>"><?php echo e($cat->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <!-- Título Personalizado -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Título Personalizado</label>
                                <input type="text" 
                                       x-model="category.title"
                                       placeholder="Deixe em branco para usar o nome da categoria"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>

                            <!-- Ordem -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Ordem</label>
                                <input type="number" 
                                       x-model="category.order"
                                       min="1"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>

                            <!-- Ações -->
                            <div class="flex items-center space-x-2">
                                <button type="button" 
                                        @click="removeCategory(index)"
                                        class="px-3 py-2 text-red-600 hover:text-red-800 border border-red-300 hover:border-red-400 rounded-lg transition-colors">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Se não há categorias -->
                <template x-if="categories.length === 0">
                    <div class="text-center py-8 text-gray-500">
                        <i class="bi bi-grid-3x3-gap text-4xl mb-2"></i>
                        <p class="font-medium">Nenhuma categoria configurada</p>
                        <p class="text-sm mt-1">Adicione categorias para exibir na página inicial</p>
                        <button type="button" 
                                @click="addCategory()"
                                class="mt-4 px-6 py-3 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors">
                            <i class="bi bi-plus mr-2"></i>Adicionar Primeira Categoria
                        </button>
                    </div>
                </template>
            </div>

            <!-- Input hidden para armazenar as categorias como JSON -->
            <input type="hidden" name="home_categories_json" id="home_categories_json" :value="JSON.stringify(categories)">
        </div>

        <!-- Gerenciador de Itens do Menu -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <i class="bi bi-list-ul text-primary text-xl mr-3"></i>
                    <h3 class="text-lg font-semibold text-gray-900">Itens do Menu</h3>
                </div>
                <button type="button" 
                        @click="addMenuItem()"
                        class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors">
                    <i class="bi bi-plus mr-2"></i>Adicionar Item
                </button>
            </div>

            <!-- Lista de Itens -->
            <div class="space-y-4" x-show="menuItems.length > 0">
                <template x-for="(item, index) in menuItems" :key="index">
                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50"
                         draggable="true"
                         @dragstart="dragStart(index)"
                         @dragover.prevent
                         @drop="drop(index)">
                        
                        <div class="flex items-center space-x-4">
                            <!-- Handle de arrastar -->
                            <div class="cursor-move text-gray-400 hover:text-gray-600">
                                <i class="bi bi-grip-vertical text-xl"></i>
                            </div>

                            <!-- Ícone -->
                            <div class="mt-2 w-12 h-12 bg-white rounded-lg border border-gray-200 flex items-center justify-center cursor-pointer hover:bg-gray-50"
                                 @click="openIconPicker(index)"
                                 title="Escolher ícone">
                                <i :class="item.icon || 'bi bi-link'" class="text-lg text-gray-600"></i>
                            </div>

                            <!-- Campos -->
                            <div class="flex-1 grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Nome</label>
                                    <input type="text" 
                                           x-model="item.name"
                                           :name="`menu_items[${index}][name]`"
                                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                           placeholder="Nome do item">
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">URL</label>
                                    <input type="text" 
                                           x-model="item.url"
                                           :name="`menu_items[${index}][url]`"
                                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                           placeholder="https://exemplo.com">
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Ícone</label>
                                    <input type="text" 
                                           x-model="item.icon"
                                           :name="`menu_items[${index}][icon]`"
                                           list="bootstrap-icons"
                                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                           placeholder="bi bi-house"
                                           @focus="openIconPicker(index)">
                                </div>

                                <div class="flex items-center space-x-4">
                                    <label class="flex items-center">
                                        <input type="checkbox" 
                                               x-model="item.is_active"
                                               :name="`menu_items[${index}][is_active]`"
                                               class="rounded border-gray-300 text-primary shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                                        <span class="ml-2 text-xs text-gray-700">Ativo</span>
                                    </label>

                                    <label class="flex items-center">
                                        <input type="checkbox" 
                                               x-model="item.is_external"
                                               :name="`menu_items[${index}][is_external]`"
                                               class="rounded border-gray-300 text-primary shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                                        <span class="ml-2 text-xs text-gray-700">Externo</span>
                                    </label>

                                    <button type="button" 
                                            @click="removeMenuItem(index)"
                                            class="text-red-600 hover:text-red-800">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Estado vazio -->
            <div x-show="menuItems.length === 0" class="text-center py-12">
                <i class="bi bi-list-ul text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum item no menu</h3>
                <p class="text-gray-500 mb-4">Adicione itens para criar o menu da loja virtual</p>
                <button type="button" 
                        @click="addMenuItem()"
                        class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors">
                    <i class="bi bi-plus mr-2"></i>Adicionar Primeiro Item
                </button>
            </div>
        </div>

        <!-- Configurações Gerais -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center mb-6">
                <i class="bi bi-gear text-primary text-xl mr-3"></i>
                <h3 class="text-lg font-semibold text-gray-900">Configurações Gerais</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="store_bottom_menu_enabled" 
                               value="1"
                               <?php echo e(($settings['store_bottom_menu_enabled'] ?? true) ? 'checked' : ''); ?>

                               class="rounded border-gray-300 text-primary shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                        <span class="ml-2 text-sm font-medium text-gray-700">Menu Inferior Ativo</span>
                    </label>
                    <p class="text-xs text-gray-500 mt-1">Exibe o menu inferior na loja virtual</p>
                </div>

                <div>
                    <label for="store_menu_style" class="block text-sm font-medium text-gray-700 mb-2">
                        Estilo do Menu
                    </label>
                    <select name="store_menu_style" 
                            id="store_menu_style"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="modern" <?php echo e(($settings['store_menu_style'] ?? 'modern') === 'modern' ? 'selected' : ''); ?>>Moderno</option>
                        <option value="classic" <?php echo e(($settings['store_menu_style'] ?? 'modern') === 'classic' ? 'selected' : ''); ?>>Clássico</option>
                        <option value="minimal" <?php echo e(($settings['store_menu_style'] ?? 'modern') === 'minimal' ? 'selected' : ''); ?>>Minimalista</option>
                    </select>
                </div>

                <div>
                    <label for="store_menu_position" class="block text-sm font-medium text-gray-700 mb-2">
                        Posição do Menu
                    </label>
                    <select name="store_menu_position" 
                            id="store_menu_position"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="bottom" <?php echo e(($settings['store_menu_position'] ?? 'bottom') === 'bottom' ? 'selected' : ''); ?>>Inferior</option>
                        <option value="top" <?php echo e(($settings['store_menu_position'] ?? 'bottom') === 'top' ? 'selected' : ''); ?>>Superior</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Preview do Menu -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center mb-6">
                <i class="bi bi-eye text-primary text-xl mr-3"></i>
                <h3 class="text-lg font-semibold text-gray-900">Preview do Menu</h3>
            </div>

            <div class="bg-gray-100 rounded-lg p-6">
                <div class="text-center text-gray-500 mb-4">Preview do Menu (Abaixo do Header)</div>
                
                <!-- Simulação do menu -->
                <div class="rounded-lg shadow-sm p-4" 
                     :class="getMenuStyleClass()">
                    <div class="flex flex-wrap justify-center items-center gap-6">
                        <template x-for="(item, index) in activeMenuItems" :key="index">
                            <div class="flex items-center space-x-2 py-2 px-4 rounded-lg transition-colors duration-200 cursor-pointer"
                                 :class="getMenuItemClass(item, index)">
                                <i :class="item.icon || 'bi bi-link'" class="text-lg"></i>
                                <span class="text-sm font-medium" x-text="item.name"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Lista de ícones Bootstrap (datalist) -->
        <datalist id="bootstrap-icons">
            <option value="bi bi-house">bi bi-house</option>
            <option value="bi bi-house-door">bi bi-house-door</option>
            <option value="bi bi-box">bi bi-box</option>
            <option value="bi bi-bag">bi bi-bag</option>
            <option value="bi bi-bag-check">bi bi-bag-check</option>
            <option value="bi bi-basket">bi bi-basket</option>
            <option value="bi bi-cart">bi bi-cart</option>
            <option value="bi bi-cart2">bi bi-cart2</option>
            <option value="bi bi-cart3">bi bi-cart3</option>
            <option value="bi bi-cart4">bi bi-cart4</option>
            <option value="bi bi-grid">bi bi-grid</option>
            <option value="bi bi-grid-1x2">bi bi-grid-1x2</option>
            <option value="bi bi-grid-3x3-gap">bi bi-grid-3x3-gap</option>
            <option value="bi bi-heart">bi bi-heart</option>
            <option value="bi bi-heart-fill">bi bi-heart-fill</option>
            <option value="bi bi-star">bi bi-star</option>
            <option value="bi bi-star-fill">bi bi-star-fill</option>
            <option value="bi bi-gear">bi bi-gear</option>
            <option value="bi bi-gear-fill">bi bi-gear-fill</option>
            <option value="bi bi-list">bi bi-list</option>
            <option value="bi bi-list-ul">bi bi-list-ul</option>
            <option value="bi bi-list-task">bi bi-list-task</option>
            <option value="bi bi-collection">bi bi-collection</option>
            <option value="bi bi-collection-play">bi bi-collection-play</option>
            <option value="bi bi-clipboard">bi bi-clipboard</option>
            <option value="bi bi-clipboard-check">bi bi-clipboard-check</option>
            <option value="bi bi-tags">bi bi-tags</option>
            <option value="bi bi-tag">bi bi-tag</option>
            <option value="bi bi-search">bi bi-search</option>
            <option value="bi bi-search-heart">bi bi-search-heart</option>
            <option value="bi bi-person">bi bi-person</option>
            <option value="bi bi-people">bi bi-people</option>
            <option value="bi bi-chat">bi bi-chat</option>
            <option value="bi bi-chat-dots">bi bi-chat-dots</option>
            <option value="bi bi-telephone">bi bi-telephone</option>
            <option value="bi bi-whatsapp">bi bi-whatsapp</option>
            <option value="bi bi-instagram">bi bi-instagram</option>
            <option value="bi bi-facebook">bi bi-facebook</option>
            <option value="bi bi-tiktok">bi bi-tiktok</option>
            <option value="bi bi-youtube">bi bi-youtube</option>
            <option value="bi bi-envelope">bi bi-envelope</option>
            <option value="bi bi-truck">bi bi-truck</option>
            <option value="bi bi-bicycle">bi bi-bicycle</option>
            <option value="bi bi-cash">bi bi-cash</option>
            <option value="bi bi-credit-card">bi bi-credit-card</option>
            <option value="bi bi-shield-check">bi bi-shield-check</option>
            <option value="bi bi-link">bi bi-link</option>
            <option value="bi bi-box-seam">bi bi-box-seam</option>
            <option value="bi bi-award">bi bi-award</option>
            <option value="bi bi-trophy">bi bi-trophy</option>
            <option value="bi bi-badge-ad">bi bi-badge-ad</option>
            <option value="bi bi-bag-plus">bi bi-bag-plus</option>
            <option value="bi bi-bag-heart">bi bi-bag-heart</option>
            <option value="bi bi-gem">bi bi-gem</option>
            <option value="bi bi-lightning">bi bi-lightning</option>
            <option value="bi bi-lightning-charge">bi bi-lightning-charge</option>
            <option value="bi bi-rocket">bi bi-rocket</option>
            <option value="bi bi-tools">bi bi-tools</option>
            <option value="bi bi-wrench">bi bi-wrench</option>
            <option value="bi bi-rulers">bi bi-rulers</option>
            <option value="bi bi-ruler">bi bi-ruler</option>
            <option value="bi bi-printer">bi bi-printer</option>
            <option value="bi bi-image">bi bi-image</option>
            <option value="bi bi-images">bi bi-images</option>
            <option value="bi bi-camera">bi bi-camera</option>
            <option value="bi bi-cloud">bi bi-cloud</option>
            <option value="bi bi-cloud-upload">bi bi-cloud-upload</option>
        </datalist>

        <!-- Mapa de Contato -->
        <div class="bg-white rounded-lg shadow p-6 mt-6">
            <div class="flex items-center mb-6">
                <i class="bi bi-map text-primary text-xl mr-3"></i>
                <h3 class="text-lg font-semibold text-gray-900">Mapa da Página de Contato</h3>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label for="contact_map_embed_url" class="block text-sm font-medium text-gray-700 mb-2">
                        URL de Incorporação do Google Maps
                    </label>
                    <textarea 
                        id="contact_map_embed_url" 
                        name="contact_map_embed_url" 
                        rows="3"
                        placeholder="Cole aqui a URL de incorporação do Google Maps (ex: https://www.google.com/maps/embed?pb=...)"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent font-mono text-sm"><?php echo e(old('contact_map_embed_url', $settings['contact_map_embed_url'] ?? '')); ?></textarea>
                    <p class="mt-2 text-sm text-gray-500">
                        <i class="bi bi-info-circle mr-1"></i>
                        Para obter a URL: Acesse <a href="https://www.google.com/maps" target="_blank" class="text-primary hover:underline">Google Maps</a> → 
                        Busque seu endereço → Clique em "Compartilhar" → "Incorporar um mapa" → Copie o src do iframe
                    </p>
                </div>
                
                <?php if(!empty($settings['contact_map_embed_url'] ?? '')): ?>
                <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                    <p class="text-sm font-medium text-gray-700 mb-3">Preview do Mapa:</p>
                    <div class="w-full h-64 rounded-lg overflow-hidden">
                        <iframe 
                            src="<?php echo e($settings['contact_map_embed_url']); ?>" 
                            width="100%" 
                            height="100%" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Perguntas Frequentes (FAQ) -->
        <div class="bg-white rounded-lg shadow p-6 mt-6" x-data="faqManager()">
            <div class="flex items-center mb-6">
                <i class="bi bi-question-circle text-primary text-xl mr-3"></i>
                <h3 class="text-lg font-semibold text-gray-900">Perguntas Frequentes - Página de Contato</h3>
            </div>
            
            <p class="text-sm text-gray-600 mb-6">
                <i class="bi bi-info-circle mr-1"></i>
                Adicione perguntas e respostas que aparecerão na página de contato. Arraste as perguntas para reordenar.
            </p>
            
            <!-- Lista de FAQs -->
            <div id="faq-sortable" class="space-y-4 mb-6">
                <template x-for="(faq, index) in faqs" :key="index">
                    <div class="border border-gray-300 rounded-lg p-4 bg-white hover:border-primary transition-colors">
                        <div class="flex items-start gap-4">
                            <div class="drag-handle flex-shrink-0 text-gray-400 hover:text-primary mt-1 cursor-move">
                                <i class="bi bi-grip-vertical text-xl"></i>
                            </div>
                            
                            <div class="flex-1 space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Pergunta <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        x-model="faq.question"
                                        placeholder="Digite a pergunta..."
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Resposta <span class="text-red-500">*</span>
                                    </label>
                                    <textarea 
                                        x-model="faq.answer"
                                        rows="3"
                                        placeholder="Digite a resposta..."
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"></textarea>
                                </div>
                            </div>
                            
                            <div class="flex-shrink-0">
                                <button 
                                    type="button"
                                    @click="removeFaq(index)"
                                    class="text-red-600 hover:text-red-800 p-2">
                                    <i class="bi bi-trash text-xl"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
                
                <div x-show="faqs.length === 0" class="text-center py-8 text-gray-500">
                    <i class="bi bi-inbox text-4xl mb-2"></i>
                    <p>Nenhuma pergunta adicionada ainda</p>
                </div>
            </div>
            
            <!-- Botão Adicionar -->
            <button 
                type="button"
                @click="addFaq()"
                class="w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg text-gray-600 hover:border-primary hover:text-primary transition-colors flex items-center justify-center gap-2">
                <i class="bi bi-plus-circle text-xl"></i>
                <span class="font-medium">Adicionar Nova Pergunta</span>
            </button>
            
            <!-- Campo hidden para enviar o JSON -->
            <input type="hidden" name="contact_faq_json" x-model="JSON.stringify(faqs)">
        </div>

        <!-- Botões -->
        <div class="flex justify-end space-x-4 mt-6">
            <a href="<?php echo e(route('admin.dashboard')); ?>" 
               class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                <i class="bi bi-arrow-left mr-2"></i>Cancelar
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors">
                <i class="bi bi-check-lg mr-2"></i>Salvar Configurações
            </button>
        </div>
        
    </form>

    <!-- Componente File Manager para Categorias -->
    <?php if (isset($component)) { $__componentOriginalf0b87ed0128a26fa6e98183f0145fdc5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf0b87ed0128a26fa6e98183f0145fdc5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.file-manager-modal','data' => ['modalId' => 'categoryImageManagerModal','title' => 'Selecionar Imagem da Categoria','onSelectCallback' => 'selectCategoryImage']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('file-manager-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['modal-id' => 'categoryImageManagerModal','title' => 'Selecionar Imagem da Categoria','on-select-callback' => 'selectCategoryImage']); ?>
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
    
    <!-- Componente File Manager para Banners Desktop -->
    <?php if (isset($component)) { $__componentOriginalf0b87ed0128a26fa6e98183f0145fdc5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf0b87ed0128a26fa6e98183f0145fdc5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.file-manager-modal','data' => ['modalId' => 'bannerDesktopFileManager','title' => 'Selecionar Banner Desktop','onSelectCallback' => 'selectBannerDesktop']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('file-manager-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['modal-id' => 'bannerDesktopFileManager','title' => 'Selecionar Banner Desktop','on-select-callback' => 'selectBannerDesktop']); ?>
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
    
    <!-- Componente File Manager para Banners Mobile -->
    <?php if (isset($component)) { $__componentOriginalf0b87ed0128a26fa6e98183f0145fdc5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf0b87ed0128a26fa6e98183f0145fdc5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.file-manager-modal','data' => ['modalId' => 'bannerMobileFileManager','title' => 'Selecionar Banner Mobile','onSelectCallback' => 'selectBannerMobile']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('file-manager-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['modal-id' => 'bannerMobileFileManager','title' => 'Selecionar Banner Mobile','on-select-callback' => 'selectBannerMobile']); ?>
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

    <!-- Icon Picker Modal -->
    <div x-show="iconPickerOpen" x-transition class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" @keydown.escape.window="closeIconPicker()">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col" @click.away="closeIconPicker()">
            <!-- Header -->
            <div class="flex items-center justify-between p-4 border-b flex-shrink-0">
                <div class="flex items-center gap-2">
                    <i class="bi bi-grid-3x3-gap text-primary"></i>
                    <h3 class="text-lg font-semibold">Selecionar Ícone</h3>
                </div>
                <button class="p-2 rounded hover:bg-gray-100" @click="closeIconPicker()"><i class="bi bi-x"></i></button>
            </div>
            
            <!-- Search -->
            <div class="p-4 border-b flex-shrink-0">
                <div class="relative">
                    <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" x-model="iconPickerSearch" placeholder="Buscar ícone..." class="w-full pl-10 pr-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                </div>
                <p class="text-xs text-gray-500 mt-1">Dica: pesquise por palavras como "cart", "gear", "tag"...</p>
            </div>
            
            <!-- Icons Grid - Scrollable -->
            <div class="flex-1 overflow-y-auto p-4">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                    <template x-for="(icon, $index) in filteredIcons" :key="icon + '_' + $index">
                        <button type="button" @click="chooseIcon(icon)" class="border rounded-lg p-3 hover:bg-gray-50 flex flex-col items-center text-center transition-colors">
                            <i :class="icon" class="text-2xl mb-2"></i>
                            <span class="text-[11px] text-gray-600 truncate w-full" x-text="icon"></span>
                        </button>
                    </template>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="p-4 border-t flex justify-end flex-shrink-0">
                <button type="button" class="px-4 py-2 border rounded-lg hover:bg-gray-50" @click="closeIconPicker()">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
// Alpine.js component para gerenciar banners
function bannerManager() {
    return {
        uploadedBanners: <?php echo json_encode($bannersForAlpine ?? [], 15, 512) ?>,
        currentBannerIndex: 0,
        
        handleFileUpload(event) {
            const files = Array.from(event.target.files);
            files.forEach(file => {
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.uploadedBanners.push({
                            url: e.target.result,
                            name: file.name,
                            path: null, // Será preenchido no backend
                            isNew: true
                        });
                    };
                    reader.readAsDataURL(file);
                }
            });
        },
        
        removeBanner(index) {
            if (confirm('Deseja remover este banner?')) {
                this.uploadedBanners.splice(index, 1);
                if (this.currentBannerIndex >= this.uploadedBanners.length) {
                    this.currentBannerIndex = Math.max(0, this.uploadedBanners.length - 1);
                }
            }
        },
        
        nextBanner() {
            this.currentBannerIndex = (this.currentBannerIndex + 1) % this.uploadedBanners.length;
        },
        
        previousBanner() {
            this.currentBannerIndex = this.currentBannerIndex === 0 
                ? this.uploadedBanners.length - 1 
                : this.currentBannerIndex - 1;
        },
        
        addBannerFromGallery(url, name) {
            // Extrair caminho correto da URL
            let path = url;
            if (url.includes('/storage/')) {
                // Imagem do storage: remover prefixo /storage/
                path = url.replace(/^.*\/storage\//, '');
            } else if (url.includes('/images/')) {
                // Imagem de public/images: manter caminho completo relativo a public
                path = 'images/' + url.split('/images/')[1];
            }
            
            this.uploadedBanners.push({
                url: url,
                name: name,
                path: path,
                fromGallery: true
            });
        }
    }
}

// Alpine.js component para gerenciar banners mobile
function bannerMobileManager() {
    return {
        uploadedBannersMobile: <?php echo json_encode($bannersMobileForAlpine ?? [], 15, 512) ?>,
        currentBannerMobileIndex: 0,
        
        handleFileUpload(event) {
            const files = Array.from(event.target.files);
            files.forEach(file => {
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.uploadedBannersMobile.push({
                            url: e.target.result,
                            name: file.name,
                            path: null, // Será preenchido no backend
                            isNew: true
                        });
                    };
                    reader.readAsDataURL(file);
                }
            });
        },
        
        removeBannerMobile(index) {
            if (confirm('Deseja remover este banner mobile?')) {
                this.uploadedBannersMobile.splice(index, 1);
                if (this.currentBannerMobileIndex >= this.uploadedBannersMobile.length) {
                    this.currentBannerMobileIndex = Math.max(0, this.uploadedBannersMobile.length - 1);
                }
            }
        },
        
        nextBannerMobile() {
            this.currentBannerMobileIndex = (this.currentBannerMobileIndex + 1) % this.uploadedBannersMobile.length;
        },
        
        previousBannerMobile() {
            this.currentBannerMobileIndex = this.currentBannerMobileIndex === 0 
                ? this.uploadedBannersMobile.length - 1 
                : this.currentBannerMobileIndex - 1;
        },
        
        addBannerMobileFromGallery(url, name) {
            // Extrair caminho correto da URL
            let path = url;
            if (url.includes('/storage/')) {
                // Imagem do storage: remover prefixo /storage/
                path = url.replace(/^.*\/storage\//, '');
            } else if (url.includes('/images/')) {
                // Imagem de public/images: manter caminho completo relativo a public
                path = 'images/' + url.split('/images/')[1];
            }
            
            this.uploadedBannersMobile.push({
                url: url,
                name: name,
                path: path,
                fromGallery: true
            });
        }
    }
}

function homeCategories() {
    return {
        categories: <?php echo json_encode(json_decode($settings['home_categories'] ?? '[]', true), 512) ?>,
        currentCategoryIndex: null,
        
        addCategory() {
            this.categories.push({
                category_id: '',
                title: '',
                image: null,
                order: this.categories.length + 1
            });
        },
        
        removeCategory(index) {
            this.categories.splice(index, 1);
            // Reordenar
            this.categories.forEach((category, idx) => {
                category.order = idx + 1;
            });
        },
        
        openImageManager(index) {
            window.currentCategoryIndexForFileManager = index;
            openFileManagercategoryImageManagerModal();
        },
        
        removeImage(index) {
            this.categories[index].image = null;
        },
        
        getImageUrl(imagePath) {
            if (!imagePath) return '';
            
            // Se já é uma URL completa
            if (imagePath.startsWith('http')) {
                return imagePath;
            }
            
            // Se começa com /, é um caminho absoluto
            if (imagePath.startsWith('/')) {
                return imagePath;
            }
            
            // O disco 'public' está configurado para public_path('images')
            // então usar /images/{path}
            return `<?php echo e(url('images/')); ?>/${imagePath}`;
        },
        
    }
}

function storeSettings() {
    return {
        menuItems: <?php echo json_encode($menuItems, 15, 512) ?>,
        draggedIndex: null,
        iconPickerOpen: false,
        iconPickerSearch: '',
        iconPickerTargetIndex: null,
        allIcons: [
                
                'bi bi-circle',
                'bi bi-circle-fill',
                'bi bi-square',
                'bi bi-square-fill',
                'bi bi-circle',
                'bi bi-circle-fill',
                'bi bi-square',
                'bi bi-square-fill',
                'bi bi-3',
                'bi bi-circle',
                'bi bi-circle-fill',
                'bi bi-square',
                'bi bi-square-fill',
                'bi bi-circle',
                'bi bi-circle-fill',
                'bi bi-square',
                'bi bi-square-fill',
                'bi bi-circle',
                'bi bi-circle-fill',
                'bi bi-square',
                'bi bi-square-fill',
                'bi bi-circle',
                'bi bi-circle-fill',
                'bi bi-square',
                'bi bi-square-fill',
                'bi bi-circle',
                'bi bi-circle-fill',
                'bi bi-square',
                'bi bi-square-fill',
                'bi bi-circle',
                'bi bi-circle-fill',
                'bi bi-square',
                'bi bi-square-fill',
                'bi bi-circle',
                'bi bi-circle-fill',
                'bi bi-square',
                'bi bi-square-fill',
                'bi bi-circle',
                'bi bi-circle-fill',
                'bi bi-square',
                'bi bi-square-fill',
                'bi bi-activity',
                'bi bi-airplane',
                'bi bi-airplane-engines',
                'bi bi-airplane-engines-fill',
                'bi bi-airplane-fill',
                'bi bi-alarm',
                'bi bi-alarm-fill',
                'bi bi-alexa',
                'bi bi-align-bottom',
                'bi bi-align-center',
                'bi bi-align-end',
                'bi bi-align-middle',
                'bi bi-align-start',
                'bi bi-align-top',
                'bi bi-alipay',
                'bi bi-alphabet',
                'bi bi-alphabet-uppercase',
                'bi bi-alt',
                'bi bi-amazon',
                'bi bi-amd',
                'bi bi-android',
                'bi bi-android2',
                'bi bi-anthropic',
                'bi bi-app',
                'bi bi-app-indicator',
                'bi bi-apple',
                'bi bi-apple-music',
                'bi bi-archive',
                'bi bi-archive-fill',
                'bi bi-arrow-90deg-down',
                'bi bi-arrow-90deg-left',
                'bi bi-arrow-90deg-right',
                'bi bi-arrow-90deg-up',
                'bi bi-arrow-bar-down',
                'bi bi-arrow-bar-left',
                'bi bi-arrow-bar-right',
                'bi bi-arrow-bar-up',
                'bi bi-arrow-clockwise',
                'bi bi-arrow-counterclockwise',
                'bi bi-arrow-down',
                'bi bi-arrow-down-circle',
                'bi bi-arrow-down-circle-fill',
                'bi bi-arrow-down-left-circle',
                'bi bi-arrow-down-left-circle-fill',
                'bi bi-arrow-down-left-square',
                'bi bi-arrow-down-left-square-fill',
                'bi bi-arrow-down-right-circle',
                'bi bi-arrow-down-right-circle-fill',
                'bi bi-arrow-down-right-square',
                'bi bi-arrow-down-right-square-fill',
                'bi bi-arrow-down-square',
                'bi bi-arrow-down-square-fill',
                'bi bi-arrow-down-left',
                'bi bi-arrow-down-right',
                'bi bi-arrow-down-short',
                'bi bi-arrow-down-up',
                'bi bi-arrow-left',
                'bi bi-arrow-left-circle',
                'bi bi-arrow-left-circle-fill',
                'bi bi-arrow-left-square',
                'bi bi-arrow-left-square-fill',
                'bi bi-arrow-left-right',
                'bi bi-arrow-left-short',
                'bi bi-arrow-repeat',
                'bi bi-arrow-return-left',
                'bi bi-arrow-return-right',
                'bi bi-arrow-right',
                'bi bi-arrow-right-circle',
                'bi bi-arrow-right-circle-fill',
                'bi bi-arrow-right-square',
                'bi bi-arrow-right-square-fill',
                'bi bi-arrow-right-short',
                'bi bi-arrow-through-heart',
                'bi bi-arrow-through-heart-fill',
                'bi bi-arrow-up',
                'bi bi-arrow-up-circle',
                'bi bi-arrow-up-circle-fill',
                'bi bi-arrow-up-left-circle',
                'bi bi-arrow-up-left-circle-fill',
                'bi bi-arrow-up-left-square',
                'bi bi-arrow-up-left-square-fill',
                'bi bi-arrow-up-right-circle',
                'bi bi-arrow-up-right-circle-fill',
                'bi bi-arrow-up-right-square',
                'bi bi-arrow-up-right-square-fill',
                'bi bi-arrow-up-square',
                'bi bi-arrow-up-square-fill',
                'bi bi-arrow-up-left',
                'bi bi-arrow-up-right',
                'bi bi-arrow-up-short',
                'bi bi-arrows',
                'bi bi-arrows-angle-contract',
                'bi bi-arrows-angle-expand',
                'bi bi-arrows-collapse',
                'bi bi-arrows-collapse-vertical',
                'bi bi-arrows-expand',
                'bi bi-arrows-expand-vertical',
                'bi bi-arrows-fullscreen',
                'bi bi-arrows-move',
                'bi bi-arrows-vertical',
                'bi bi-aspect-ratio',
                'bi bi-aspect-ratio-fill',
                'bi bi-asterisk',
                'bi bi-at',
                'bi bi-award',
                'bi bi-award-fill',
                'bi bi-back',
                'bi bi-backpack',
                'bi bi-backpack-fill',
                'bi bi-backpack2',
                'bi bi-backpack2-fill',
                'bi bi-backpack3',
                'bi bi-backpack3-fill',
                'bi bi-backpack4',
                'bi bi-backpack4-fill',
                'bi bi-backspace',
                'bi bi-backspace-fill',
                'bi bi-backspace-reverse',
                'bi bi-backspace-reverse-fill',
                'bi bi-badge-3d',
                'bi bi-badge-3d-fill',
                'bi bi-badge-4k',
                'bi bi-badge-4k-fill',
                'bi bi-badge-8k',
                'bi bi-badge-8k-fill',
                'bi bi-badge-ad',
                'bi bi-badge-ad-fill',
                'bi bi-badge-ar',
                'bi bi-badge-ar-fill',
                'bi bi-badge-cc',
                'bi bi-badge-cc-fill',
                'bi bi-badge-hd',
                'bi bi-badge-hd-fill',
                'bi bi-badge-sd',
                'bi bi-badge-sd-fill',
                'bi bi-badge-tm',
                'bi bi-badge-tm-fill',
                'bi bi-badge-vo',
                'bi bi-badge-vo-fill',
                'bi bi-badge-vr',
                'bi bi-badge-vr-fill',
                'bi bi-badge-wc',
                'bi bi-badge-wc-fill',
                'bi bi-bag',
                'bi bi-bag-check',
                'bi bi-bag-check-fill',
                'bi bi-bag-dash',
                'bi bi-bag-dash-fill',
                'bi bi-bag-fill',
                'bi bi-bag-heart',
                'bi bi-bag-heart-fill',
                'bi bi-bag-plus',
                'bi bi-bag-plus-fill',
                'bi bi-bag-x',
                'bi bi-bag-x-fill',
                'bi bi-balloon',
                'bi bi-balloon-fill',
                'bi bi-balloon-heart',
                'bi bi-balloon-heart-fill',
                'bi bi-ban',
                'bi bi-ban-fill',
                'bi bi-bandaid',
                'bi bi-bandaid-fill',
                'bi bi-bank',
                'bi bi-bank2',
                'bi bi-bar-chart',
                'bi bi-bar-chart-fill',
                'bi bi-bar-chart-line',
                'bi bi-bar-chart-line-fill',
                'bi bi-bar-chart-steps',
                'bi bi-basket',
                'bi bi-basket-fill',
                'bi bi-basket2',
                'bi bi-basket2-fill',
                'bi bi-basket3',
                'bi bi-basket3-fill',
                'bi bi-battery',
                'bi bi-battery-charging',
                'bi bi-battery-full',
                'bi bi-battery-half',
                'bi bi-battery-low',
                'bi bi-beaker',
                'bi bi-beaker-fill',
                'bi bi-behance',
                'bi bi-bell',
                'bi bi-bell-fill',
                'bi bi-bell-slash',
                'bi bi-bell-slash-fill',
                'bi bi-bezier',
                'bi bi-bezier2',
                'bi bi-bicycle',
                'bi bi-bing',
                'bi bi-binoculars',
                'bi bi-binoculars-fill',
                'bi bi-blockquote-left',
                'bi bi-blockquote-right',
                'bi bi-bluesky',
                'bi bi-bluetooth',
                'bi bi-body-text',
                'bi bi-book',
                'bi bi-book-fill',
                'bi bi-book-half',
                'bi bi-bookmark',
                'bi bi-bookmark-check',
                'bi bi-bookmark-check-fill',
                'bi bi-bookmark-dash',
                'bi bi-bookmark-dash-fill',
                'bi bi-bookmark-fill',
                'bi bi-bookmark-heart',
                'bi bi-bookmark-heart-fill',
                'bi bi-bookmark-plus',
                'bi bi-bookmark-plus-fill',
                'bi bi-bookmark-star',
                'bi bi-bookmark-star-fill',
                'bi bi-bookmark-x',
                'bi bi-bookmark-x-fill',
                'bi bi-bookmarks',
                'bi bi-bookmarks-fill',
                'bi bi-bookshelf',
                'bi bi-boombox',
                'bi bi-boombox-fill',
                'bi bi-bootstrap',
                'bi bi-bootstrap-fill',
                'bi bi-bootstrap-reboot',
                'bi bi-border',
                'bi bi-border-all',
                'bi bi-border-bottom',
                'bi bi-border-center',
                'bi bi-border-inner',
                'bi bi-border-left',
                'bi bi-border-middle',
                'bi bi-border-outer',
                'bi bi-border-right',
                'bi bi-border-style',
                'bi bi-border-top',
                'bi bi-border-width',
                'bi bi-bounding-box',
                'bi bi-bounding-box-circles',
                'bi bi-box',
                'bi bi-box-arrow-down-left',
                'bi bi-box-arrow-down-right',
                'bi bi-box-arrow-down',
                'bi bi-box-arrow-in-down',
                'bi bi-box-arrow-in-down-left',
                'bi bi-box-arrow-in-down-right',
                'bi bi-box-arrow-in-left',
                'bi bi-box-arrow-in-right',
                'bi bi-box-arrow-in-up',
                'bi bi-box-arrow-in-up-left',
                'bi bi-box-arrow-in-up-right',
                'bi bi-box-arrow-left',
                'bi bi-box-arrow-right',
                'bi bi-box-arrow-up',
                'bi bi-box-arrow-up-left',
                'bi bi-box-arrow-up-right',
                'bi bi-box-fill',
                'bi bi-box-seam',
                'bi bi-box-seam-fill',
                'bi bi-box2',
                'bi bi-box2-fill',
                'bi bi-box2-heart',
                'bi bi-box2-heart-fill',
                'bi bi-boxes',
                'bi bi-braces',
                'bi bi-braces-asterisk',
                'bi bi-bricks',
                'bi bi-briefcase',
                'bi bi-briefcase-fill',
                'bi bi-brightness-alt-high',
                'bi bi-brightness-alt-high-fill',
                'bi bi-brightness-alt-low',
                'bi bi-brightness-alt-low-fill',
                'bi bi-brightness-high',
                'bi bi-brightness-high-fill',
                'bi bi-brightness-low',
                'bi bi-brightness-low-fill',
                'bi bi-brilliance',
                'bi bi-broadcast',
                'bi bi-broadcast-pin',
                'bi bi-browser-chrome',
                'bi bi-browser-edge',
                'bi bi-browser-firefox',
                'bi bi-browser-safari',
                'bi bi-brush',
                'bi bi-brush-fill',
                'bi bi-bucket',
                'bi bi-bucket-fill',
                'bi bi-bug',
                'bi bi-bug-fill',
                'bi bi-building',
                'bi bi-building-add',
                'bi bi-building-check',
                'bi bi-building-dash',
                'bi bi-building-down',
                'bi bi-building-exclamation',
                'bi bi-building-fill',
                'bi bi-building-fill-add',
                'bi bi-building-fill-check',
                'bi bi-building-fill-dash',
                'bi bi-building-fill-down',
                'bi bi-building-fill-exclamation',
                'bi bi-building-fill-gear',
                'bi bi-building-fill-lock',
                'bi bi-building-fill-slash',
                'bi bi-building-fill-up',
                'bi bi-building-fill-x',
                'bi bi-building-gear',
                'bi bi-building-lock',
                'bi bi-building-slash',
                'bi bi-building-up',
                'bi bi-building-x',
                'bi bi-buildings',
                'bi bi-buildings-fill',
                'bi bi-bullseye',
                'bi bi-bus-front',
                'bi bi-bus-front-fill',
                'bi bi-c-circle',
                'bi bi-c-circle-fill',
                'bi bi-c-square',
                'bi bi-c-square-fill',
                'bi bi-cake',
                'bi bi-cake-fill',
                'bi bi-cake2',
                'bi bi-cake2-fill',
                'bi bi-calculator',
                'bi bi-calculator-fill',
                'bi bi-calendar',
                'bi bi-calendar-check',
                'bi bi-calendar-check-fill',
                'bi bi-calendar-date',
                'bi bi-calendar-date-fill',
                'bi bi-calendar-day',
                'bi bi-calendar-day-fill',
                'bi bi-calendar-event',
                'bi bi-calendar-event-fill',
                'bi bi-calendar-fill',
                'bi bi-calendar-heart',
                'bi bi-calendar-heart-fill',
                'bi bi-calendar-minus',
                'bi bi-calendar-minus-fill',
                'bi bi-calendar-month',
                'bi bi-calendar-month-fill',
                'bi bi-calendar-plus',
                'bi bi-calendar-plus-fill',
                'bi bi-calendar-range',
                'bi bi-calendar-range-fill',
                'bi bi-calendar-week',
                'bi bi-calendar-week-fill',
                'bi bi-calendar-x',
                'bi bi-calendar-x-fill',
                'bi bi-calendar2',
                'bi bi-calendar2-check',
                'bi bi-calendar2-check-fill',
                'bi bi-calendar2-date',
                'bi bi-calendar2-date-fill',
                'bi bi-calendar2-day',
                'bi bi-calendar2-day-fill',
                'bi bi-calendar2-event',
                'bi bi-calendar2-event-fill',
                'bi bi-calendar2-fill',
                'bi bi-calendar2-heart',
                'bi bi-calendar2-heart-fill',
                'bi bi-calendar2-minus',
                'bi bi-calendar2-minus-fill',
                'bi bi-calendar2-month',
                'bi bi-calendar2-month-fill',
                'bi bi-calendar2-plus',
                'bi bi-calendar2-plus-fill',
                'bi bi-calendar2-range',
                'bi bi-calendar2-range-fill',
                'bi bi-calendar2-week',
                'bi bi-calendar2-week-fill',
                'bi bi-calendar2-x',
                'bi bi-calendar2-x-fill',
                'bi bi-calendar3',
                'bi bi-calendar3-event',
                'bi bi-calendar3-event-fill',
                'bi bi-calendar3-fill',
                'bi bi-calendar3-range',
                'bi bi-calendar3-range-fill',
                'bi bi-calendar3-week',
                'bi bi-calendar3-week-fill',
                'bi bi-calendar4',
                'bi bi-calendar4-event',
                'bi bi-calendar4-range',
                'bi bi-calendar4-week',
                'bi bi-camera',
                'bi bi-camera2',
                'bi bi-camera-fill',
                'bi bi-camera-reels',
                'bi bi-camera-reels-fill',
                'bi bi-camera-video',
                'bi bi-camera-video-fill',
                'bi bi-camera-video-off',
                'bi bi-camera-video-off-fill',
                'bi bi-capslock',
                'bi bi-capslock-fill',
                'bi bi-capsule',
                'bi bi-capsule-pill',
                'bi bi-car-front',
                'bi bi-car-front-fill',
                'bi bi-card-checklist',
                'bi bi-card-heading',
                'bi bi-card-image',
                'bi bi-card-list',
                'bi bi-card-text',
                'bi bi-caret-down',
                'bi bi-caret-down-fill',
                'bi bi-caret-down-square',
                'bi bi-caret-down-square-fill',
                'bi bi-caret-left',
                'bi bi-caret-left-fill',
                'bi bi-caret-left-square',
                'bi bi-caret-left-square-fill',
                'bi bi-caret-right',
                'bi bi-caret-right-fill',
                'bi bi-caret-right-square',
                'bi bi-caret-right-square-fill',
                'bi bi-caret-up',
                'bi bi-caret-up-fill',
                'bi bi-caret-up-square',
                'bi bi-caret-up-square-fill',
                'bi bi-cart',
                'bi bi-cart-check',
                'bi bi-cart-check-fill',
                'bi bi-cart-dash',
                'bi bi-cart-dash-fill',
                'bi bi-cart-fill',
                'bi bi-cart-plus',
                'bi bi-cart-plus-fill',
                'bi bi-cart-x',
                'bi bi-cart-x-fill',
                'bi bi-cart2',
                'bi bi-cart3',
                'bi bi-cart4',
                'bi bi-cash',
                'bi bi-cash-coin',
                'bi bi-cash-stack',
                'bi bi-cassette',
                'bi bi-cassette-fill',
                'bi bi-cast',
                'bi bi-cc-circle',
                'bi bi-cc-circle-fill',
                'bi bi-cc-square',
                'bi bi-cc-square-fill',
                'bi bi-chat',
                'bi bi-chat-dots',
                'bi bi-chat-dots-fill',
                'bi bi-chat-fill',
                'bi bi-chat-heart',
                'bi bi-chat-heart-fill',
                'bi bi-chat-left',
                'bi bi-chat-left-dots',
                'bi bi-chat-left-dots-fill',
                'bi bi-chat-left-fill',
                'bi bi-chat-left-heart',
                'bi bi-chat-left-heart-fill',
                'bi bi-chat-left-quote',
                'bi bi-chat-left-quote-fill',
                'bi bi-chat-left-text',
                'bi bi-chat-left-text-fill',
                'bi bi-chat-quote',
                'bi bi-chat-quote-fill',
                'bi bi-chat-right',
                'bi bi-chat-right-dots',
                'bi bi-chat-right-dots-fill',
                'bi bi-chat-right-fill',
                'bi bi-chat-right-heart',
                'bi bi-chat-right-heart-fill',
                'bi bi-chat-right-quote',
                'bi bi-chat-right-quote-fill',
                'bi bi-chat-right-text',
                'bi bi-chat-right-text-fill',
                'bi bi-chat-square',
                'bi bi-chat-square-dots',
                'bi bi-chat-square-dots-fill',
                'bi bi-chat-square-fill',
                'bi bi-chat-square-heart',
                'bi bi-chat-square-heart-fill',
                'bi bi-chat-square-quote',
                'bi bi-chat-square-quote-fill',
                'bi bi-chat-square-text',
                'bi bi-chat-square-text-fill',
                'bi bi-chat-text',
                'bi bi-chat-text-fill',
                'bi bi-check',
                'bi bi-check-all',
                'bi bi-check-circle',
                'bi bi-check-circle-fill',
                'bi bi-check-lg',
                'bi bi-check-square',
                'bi bi-check-square-fill',
                'bi bi-check2',
                'bi bi-check2-all',
                'bi bi-check2-circle',
                'bi bi-check2-square',
                'bi bi-chevron-bar-contract',
                'bi bi-chevron-bar-down',
                'bi bi-chevron-bar-expand',
                'bi bi-chevron-bar-left',
                'bi bi-chevron-bar-right',
                'bi bi-chevron-bar-up',
                'bi bi-chevron-compact-down',
                'bi bi-chevron-compact-left',
                'bi bi-chevron-compact-right',
                'bi bi-chevron-compact-up',
                'bi bi-chevron-contract',
                'bi bi-chevron-double-down',
                'bi bi-chevron-double-left',
                'bi bi-chevron-double-right',
                'bi bi-chevron-double-up',
                'bi bi-chevron-down',
                'bi bi-chevron-expand',
                'bi bi-chevron-left',
                'bi bi-chevron-right',
                'bi bi-chevron-up',
                'bi bi-circle',
                'bi bi-circle-fill',
                'bi bi-circle-half',
                'bi bi-slash-circle',
                'bi bi-circle-square',
                'bi bi-claude',
                'bi bi-clipboard',
                'bi bi-clipboard-check',
                'bi bi-clipboard-check-fill',
                'bi bi-clipboard-data',
                'bi bi-clipboard-data-fill',
                'bi bi-clipboard-fill',
                'bi bi-clipboard-heart',
                'bi bi-clipboard-heart-fill',
                'bi bi-clipboard-minus',
                'bi bi-clipboard-minus-fill',
                'bi bi-clipboard-plus',
                'bi bi-clipboard-plus-fill',
                'bi bi-clipboard-pulse',
                'bi bi-clipboard-x',
                'bi bi-clipboard-x-fill',
                'bi bi-clipboard2',
                'bi bi-clipboard2-check',
                'bi bi-clipboard2-check-fill',
                'bi bi-clipboard2-data',
                'bi bi-clipboard2-data-fill',
                'bi bi-clipboard2-fill',
                'bi bi-clipboard2-heart',
                'bi bi-clipboard2-heart-fill',
                'bi bi-clipboard2-minus',
                'bi bi-clipboard2-minus-fill',
                'bi bi-clipboard2-plus',
                'bi bi-clipboard2-plus-fill',
                'bi bi-clipboard2-pulse',
                'bi bi-clipboard2-pulse-fill',
                'bi bi-clipboard2-x',
                'bi bi-clipboard2-x-fill',
                'bi bi-clock',
                'bi bi-clock-fill',
                'bi bi-clock-history',
                'bi bi-cloud',
                'bi bi-cloud-arrow-down',
                'bi bi-cloud-arrow-down-fill',
                'bi bi-cloud-arrow-up',
                'bi bi-cloud-arrow-up-fill',
                'bi bi-cloud-check',
                'bi bi-cloud-check-fill',
                'bi bi-cloud-download',
                'bi bi-cloud-download-fill',
                'bi bi-cloud-drizzle',
                'bi bi-cloud-drizzle-fill',
                'bi bi-cloud-fill',
                'bi bi-cloud-fog',
                'bi bi-cloud-fog-fill',
                'bi bi-cloud-fog2',
                'bi bi-cloud-fog2-fill',
                'bi bi-cloud-hail',
                'bi bi-cloud-hail-fill',
                'bi bi-cloud-haze',
                'bi bi-cloud-haze-fill',
                'bi bi-cloud-haze2',
                'bi bi-cloud-haze2-fill',
                'bi bi-cloud-lightning',
                'bi bi-cloud-lightning-fill',
                'bi bi-cloud-lightning-rain',
                'bi bi-cloud-lightning-rain-fill',
                'bi bi-cloud-minus',
                'bi bi-cloud-minus-fill',
                'bi bi-cloud-moon',
                'bi bi-cloud-moon-fill',
                'bi bi-cloud-plus',
                'bi bi-cloud-plus-fill',
                'bi bi-cloud-rain',
                'bi bi-cloud-rain-fill',
                'bi bi-cloud-rain-heavy',
                'bi bi-cloud-rain-heavy-fill',
                'bi bi-cloud-slash',
                'bi bi-cloud-slash-fill',
                'bi bi-cloud-sleet',
                'bi bi-cloud-sleet-fill',
                'bi bi-cloud-snow',
                'bi bi-cloud-snow-fill',
                'bi bi-cloud-sun',
                'bi bi-cloud-sun-fill',
                'bi bi-cloud-upload',
                'bi bi-cloud-upload-fill',
                'bi bi-clouds',
                'bi bi-clouds-fill',
                'bi bi-cloudy',
                'bi bi-cloudy-fill',
                'bi bi-code',
                'bi bi-code-slash',
                'bi bi-code-square',
                'bi bi-coin',
                'bi bi-collection',
                'bi bi-collection-fill',
                'bi bi-collection-play',
                'bi bi-collection-play-fill',
                'bi bi-columns',
                'bi bi-columns-gap',
                'bi bi-command',
                'bi bi-compass',
                'bi bi-compass-fill',
                'bi bi-cone',
                'bi bi-cone-striped',
                'bi bi-controller',
                'bi bi-cookie',
                'bi bi-copy',
                'bi bi-cpu',
                'bi bi-cpu-fill',
                'bi bi-credit-card',
                'bi bi-credit-card-2-back',
                'bi bi-credit-card-2-back-fill',
                'bi bi-credit-card-2-front',
                'bi bi-credit-card-2-front-fill',
                'bi bi-credit-card-fill',
                'bi bi-crop',
                'bi bi-crosshair',
                'bi bi-crosshair2',
                'bi bi-css',
                'bi bi-cup',
                'bi bi-cup-fill',
                'bi bi-cup-hot',
                'bi bi-cup-hot-fill',
                'bi bi-cup-straw',
                'bi bi-currency-bitcoin',
                'bi bi-currency-dollar',
                'bi bi-currency-euro',
                'bi bi-currency-exchange',
                'bi bi-currency-pound',
                'bi bi-currency-rupee',
                'bi bi-currency-yen',
                'bi bi-cursor',
                'bi bi-cursor-fill',
                'bi bi-cursor-text',
                'bi bi-dash',
                'bi bi-dash-circle',
                'bi bi-dash-circle-dotted',
                'bi bi-dash-circle-fill',
                'bi bi-dash-lg',
                'bi bi-dash-square',
                'bi bi-dash-square-dotted',
                'bi bi-dash-square-fill',
                'bi bi-database',
                'bi bi-database-add',
                'bi bi-database-check',
                'bi bi-database-dash',
                'bi bi-database-down',
                'bi bi-database-exclamation',
                'bi bi-database-fill',
                'bi bi-database-fill-add',
                'bi bi-database-fill-check',
                'bi bi-database-fill-dash',
                'bi bi-database-fill-down',
                'bi bi-database-fill-exclamation',
                'bi bi-database-fill-gear',
                'bi bi-database-fill-lock',
                'bi bi-database-fill-slash',
                'bi bi-database-fill-up',
                'bi bi-database-fill-x',
                'bi bi-database-gear',
                'bi bi-database-lock',
                'bi bi-database-slash',
                'bi bi-database-up',
                'bi bi-database-x',
                'bi bi-device-hdd',
                'bi bi-device-hdd-fill',
                'bi bi-device-ssd',
                'bi bi-device-ssd-fill',
                'bi bi-diagram-2',
                'bi bi-diagram-2-fill',
                'bi bi-diagram-3',
                'bi bi-diagram-3-fill',
                'bi bi-diamond',
                'bi bi-diamond-fill',
                'bi bi-diamond-half',
                'bi bi-dice-1',
                'bi bi-dice-1-fill',
                'bi bi-dice-2',
                'bi bi-dice-2-fill',
                'bi bi-dice-3',
                'bi bi-dice-3-fill',
                'bi bi-dice-4',
                'bi bi-dice-4-fill',
                'bi bi-dice-5',
                'bi bi-dice-5-fill',
                'bi bi-dice-6',
                'bi bi-dice-6-fill',
                'bi bi-disc',
                'bi bi-disc-fill',
                'bi bi-discord',
                'bi bi-display',
                'bi bi-display-fill',
                'bi bi-displayport',
                'bi bi-displayport-fill',
                'bi bi-distribute-horizontal',
                'bi bi-distribute-vertical',
                'bi bi-door-closed',
                'bi bi-door-closed-fill',
                'bi bi-door-open',
                'bi bi-door-open-fill',
                'bi bi-dot',
                'bi bi-download',
                'bi bi-dpad',
                'bi bi-dpad-fill',
                'bi bi-dribbble',
                'bi bi-dropbox',
                'bi bi-droplet',
                'bi bi-droplet-fill',
                'bi bi-droplet-half',
                'bi bi-duffle',
                'bi bi-duffle-fill',
                'bi bi-ear',
                'bi bi-ear-fill',
                'bi bi-earbuds',
                'bi bi-easel',
                'bi bi-easel-fill',
                'bi bi-easel2',
                'bi bi-easel2-fill',
                'bi bi-easel3',
                'bi bi-easel3-fill',
                'bi bi-egg',
                'bi bi-egg-fill',
                'bi bi-egg-fried',
                'bi bi-eject',
                'bi bi-eject-fill',
                'bi bi-emoji-angry',
                'bi bi-emoji-angry-fill',
                'bi bi-emoji-astonished',
                'bi bi-emoji-astonished-fill',
                'bi bi-emoji-dizzy',
                'bi bi-emoji-dizzy-fill',
                'bi bi-emoji-expressionless',
                'bi bi-emoji-expressionless-fill',
                'bi bi-emoji-frown',
                'bi bi-emoji-frown-fill',
                'bi bi-emoji-grimace',
                'bi bi-emoji-grimace-fill',
                'bi bi-emoji-grin',
                'bi bi-emoji-grin-fill',
                'bi bi-emoji-heart-eyes',
                'bi bi-emoji-heart-eyes-fill',
                'bi bi-emoji-kiss',
                'bi bi-emoji-kiss-fill',
                'bi bi-emoji-laughing',
                'bi bi-emoji-laughing-fill',
                'bi bi-emoji-neutral',
                'bi bi-emoji-neutral-fill',
                'bi bi-emoji-smile',
                'bi bi-emoji-smile-fill',
                'bi bi-emoji-smile-upside-down',
                'bi bi-emoji-smile-upside-down-fill',
                'bi bi-emoji-sunglasses',
                'bi bi-emoji-sunglasses-fill',
                'bi bi-emoji-surprise',
                'bi bi-emoji-surprise-fill',
                'bi bi-emoji-tear',
                'bi bi-emoji-tear-fill',
                'bi bi-emoji-wink',
                'bi bi-emoji-wink-fill',
                'bi bi-envelope',
                'bi bi-envelope-arrow-down',
                'bi bi-envelope-arrow-down-fill',
                'bi bi-envelope-arrow-up',
                'bi bi-envelope-arrow-up-fill',
                'bi bi-envelope-at',
                'bi bi-envelope-at-fill',
                'bi bi-envelope-check',
                'bi bi-envelope-check-fill',
                'bi bi-envelope-dash',
                'bi bi-envelope-dash-fill',
                'bi bi-envelope-exclamation',
                'bi bi-envelope-exclamation-fill',
                'bi bi-envelope-fill',
                'bi bi-envelope-heart',
                'bi bi-envelope-heart-fill',
                'bi bi-envelope-open',
                'bi bi-envelope-open-fill',
                'bi bi-envelope-open-heart',
                'bi bi-envelope-open-heart-fill',
                'bi bi-envelope-paper',
                'bi bi-envelope-paper-fill',
                'bi bi-envelope-paper-heart',
                'bi bi-envelope-paper-heart-fill',
                'bi bi-envelope-plus',
                'bi bi-envelope-plus-fill',
                'bi bi-envelope-slash',
                'bi bi-envelope-slash-fill',
                'bi bi-envelope-x',
                'bi bi-envelope-x-fill',
                'bi bi-eraser',
                'bi bi-eraser-fill',
                'bi bi-escape',
                'bi bi-ethernet',
                'bi bi-ev-front',
                'bi bi-ev-front-fill',
                'bi bi-ev-station',
                'bi bi-ev-station-fill',
                'bi bi-exclamation',
                'bi bi-exclamation-circle',
                'bi bi-exclamation-circle-fill',
                'bi bi-exclamation-diamond',
                'bi bi-exclamation-diamond-fill',
                'bi bi-exclamation-lg',
                'bi bi-exclamation-octagon',
                'bi bi-exclamation-octagon-fill',
                'bi bi-exclamation-square',
                'bi bi-exclamation-square-fill',
                'bi bi-exclamation-triangle',
                'bi bi-exclamation-triangle-fill',
                'bi bi-exclude',
                'bi bi-explicit',
                'bi bi-explicit-fill',
                'bi bi-exposure',
                'bi bi-eye',
                'bi bi-eye-fill',
                'bi bi-eye-slash',
                'bi bi-eye-slash-fill',
                'bi bi-eyedropper',
                'bi bi-eyeglasses',
                'bi bi-facebook',
                'bi bi-fan',
                'bi bi-fast-forward',
                'bi bi-fast-forward-btn',
                'bi bi-fast-forward-btn-fill',
                'bi bi-fast-forward-circle',
                'bi bi-fast-forward-circle-fill',
                'bi bi-fast-forward-fill',
                'bi bi-feather',
                'bi bi-feather2',
                'bi bi-file',
                'bi bi-file-arrow-down',
                'bi bi-file-arrow-down-fill',
                'bi bi-file-arrow-up',
                'bi bi-file-arrow-up-fill',
                'bi bi-file-bar-graph',
                'bi bi-file-bar-graph-fill',
                'bi bi-file-binary',
                'bi bi-file-binary-fill',
                'bi bi-file-break',
                'bi bi-file-break-fill',
                'bi bi-file-check',
                'bi bi-file-check-fill',
                'bi bi-file-code',
                'bi bi-file-code-fill',
                'bi bi-file-diff',
                'bi bi-file-diff-fill',
                'bi bi-file-earmark',
                'bi bi-file-earmark-arrow-down',
                'bi bi-file-earmark-arrow-down-fill',
                'bi bi-file-earmark-arrow-up',
                'bi bi-file-earmark-arrow-up-fill',
                'bi bi-file-earmark-bar-graph',
                'bi bi-file-earmark-bar-graph-fill',
                'bi bi-file-earmark-binary',
                'bi bi-file-earmark-binary-fill',
                'bi bi-file-earmark-break',
                'bi bi-file-earmark-break-fill',
                'bi bi-file-earmark-check',
                'bi bi-file-earmark-check-fill',
                'bi bi-file-earmark-code',
                'bi bi-file-earmark-code-fill',
                'bi bi-file-earmark-diff',
                'bi bi-file-earmark-diff-fill',
                'bi bi-file-earmark-easel',
                'bi bi-file-earmark-easel-fill',
                'bi bi-file-earmark-excel',
                'bi bi-file-earmark-excel-fill',
                'bi bi-file-earmark-fill',
                'bi bi-file-earmark-font',
                'bi bi-file-earmark-font-fill',
                'bi bi-file-earmark-image',
                'bi bi-file-earmark-image-fill',
                'bi bi-file-earmark-lock',
                'bi bi-file-earmark-lock-fill',
                'bi bi-file-earmark-lock2',
                'bi bi-file-earmark-lock2-fill',
                'bi bi-file-earmark-medical',
                'bi bi-file-earmark-medical-fill',
                'bi bi-file-earmark-minus',
                'bi bi-file-earmark-minus-fill',
                'bi bi-file-earmark-music',
                'bi bi-file-earmark-music-fill',
                'bi bi-file-earmark-pdf',
                'bi bi-file-earmark-pdf-fill',
                'bi bi-file-earmark-person',
                'bi bi-file-earmark-person-fill',
                'bi bi-file-earmark-play',
                'bi bi-file-earmark-play-fill',
                'bi bi-file-earmark-plus',
                'bi bi-file-earmark-plus-fill',
                'bi bi-file-earmark-post',
                'bi bi-file-earmark-post-fill',
                'bi bi-file-earmark-ppt',
                'bi bi-file-earmark-ppt-fill',
                'bi bi-file-earmark-richtext',
                'bi bi-file-earmark-richtext-fill',
                'bi bi-file-earmark-ruled',
                'bi bi-file-earmark-ruled-fill',
                'bi bi-file-earmark-slides',
                'bi bi-file-earmark-slides-fill',
                'bi bi-file-earmark-spreadsheet',
                'bi bi-file-earmark-spreadsheet-fill',
                'bi bi-file-earmark-text',
                'bi bi-file-earmark-text-fill',
                'bi bi-file-earmark-word',
                'bi bi-file-earmark-word-fill',
                'bi bi-file-earmark-x',
                'bi bi-file-earmark-x-fill',
                'bi bi-file-earmark-zip',
                'bi bi-file-earmark-zip-fill',
                'bi bi-file-easel',
                'bi bi-file-easel-fill',
                'bi bi-file-excel',
                'bi bi-file-excel-fill',
                'bi bi-file-fill',
                'bi bi-file-font',
                'bi bi-file-font-fill',
                'bi bi-file-image',
                'bi bi-file-image-fill',
                'bi bi-file-lock',
                'bi bi-file-lock-fill',
                'bi bi-file-lock2',
                'bi bi-file-lock2-fill',
                'bi bi-file-medical',
                'bi bi-file-medical-fill',
                'bi bi-file-minus',
                'bi bi-file-minus-fill',
                'bi bi-file-music',
                'bi bi-file-music-fill',
                'bi bi-file-pdf',
                'bi bi-file-pdf-fill',
                'bi bi-file-person',
                'bi bi-file-person-fill',
                'bi bi-file-play',
                'bi bi-file-play-fill',
                'bi bi-file-plus',
                'bi bi-file-plus-fill',
                'bi bi-file-post',
                'bi bi-file-post-fill',
                'bi bi-file-ppt',
                'bi bi-file-ppt-fill',
                'bi bi-file-richtext',
                'bi bi-file-richtext-fill',
                'bi bi-file-ruled',
                'bi bi-file-ruled-fill',
                'bi bi-file-slides',
                'bi bi-file-slides-fill',
                'bi bi-file-spreadsheet',
                'bi bi-file-spreadsheet-fill',
                'bi bi-file-text',
                'bi bi-file-text-fill',
                'bi bi-file-word',
                'bi bi-file-word-fill',
                'bi bi-file-x',
                'bi bi-file-x-fill',
                'bi bi-file-zip',
                'bi bi-file-zip-fill',
                'bi bi-files',
                'bi bi-files-alt',
                'bi bi-filetype-aac',
                'bi bi-filetype-ai',
                'bi bi-filetype-bmp',
                'bi bi-filetype-cs',
                'bi bi-filetype-css',
                'bi bi-filetype-csv',
                'bi bi-filetype-doc',
                'bi bi-filetype-docx',
                'bi bi-filetype-exe',
                'bi bi-filetype-gif',
                'bi bi-filetype-heic',
                'bi bi-filetype-html',
                'bi bi-filetype-java',
                'bi bi-filetype-jpg',
                'bi bi-filetype-js',
                'bi bi-filetype-json',
                'bi bi-filetype-jsx',
                'bi bi-filetype-key',
                'bi bi-filetype-m4p',
                'bi bi-filetype-md',
                'bi bi-filetype-mdx',
                'bi bi-filetype-mov',
                'bi bi-filetype-mp3',
                'bi bi-filetype-mp4',
                'bi bi-filetype-otf',
                'bi bi-filetype-pdf',
                'bi bi-filetype-php',
                'bi bi-filetype-png',
                'bi bi-filetype-ppt',
                'bi bi-filetype-pptx',
                'bi bi-filetype-psd',
                'bi bi-filetype-py',
                'bi bi-filetype-raw',
                'bi bi-filetype-rb',
                'bi bi-filetype-sass',
                'bi bi-filetype-scss',
                'bi bi-filetype-sh',
                'bi bi-filetype-sql',
                'bi bi-filetype-svg',
                'bi bi-filetype-tiff',
                'bi bi-filetype-tsx',
                'bi bi-filetype-ttf',
                'bi bi-filetype-txt',
                'bi bi-filetype-wav',
                'bi bi-filetype-woff',
                'bi bi-filetype-xls',
                'bi bi-filetype-xlsx',
                'bi bi-filetype-xml',
                'bi bi-filetype-yml',
                'bi bi-film',
                'bi bi-filter',
                'bi bi-filter-circle',
                'bi bi-filter-circle-fill',
                'bi bi-filter-left',
                'bi bi-filter-right',
                'bi bi-filter-square',
                'bi bi-filter-square-fill',
                'bi bi-fingerprint',
                'bi bi-fire',
                'bi bi-flag',
                'bi bi-flag-fill',
                'bi bi-flask',
                'bi bi-flask-fill',
                'bi bi-flask-florence',
                'bi bi-flask-florence-fill',
                'bi bi-floppy',
                'bi bi-floppy-fill',
                'bi bi-floppy2',
                'bi bi-floppy2-fill',
                'bi bi-flower1',
                'bi bi-flower2',
                'bi bi-flower3',
                'bi bi-folder',
                'bi bi-folder-check',
                'bi bi-folder-fill',
                'bi bi-folder-minus',
                'bi bi-folder-plus',
                'bi bi-folder-symlink',
                'bi bi-folder-symlink-fill',
                'bi bi-folder-x',
                'bi bi-folder2',
                'bi bi-folder2-open',
                'bi bi-fonts',
                'bi bi-fork-knife',
                'bi bi-forward',
                'bi bi-forward-fill',
                'bi bi-front',
                'bi bi-fuel-pump',
                'bi bi-fuel-pump-diesel',
                'bi bi-fuel-pump-diesel-fill',
                'bi bi-fuel-pump-fill',
                'bi bi-fullscreen',
                'bi bi-fullscreen-exit',
                'bi bi-funnel',
                'bi bi-funnel-fill',
                'bi bi-gear',
                'bi bi-gear-fill',
                'bi bi-gear-wide',
                'bi bi-gear-wide-connected',
                'bi bi-gem',
                'bi bi-gender-ambiguous',
                'bi bi-gender-female',
                'bi bi-gender-male',
                'bi bi-gender-neuter',
                'bi bi-gender-trans',
                'bi bi-geo',
                'bi bi-geo-alt',
                'bi bi-geo-alt-fill',
                'bi bi-geo-fill',
                'bi bi-gift',
                'bi bi-gift-fill',
                'bi bi-git',
                'bi bi-github',
                'bi bi-gitlab',
                'bi bi-globe',
                'bi bi-globe-americas',
                'bi bi-globe-americas-fill',
                'bi bi-globe-asia-australia',
                'bi bi-globe-asia-australia-fill',
                'bi bi-globe-central-south-asia',
                'bi bi-globe-central-south-asia-fill',
                'bi bi-globe-europe-africa',
                'bi bi-globe-europe-africa-fill',
                'bi bi-globe2',
                'bi bi-google',
                'bi bi-google-play',
                'bi bi-gpu-card',
                'bi bi-graph-down',
                'bi bi-graph-down-arrow',
                'bi bi-graph-up',
                'bi bi-graph-up-arrow',
                'bi bi-grid',
                'bi bi-grid-1x2',
                'bi bi-grid-1x2-fill',
                'bi bi-grid-3x2',
                'bi bi-grid-3x2-gap',
                'bi bi-grid-3x2-gap-fill',
                'bi bi-grid-3x3',
                'bi bi-grid-3x3-gap',
                'bi bi-grid-3x3-gap-fill',
                'bi bi-grid-fill',
                'bi bi-grip-horizontal',
                'bi bi-grip-vertical',
                'bi bi-h-circle',
                'bi bi-h-circle-fill',
                'bi bi-h-square',
                'bi bi-h-square-fill',
                'bi bi-hammer',
                'bi bi-hand-index',
                'bi bi-hand-index-fill',
                'bi bi-hand-index-thumb',
                'bi bi-hand-index-thumb-fill',
                'bi bi-hand-thumbs-down',
                'bi bi-hand-thumbs-down-fill',
                'bi bi-hand-thumbs-up',
                'bi bi-hand-thumbs-up-fill',
                'bi bi-handbag',
                'bi bi-handbag-fill',
                'bi bi-hash',
                'bi bi-hdd',
                'bi bi-hdd-fill',
                'bi bi-hdd-network',
                'bi bi-hdd-network-fill',
                'bi bi-hdd-rack',
                'bi bi-hdd-rack-fill',
                'bi bi-hdd-stack',
                'bi bi-hdd-stack-fill',
                'bi bi-hdmi',
                'bi bi-hdmi-fill',
                'bi bi-headphones',
                'bi bi-headset',
                'bi bi-headset-vr',
                'bi bi-heart',
                'bi bi-heart-arrow',
                'bi bi-heart-fill',
                'bi bi-heart-half',
                'bi bi-heart-pulse',
                'bi bi-heart-pulse-fill',
                'bi bi-heartbreak',
                'bi bi-heartbreak-fill',
                'bi bi-hearts',
                'bi bi-heptagon',
                'bi bi-heptagon-fill',
                'bi bi-heptagon-half',
                'bi bi-hexagon',
                'bi bi-hexagon-fill',
                'bi bi-hexagon-half',
                'bi bi-highlighter',
                'bi bi-highlights',
                'bi bi-hospital',
                'bi bi-hospital-fill',
                'bi bi-hourglass',
                'bi bi-hourglass-bottom',
                'bi bi-hourglass-split',
                'bi bi-hourglass-top',
                'bi bi-house',
                'bi bi-house-add',
                'bi bi-house-add-fill',
                'bi bi-house-check',
                'bi bi-house-check-fill',
                'bi bi-house-dash',
                'bi bi-house-dash-fill',
                'bi bi-house-door',
                'bi bi-house-door-fill',
                'bi bi-house-down',
                'bi bi-house-down-fill',
                'bi bi-house-exclamation',
                'bi bi-house-exclamation-fill',
                'bi bi-house-fill',
                'bi bi-house-gear',
                'bi bi-house-gear-fill',
                'bi bi-house-heart',
                'bi bi-house-heart-fill',
                'bi bi-house-lock',
                'bi bi-house-lock-fill',
                'bi bi-house-slash',
                'bi bi-house-slash-fill',
                'bi bi-house-up',
                'bi bi-house-up-fill',
                'bi bi-house-x',
                'bi bi-house-x-fill',
                'bi bi-houses',
                'bi bi-houses-fill',
                'bi bi-hr',
                'bi bi-hurricane',
                'bi bi-hypnotize',
                'bi bi-image',
                'bi bi-image-alt',
                'bi bi-image-fill',
                'bi bi-images',
                'bi bi-inbox',
                'bi bi-inbox-fill',
                'bi bi-inboxes-fill',
                'bi bi-inboxes',
                'bi bi-incognito',
                'bi bi-indent',
                'bi bi-infinity',
                'bi bi-info',
                'bi bi-info-circle',
                'bi bi-info-circle-fill',
                'bi bi-info-lg',
                'bi bi-info-square',
                'bi bi-info-square-fill',
                'bi bi-input-cursor',
                'bi bi-input-cursor-text',
                'bi bi-instagram',
                'bi bi-intersect',
                'bi bi-javascript',
                'bi bi-journal',
                'bi bi-journal-album',
                'bi bi-journal-arrow-down',
                'bi bi-journal-arrow-up',
                'bi bi-journal-bookmark',
                'bi bi-journal-bookmark-fill',
                'bi bi-journal-check',
                'bi bi-journal-code',
                'bi bi-journal-medical',
                'bi bi-journal-minus',
                'bi bi-journal-plus',
                'bi bi-journal-richtext',
                'bi bi-journal-text',
                'bi bi-journal-x',
                'bi bi-journals',
                'bi bi-joystick',
                'bi bi-justify',
                'bi bi-justify-left',
                'bi bi-justify-right',
                'bi bi-kanban',
                'bi bi-kanban-fill',
                'bi bi-key',
                'bi bi-key-fill',
                'bi bi-keyboard',
                'bi bi-keyboard-fill',
                'bi bi-ladder',
                'bi bi-lamp',
                'bi bi-lamp-fill',
                'bi bi-laptop',
                'bi bi-laptop-fill',
                'bi bi-layer-backward',
                'bi bi-layer-forward',
                'bi bi-layers',
                'bi bi-layers-fill',
                'bi bi-layers-half',
                'bi bi-layout-sidebar',
                'bi bi-layout-sidebar-inset-reverse',
                'bi bi-layout-sidebar-inset',
                'bi bi-layout-sidebar-reverse',
                'bi bi-layout-split',
                'bi bi-layout-text-sidebar',
                'bi bi-layout-text-sidebar-reverse',
                'bi bi-layout-text-window',
                'bi bi-layout-text-window-reverse',
                'bi bi-layout-three-columns',
                'bi bi-layout-wtf',
                'bi bi-leaf',
                'bi bi-leaf-fill',
                'bi bi-life-preserver',
                'bi bi-lightbulb',
                'bi bi-lightbulb-fill',
                'bi bi-lightbulb-off',
                'bi bi-lightbulb-off-fill',
                'bi bi-lightning',
                'bi bi-lightning-charge',
                'bi bi-lightning-charge-fill',
                'bi bi-lightning-fill',
                'bi bi-line',
                'bi bi-link',
                'bi bi-link-45deg',
                'bi bi-linkedin',
                'bi bi-list',
                'bi bi-list-check',
                'bi bi-list-columns',
                'bi bi-list-columns-reverse',
                'bi bi-list-nested',
                'bi bi-list-ol',
                'bi bi-list-stars',
                'bi bi-list-task',
                'bi bi-list-ul',
                'bi bi-lock',
                'bi bi-lock-fill',
                'bi bi-luggage',
                'bi bi-luggage-fill',
                'bi bi-lungs',
                'bi bi-lungs-fill',
                'bi bi-magic',
                'bi bi-magnet',
                'bi bi-magnet-fill',
                'bi bi-mailbox',
                'bi bi-mailbox-flag',
                'bi bi-mailbox2',
                'bi bi-mailbox2-flag',
                'bi bi-map',
                'bi bi-map-fill',
                'bi bi-markdown',
                'bi bi-markdown-fill',
                'bi bi-marker-tip',
                'bi bi-mask',
                'bi bi-mastodon',
                'bi bi-measuring-cup',
                'bi bi-measuring-cup-fill',
                'bi bi-medium',
                'bi bi-megaphone',
                'bi bi-megaphone-fill',
                'bi bi-memory',
                'bi bi-menu-app',
                'bi bi-menu-app-fill',
                'bi bi-menu-button',
                'bi bi-menu-button-fill',
                'bi bi-menu-button-wide',
                'bi bi-menu-button-wide-fill',
                'bi bi-menu-down',
                'bi bi-menu-up',
                'bi bi-messenger',
                'bi bi-meta',
                'bi bi-mic',
                'bi bi-mic-fill',
                'bi bi-mic-mute',
                'bi bi-mic-mute-fill',
                'bi bi-microsoft',
                'bi bi-microsoft-teams',
                'bi bi-minecart',
                'bi bi-minecart-loaded',
                'bi bi-modem',
                'bi bi-modem-fill',
                'bi bi-moisture',
                'bi bi-moon',
                'bi bi-moon-fill',
                'bi bi-moon-stars',
                'bi bi-moon-stars-fill',
                'bi bi-mortarboard',
                'bi bi-mortarboard-fill',
                'bi bi-motherboard',
                'bi bi-motherboard-fill',
                'bi bi-mouse',
                'bi bi-mouse-fill',
                'bi bi-mouse2',
                'bi bi-mouse2-fill',
                'bi bi-mouse3',
                'bi bi-mouse3-fill',
                'bi bi-music-note',
                'bi bi-music-note-beamed',
                'bi bi-music-note-list',
                'bi bi-music-player',
                'bi bi-music-player-fill',
                'bi bi-newspaper',
                'bi bi-nintendo-switch',
                'bi bi-node-minus',
                'bi bi-node-minus-fill',
                'bi bi-node-plus',
                'bi bi-node-plus-fill',
                'bi bi-noise-reduction',
                'bi bi-nut',
                'bi bi-nut-fill',
                'bi bi-nvidia',
                'bi bi-nvme',
                'bi bi-nvme-fill',
                'bi bi-octagon',
                'bi bi-octagon-fill',
                'bi bi-octagon-half',
                'bi bi-openai',
                'bi bi-opencollective',
                'bi bi-optical-audio',
                'bi bi-optical-audio-fill',
                'bi bi-option',
                'bi bi-outlet',
                'bi bi-p-circle',
                'bi bi-p-circle-fill',
                'bi bi-p-square',
                'bi bi-p-square-fill',
                'bi bi-paint-bucket',
                'bi bi-palette',
                'bi bi-palette-fill',
                'bi bi-palette2',
                'bi bi-paperclip',
                'bi bi-paragraph',
                'bi bi-pass',
                'bi bi-pass-fill',
                'bi bi-passport',
                'bi bi-passport-fill',
                'bi bi-patch-check',
                'bi bi-patch-check-fill',
                'bi bi-patch-exclamation',
                'bi bi-patch-exclamation-fill',
                'bi bi-patch-minus',
                'bi bi-patch-minus-fill',
                'bi bi-patch-plus',
                'bi bi-patch-plus-fill',
                'bi bi-patch-question',
                'bi bi-patch-question-fill',
                'bi bi-pause',
                'bi bi-pause-btn',
                'bi bi-pause-btn-fill',
                'bi bi-pause-circle',
                'bi bi-pause-circle-fill',
                'bi bi-pause-fill',
                'bi bi-paypal',
                'bi bi-pc',
                'bi bi-pc-display',
                'bi bi-pc-display-horizontal',
                'bi bi-pc-horizontal',
                'bi bi-pci-card',
                'bi bi-pci-card-network',
                'bi bi-pci-card-sound',
                'bi bi-peace',
                'bi bi-peace-fill',
                'bi bi-pen',
                'bi bi-pen-fill',
                'bi bi-pencil',
                'bi bi-pencil-fill',
                'bi bi-pencil-square',
                'bi bi-pentagon',
                'bi bi-pentagon-fill',
                'bi bi-pentagon-half',
                'bi bi-people',
                'bi bi-person-circle',
                'bi bi-people-fill',
                'bi bi-percent',
                'bi bi-perplexity',
                'bi bi-person',
                'bi bi-person-add',
                'bi bi-person-arms-up',
                'bi bi-person-badge',
                'bi bi-person-badge-fill',
                'bi bi-person-bounding-box',
                'bi bi-person-check',
                'bi bi-person-check-fill',
                'bi bi-person-dash',
                'bi bi-person-dash-fill',
                'bi bi-person-down',
                'bi bi-person-exclamation',
                'bi bi-person-fill',
                'bi bi-person-fill-add',
                'bi bi-person-fill-check',
                'bi bi-person-fill-dash',
                'bi bi-person-fill-down',
                'bi bi-person-fill-exclamation',
                'bi bi-person-fill-gear',
                'bi bi-person-fill-lock',
                'bi bi-person-fill-slash',
                'bi bi-person-fill-up',
                'bi bi-person-fill-x',
                'bi bi-person-gear',
                'bi bi-person-heart',
                'bi bi-person-hearts',
                'bi bi-person-lines-fill',
                'bi bi-person-lock',
                'bi bi-person-plus',
                'bi bi-person-plus-fill',
                'bi bi-person-raised-hand',
                'bi bi-person-rolodex',
                'bi bi-person-slash',
                'bi bi-person-square',
                'bi bi-person-standing',
                'bi bi-person-standing-dress',
                'bi bi-person-up',
                'bi bi-person-vcard',
                'bi bi-person-vcard-fill',
                'bi bi-person-video',
                'bi bi-person-video2',
                'bi bi-person-video3',
                'bi bi-person-walking',
                'bi bi-person-wheelchair',
                'bi bi-person-workspace',
                'bi bi-person-x',
                'bi bi-person-x-fill',
                'bi bi-phone',
                'bi bi-phone-fill',
                'bi bi-phone-flip',
                'bi bi-phone-landscape',
                'bi bi-phone-landscape-fill',
                'bi bi-phone-vibrate',
                'bi bi-phone-vibrate-fill',
                'bi bi-pie-chart',
                'bi bi-pie-chart-fill',
                'bi bi-piggy-bank',
                'bi bi-piggy-bank-fill',
                'bi bi-pin',
                'bi bi-pin-angle',
                'bi bi-pin-angle-fill',
                
                'bi bi-pin-fill',
                'bi bi-pin-map',
                'bi bi-pin-map-fill',
                'bi bi-pinterest',
                'bi bi-pip',
                'bi bi-pip-fill',
                'bi bi-play',
                'bi bi-play-btn',
                'bi bi-play-btn-fill',
                'bi bi-play-circle',
                'bi bi-play-circle-fill',
                'bi bi-play-fill',
                'bi bi-playstation',
                'bi bi-plug',
                'bi bi-plug-fill',
                'bi bi-plugin',
                'bi bi-plus',
                'bi bi-plus-circle',
                'bi bi-plus-circle-dotted',
                'bi bi-plus-circle-fill',
                'bi bi-plus-lg',
                'bi bi-plus-slash-minus',
                'bi bi-plus-square',
                'bi bi-plus-square-dotted',
                'bi bi-plus-square-fill',
                'bi bi-postage',
                'bi bi-postage-fill',
                'bi bi-postage-heart',
                'bi bi-postage-heart-fill',
                'bi bi-postcard',
                'bi bi-postcard-fill',
                'bi bi-postcard-heart',
                'bi bi-postcard-heart-fill',
                'bi bi-power',
                'bi bi-prescription',
                'bi bi-prescription2',
                'bi bi-printer',
                'bi bi-printer-fill',
                'bi bi-projector',
                'bi bi-projector-fill',
                'bi bi-puzzle',
                'bi bi-puzzle-fill',
                'bi bi-qr-code',
                'bi bi-qr-code-scan',
                'bi bi-question',
                'bi bi-question-circle',
                'bi bi-question-diamond',
                'bi bi-question-diamond-fill',
                'bi bi-question-circle-fill',
                'bi bi-question-lg',
                'bi bi-question-octagon',
                'bi bi-question-octagon-fill',
                'bi bi-question-square',
                'bi bi-question-square-fill',
                'bi bi-quora',
                'bi bi-quote',
                'bi bi-r-circle',
                'bi bi-r-circle-fill',
                'bi bi-r-square',
                'bi bi-r-square-fill',
                'bi bi-radar',
                'bi bi-radioactive',
                'bi bi-rainbow',
                'bi bi-receipt',
                'bi bi-receipt-cutoff',
                'bi bi-reception-0',
                'bi bi-reception-1',
                'bi bi-reception-2',
                'bi bi-reception-3',
                'bi bi-reception-4',
                'bi bi-record',
                'bi bi-record-btn',
                'bi bi-record-btn-fill',
                'bi bi-record-circle',
                'bi bi-record-circle-fill',
                'bi bi-record-fill',
                'bi bi-record2',
                'bi bi-record2-fill',
                'bi bi-recycle',
                'bi bi-reddit',
                'bi bi-regex',
                'bi bi-repeat',
                'bi bi-repeat-1',
                'bi bi-reply',
                'bi bi-reply-all',
                'bi bi-reply-all-fill',
                'bi bi-reply-fill',
                'bi bi-rewind',
                'bi bi-rewind-btn',
                'bi bi-rewind-btn-fill',
                'bi bi-rewind-circle',
                'bi bi-rewind-circle-fill',
                'bi bi-rewind-fill',
                'bi bi-robot',
                'bi bi-rocket',
                'bi bi-rocket-fill',
                'bi bi-rocket-takeoff',
                'bi bi-rocket-takeoff-fill',
                'bi bi-router',
                'bi bi-router-fill',
                'bi bi-rss',
                'bi bi-rss-fill',
                'bi bi-rulers',
                'bi bi-safe',
                'bi bi-safe-fill',
                'bi bi-safe2',
                'bi bi-safe2-fill',
                'bi bi-save',
                'bi bi-save-fill',
                'bi bi-save2',
                'bi bi-save2-fill',
                'bi bi-scissors',
                'bi bi-scooter',
                'bi bi-screwdriver',
                'bi bi-sd-card',
                'bi bi-sd-card-fill',
                'bi bi-search',
                'bi bi-search-heart',
                'bi bi-search-heart-fill',
                'bi bi-segmented-nav',
                'bi bi-send',
                'bi bi-send-arrow-down',
                'bi bi-send-arrow-down-fill',
                'bi bi-send-arrow-up',
                'bi bi-send-arrow-up-fill',
                'bi bi-send-check',
                'bi bi-send-check-fill',
                'bi bi-send-dash',
                'bi bi-send-dash-fill',
                'bi bi-send-exclamation',
                'bi bi-send-exclamation-fill',
                'bi bi-send-fill',
                'bi bi-send-plus',
                'bi bi-send-plus-fill',
                'bi bi-send-slash',
                'bi bi-send-slash-fill',
                'bi bi-send-x',
                'bi bi-send-x-fill',
                'bi bi-server',
                'bi bi-shadows',
                'bi bi-share',
                'bi bi-share-fill',
                'bi bi-shield',
                'bi bi-shield-check',
                'bi bi-shield-exclamation',
                'bi bi-shield-fill',
                'bi bi-shield-fill-check',
                'bi bi-shield-fill-exclamation',
                'bi bi-shield-fill-minus',
                'bi bi-shield-fill-plus',
                'bi bi-shield-fill-x',
                'bi bi-shield-lock',
                'bi bi-shield-lock-fill',
                'bi bi-shield-minus',
                'bi bi-shield-plus',
                'bi bi-shield-shaded',
                'bi bi-shield-slash',
                'bi bi-shield-slash-fill',
                'bi bi-shield-x',
                'bi bi-shift',
                'bi bi-shift-fill',
                'bi bi-shop',
                'bi bi-shop-window',
                'bi bi-shuffle',
                'bi bi-sign-dead-end',
                'bi bi-sign-dead-end-fill',
                'bi bi-sign-do-not-enter',
                'bi bi-sign-do-not-enter-fill',
                'bi bi-sign-intersection',
                'bi bi-sign-intersection-fill',
                'bi bi-sign-intersection-side',
                'bi bi-sign-intersection-side-fill',
                'bi bi-sign-intersection-t',
                'bi bi-sign-intersection-t-fill',
                'bi bi-sign-intersection-y',
                'bi bi-sign-intersection-y-fill',
                'bi bi-sign-merge-left',
                'bi bi-sign-merge-left-fill',
                'bi bi-sign-merge-right',
                'bi bi-sign-merge-right-fill',
                'bi bi-sign-no-left-turn',
                'bi bi-sign-no-left-turn-fill',
                'bi bi-sign-no-parking',
                'bi bi-sign-no-parking-fill',
                'bi bi-sign-no-right-turn',
                'bi bi-sign-no-right-turn-fill',
                'bi bi-sign-railroad',
                'bi bi-sign-railroad-fill',
                'bi bi-sign-stop',
                'bi bi-sign-stop-fill',
                'bi bi-sign-stop-lights',
                'bi bi-sign-stop-lights-fill',
                'bi bi-sign-turn-left',
                'bi bi-sign-turn-left-fill',
                'bi bi-sign-turn-right',
                'bi bi-sign-turn-right-fill',
                'bi bi-sign-turn-slight-left',
                'bi bi-sign-turn-slight-left-fill',
                'bi bi-sign-turn-slight-right',
                'bi bi-sign-turn-slight-right-fill',
                'bi bi-sign-yield',
                'bi bi-sign-yield-fill',
                'bi bi-signal',
                'bi bi-signpost',
                'bi bi-signpost-2',
                'bi bi-signpost-2-fill',
                'bi bi-signpost-fill',
                'bi bi-signpost-split',
                'bi bi-signpost-split-fill',
                'bi bi-sim',
                'bi bi-sim-fill',
                'bi bi-sim-slash',
                'bi bi-sim-slash-fill',
                'bi bi-sina-weibo',
                'bi bi-skip-backward',
                'bi bi-skip-backward-btn',
                'bi bi-skip-backward-btn-fill',
                'bi bi-skip-backward-circle',
                'bi bi-skip-backward-circle-fill',
                'bi bi-skip-backward-fill',
                'bi bi-skip-end',
                'bi bi-skip-end-btn',
                'bi bi-skip-end-btn-fill',
                'bi bi-skip-end-circle',
                'bi bi-skip-end-circle-fill',
                'bi bi-skip-end-fill',
                'bi bi-skip-forward',
                'bi bi-skip-forward-btn',
                'bi bi-skip-forward-btn-fill',
                'bi bi-skip-forward-circle',
                'bi bi-skip-forward-circle-fill',
                'bi bi-skip-forward-fill',
                'bi bi-skip-start',
                'bi bi-skip-start-btn',
                'bi bi-skip-start-btn-fill',
                'bi bi-skip-start-circle',
                'bi bi-skip-start-circle-fill',
                'bi bi-skip-start-fill',
                'bi bi-skype',
                'bi bi-slack',
                'bi bi-slash',
                'bi bi-slash-circle-fill',
                'bi bi-slash-lg',
                'bi bi-slash-square',
                'bi bi-slash-square-fill',
                'bi bi-sliders',
                'bi bi-sliders2',
                'bi bi-sliders2-vertical',
                'bi bi-smartwatch',
                'bi bi-snapchat',
                'bi bi-snow',
                'bi bi-snow2',
                'bi bi-snow3',
                'bi bi-sort-alpha-down',
                'bi bi-sort-alpha-down-alt',
                'bi bi-sort-alpha-up',
                'bi bi-sort-alpha-up-alt',
                'bi bi-sort-down',
                'bi bi-sort-down-alt',
                'bi bi-sort-numeric-down',
                'bi bi-sort-numeric-down-alt',
                'bi bi-sort-numeric-up',
                'bi bi-sort-numeric-up-alt',
                'bi bi-sort-up',
                'bi bi-sort-up-alt',
                'bi bi-soundwave',
                'bi bi-sourceforge',
                'bi bi-speaker',
                'bi bi-speaker-fill',
                'bi bi-speedometer',
                'bi bi-speedometer2',
                'bi bi-spellcheck',
                'bi bi-spotify',
                'bi bi-square',
                'bi bi-square-fill',
                'bi bi-square-half',
                'bi bi-stack',
                'bi bi-stack-overflow',
                'bi bi-star',
                'bi bi-star-fill',
                'bi bi-star-half',
                'bi bi-stars',
                'bi bi-steam',
                'bi bi-stickies',
                'bi bi-stickies-fill',
                'bi bi-sticky',
                'bi bi-sticky-fill',
                'bi bi-stop',
                'bi bi-stop-btn',
                'bi bi-stop-btn-fill',
                'bi bi-stop-circle',
                'bi bi-stop-circle-fill',
                'bi bi-stop-fill',
                'bi bi-stoplights',
                'bi bi-stoplights-fill',
                'bi bi-stopwatch',
                'bi bi-stopwatch-fill',
                'bi bi-strava',
                'bi bi-stripe',
                'bi bi-subscript',
                'bi bi-substack',
                'bi bi-subtract',
                'bi bi-suit-club',
                'bi bi-suit-club-fill',
                'bi bi-suit-diamond',
                'bi bi-suit-diamond-fill',
                'bi bi-suit-heart',
                'bi bi-suit-heart-fill',
                'bi bi-suit-spade',
                'bi bi-suit-spade-fill',
                'bi bi-suitcase',
                'bi bi-suitcase-fill',
                'bi bi-suitcase-lg',
                'bi bi-suitcase-lg-fill',
                'bi bi-suitcase2',
                'bi bi-suitcase2-fill',
                'bi bi-sun',
                'bi bi-sun-fill',
                'bi bi-sunglasses',
                'bi bi-sunrise',
                'bi bi-sunrise-fill',
                'bi bi-sunset',
                'bi bi-sunset-fill',
                'bi bi-superscript',
                'bi bi-symmetry-horizontal',
                'bi bi-symmetry-vertical',
                'bi bi-table',
                'bi bi-tablet',
                'bi bi-tablet-fill',
                'bi bi-tablet-landscape',
                'bi bi-tablet-landscape-fill',
                'bi bi-tag',
                'bi bi-tag-fill',
                'bi bi-tags',
                'bi bi-tags-fill',
                'bi bi-taxi-front',
                'bi bi-taxi-front-fill',
                'bi bi-telegram',
                'bi bi-telephone',
                'bi bi-telephone-fill',
                'bi bi-telephone-forward',
                'bi bi-telephone-forward-fill',
                'bi bi-telephone-inbound',
                'bi bi-telephone-inbound-fill',
                'bi bi-telephone-minus',
                'bi bi-telephone-minus-fill',
                'bi bi-telephone-outbound',
                'bi bi-telephone-outbound-fill',
                'bi bi-telephone-plus',
                'bi bi-telephone-plus-fill',
                'bi bi-telephone-x',
                'bi bi-telephone-x-fill',
                'bi bi-tencent-qq',
                'bi bi-terminal',
                'bi bi-terminal-dash',
                'bi bi-terminal-fill',
                'bi bi-terminal-plus',
                'bi bi-terminal-split',
                'bi bi-terminal-x',
                'bi bi-text-center',
                'bi bi-text-indent-left',
                'bi bi-text-indent-right',
                'bi bi-text-left',
                'bi bi-text-paragraph',
                'bi bi-text-right',
                'bi bi-text-wrap',
                'bi bi-textarea',
                'bi bi-textarea-resize',
                'bi bi-textarea-t',
                'bi bi-thermometer',
                'bi bi-thermometer-half',
                'bi bi-thermometer-high',
                'bi bi-thermometer-low',
                'bi bi-thermometer-snow',
                'bi bi-thermometer-sun',
                'bi bi-threads',
                'bi bi-threads-fill',
                'bi bi-three-dots',
                'bi bi-three-dots-vertical',
                'bi bi-thunderbolt',
                'bi bi-thunderbolt-fill',
                'bi bi-ticket',
                'bi bi-ticket-detailed',
                'bi bi-ticket-detailed-fill',
                'bi bi-ticket-fill',
                'bi bi-ticket-perforated',
                'bi bi-ticket-perforated-fill',
                'bi bi-tiktok',
                'bi bi-toggle-off',
                'bi bi-toggle-on',
                'bi bi-toggle2-off',
                'bi bi-toggle2-on',
                'bi bi-toggles',
                'bi bi-toggles2',
                'bi bi-tools',
                'bi bi-tornado',
                'bi bi-train-freight-front',
                'bi bi-train-freight-front-fill',
                'bi bi-train-front',
                'bi bi-train-front-fill',
                'bi bi-train-lightrail-front',
                'bi bi-train-lightrail-front-fill',
                'bi bi-translate',
                'bi bi-transparency',
                'bi bi-trash',
                'bi bi-trash-fill',
                'bi bi-trash2',
                'bi bi-trash2-fill',
                'bi bi-trash3',
                'bi bi-trash3-fill',
                'bi bi-tree',
                'bi bi-tree-fill',
                'bi bi-trello',
                'bi bi-triangle',
                'bi bi-triangle-fill',
                'bi bi-triangle-half',
                'bi bi-trophy',
                'bi bi-trophy-fill',
                'bi bi-tropical-storm',
                'bi bi-truck',
                'bi bi-truck-flatbed',
                'bi bi-truck-front',
                'bi bi-truck-front-fill',
                'bi bi-tsunami',
                'bi bi-tux',
                'bi bi-tv',
                'bi bi-tv-fill',
                'bi bi-twitch',
                'bi bi-twitter',
                'bi bi-twitter-x',
                'bi bi-type',
                'bi bi-type-bold',
                'bi bi-type-h1',
                'bi bi-type-h2',
                'bi bi-type-h3',
                'bi bi-type-h4',
                'bi bi-type-h5',
                'bi bi-type-h6',
                'bi bi-type-italic',
                'bi bi-type-strikethrough',
                'bi bi-type-underline',
                'bi bi-typescript',
                'bi bi-ubuntu',
                'bi bi-ui-checks',
                'bi bi-ui-checks-grid',
                'bi bi-ui-radios',
                'bi bi-ui-radios-grid',
                'bi bi-umbrella',
                'bi bi-umbrella-fill',
                'bi bi-unindent',
                'bi bi-union',
                'bi bi-unity',
                'bi bi-universal-access',
                'bi bi-universal-access-circle',
                'bi bi-unlock',
                'bi bi-unlock-fill',
                'bi bi-unlock2',
                'bi bi-unlock2-fill',
                'bi bi-upc',
                'bi bi-upc-scan',
                'bi bi-upload',
                'bi bi-usb',
                'bi bi-usb-c',
                'bi bi-usb-c-fill',
                'bi bi-usb-drive',
                'bi bi-usb-drive-fill',
                'bi bi-usb-fill',
                'bi bi-usb-micro',
                'bi bi-usb-micro-fill',
                'bi bi-usb-mini',
                'bi bi-usb-mini-fill',
                'bi bi-usb-plug',
                'bi bi-usb-plug-fill',
                'bi bi-usb-symbol',
                'bi bi-valentine',
                'bi bi-valentine2',
                'bi bi-vector-pen',
                'bi bi-view-list',
                'bi bi-view-stacked',
                'bi bi-vignette',
                'bi bi-vimeo',
                'bi bi-vinyl',
                'bi bi-vinyl-fill',
                'bi bi-virus',
                'bi bi-virus2',
                'bi bi-voicemail',
                'bi bi-volume-down',
                'bi bi-volume-down-fill',
                'bi bi-volume-mute',
                'bi bi-volume-mute-fill',
                'bi bi-volume-off',
                'bi bi-volume-off-fill',
                'bi bi-volume-up',
                'bi bi-volume-up-fill',
                'bi bi-vr',
                'bi bi-wallet',
                'bi bi-wallet-fill',
                'bi bi-wallet2',
                'bi bi-watch',
                'bi bi-water',
                'bi bi-webcam',
                'bi bi-webcam-fill',
                'bi bi-wechat',
                'bi bi-whatsapp',
                'bi bi-wifi',
                'bi bi-wifi-1',
                'bi bi-wifi-2',
                'bi bi-wifi-off',
                'bi bi-wikipedia',
                'bi bi-wind',
                'bi bi-window',
                'bi bi-window-dash',
                'bi bi-window-desktop',
                'bi bi-window-dock',
                'bi bi-window-fullscreen',
                'bi bi-window-plus',
                'bi bi-window-sidebar',
                'bi bi-window-split',
                'bi bi-window-stack',
                'bi bi-window-x',
                'bi bi-windows',
                'bi bi-wordpress',
                'bi bi-wrench',
                'bi bi-wrench-adjustable',
                'bi bi-wrench-adjustable-circle',
                'bi bi-wrench-adjustable-circle-fill',
                'bi bi-x',
                'bi bi-x-circle',
                'bi bi-x-circle-fill',
                'bi bi-x-diamond',
                'bi bi-x-diamond-fill',
                'bi bi-x-lg',
                'bi bi-x-octagon',
                'bi bi-x-octagon-fill',
                'bi bi-x-square',
                'bi bi-x-square-fill',
                'bi bi-xbox',
                'bi bi-yelp',
                'bi bi-yin-yang',
                'bi bi-youtube',
                'bi bi-zoom-in',
                'bi bi-zoom-out',
        ],

        init() {
            // Converter objetos para array se necessário
            if (this.menuItems && typeof this.menuItems === 'object' && !Array.isArray(this.menuItems)) {
                this.menuItems = Object.values(this.menuItems);
            }
            
            // Garantir que seja um array
            if (!Array.isArray(this.menuItems)) {
                this.menuItems = [];
            }
            
            // Sincronizar inputs de cor
            this.syncColorInputs();
        },
        
        syncColorInputs() {
            // Sincronizar color picker com input de texto
            document.querySelectorAll('input[type="color"]').forEach(colorInput => {
                const textInput = colorInput.parentElement.nextElementSibling;
                if (textInput) {
                    colorInput.addEventListener('input', () => {
                        textInput.value = colorInput.value;
                    });
                }
            });
        },

        openIconPicker(index) {
            this.iconPickerTargetIndex = index;
            this.iconPickerOpen = true;
            this.iconPickerSearch = '';
        },

        closeIconPicker() {
            this.iconPickerOpen = false;
            this.iconPickerTargetIndex = null;
            this.iconPickerSearch = '';
        },

        get filteredIcons() {
            const q = this.iconPickerSearch.toLowerCase().trim();
            if (!q) return this.allIcons;
            return this.allIcons.filter(i => i.toLowerCase().includes(q));
        },

        chooseIcon(icon) {
            if (this.iconPickerTargetIndex !== null) {
                this.menuItems[this.iconPickerTargetIndex].icon = icon;
            }
            this.closeIconPicker();
        },
        addMenuItem() {
            this.menuItems.push({
                name: '',
                url: '',
                icon: 'bi bi-link',
                is_active: true,
                is_external: false
            });
        },

        removeMenuItem(index) {
            this.menuItems.splice(index, 1);
        },

        dragStart(index) {
            this.draggedIndex = index;
        },

        drop(dropIndex) {
            if (this.draggedIndex !== null && this.draggedIndex !== dropIndex) {
                const draggedItem = this.menuItems[this.draggedIndex];
                this.menuItems.splice(this.draggedIndex, 1);
                this.menuItems.splice(dropIndex, 0, draggedItem);
                this.draggedIndex = null;
            }
        },

        get activeMenuItems() {
            return this.menuItems.filter(item => item.is_active);
        },

        getMenuStyleClass() {
            const style = document.getElementById('store_menu_style')?.value || 'modern';
            
            switch(style) {
                case 'classic':
                    return 'bg-white border-2 border-gray-300 text-gray-700';
                case 'minimal':
                    return 'bg-gray-50 border border-gray-200 text-gray-600';
                default:
                    return 'bg-black text-white border-t border-gray-700';
            }
        },

        getMenuItemClass(item, index) {
            const style = document.getElementById('store_menu_style')?.value || 'modern';
            const isActive = index === 0; // Simular o primeiro item como ativo no preview
            
            if (style === 'modern') {
                return isActive ? 'text-red-500 bg-[#222222]' : 'text-white hover:text-red-500 hover:bg-[#222222]';
            } else if (style === 'classic') {
                return isActive ? 'text-primary bg-blue-100' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-100';
            } else {
                return isActive ? 'text-gray-900 bg-gray-200' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100';
            }
        }
    }
}

// Função para mostrar notificação
function showNotification(message, type = 'info') {
    // Criar container de notificações se não existir
    let container = document.getElementById('notification-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'notification-container';
        container.className = 'fixed top-4 right-4 z-50 space-y-2';
        document.body.appendChild(container);
    }
    
    // Criar notificação
    const notification = document.createElement('div');
    notification.className = `max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden transform transition-all duration-300 ease-in-out translate-x-full`;
    
    // Definir cores baseadas no tipo
    let bgColor = 'bg-blue-50';
    let textColor = 'text-blue-800';
    let iconColor = 'text-blue-400';
    let iconName = 'info-circle';
    
    if (type === 'success') {
        bgColor = 'bg-green-50';
        textColor = 'text-green-800';
        iconColor = 'text-green-400';
        iconName = 'check-circle';
    } else if (type === 'error') {
        bgColor = 'bg-red-50';
        textColor = 'text-red-800';
        iconColor = 'text-red-400';
        iconName = 'exclamation-triangle';
    } else if (type === 'warning') {
        bgColor = 'bg-yellow-50';
        textColor = 'text-yellow-800';
        iconColor = 'text-yellow-400';
        iconName = 'exclamation-triangle';
    }
    
    notification.innerHTML = `
        <div class="p-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="bi bi-${iconName} ${iconColor} text-lg"></i>
                </div>
                <div class="ml-3 w-0 flex-1 pt-0.5">
                    <p class="text-sm font-medium ${textColor}">
                        ${message}
                    </p>
                </div>
                <div class="ml-4 flex-shrink-0 flex">
                    <button class="bg-white rounded-md inline-flex ${textColor} hover:${textColor.replace('800', '600')} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" onclick="removeNotification(this)">
                        <span class="sr-only">Fechar</span>
                        <i class="bi bi-x text-lg"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    
    // Adicionar ao container
    container.appendChild(notification);
    
    // Animar entrada
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Auto-remover após 5 segundos
    setTimeout(() => {
        removeNotification(notification.querySelector('button'));
    }, 5000);
}

// Função para remover notificação
function removeNotification(button) {
    const notification = button.closest('.max-w-sm');
    if (notification) {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }
}

// ==========================================
// GERENCIADOR DE ARQUIVOS PARA BANNER
// ==========================================

// Abrir o gerenciador de arquivos para selecionar banner desktop
function openFileManagerForBanner() {
    console.log('Opening file manager for banner...');
    window.currentBannerSelector = 'desktop';
    
    const modal = document.getElementById('bannerFileManagerModal');
    modal.classList.remove('hidden');
    
    // Carregar arquivos
    loadBannerFiles();
}

// Abrir o gerenciador de arquivos para selecionar banner mobile
function openFileManagerForBannerMobile() {
    console.log('Opening file manager for mobile banner...');
    window.currentBannerSelector = 'mobile';
    
    const modal = document.getElementById('bannerFileManagerModal');
    modal.classList.remove('hidden');
    
    // Carregar arquivos
    loadBannerFiles();
}

// Fechar o gerenciador de arquivos
function closeBannerFileManager() {
    const modal = document.getElementById('bannerFileManagerModal');
    modal.classList.add('hidden');
    window.currentBannerSelector = false;
}

// Carregar arquivos no modal
function loadBannerFiles() {
    const files = <?php echo json_encode($files ?? [], 15, 512) ?>;
    displayBannerFiles(files);
}

// Exibir arquivos no grid
function displayBannerFiles(items) {
    const grid = document.getElementById('bannerFileManagerGrid');
    const loading = document.getElementById('bannerFileManagerLoading');
    
    if (loading) loading.classList.add('hidden');
    if (grid) grid.classList.remove('hidden');
    
    // Filtrar apenas imagens
    const images = items.filter(item => 
        item.type === 'file' && 
        ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp', 'bmp', 'avif', 'ico'].includes(item.extension?.toLowerCase())
    );
    
    if (images.length === 0) {
        grid.innerHTML = `
            <div class="col-span-full flex flex-col items-center justify-center py-12">
                <i class="bi bi-images text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 font-medium">Nenhuma imagem encontrada</p>
                <p class="text-gray-400 text-sm">Faça upload de imagens primeiro</p>
            </div>
        `;
        return;
    }
    
    grid.innerHTML = images.map(item => `
        <div class="relative group cursor-pointer rounded-xl p-4 transition-all duration-300 h-32 bg-gray-50 border-2 border-gray-200 hover:bg-gray-100 hover:border-gray-300 hover:shadow-lg"
             onclick="selectBannerImage('${item.url}', '${item.name}')">
            <div class="flex flex-col items-center justify-center h-full">
                <i class="bi bi-file-earmark-image text-4xl text-green-500 mb-2"></i>
                <p class="text-xs text-gray-700 text-center truncate w-full" title="${item.name}">
                    ${item.name.length > 15 ? item.name.substring(0, 15) + '...' : item.name}
                </p>
            </div>
        </div>
    `).join('');
}

// Selecionar imagem do banner (adicionar ao array)
function selectBannerImage(imageUrl, fileName) {
    console.log('Banner image selected:', { imageUrl, fileName, type: window.currentBannerSelector });
    
    // Verificar se é desktop ou mobile
    if (window.currentBannerSelector === 'mobile') {
        // Adicionar ao gerenciador mobile
        const bannerMobileComponent = document.querySelector('[x-data*="bannerMobileManager"]');
        if (bannerMobileComponent && bannerMobileComponent._x_dataStack) {
            const alpineData = bannerMobileComponent._x_dataStack[0];
            if (alpineData && alpineData.addBannerMobileFromGallery) {
                alpineData.addBannerMobileFromGallery(imageUrl, fileName);
            }
        }
        showNotification('Banner mobile adicionado com sucesso!', 'success');
    } else {
        // Adicionar ao gerenciador desktop (padrão)
        const bannerComponent = document.querySelector('[x-data*="bannerManager"]');
        if (bannerComponent && bannerComponent._x_dataStack) {
            const alpineData = bannerComponent._x_dataStack[0];
            if (alpineData && alpineData.addBannerFromGallery) {
                alpineData.addBannerFromGallery(imageUrl, fileName);
            }
        }
        showNotification('Banner desktop adicionado com sucesso!', 'success');
    }
    
    // Fechar modal
    closeBannerFileManager();
}

// Formatar tamanho de arquivo
function formatFileSize(bytes) {
    if (!bytes) return '';
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
    return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
}
</script>

<!-- Modal do Gerenciador de Arquivos para Banner -->
<div id="bannerFileManagerModal" 
     class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50 hidden"
     onclick="if(event.target === this) closeBannerFileManager()">
    <div class="bg-white rounded-2xl shadow-2xl w-11/12 h-5/6 mx-4 flex flex-col max-w-6xl"
         onclick="event.stopPropagation()">
        
        <!-- Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-t-2xl">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                    <i class="bi bi-folder text-white text-lg"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Selecionar Banner</h3>
                    <p class="text-sm text-gray-600">Escolha uma imagem para o banner da home</p>
                </div>
            </div>
            <button onclick="closeBannerFileManager()" 
                    class="w-10 h-10 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center transition-colors">
                <i class="bi bi-x text-gray-600 text-lg"></i>
            </button>
        </div>
        
        <!-- File Manager Content -->
        <div class="flex-1 p-6 overflow-hidden">
            <!-- Loading -->
            <div id="bannerFileManagerLoading" class="flex items-center justify-center h-full">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4 mx-auto">
                        <i class="bi bi-arrow-clockwise animate-spin text-2xl text-blue-600"></i>
                    </div>
                    <p class="text-gray-600 font-medium">Carregando imagens...</p>
                </div>
            </div>
            
            <!-- File Grid -->
            <div id="bannerFileManagerGrid" class="hidden grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 h-full overflow-y-auto">
                <!-- Files will be loaded here by JavaScript -->
            </div>
        </div>
        
        <!-- Footer -->
        <div class="flex justify-end space-x-3 p-6 border-t border-gray-200 bg-gray-50 rounded-b-2xl">
            <button onclick="closeBannerFileManager()"
                    class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors font-medium">
                Cancelar
            </button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<script>
// Alpine.js component para gerenciar FAQs
function faqManager() {
    return {
        faqs: <?php echo json_encode($settings['contact_faq'] ? json_decode($settings['contact_faq'], true) : [], 512) ?>,
        sortable: null,
        
        init() {
            this.$nextTick(() => {
                this.initSortable();
            });
        },
        
        initSortable() {
            const el = document.getElementById('faq-sortable');
            if (el && !this.sortable) {
                this.sortable = new Sortable(el, {
                    animation: 150,
                    handle: '.drag-handle',
                    ghostClass: 'bg-blue-100',
                    dragClass: 'opacity-50',
                    onEnd: (evt) => {
                        // Reordenar o array de FAQs
                        const item = this.faqs.splice(evt.oldIndex, 1)[0];
                        this.faqs.splice(evt.newIndex, 0, item);
                    }
                });
            }
        },
        
        addFaq() {
            this.faqs.push({
                question: '',
                answer: ''
            });
            
            // Reinicializar sortable após adicionar
            this.$nextTick(() => {
                if (this.sortable) {
                    this.sortable.destroy();
                    this.sortable = null;
                }
                this.initSortable();
            });
        },
        
        removeFaq(index) {
            if (confirm('Deseja remover esta pergunta?')) {
                this.faqs.splice(index, 1);
                
                // Reinicializar sortable após remover
                this.$nextTick(() => {
                    if (this.sortable) {
                        this.sortable.destroy();
                        this.sortable = null;
                    }
                    this.initSortable();
                });
            }
        }
    }
}
</script>

<?php $__env->stopSection(); ?>

<script>
// Função de callback para quando uma imagem é selecionada - Categorias
function selectCategoryImage(imagePath) {
    const index = window.currentCategoryIndexForFileManager;
    if (index !== undefined && index !== null) {
        // Acessar o componente Alpine.js
        const homeCategoriesComponent = Alpine.$data(document.querySelector('[x-data*="homeCategories()"]'));
        if (homeCategoriesComponent && homeCategoriesComponent.categories[index]) {
            homeCategoriesComponent.categories[index].image = imagePath;
        }
        closeFileManagercategoryImageManagerModal();
        window.currentCategoryIndexForFileManager = null;
    }
}

// Função de callback para Banner Desktop
function selectBannerDesktop(imagePath) {
    const bannerComponent = Alpine.$data(document.querySelector('[x-data*="bannerManager()"]'));
    if (bannerComponent) {
        // Obter URL completa da imagem
        const imageUrl = imagePath.startsWith('http') ? imagePath : `<?php echo e(url('images/')); ?>/${imagePath}`;
        
        // Adicionar banner
        bannerComponent.uploadedBanners.push({
            url: imageUrl,
            name: imagePath.split('/').pop(),
            path: imagePath
        });
        
        closeFileManagerbannerDesktopFileManager();
    }
}

// Função de callback para Banner Mobile
function selectBannerMobile(imagePath) {
    const bannerMobileComponent = Alpine.$data(document.querySelector('[x-data*="bannerMobileManager()"]'));
    if (bannerMobileComponent) {
        // Obter URL completa da imagem
        const imageUrl = imagePath.startsWith('http') ? imagePath : `<?php echo e(url('images/')); ?>/${imagePath}`;
        
        // Adicionar banner
        bannerMobileComponent.uploadedBannersMobile.push({
            url: imageUrl,
            name: imagePath.split('/').pop(),
            path: imagePath
        });
        
        closeFileManagerbannerMobileFileManager();
    }
}
</script>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\resources\views/admin/settings/store.blade.php ENDPATH**/ ?>