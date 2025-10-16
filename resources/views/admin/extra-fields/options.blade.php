@extends('admin.layout')

@section('title', 'Opções - ' . $extraField->name)
@section('page-title', 'Opções do Campo')

@section('content')
<div class="space-y-6">
    <form method="POST" action="{{ route('admin.extra-fields.save-options', $extraField) }}">
        @csrf
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="mb-4">
                <h2 class="text-lg font-medium text-gray-900">Configurar Opções</h2>
                <p class="text-sm text-gray-600">{{ $extraField->name }} ({{ ucfirst($extraField->type) }})</p>
                <p class="text-xs text-gray-500">Debug: Tipo = "{{ $extraField->type }}"</p>
            </div>
            
            @if($extraField->type === 'image')
            <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0">
                        <i class="bi bi-info-circle text-blue-600 text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm font-semibold text-blue-900 mb-1">Campo de Seleção Visual (Imagens)</h3>
                        <p class="text-sm text-blue-800">
                            As opções serão exibidas como <strong>miniaturas de imagens</strong> na página do produto. 
                            Clique em <span class="inline-flex items-center px-2 py-0.5 bg-blue-600 text-white rounded text-xs">
                                <i class="bi bi-folder-open mr-1"></i> Selecionar
                            </span> para escolher imagens da galeria.
                        </p>
                    </div>
                </div>
            </div>
            @endif
            
            @if($extraField->type === 'color')
            <div class="mb-6 bg-purple-50 border border-purple-200 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0">
                        <i class="bi bi-palette text-purple-600 text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm font-semibold text-purple-900 mb-1">Campo de Seleção Visual (Cores)</h3>
                        <p class="text-sm text-purple-800">
                            As opções serão exibidas como <strong>swatches de cores</strong> na página do produto. 
                            Use o color picker ou digite o código hexadecimal da cor (ex: #FF0000).
                        </p>
                    </div>
                </div>
            </div>
            @endif
            
            <div id="options-container" class="space-y-4">
                @if($options->count() > 0)
                    @foreach($options as $index => $option)
                        <div class="option-row border rounded-lg p-4 hover:bg-gray-50 transition-colors" data-index="{{ $index }}" data-id="{{ $option->id }}">
                            <div class="flex items-start gap-4">
                                <!-- Handle de arrastar -->
                                <div class="flex-shrink-0 pt-6">
                                    <i class="bi bi-grip-vertical text-gray-400 cursor-move text-lg"></i>
                                </div>
                                
                                <!-- Conteúdo da opção -->
                                <div class="flex-1">
                                    <!-- Linha 1: Campos básicos -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Valor *</label>
                                            <input type="text" 
                                                   name="options[{{ $index }}][value]" 
                                                   value="{{ $option->value }}"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                                   required>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Label *</label>
                                            <input type="text" 
                                                   name="options[{{ $index }}][label]" 
                                                   value="{{ $option->label }}"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                                   required>
                                        </div>
                                    </div>
                                    
                                    <!-- Linha 2: Campos específicos (imagem/cor) -->
                                    @if($extraField->type === 'image' || $extraField->type === 'color')
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        @if($extraField->type === 'image')
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                <i class="bi bi-image mr-1"></i> Imagem
                                            </label>
                                            <div class="flex gap-2">
                                                <input type="text" 
                                                       name="options[{{ $index }}][image_url]" 
                                                       id="image_url_{{ $index }}"
                                                       value="{{ $option->image_url ?? '' }}"
                                                       placeholder="products/material.jpg"
                                                       readonly
                                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary">
                                                <button type="button"
                                                        onclick="openFileManagerForOption({{ $index }})"
                                                        class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center gap-1 text-sm">
                                                    <i class="bi bi-folder-open"></i>
                                                    <span class="hidden sm:inline">Selecionar</span>
                                                </button>
                                            </div>
                                            @if($option->image_url)
                                            <div class="mt-2">
                                                <img src="{{ url('images/' . $option->image_url) }}" 
                                                     alt="Preview"
                                                     class="w-16 h-16 object-cover rounded border border-gray-300"
                                                     onerror="this.src='{{ url('images/general/callback-image.svg') }}'">
                                            </div>
                                            @endif
                                        </div>
                                        @endif
                                        
                                        @if($extraField->type === 'color')
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                <i class="bi bi-palette mr-1"></i> Código da Cor (Hex)
                                            </label>
                                            <div class="flex gap-2">
                                                <input type="color" 
                                                       id="color_picker_{{ $index }}"
                                                       value="{{ $option->color_hex ?? '#000000' }}"
                                                       onchange="document.getElementById('color_hex_{{ $index }}').value = this.value"
                                                       class="h-10 w-12 border border-gray-300 rounded cursor-pointer">
                                                <input type="text" 
                                                       id="color_hex_{{ $index }}"
                                                       name="options[{{ $index }}][color_hex]" 
                                                       value="{{ $option->color_hex ?? '' }}"
                                                       placeholder="#FF0000"
                                                       pattern="^#[0-9A-Fa-f]{6}$"
                                                       onchange="document.getElementById('color_picker_{{ $index }}').value = this.value"
                                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    @endif
                                    
                                    <!-- Linha 3: Preço e Tipo -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Preço *</label>
                                            <input type="number" 
                                                   name="options[{{ $index }}][price]" 
                                                   value="{{ $option->price }}"
                                                   step="0.01"
                                                   min="0"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                                   required>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Preço *</label>
                                            <select name="options[{{ $index }}][price_type]" 
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                                    required>
                                                <option value="fixed" {{ $option->price_type == 'fixed' ? 'selected' : '' }}>Fixo</option>
                                                <option value="percentage" {{ $option->price_type == 'percentage' ? 'selected' : '' }}>Percentual</option>
                                                <option value="per_unit" {{ $option->price_type == 'per_unit' ? 'selected' : '' }}>Por Unidade</option>
                                                <option value="per_area" {{ $option->price_type == 'per_area' ? 'selected' : '' }}>Por Área</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <!-- Linha 4: Controles -->
                                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pt-4 border-t border-gray-200">
                                        <div class="flex items-center">
                                            <input type="checkbox" 
                                                   name="options[{{ $index }}][is_active]" 
                                                   value="1"
                                                   {{ $option->is_active ? 'checked' : '' }}
                                                   class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                            <label class="ml-2 text-sm text-gray-900">Ativo</label>
                                        </div>
                                        
                                        <div class="flex items-center gap-3">
                                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                                Ordem: <span class="font-medium">{{ $option->sort_order ?? $index + 1 }}</span>
                                            </span>
                                            <button type="button" 
                                                    onclick="removeOption(this)"
                                                    class="px-3 py-1 text-red-600 hover:text-red-900 hover:bg-red-50 rounded-lg transition-colors flex items-center gap-1 text-sm">
                                                <i class="bi bi-trash"></i>
                                                Remover
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-8 text-gray-500">
                        <p>Nenhuma opção configurada</p>
                        <button type="button" 
                                onclick="addOption()"
                                class="mt-4 px-6 py-3 bg-primary hover:bg-primary/90 text-white rounded-lg transition-colors flex items-center gap-2 mx-auto">
                            <i class="bi bi-plus-circle"></i>
                            Adicionar primeira opção
                        </button>
                    </div>
                @endif
            </div>
            
            <div class="mt-6">
                <button type="button" 
                        onclick="addOption()"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                    + Adicionar Opção
                </button>
            </div>
        </div>

        <div class="flex justify-end space-x-3 mt-4">
            <a href="{{ route('admin.extra-fields.index') }}" 
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition-colors">
                Cancelar
            </a>
            <button type="submit" 
                    class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg transition-colors">
                Salvar Opções
            </button>
        </div>
    </form>
</div>

<!-- Scripts para drag-and-drop e funcionalidades -->
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script>
let optionIndex = {{ $options->count() }};

function addOption() {
    const container = document.getElementById('options-container');
    const optionHtml = `
        <div class="option-row border rounded-lg p-4 hover:bg-gray-50 transition-colors" data-index="${optionIndex}">
            <div class="flex items-start gap-4">
                <!-- Handle de arrastar -->
                <div class="flex-shrink-0 pt-6">
                    <i class="bi bi-grip-vertical text-gray-400 cursor-move text-lg"></i>
                </div>
                
                <!-- Conteúdo da opção -->
                <div class="flex-1">
                    <!-- Linha 1: Campos básicos -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Valor *</label>
                            <input type="text" 
                                   name="options[${optionIndex}][value]" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                   required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Label *</label>
                            <input type="text" 
                                   name="options[${optionIndex}][label]" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                   required>
                        </div>
                    </div>
                    
                    <!-- Linha 2: Campos específicos (imagem/cor) -->
                    @if($extraField->type === 'image' || $extraField->type === 'color')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        @if($extraField->type === 'image')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="bi bi-image mr-1"></i> Imagem
                            </label>
                            <div class="flex gap-2">
                                <input type="text" 
                                       name="options[${optionIndex}][image_url]" 
                                       id="image_url_${optionIndex}"
                                       placeholder="products/material.jpg"
                                       readonly
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary">
                                <button type="button"
                                        onclick="openFileManagerForOption(${optionIndex})"
                                        class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center gap-1 text-sm">
                                    <i class="bi bi-folder-open"></i>
                                    <span class="hidden sm:inline">Selecionar</span>
                                </button>
                            </div>
                            <div id="preview_${optionIndex}" class="mt-2 hidden">
                                <img id="preview_img_${optionIndex}"
                                     src=""
                                     alt="Preview"
                                     class="w-16 h-16 object-cover rounded border border-gray-300">
                            </div>
                        </div>
                        @endif
                        
                        @if($extraField->type === 'color')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="bi bi-palette mr-1"></i> Código da Cor (Hex)
                            </label>
                            <div class="flex gap-2">
                                <input type="color" 
                                       id="color_picker_${optionIndex}"
                                       value="#000000"
                                       onchange="document.getElementById('color_hex_${optionIndex}').value = this.value"
                                       class="h-10 w-12 border border-gray-300 rounded cursor-pointer">
                                <input type="text" 
                                       id="color_hex_${optionIndex}"
                                       name="options[${optionIndex}][color_hex]" 
                                       placeholder="#FF0000"
                                       pattern="^#[0-9A-Fa-f]{6}$"
                                       onchange="document.getElementById('color_picker_${optionIndex}').value = this.value"
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif
                    
                    <!-- Linha 3: Preço e Tipo -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Preço *</label>
                            <input type="number" 
                                   name="options[${optionIndex}][price]" 
                                   step="0.01"
                                   min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                   required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Preço *</label>
                            <select name="options[${optionIndex}][price_type]" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                    required>
                                <option value="fixed">Fixo</option>
                                <option value="percentage">Percentual</option>
                                <option value="per_unit">Por Unidade</option>
                                <option value="per_area">Por Área</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Linha 4: Controles -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pt-4 border-t border-gray-200">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   name="options[${optionIndex}][is_active]" 
                                   value="1"
                                   checked
                                   class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                            <label class="ml-2 text-sm text-gray-900">Ativo</label>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                Ordem: <span class="font-medium">${optionIndex + 1}</span>
                            </span>
                            <button type="button" 
                                    onclick="removeOption(this)"
                                    class="px-3 py-1 text-red-600 hover:text-red-900 hover:bg-red-50 rounded-lg transition-colors flex items-center gap-1 text-sm">
                                <i class="bi bi-trash"></i>
                                Remover
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', optionHtml);
    optionIndex++;
    
    // Reinicializar sortable após adicionar nova opção
    initializeSortable();
}

function removeOption(button) {
    const optionRow = button.closest('.option-row');
    optionRow.remove();
    updateOrderNumbers();
}

function updateOrderNumbers() {
    const options = document.querySelectorAll('.option-row');
    options.forEach((option, index) => {
        const orderSpan = option.querySelector('.font-medium');
        if (orderSpan) {
            orderSpan.textContent = index + 1;
        }
    });
}

function initializeSortable() {
    $("#options-container").sortable({
        items: ".option-row",
        cursor: "move",
        opacity: 0.6,
        handle: ".bi-grip-vertical",
        update: function(event, ui) {
            updateOrderNumbers();
            console.log('Ordem das opções atualizada!');
        }
    }).disableSelection();
}

// Reindexar campos após remoção
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar sortable
    initializeSortable();
    
    const form = document.querySelector('form');
    form.addEventListener('submit', function() {
        const options = document.querySelectorAll('.option-row');
        options.forEach((option, index) => {
            const inputs = option.querySelectorAll('input, select');
            inputs.forEach(input => {
                const name = input.getAttribute('name');
                if (name) {
                    input.setAttribute('name', name.replace(/\[\d+\]/, `[${index}]`));
                }
            });
        });
    });
});

// File Manager para Opções
let currentOptionIndex = null;
let fileManagerData = {
    items: [],
    folders: [],
    files: [],
    loading: false,
    currentPath: '',
    breadcrumb: []
};

// Debug: Verificar se o script está carregando
console.log('🚀 Script do file manager carregado!');

// Debug: Verificar se o modal existe
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('optionFileManagerModal');
    console.log('🔍 Modal encontrado:', modal ? 'SIM' : 'NÃO');
    if (modal) {
        console.log('✅ Modal está no DOM');
    } else {
        console.error('❌ Modal não encontrado no DOM!');
    }
});

function openFileManagerForOption(optionIndex) {
    console.log('🎯 Abrindo file manager para opção:', optionIndex);
    currentOptionIndex = optionIndex;
    const modal = document.getElementById('optionFileManagerModal');
    if (!modal) {
        console.error('❌ Modal não encontrado!');
        alert('Erro: Modal do file manager não encontrado');
        return;
    }
    modal.classList.remove('hidden');
    loadFileManagerImages();
}

function closeOptionFileManager() {
    const modal = document.getElementById('optionFileManagerModal');
    modal.classList.add('hidden');
    currentOptionIndex = null;
}

async function loadFileManagerImages() {
    console.log('📁 Carregando file manager...');
    const loading = document.getElementById('optionFileManagerLoading');
    const grid = document.getElementById('optionFileManagerGrid');
    
    if (!loading || !grid) {
        console.error('❌ Elementos do modal não encontrados!');
        return;
    }
    
    loading.classList.remove('hidden');
    grid.classList.add('hidden');
    
    // Começar na raiz
    fileManagerData.currentPath = '';
    await loadCurrentDirectory();
}

async function loadCurrentDirectory() {
    console.log('📁 Carregando diretório:', fileManagerData.currentPath);
    const loading = document.getElementById('optionFileManagerLoading');
    const grid = document.getElementById('optionFileManagerGrid');
    
    if (!loading || !grid) {
        console.error('❌ Elementos do modal não encontrados!');
        return;
    }
    
    loading.classList.remove('hidden');
    grid.classList.add('hidden');
    
    try {
        // Construir URL corretamente - se currentPath está vazio, não adicionar parâmetro directory
        let url = '{{ route("admin.admin.file-manager.index") }}';
        if (fileManagerData.currentPath && fileManagerData.currentPath.trim() !== '') {
            url += `?directory=${encodeURIComponent(fileManagerData.currentPath)}`;
        }
        console.log('🌐 Fazendo requisição para:', url);
        
        const response = await fetch(url);
        console.log('📡 Resposta recebida:', response.status);
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        const data = await response.json();
        console.log('📊 Dados recebidos:', data);
        console.log('🔍 URL da requisição:', url);
        console.log('🔍 Current path:', fileManagerData.currentPath);
        
        // Separar pastas e arquivos
        fileManagerData.folders = data.items.filter(item => item.type === 'directory');
        fileManagerData.files = data.items.filter(item => 
            item.type === 'file' && isImageFile(item.name)
        );
        
        // Atualizar breadcrumb
        updateBreadcrumb();
        
        console.log('📁 Pastas encontradas:', fileManagerData.folders.length);
        console.log('🖼️ Imagens encontradas:', fileManagerData.files.length);
        console.log('📊 Dados completos:', data.items);
        
        displayFileManagerContent();
    } catch (error) {
        console.error('❌ Erro ao carregar diretório:', error);
        alert('Erro ao carregar diretório: ' + error.message);
    }
}

function updateBreadcrumb() {
    const parts = fileManagerData.currentPath ? fileManagerData.currentPath.split('/').filter(p => p) : [];
    fileManagerData.breadcrumb = parts.map((part, index) => ({
        name: part,
        path: parts.slice(0, index + 1).join('/')
    }));
    
    // Atualizar o breadcrumb no DOM (se usando Alpine.js)
    if (window.Alpine && window.Alpine.store) {
        // Para compatibilidade com Alpine.js se necessário
    }
}

async function getAllImagesRecursively() {
    const allImages = [];
    
    async function scanDirectory(path = '') {
        try {
            const url = `{{ route("admin.admin.file-manager.index") }}?directory=${path}`;
            const response = await fetch(url);
            const data = await response.json();
            
            if (data.items) {
                for (const item of data.items) {
                    if (item.type === 'file' && isImageFile(item.name)) {
                        allImages.push(item);
                    } else if (item.type === 'directory') {
                        // Recursivamente escanear subdiretórios
                        await scanDirectory(item.path);
                    }
                }
            }
        } catch (error) {
            console.warn('Erro ao escanear diretório:', path, error);
        }
    }
    
    await scanDirectory();
    return allImages;
}

function isImageFile(filename) {
    const imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp', 'bmp', 'ico'];
    const extension = filename.split('.').pop().toLowerCase();
    return imageExtensions.includes(extension);
}

function displayFileManagerContent() {
    const grid = document.getElementById('optionFileManagerGrid');
    const loading = document.getElementById('optionFileManagerLoading');
    
    if (!grid || !loading) {
        console.error('❌ Elementos principais do modal não encontrados!');
        return;
    }
    
    loading.classList.add('hidden');
    grid.classList.remove('hidden');
    
    const foldersSection = document.getElementById('foldersSection');
    const filesSection = document.getElementById('filesSection');
    const foldersGrid = document.getElementById('foldersGrid');
    const filesGrid = document.getElementById('filesGrid');
    
    // Atualizar breadcrumb no DOM
    updateBreadcrumbDOM();
    
    // Mostrar/ocultar seções (verificar se existem)
    if (foldersSection) {
        foldersSection.style.display = fileManagerData.folders.length > 0 ? 'block' : 'none';
    }
    if (filesSection) {
        filesSection.style.display = fileManagerData.files.length > 0 ? 'block' : 'none';
    }
    
    // Renderizar pastas
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
    
    // Renderizar arquivos
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
                    
                    <!-- Overlay de seleção -->
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all flex items-center justify-center">
                        <i class="bi bi-check-circle-fill text-white text-3xl opacity-0 group-hover:opacity-100 transition-opacity"></i>
                    </div>
                    
                    <!-- Informações do arquivo -->
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-2">
                        <p class="text-white text-xs font-medium truncate mb-1">${file.name}</p>
                        <div class="flex justify-between items-center text-xs text-gray-300">
                            <span>${fileExtension}</span>
                            <span>${fileSize}</span>
                        </div>
                    </div>
                    
                    <!-- Badge de tipo de arquivo -->
                    <div class="absolute top-2 right-2 bg-black/60 text-white text-xs px-2 py-1 rounded-full">
                        ${fileExtension}
                    </div>
                </div>
            `;
        }).join('');
    }
    
    // Se não há conteúdo
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

