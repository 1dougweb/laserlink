<!-- Notificações -->
<div id="notification-container"></div>

<div 
    x-data="window.extraFieldsData()" 
    class="space-y-6"
>
    @push('styles')
    <style>
        /* Botões redondos e quadrados: */
        .extra-field-action-btn {
            @apply flex items-center justify-center rounded-md w-10 h-10 p-0 border transition-all text-lg;
        }
        /* Ajustes de gap nos cards */
        .extra-field-card:not(:last-child) {
            margin-bottom: 1.25rem; /* 20px (mb-5) */
        }
    </style>
    @endpush

    <!-- Modal de confirmação para remoção de campo extra -->
    <div 
        x-show="fieldToRemove !== null"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        x-cloak
    >
        <div 
            class="bg-white rounded-xl shadow-2xl p-6 max-w-sm w-full"
            @click.stop
        >
            <h2 class="text-lg font-semibold text-gray-900 mb-3">Remover Campo Extra</h2>
            <p class="text-gray-700 mb-4">Tem certeza que deseja remover <span class="font-bold" x-text="fieldToRemoveName"></span> deste produto?</p>
            <div class="flex justify-end gap-3 mt-4">
                <button
                    @click="fieldToRemove = null"
                    type="button"
                    class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition-colors"
                >Cancelar</button>
                <button
                    @click="removeFieldFromProductModal()"
                    type="button"
                    class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition-colors"
                >Remover</button>
            </div>
        </div>
    </div>
