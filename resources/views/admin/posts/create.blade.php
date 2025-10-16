@extends('admin.layout')

@section('title', 'Novo Post - Laser Link')
@section('page-title', 'Novo Post')

@push('head')
<!-- Desabilitar cache para página de criação de posts -->
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
@endpush

@section('content')

<!-- Notificações de erro -->
@if ($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">Há {{ count($errors) }} erro(s) no formulário:</h3>
                <div class="mt-2 text-sm text-red-700">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endif

<div class="space-y-6" x-data="postManager()">
    <form method="POST" action="{{ route('admin.posts.store') }}" enctype="multipart/form-data" id="postForm">
        @csrf
        
        <div class="flex gap-6">
            <!-- Conteúdo Principal -->
            <div class="flex-1">
                <!-- Informações Básicas -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informações do Post</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Título do Post *
                            </label>
                            <input type="text" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('title') border-red-500 @enderror"
                                   required
                                   @input="seoData.slug = $event.target.value.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/[^\w\s-]/g, '').replace(/\s+/g, '-'); seoData.meta_title = $event.target.value">
                            @error('title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                                Slug (URL amigável)
                            </label>
                            <input type="text" 
                                   id="slug" 
                                   name="slug" 
                                   x-model="seoData.slug"
                                   value="{{ old('slug') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('slug') border-red-500 @enderror">
                            <p class="text-xs text-gray-500 mt-1">Gerado automaticamente se deixado em branco</p>
                            @error('slug')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">
                                Resumo/Excerpt
                            </label>
                            <textarea id="excerpt" 
                                      name="excerpt" 
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('excerpt') border-red-500 @enderror">{{ old('excerpt') }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Breve descrição do post para listagens</p>
                            @error('excerpt')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                                Conteúdo do Post *
                            </label>
                            <textarea id="content" 
                                      name="content" 
                                      rows="20"
                                      class="quill-editor">{{ old('content') }}</textarea>
                            @error('content')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- SEO -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="bi bi-search mr-2"></i>SEO
                        </h3>
                        <button type="button" 
                                @click="toggleSeoPreview()"
                                class="text-sm text-primary hover:text-red-700">
                            <i class="bi bi-eye mr-1"></i>
                            <span x-text="showSeoPreview ? 'Ocultar Preview' : 'Mostrar Preview'"></span>
                        </button>
                    </div>
                    
                    <!-- SEO Preview -->
                    <div x-show="showSeoPreview" 
                         x-transition
                         class="mb-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                            <i class="bi bi-google text-blue-600 mr-2"></i>
                            Preview do Google
                        </h4>
                        
                        <div class="space-y-2">
                            <div class="text-green-700 text-sm">
                                {{ url('/blog') }}/<span x-text="seoData.slug || 'titulo-do-post'"></span>
                            </div>
                            <div class="text-blue-600 text-lg font-medium leading-tight">
                                <span x-text="seoData.meta_title || 'Título do post aparecerá aqui'"></span>
                            </div>
                            <div class="text-gray-600 text-sm leading-relaxed">
                                <span x-text="seoData.meta_description || 'Descrição do post aparecerá aqui nos resultados de busca.'"></span>
                            </div>
                            
                            <!-- Keywords/Tags Preview -->
                            <div class="mt-3 p-2 bg-white rounded border border-gray-200">
                                <div class="text-xs text-gray-600 mb-2 font-semibold">
                                    <i class="bi bi-tags mr-1"></i>Tags (SEO):
                                </div>
                                <div class="flex flex-wrap gap-1.5">
                                    <template x-if="seoData.meta_keywords && seoData.meta_keywords.length > 0">
                                        <template x-for="keyword in (seoData.meta_keywords || '').split(',').map(k => k.trim()).filter(k => k)" :key="keyword">
                                            <span class="inline-block px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full text-xs font-medium" x-text="keyword"></span>
                                        </template>
                                    </template>
                                    <template x-if="!seoData.meta_keywords || seoData.meta_keywords.length === 0">
                                        <span class="text-xs text-gray-400 italic">Nenhuma tag definida</span>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">
                                Meta Title
                            </label>
                            <input type="text" 
                                   id="meta_title" 
                                   name="meta_title" 
                                   x-model="seoData.meta_title"
                                   maxlength="60"
                                   placeholder="Título otimizado para SEO"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            <p class="text-xs text-gray-500 mt-1">Máximo de 60 caracteres (<span x-text="seoData.meta_title.length"></span>/60)</p>
                        </div>

                        <div>
                            <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">
                                Meta Description
                            </label>
                            <textarea id="meta_description" 
                                      name="meta_description" 
                                      x-model="seoData.meta_description"
                                      rows="2"
                                      maxlength="160"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('meta_description') border-red-500 @enderror">{{ old('meta_description') }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Máximo de 160 caracteres (<span x-text="seoData.meta_description.length"></span>/160)</p>
                        </div>

                        <!-- Meta Keywords com Tags -->
                        <div x-data="keywordsTags()">
                            <label for="meta_keywords_input" class="block text-sm font-medium text-gray-700 mb-2">
                                Meta Keywords (Tags)
                                <span class="text-xs text-gray-500">(Para SEO e busca)</span>
                            </label>
                            
                            <!-- Tags Display -->
                            <div class="mb-2 min-h-[42px] p-2 rounded-full bg-gray-50 flex flex-wrap gap-2 items-center border border-gray-200">
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
                                       placeholder="Digite e pressione Enter ou vírgula"
                                        style="border: none !important; outline: none !important; box-shadow: none !important;"
                                        class="flex-1 min-w-[200px] bg-transparent text-sm">
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
                </div>
            </div>

            <!-- Sidebar -->
            <div class="w-80 space-y-6">
                <!-- Publicação -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Publicação</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Status
                            </label>
                            <select id="status" 
                                    name="status" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Rascunho</option>
                                <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Publicado</option>
                            </select>
                        </div>

                        <div>
                            <label for="published_at" class="block text-sm font-medium text-gray-700 mb-2">
                                Data de Publicação
                            </label>
                            <input type="datetime-local" 
                                   id="published_at" 
                                   name="published_at" 
                                   value="{{ old('published_at') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            <p class="text-xs text-gray-500 mt-1">Deixe em branco para usar data atual</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                <i class="bi bi-tags mr-1"></i>Categorias
                            </label>
                            <div class="space-y-2 max-h-60 overflow-y-auto p-3 border border-gray-200 rounded-lg bg-gray-50">
                                @if($categories->count() > 0)
                                    @foreach ($categories as $category)
                                        <label class="flex items-center p-3 hover:bg-white rounded-lg cursor-pointer transition-colors group">
                                            <input type="checkbox" 
                                                   name="categories[]" 
                                                   value="{{ $category->id }}"
                                                   {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}
                                                   class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-2 focus:ring-primary">
                                            <span class="ml-3 text-sm font-medium text-gray-900 group-hover:text-primary transition-colors">
                                                {{ $category->name }}
                                            </span>
                                        </label>
                                    @endforeach
                                @else
                                    <p class="text-sm text-gray-500 italic text-center py-4">
                                        <i class="bi bi-info-circle mr-1"></i>
                                        Nenhuma categoria disponível. 
                                        <a href="{{ route('admin.categories.create') }}" class="text-primary hover:underline">Criar primeira categoria</a>
                                    </p>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500 mt-2">
                                <i class="bi bi-info-circle mr-1"></i>
                                Selecione uma ou mais categorias para organizar seu post
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Imagem Destacada -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="bi bi-image mr-2"></i>Imagem Destacada
                    </h3>
                    
                    <div class="space-y-4">
                        <div x-show="featuredImage" class="relative">
                            <img :src="featuredImage" 
                                 alt="Imagem destacada" 
                                 class="w-full h-48 object-cover rounded-lg border border-gray-200 shadow-sm">
                            <button @click="removeFeaturedImage()" 
                                    type="button"
                                    class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-2 hover:bg-red-600 transition-colors shadow-lg">
                                <i class="bi bi-x-lg text-sm"></i>
                            </button>
                        </div>
                        
                        <div x-show="!featuredImage" class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-primary transition-colors">
                            <i class="bi bi-image text-5xl text-gray-400 mb-3"></i>
                            <p class="text-sm text-gray-500 mb-4">Nenhuma imagem selecionada</p>
                            <button type="button" 
                                    onclick="openFileManagerfeaturedImageModal()"
                                    class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors shadow-md">
                                <i class="bi bi-folder2-open mr-2"></i>
                                Gerenciador de Arquivos
                            </button>
                        </div>
                        
                        <input type="hidden" name="featured_image" x-model="featuredImagePath">
                        
                        <p class="text-xs text-gray-500">
                            <i class="bi bi-info-circle mr-1"></i>
                            Tamanho recomendado: 1200x630px para melhor compartilhamento em redes sociais
                        </p>
                    </div>
                </div>

                <!-- Botão IA -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">IA Assistente</h3>
                    <button type="button" 
                            @click="generateContentWithAI()"
                            :disabled="aiLoading"
                            class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg p-3 hover:from-blue-600 hover:to-purple-700 disabled:opacity-50 transition-all">
                        <i class="bi bi-robot mr-2" x-show="!aiLoading"></i>
                        <i class="bi bi-arrow-clockwise animate-spin mr-2" x-show="aiLoading"></i>
                        <span x-text="aiLoading ? 'Gerando...' : 'Gerar Conteúdo com IA'"></span>
                    </button>
                </div>

                <!-- Ações -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex flex-col gap-3">
                        <button type="submit" 
                                class="w-full bg-primary text-white px-4 py-2 rounded-lg hover:opacity-90 transition-colors">
                            <i class="bi bi-check-lg mr-2"></i>Salvar Post
                        </button>
                        
                        <a href="{{ route('admin.posts.index') }}" 
                           class="w-full bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition-colors text-center">
                            <i class="bi bi-x-lg mr-2"></i>Cancelar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- File Manager Modal Component -->
    <x-file-manager-modal 
        modalId="featuredImageModal"
        title="Selecionar Imagem Destacada"
        onSelectCallback="selectFeaturedImage"
    />
</div>

@endsection

@push('scripts')
<script>
    window.generateContentRoute = '{{ route("admin.posts.generate-content") }}';
    window.files = @json($fileManagerItems ?? []);
    
    // Função global para seleção de imagem destacada (DEVE vir antes do componente)
    function selectFeaturedImage(filePath) {
        
        // Construir URL completa da imagem
        // Verificar se já tem /storage/ ou /images/ no caminho
        let imageUrl;
        if (filePath.startsWith('http')) {
            imageUrl = filePath;
        } else if (filePath.startsWith('storage/') || filePath.startsWith('/storage/')) {
            imageUrl = window.location.origin + '/' + filePath.replace(/^\/+/, '');
        } else if (filePath.startsWith('images/') || filePath.startsWith('/images/')) {
            imageUrl = window.location.origin + '/' + filePath.replace(/^\/+/, '');
        } else {
            // Por padrão, assumir que está em images/
            imageUrl = window.location.origin + '/images/' + filePath;
        }
        
        
        // Método 1: Tentar encontrar via Alpine.$data
        try {
            const postManagerElement = document.querySelector('[x-data*="postManager"]');
            if (postManagerElement) {
                
                // Esperar Alpine estar pronto
                if (window.Alpine && postManagerElement._x_dataStack) {
                    const component = postManagerElement._x_dataStack[0];
                    if (component) {
                        component.featuredImage = imageUrl;
                        component.featuredImagePath = filePath;
                        return;
                    }
                }
                
                // Fallback para __x
                if (postManagerElement.__x) {
                    const component = postManagerElement.__x.$data;
                    component.featuredImage = imageUrl;
                    component.featuredImagePath = filePath;
                    return;
                }
            }
        } catch (e) {
            console.warn('⚠️ Erro ao acessar Alpine:', e);
        }
        
        // Método 2: Fallback - atualizar diretamente o campo hidden e o preview
        const hiddenInput = document.querySelector('input[name="featured_image"]');
        if (hiddenInput) {
            hiddenInput.value = filePath;
        }
        
        // Atualizar preview de imagem manualmente
        const previewContainer = document.querySelector('[x-show="featuredImage"]');
        const previewImg = document.querySelector('[x-show="featuredImage"] img');
        const noImageContainer = document.querySelector('[x-show="!featuredImage"]');
        
        if (previewImg) {
            previewImg.src = imageUrl;
            previewImg.parentElement.style.display = 'block';
        }
        
        if (previewContainer) {
            previewContainer.style.display = 'block';
        }
        
        if (noImageContainer) {
            noImageContainer.style.display = 'none';
        }
        
        // Disparar evento customizado para o Alpine reagir
        window.dispatchEvent(new CustomEvent('featuredImageSelected', {
            detail: {
                imageUrl: imageUrl,
                filePath: filePath
            }
        }));
        
    }
    
    // Sistema de Tags para Keywords
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
                }
            },
            
            addTag() {
                const tag = this.newTag.trim();
                if (tag && !this.tags.includes(tag)) {
                    this.tags.push(tag);
                    this.newTag = '';
                    this.updateHiddenInput();
                }
            },
            
            removeTag(index) {
                this.tags.splice(index, 1);
                this.updateHiddenInput();
            },
            
            updateHiddenInput() {
                this.hiddenValue = this.tags.join(', ');
                const hiddenInput = document.getElementById('meta_keywords');
                if (hiddenInput) {
                    hiddenInput.value = this.hiddenValue;
                    hiddenInput.dispatchEvent(new Event('input'));
                }
                this.updateSeoData();
            },
            
            updateSeoData() {
                // Update the parent component's seoData if it exists
                const postManagerElement = document.querySelector('[x-data*="postManager"]');
                if (postManagerElement && postManagerElement.__x) {
                    const component = postManagerElement.__x.$data;
                    if (component.seoData) {
                        component.seoData.meta_keywords = this.hiddenValue;
                    }
                }
            }
        }
    }
</script>
<script src="{{ asset('js/post-manager.js') }}"></script>
<script>
    // Função de teste temporária
    async function testGeminiConnection() {
        try {
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Testar endpoint de debug
            const response = await fetch('/admin/posts/debug-gemini', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            
            const data = await response.json();
            
            if (response.ok) {
                alert('✅ Conexão OK!\n' + 
                      'Gemini habilitado: ' + data.gemini_enabled + '\n' +
                      'API Key: ' + (data.api_key_set ? 'Configurada' : 'Não configurada') + '\n' +
                      'Usuário autenticado: ' + (data.user_authenticated ? 'Sim' : 'Não') + '\n' +
                      'É admin: ' + (data.user_is_admin ? 'Sim' : 'Não'));
            } else {
                alert('❌ Erro: ' + response.status + ' - ' + response.statusText);
            }
        } catch (error) {
            console.error('❌ Erro no teste:', error);
            alert('❌ Erro: ' + error.message);
        }
    }
</script>
@endpush