function applyFilters(items) {
    const searchTerm = document.getElementById('imageSearchInput')?.value.toLowerCase() || '';
    const folderFilter = document.getElementById('folderFilter')?.value || '';
    
    return items.filter(item => {
        const matchesSearch = item.name.toLowerCase().includes(searchTerm);
        const folder = item.path.split('/').slice(0, -1).join('/') || 'Raiz';
        const matchesFolder = !folderFilter || folder === folderFilter;
        
        return matchesSearch && matchesFolder;
    });
}

function updateFolderFilter(folders) {
    const select = document.getElementById('folderFilter');
    if (!select) return;
    
    // Manter a opção "Todas as pastas" e adicionar novas pastas
    const currentValue = select.value;
    select.innerHTML = '<option value="">Todas as pastas</option>';
    
    folders.forEach(folder => {
        const option = document.createElement('option');
        option.value = folder;
        option.textContent = folder;
        select.appendChild(option);
    });
    
    // Restaurar seleção anterior
    select.value = currentValue;
}

function formatFileSize(bytes) {
    if (!bytes) return '0 B';
    const k = 1024;
    const sizes = ['B', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
}

function updateBreadcrumbDOM() {
    // Atualizar breadcrumb no DOM
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
    console.log('📁 Navegando para pasta:', path);
    fileManagerData.currentPath = path;
    loadCurrentDirectory();
}

function selectImageForOption(imagePath) {
    console.log('🖼️ Selecionando imagem:', imagePath);
    if (currentOptionIndex === null) return;
    
    const input = document.getElementById(`image_url_${currentOptionIndex}`);
    if (input) {
        // Remover 'public/' do início se existir
        const cleanPath = imagePath.startsWith('public/') ? imagePath.replace('public/', '') : imagePath;
        input.value = cleanPath;
        
        // Atualizar preview
        const preview = document.getElementById(`preview_${currentOptionIndex}`);
        const previewImg = document.getElementById(`preview_img_${currentOptionIndex}`);
        if (preview && previewImg) {
            previewImg.src = `/images/${cleanPath}`;
            preview.classList.remove('hidden');
        }
        
        console.log('✅ Imagem selecionada:', cleanPath);
    }
    
    closeOptionFileManager();
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
        console.log('📁 Arquivo selecionado:', file.name, formatFileSize(file.size));
        
        // Verificar tipo de arquivo
        if (!file.type.startsWith('image/')) {
            alert('Por favor, selecione apenas arquivos de imagem.');
            event.target.value = '';
            return;
        }
        
        // Verificar tamanho (5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert('O arquivo deve ter no máximo 5MB.');
            event.target.value = '';
            return;
        }
        
        // Mostrar preview
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

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    // Busca em tempo real
    const searchInput = document.getElementById('imageSearchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            if (fileManagerData.items.length > 0) {
                displayFileManagerImages();
            }
        });
    }
    
    // Filtro de pasta
    const folderFilter = document.getElementById('folderFilter');
    if (folderFilter) {
        folderFilter.addEventListener('change', function() {
            if (fileManagerData.items.length > 0) {
                displayFileManagerImages();
            }
        });
    }
    
    // Upload form
    const uploadForm = document.getElementById('uploadForm');
    if (uploadForm) {
        uploadForm.addEventListener('submit', handleUpload);
    }
    
    // Drag & Drop
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

