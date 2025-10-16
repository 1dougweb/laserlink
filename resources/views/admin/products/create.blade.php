@extends('admin.layout')

@section('title', 'Novo Produto - Laser Link')
@section('page-title', 'Novo Produto')

@push('head')
<!-- Desabilitar cache para página de criação de produtos -->
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
@endpush

@section('content')
<div class="space-y-6" x-data="productManager()">
    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" id="productForm" onsubmit="return validateProductForm(event)">
        @csrf
        
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
                           value="{{ old('name') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('name') border-red-500 @enderror"
                           required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                
                
                <div>
                    <label for="sku" class="block text-sm font-medium text-gray-700 mb-2">
                        SKU
                    </label>
                    <input type="text" 
                           id="sku" 
                           name="sku" 
                           value="{{ old('sku') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('sku') border-red-500 @enderror"
                           required>
                    @error('sku')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="md:col-span-2">
                    <label for="short_description" class="block text-sm font-medium text-gray-700 mb-2">
                        Descrição Curta
                    </label>
                    <textarea id="short_description" 
                              name="short_description" 
                              rows="2"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('short_description') border-red-500 @enderror">{{ old('short_description') }}</textarea>
                    @error('short_description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Descrição Completa
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="10"
                              class="quill-editor">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
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
                               value="{{ old('price') ? number_format((float)old('price'), 2, ',', '.') : '' }}"
                               placeholder="0,00"
                               class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('price') border-red-500 @enderror"
                               required>
                    </div>
                    @error('price')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
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
                               value="{{ old('sale_price') ? number_format((float)old('sale_price'), 2, ',', '.') : '' }}"
                               placeholder="0,00"
                               class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('sale_price') border-red-500 @enderror">
                    </div>
                    @error('sale_price')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="stock_quantity" class="block text-sm font-medium text-gray-700 mb-2">
                        Estoque
                    </label>
                    <input type="number" 
                           id="stock_quantity" 
                           name="stock_quantity" 
                           min="0"
                           value="{{ old('stock_quantity', 0) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('stock_quantity') border-red-500 @enderror"
                           required>
                    @error('stock_quantity')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                        Ordem de Exibição
                    </label>
                    <input type="number" 
                           id="sort_order" 
                           name="sort_order" 
                           value="{{ old('sort_order', 0) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('sort_order') border-red-500 @enderror">
                    @error('sort_order')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                
                <!-- Campos SEO -->
                <div class="md:col-span-2">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center mb-4">
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
                                       value="{{ old('meta_title') }}"
                                       placeholder="Título otimizado para SEO"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('meta_title') border-red-500 @enderror">
                                <div class="flex justify-between text-xs mt-1">
                                    <span class="text-gray-500">Usado no Google</span>
                                    <span :class="seoData.meta_title.length > 60 ? 'text-red-500' : 'text-gray-500'" 
                                          x-text="seoData.meta_title.length + '/60'"></span>
                                </div>
                                @error('meta_title')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
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
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('meta_description') border-red-500 @enderror">{{ old('meta_description') }}</textarea>
                                <div class="flex justify-between text-xs mt-1">
                                    <span class="text-gray-500">Usado no Google</span>
                                    <span :class="seoData.meta_description.length > 160 ? 'text-red-500' : 'text-gray-500'" 
                                          x-text="seoData.meta_description.length + '/160'"></span>
                                </div>
                                @error('meta_description')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
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
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-700 border border-gray-300 shadow-sm hover:bg-gray-200 transition-colors ">
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
                                           class="flex-1 min-w-[200px] text-sm bg-transparent">
                                </div>
                                
                                <!-- Hidden input for form submission -->
                                <input type="hidden" 
                                       id="meta_keywords" 
                                       name="meta_keywords" 
                                       x-model="hiddenValue"
                                       value="{{ old('meta_keywords') }}">
                                
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="bi bi-info-circle mr-1"></i>
                                    Digite uma palavra-chave e pressione Enter ou vírgula para adicionar. Clique no × para remover.
                                </p>
                                @error('meta_keywords')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
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
                                    {{ url('/') }}/produto/<span x-text="seoData.slug || 'nome-do-produto'"></span>
                                </div>
                                
                                <!-- Title -->
                                <div class="text-blue-600 text-lg font-medium leading-tight">
                                    <span x-text="seoData.meta_title || 'Título do produto aparecerá aqui'"></span>
                                </div>
                                
                                <!-- Description -->
                                <div class="text-gray-600 text-sm leading-relaxed">
                                    <span x-text="seoData.meta_description || 'Descrição do produto aparecerá aqui. Esta é a descrição que os usuários verão nos resultados de busca do Google.'"></span>
                                </div>
                                
                                <!-- Keywords para LLMs -->
                                <div class="mt-2 p-2 bg-gray-50 rounded">
                                    <div class="text-xs text-gray-600 mb-2"><strong>Para LLMs e busca interna:</strong></div>
                                    <div class="flex flex-wrap gap-1.5">
                                        <template x-if="seoData.meta_keywords">
                                            <template x-for="keyword in (seoData.meta_keywords || '').split(',').map(k => k.trim()).filter(k => k)" :key="keyword">
                                                <span class="inline-block px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full text-xs font-medium" x-text="keyword"></span>
                                            </template>
                                        </template>
                                        <template x-if="!seoData.meta_keywords">
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
                                   {{ old('is_active', true) ? 'checked' : '' }}
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
                                   {{ old('is_featured', false) ? 'checked' : '' }}
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
                            <button @click="removeFeaturedImage()" 
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
                                            @click="removeGalleryImage(index)"
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
                
                <!-- Categorias -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Categorias</h3>
                    <div class="space-y-3 max-h-48 overflow-y-auto" id="categories-list">
                        @if(isset($categories) && count($categories) > 0)
                            @foreach($categories as $category)
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           id="category_{{ $category->id }}" 
                                           name="categories[]" 
                                           value="{{ $category->id }}"
                                           {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}
                                           class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                    <label for="category_{{ $category->id }}" class="ml-2 block text-sm text-gray-900">
                                        {{ $category->name }}
                                    </label>
                                </div>
                            @endforeach
                        @else
                            <p class="text-gray-500 text-sm">Nenhuma categoria disponível</p>
                        @endif
                    </div>
                    @error('categories')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    
                    <!-- Botão para criar nova categoria -->
                    <button type="button" 
                            @click="showNewCategoryModal = true"
                            class="w-full mt-4 border-2 border-dashed border-gray-300 rounded-lg p-3 text-center hover:border-gray-400 transition-colors">
                        <i class="bi bi-plus-circle text-lg text-gray-400 mb-1"></i>
                        <p class="text-sm text-gray-500">Nova Categoria</p>
                    </button>
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
        
        <!-- Botões -->
        <div class="flex justify-between items-center mt-4">
            <div class="flex items-center text-sm text-gray-600">
                <i class="bi bi-info-circle mr-2"></i>
                <span>Após salvar o produto, você poderá gerenciar os campos extras</span>
            </div>
            
            <div class="flex space-x-3">
                <a href="{{ route('admin.products') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors">
                    Salvar Produto
                </button>
            </div>
        </div>
    </form>
    
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

<!-- Dados globais para JS -->
<script>
window.categoriesData = {!! json_encode($categories ?? []) !!};
window.fileManagerRoute = '{{ route('admin.admin.file-manager.index') }}';
window.categoryStoreRoute = '{{ route('admin.categories.store') }}';
window.generateDescriptionRoute = '{{ route('admin.products.generate-description') }}';
window.files = @json($files ?? []);

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
<script src="{{ asset('js/product-manager.js') }}"></script>
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
    if (!value) return 0;
    // Remove tudo exceto dígitos e vírgula
    value = value.replace(/[^\d,]/g, '');
    // Substitui vírgula por ponto para decimal
    value = value.replace(',', '.');
    return parseFloat(value) || 0;
}