<!-- O resto do modal e conteúdo permanece igual, veja o restante do arquivo. -->
    
    <!-- VIEW: LISTA DE CAMPOS -->
    <div :style="currentView === 'list' ? '' : 'display: none;'"  class="flex flex-col gap-4">
    
    <!-- Header com Instruções -->
    <div class="bg-gray-50 to-indigo-50 border border-gray-200 rounded-md p-4">
        <div class="flex items-start space-x-4">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="bi bi-lightbulb text-blue-600 text-lg"></i>
                </div>
    </div>
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Gerenciar Campos Extras</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Adicione campos personalizados ao seu produto (cores, materiais, acabamentos, etc.). 
                    <strong>Arraste e solte</strong> para reordenar os campos associados.
                </p>
                <div class="flex flex-wrap gap-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <i class="bi bi-arrow-up-down mr-1"></i> Arrastar para reordenar
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        <i class="bi bi-plus-circle mr-1"></i> Adicionar campos
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                        <i class="bi bi-gear mr-1"></i> Configurar cada campo
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Campos Associados ao Produto -->
    <div class="bg-white rounded-md border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="bg-green-50 p-4 border-b border-green-200 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="bi bi-check-circle text-green-600"></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Campos Ativos</h2>
                    <p class="text-sm text-gray-600">Campos que aparecerão no produto</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                    <i class="bi bi-ui-checks mr-1.5"></i> {{ $product->extraFields->count() }} campos
                </span>
            </div>
        </div>
        
        <!-- Lista de Campos Associados -->
        <div class="p-4">
            @if($product->extraFields->count() > 0)
            <div class="space-y-4" id="associated-fields" data-sortable="true">
                @foreach($product->extraFields->sortBy('pivot.sort_order') as $field)
                    <div class="bg-white border-1 border-gray-300 rounded-md p-2 group relative"
                         data-field-id="{{ $field->id }}"
                         data-sort-order="{{ $field->pivot->sort_order }}">
                        
                        <div class="flex items-center space-x-4">
                            <!-- Drag Handle -->
                            <div class="field-drag-handle flex-shrink-0 text-gray-400 hover:text-primary transition-colors cursor-grab active:cursor-grabbing p-2 -ml-2 rounded hover:bg-gray-100">
                                <i class="bi bi-grip-vertical text-xl"></i>
                            </div>
                            
                            
                            
                            <!-- Info do Campo -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center space-x-2 mb-1">
                                    <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $field->name }}</h3>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 uppercase">
                                        <i class="bi bi-tag mr-1"></i> {{ $field->type }}
                                    </span>
                                    @if($field->pivot->is_required)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                            <i class="bi bi-asterisk mr-1"></i> Obrigatório
                                        </span>
                                    @endif
                                    @if($field->options && $field->options->count() > 0)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                            <i class="bi bi-ui-checks-grid mr-1"></i> {{ $field->options->count() }} opções
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-600">{{ $field->description }}</p>
                            </div>
                            
                            <!-- Botões de Ação -->
                            <div class="flex flex-shrink-0 gap-2">
                                <button @click="openFieldSettings({{ $field->id }}, '{{ addslashes($field->name) }}', '{{ $field->type }}')" 
                                        class="text-blue-600 hover:text-white w-10 h-10 hover:bg-blue-600 px-2 py-1 rounded-md transition-all duration-200 border border-blue-200 hover:border-blue-600"
                                        title="Configurações">
                                    <i class="bi bi-gear text-lg"></i>
                                </button>
                                <button onclick="removeFieldFromProduct({{ $field->id }}, '{{ addslashes($field->name) }}')" 
                                        class="text-red-600 hover:text-white hover:bg-red-600 px-2.5 py-1 rounded-md transition-all duration-200 border border-red-200 hover:border-red-600"
                                        title="Remover">
                                    <i class="bi bi-trash text-lg"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @else
                <div class="text-center py-12 text-gray-500">
                    <i class="bi bi-inbox text-4xl mb-4"></i>
                    <p>Nenhum campo associado a este produto</p>
                    <p class="text-sm">Adicione campos da lista abaixo</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Campos Disponíveis -->
    <div class="bg-white rounded-md border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="bg-indigo-50 p-4 border-b border-indigo-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <i class="bi bi-plus-circle text-indigo-600"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Campos Disponíveis</h2>
                        <p class="text-sm text-gray-600">Adicione novos campos ao produto</p>
                    </div>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                    {{ $availableFields->count() }} disponíveis
                </span>
            </div>
        </div>
        
        <!-- Lista de Campos Disponíveis -->
        <div class="p-6">
        @if($availableFields->count() > 0)
            <div class="grid grid-cols-1 gap-4">
                @foreach($availableFields as $field)
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-5 hover:border-primary hover:bg-primary/5 transition-all duration-200 cursor-pointer"
                         onclick="addFieldToProduct({{ $field->id }}, '{{ addslashes($field->name) }}')">
                        <div class="flex items-center space-x-4">
                            <!-- Ícone -->
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                    @if($field->type === 'select')
                                        <i class="bi bi-list-ul text-gray-600"></i>
                                    @elseif($field->type === 'textarea')
                                        <i class="bi bi-textarea-t text-gray-600"></i>
                                    @else
                                        <i class="bi bi-input-cursor-text text-gray-600"></i>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Info -->
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900">{{ $field->name }}</h3>
                                <span class="text-xs text-gray-500 uppercase">{{ $field->type }}</span>
                            </div>
                            
                            <p class="text-sm text-gray-600 flex-1">{{ $field->description }}</p>
                            
                            <!-- Botão -->
                            <button class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors flex items-center">
                                <i class="bi bi-plus-lg mr-2"></i>
                                Adicionar
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 text-gray-400">
                <i class="bi bi-check-circle text-4xl mb-4"></i>
                <p>Todos os campos já estão associados</p>
                <p class="text-sm">Ou não há campos extras disponíveis</p>
            </div>
        @endif
        </div>
    </div>
    </div>
    <!-- FIM VIEW: LISTA DE CAMPOS -->

    <!-- VIEW: CONFIGURAÇÕES DO CAMPO -->
    <div :style="currentView === 'settings' ? '' : 'display: none;'" x-init="setTimeout(() => { if (typeof initOptionsSortable === 'function') initOptionsSortable(); }, 200)">
        <!-- Header da Tela de Configurações -->
        <div class="bg-gradient-to-r from-purple-50 to-blue-50 border border-purple-200 rounded-xl p-6 mb-6">
            <div class="flex items-center space-x-4">
                <button @click="currentView = 'list'; selectedField = null" 
                        class="flex-shrink-0 w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center hover:bg-purple-200 transition-colors">
                    <i class="bi bi-arrow-left text-purple-600 text-xl"></i>
                </button>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="bi bi-gear mr-2 text-purple-600"></i>
                        Configurações do Campo
                    </h3>
                    <p class="text-sm text-gray-600 mt-1" x-text="selectedField ? selectedField.name : ''"></p>
                </div>
            </div>
        </div>
        
        <!-- Conteúdo das Configurações -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200">
            
            <div>
                <!-- Título da Seção -->
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">Configurar Opções</h2>
                    <p class="text-sm text-gray-600 mt-1" x-text="selectedField ? (selectedField.name + ' (' + selectedField.type + ')') : ''"></p>
                </div>

                <!-- Formulário de Opções -->
                <form @submit.prevent="saveFieldOptions()" class="p-6">
                    <!-- Lista de Opções -->
                    <div class="space-y-4 mb-6" x-ref="optionsContainer">
                        <template x-for="(option, index) in (selectedField ? selectedField.options : [])" :key="'option-' + index">
                                <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-sm transition-shadow"
                                     :data-option-index="index">
                                    
                                    <!-- Handle de Arrastar -->
                                    <div class="flex items-start space-x-4">
                                        <div class="flex-shrink-0 mt-2 cursor-move text-gray-400">
                                            <i class="bi bi-grip-vertical text-lg"></i>
                                        </div>

                                        <!-- Campos da Opção -->
                                        <div class="flex-1 space-y-4">
                                            <!-- Linha 1: Campos básicos -->
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <!-- Valor -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Valor *</label>
                                                    <input type="text" 
                                                           x-model="option.value"
                                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary text-sm"
                                                           placeholder="valor-unico"
                                                           required>
                                                </div>

                                                <!-- Label -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Label *</label>
                                                    <input type="text" 
                                                           x-model="option.label"
                                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary text-sm"
                                                           placeholder="Nome da opção"
                                                           required>
                                                </div>
                                            </div>
                                            
                                            <!-- Linha 2: Campos específicos (imagem/cor) -->
                                            <template x-if="selectedField && (selectedField.type === 'image' || selectedField.type === 'color')">
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <!-- Campo de Imagem -->
                                                    <template x-if="selectedField.type === 'image'">
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                                <i class="bi bi-image mr-1"></i> Imagem
                                                            </label>
                                                            <div class="flex gap-2">
                                                                <input type="text" 
                                                                       x-model="option.image_url"
                                                                       placeholder="products/material.jpg"
                                                                       readonly
                                                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary text-sm">
                                                                <button type="button"
                                                                        @click="openFileManagerForOption(index)"
                                                                        class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center gap-1 text-sm">
                                                                    <i class="bi bi-folder-open"></i>
                                                                    <span class="hidden sm:inline">Selecionar</span>
                                                                </button>
                                                            </div>
                                                            <div x-show="option.image_url" class="mt-2">
                                                                <img :src="option.image_url ? '/images/' + option.image_url : ''" 
                                                                     alt="Preview"
                                                                     class="w-16 h-16 object-cover rounded border border-gray-300"
                                                                     onerror="this.src='/images/general/callback-image.svg'">
                                                            </div>
                                                        </div>
                                                    </template>
                                                    
                                                    <!-- Campo de Cor -->
                                                    <template x-if="selectedField.type === 'color'">
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                                <i class="bi bi-palette mr-1"></i> Código da Cor (Hex)
                                                            </label>
                                                            <div class="flex gap-2">
                                                                <input type="color" 
                                                                       x-model="option.color_hex"
                                                                       class="h-10 w-12 border border-gray-300 rounded cursor-pointer">
                                                                <input type="text" 
                                                                       x-model="option.color_hex"
                                                                       placeholder="#FF0000"
                                                                       pattern="^#[0-9A-Fa-f]{6}$"
                                                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary text-sm">
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
                                            
                                            <!-- Linha 3: Preço e Tipo -->
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <!-- Preço -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Preço *</label>
                                                    <input type="text" 
                                                           x-model="option.price"
                                                           @input="formatPrice($event)"
                                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary text-sm"
                                                           placeholder="0,00"
                                                           required>
                                                </div>

                                                <!-- Tipo de Preço -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Preço *</label>
                                                    <select x-model="option.price_type"
                                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary text-sm"
                                                            required>
                                                        <option value="fixed">Fixo</option>
                                                        <option value="per_unit">Por Unidade</option>
                                                        <option value="per_area">Por Área</option>
                                                        <option value="percentage">Percentual</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <!-- Linha 4: Ativo -->
                                            <div class="flex items-center">
                                                <input type="checkbox" 
                                                       x-model="option.is_active"
                                                       class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                                <label class="ml-2 text-sm text-gray-700">Ativo</label>
                                            </div>
                                        </div>

                                        <!-- Ordem e Remover -->
                                        <div class="flex-shrink-0 flex flex-col items-end space-y-2">
                                            <div class="text-xs text-gray-500">
                                                Ordem: <span x-text="index + 1"></span>
                                            </div>
                                            <button type="button" 
                                                    @click="removeOption(index)"
                                                    class="text-red-500 hover:text-red-700 text-sm flex items-center">
                                                <i class="bi bi-trash mr-1"></i>
                                                Remover
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Botão Adicionar Opção -->
                        <div class="mb-6">
                            <button type="button" 
                                    @click="addOption()"
                                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                                <i class="bi bi-plus-circle mr-2"></i>
                                Adicionar Opção
                            </button>
                        </div>

                        <!-- Botões de Ação -->
                        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                            <button type="button" 
                                    @click="currentView = 'list'; selectedField = null"
                                    class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                                Cancelar
                            </button>
                            <button type="submit" 
                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                                Salvar Opções
                            </button>
                        </div>
                </form>
            </div>
        </div>
    </div>
    <!-- FIM VIEW: CONFIGURAÇÕES DO CAMPO -->