async function handleUpload(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const fileInput = document.getElementById('imageFileInput');
    
    if (!fileInput.files[0]) {
        alert('Por favor, selecione um arquivo para upload.');
        return;
    }
    
    // Mostrar progresso
    document.getElementById('uploadContent').classList.add('hidden');
    document.getElementById('uploadProgress').classList.remove('hidden');
    document.getElementById('uploadButton').disabled = true;
    
    try {
        const response = await fetch('{{ route("admin.admin.file-manager.upload") }}', {
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
            // Mostrar sucesso
            document.getElementById('uploadProgress').classList.add('hidden');
            document.getElementById('uploadSuccess').classList.remove('hidden');
            
            // Recarregar imagens após 1 segundo
            setTimeout(() => {
                loadFileManagerImages();
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
</script>
@endpush

<!-- File Manager Modal -->
<div id="optionFileManagerModal" 
     class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50 hidden"
     onclick="if(event.target === this) closeOptionFileManager()">
    <div class="bg-white rounded-2xl shadow-2xl w-11/12 h-5/6 mx-4 flex flex-col max-w-6xl"
         onclick="event.stopPropagation()">
        <!-- Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-t-2xl">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                    <i class="bi bi-folder text-white text-lg"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Selecionar Imagem</h3>
                    <p class="text-sm text-gray-600">Escolha uma imagem para a opção</p>
                </div>
            </div>
            
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
                        <i class="bi bi-arrow-clockwise animate-spin text-2xl text-blue-600"></i>
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
                Clique em uma imagem para selecionar
            </div>
            <button onclick="closeOptionFileManager()"
                    class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors font-medium">
                Cancelar
            </button>
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div id="uploadModal" 
     class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50 hidden"
     onclick="if(event.target === this) closeUploadModal()">
    <div class="bg-white rounded-2xl shadow-2xl w-96 mx-4"
         onclick="event.stopPropagation()">
        <!-- Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center">
                    <i class="bi bi-upload text-white text-lg"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Upload de Imagem</h3>
                    <p class="text-sm text-gray-600">Envie uma nova imagem</p>
                </div>
            </div>
            <button onclick="closeUploadModal()" 
                    class="w-10 h-10 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center transition-colors">
                <i class="bi bi-x text-gray-600 text-lg"></i>
            </button>
        </div>
        
        <!-- Upload Content -->
        <div class="p-6">
            <form id="uploadForm" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <!-- Pasta de destino -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="bi bi-folder mr-1"></i> Pasta de destino
                        </label>
                        <select name="directory" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">Raiz</option>
                            <option value="products">products/</option>
                            <option value="materials">materials/</option>
                            <option value="categories">categories/</option>
                            <option value="uploads">uploads/</option>
                        </select>
                    </div>
                    
                    <!-- Upload Area -->
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-green-500 transition-colors"
                         onclick="document.getElementById('imageFileInput').click()"
                         id="uploadArea">
                        <div id="uploadContent">
                            <i class="bi bi-cloud-upload text-4xl text-gray-400 mb-4"></i>
                            <p class="text-gray-600 font-medium">Clique ou arraste uma imagem aqui</p>
                            <p class="text-sm text-gray-500 mt-2">JPG, PNG, GIF, SVG, WebP (máx. 5MB)</p>
                        </div>
                        <div id="uploadProgress" class="hidden">
                            <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
                                <div id="progressBar" class="bg-green-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                            </div>
                            <p class="text-sm text-gray-600">Enviando...</p>
                        </div>
                        <div id="uploadSuccess" class="hidden">
                            <i class="bi bi-check-circle text-4xl text-green-500 mb-4"></i>
                            <p class="text-green-600 font-medium">Upload concluído!</p>
                        </div>
                    </div>
                    
                    <input type="file" 
                           id="imageFileInput"
                           name="file"
                           accept="image/*"
                           class="hidden"
                           onchange="handleFileSelect(event)">
                </div>
                
                <!-- Actions -->
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" 
                            onclick="closeUploadModal()"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" 
                            id="uploadButton"
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