// Aplica máscara de moeda nos campos de preço
document.addEventListener('DOMContentLoaded', function() {
    const priceFields = ['price', 'sale_price'];

    priceFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            // Formata valor inicial
            if (field.value) {
                field.value = formatCurrencyBRL(field.value);
            }

            // Máscara ao digitar
            field.addEventListener('input', function(e) {
                const oldValue = e.target.value;
                const selectionStart = e.target.selectionStart;

                // Formata valor
                const formatted = formatCurrencyBRL(oldValue);
                e.target.value = formatted;

                // Ajusta cursor (mantém no fim)
                setTimeout(() => {
                    e.target.setSelectionRange(e.target.value.length, e.target.value.length);
                }, 0);

                // Atualiza campo hidden
                const hiddenField = document.getElementById(fieldId + '_numeric');
                if (hiddenField) {
                    hiddenField.value = parseCurrencyBRL(formatted);
                }
            });

            // Atualiza campo hidden ao perder foco
            field.addEventListener('blur', function(e) {
                const formatted = formatCurrencyBRL(e.target.value);
                e.target.value = formatted;
                const hiddenField = document.getElementById(fieldId + '_numeric');
                if (hiddenField) {
                    hiddenField.value = parseCurrencyBRL(formatted);
                }
            });
        }
    });

    // Intercepta o submit do formulário para converter preços
    const form = document.getElementById('productForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Converte os preços de vírgula para ponto antes de enviar
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

// Validação do formulário antes de enviar
function validateProductForm(event) {
    const form = event.target;
    const errors = [];
    
    // Debug: Verificar valores das imagens
    const featuredImageInput = form.querySelector('input[name="featured_image"]');
    const galleryImagesInputs = form.querySelectorAll('input[name^="gallery_images"]');
    
    
    // Validar campos obrigatórios
    const requiredFields = [
        { id: 'name', label: 'Nome do Produto' },
        { id: 'sku', label: 'SKU' },
        { id: 'price', label: 'Preço' },
        { id: 'stock_quantity', label: 'Quantidade em Estoque' }
    ];
    
    requiredFields.forEach(field => {
        const element = document.getElementById(field.id);
        if (!element || !element.value || element.value.trim() === '') {
            errors.push(`O campo "${field.label}" é obrigatório.`);
            element?.classList.add('border-red-500');
        } else {
            element?.classList.remove('border-red-500');
        }
    });
    
    // Validar preço
    const priceField = document.getElementById('price');
    if (priceField && priceField.value) {
        const priceValue = parseFloat(priceField.value.replace(/\./g, '').replace(',', '.'));
        if (isNaN(priceValue) || priceValue < 0) {
            errors.push('O preço deve ser um número válido maior ou igual a zero.');
            priceField.classList.add('border-red-500');
        }
    }
    
    // Validar categoria (pelo menos uma deve estar selecionada)
    const categoryCheckboxes = document.querySelectorAll('input[name="categories[]"]:checked');
    if (categoryCheckboxes.length === 0) {
        errors.push('Por favor, selecione pelo menos uma categoria.');
    }
    
    // Se houver erros, mostrar e impedir envio
    if (errors.length > 0) {
        event.preventDefault();
        
        // Criar div de notificação de erro
        const existingError = document.getElementById('validation-error');
        if (existingError) {
            existingError.remove();
        }
        
        const errorDiv = document.createElement('div');
        errorDiv.id = 'validation-error';
        errorDiv.className = 'fixed top-4 right-4 bg-red-50 border-l-4 border-red-500 p-4 mb-6 shadow-lg z-50 max-w-md';
        errorDiv.innerHTML = `
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Há ${errors.length} erro(s) no formulário:</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc list-inside space-y-1">
                            ${errors.map(error => `<li>${error}</li>`).join('')}
                        </ul>
                    </div>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-auto">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        `;
        
        document.body.appendChild(errorDiv);
        
        // Scroll para o primeiro campo com erro
        const firstErrorField = form.querySelector('.border-red-500');
        if (firstErrorField) {
            firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstErrorField.focus();
        }
        
        // Remover notificação após 5 segundos
        setTimeout(() => {
            errorDiv.remove();
        }, 5000);
        
        return false;
    }
    
    // Mostrar indicador de loading
    const submitButton = form.querySelector('button[type="submit"]');
    if (submitButton) {
        submitButton.disabled = true;
        const originalText = submitButton.innerHTML;
        submitButton.innerHTML = '<i class="bi bi-arrow-clockwise animate-spin mr-2"></i> Salvando...';
        
        // Restaurar após timeout (caso dê erro)
        setTimeout(() => {
            submitButton.disabled = false;
            submitButton.innerHTML = originalText;
        }, 10000);
    }
    
    return true;
}
</script>

<script src="{{ asset('js/file-manager-for-create.js') }}"></script>

<!-- Componente File Manager para Imagem Destacada -->
<x-file-manager-modal 
    modal-id="productFeaturedImageManager" 
    title="Selecionar Imagem Destacada" 
    on-select-callback="selectProductFeaturedImage" />

<!-- Componente File Manager para Galeria -->
<x-file-manager-modal 
    modal-id="productGalleryManager" 
    title="Adicionar Imagens à Galeria" 
    on-select-callback="selectProductGalleryImage" />

<script>
// Inicializar variáveis JavaScript para o productManager
window.productFeaturedImage = null;
window.productFeaturedImagePath = '';
window.productGalleryImages = [];
window.files = [];

// Callbacks para seleção de imagens do produto
function selectProductFeaturedImage(imagePath) {
    const imageUrl = imagePath.startsWith('http') ? imagePath : `{{ url('images/') }}/${imagePath}`;
    
    // Atualizar Alpine.js
    const component = Alpine.$data(document.querySelector('[x-data*="productManager()"]'));
    if (component) {
        component.featuredImage = imageUrl;
        component.featuredImagePath = imagePath;
    }
    
    closeFileManagerproductFeaturedImageManager();
}

function selectProductGalleryImage(imagePath) {
    const imageUrl = imagePath.startsWith('http') ? imagePath : `{{ url('images/') }}/${imagePath}`;
    
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
</script>

@endsection


@include('admin.products.partials.file-manager-modals')