</div>

<style>
/* Estilos para drag and drop */
.sortable-ghost {
    opacity: 0.4;
    background: #e0e7ff;
}

.sortable-drag {
    opacity: 0.8;
}

.sortable-chosen {
    box-shadow: 0 0 0 2px #4f46e5;
}
</style>

<script>
// Função para inicializar drag and drop das opções
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

// Função para adicionar campo ao produto
async function addFieldToProduct(fieldId, fieldName) {
    try {
        const formData = new FormData();
        formData.append('extra_field_id', fieldId);
        
        const response = await fetch(`{{ route('admin.products.extra-fields.store', $product) }}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            showNotification('✅ Campo adicionado com sucesso!', 'success');
            loadExtraFieldsContent();
        } else {
            showNotification('❌ ' + (data.message || 'Erro ao adicionar campo'), 'error');
        }
    } catch (error) {
        console.error('Erro ao adicionar campo:', error);
        showNotification('❌ Erro ao adicionar campo', 'error');
    }
}

// Função para remover campo do produto
async function removeFieldFromProduct(fieldId, fieldName) {
    if (!confirm(`Tem certeza que deseja remover o campo "${fieldName}" deste produto?`)) {
        return;
    }

    try {
        const response = await fetch(`{{ route('admin.products.extra-fields.destroy', ['product' => $product, 'fieldId' => '__FIELD_ID__']) }}`.replace('__FIELD_ID__', fieldId), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        if (data.success) {
            showNotification('✅ Campo removido com sucesso!', 'success');
            loadExtraFieldsContent();
        } else {
            showNotification('❌ Erro ao remover campo', 'error');
        }
    } catch (error) {
        console.error('Erro ao remover campo:', error);
        showNotification('❌ Erro ao remover campo', 'error');
    }
}
</script>
