@extends('admin.layout')

@section('title', 'Criar Campo Extra')
@section('page-title', 'Criar Campo Extra')

@section('content')
<div class="space-y-6">
    <form method="POST" action="{{ route('admin.extra-fields.store') }}">
        @csrf
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nome do Campo *
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
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                        Tipo do Campo *
                    </label>
                    <select id="type" 
                            name="type" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('type') border-red-500 @enderror"
                            required>
                        <option value="">Selecione o tipo</option>
                        <option value="text" {{ old('type') == 'text' ? 'selected' : '' }}>Texto</option>
                        <option value="textarea" {{ old('type') == 'textarea' ? 'selected' : '' }}>Área de Texto</option>
                        <option value="select" {{ old('type') == 'select' ? 'selected' : '' }}>Seleção (Dropdown)</option>
                        <option value="radio" {{ old('type') == 'radio' ? 'selected' : '' }}>Radio Buttons</option>
                        <option value="checkbox" {{ old('type') == 'checkbox' ? 'selected' : '' }}>Checkboxes</option>
                        <option value="image" {{ old('type') == 'image' ? 'selected' : '' }}>Seleção Visual (Imagens)</option>
                        <option value="color" {{ old('type') == 'color' ? 'selected' : '' }}>Seleção Visual (Cores)</option>
                        <option value="number" {{ old('type') == 'number' ? 'selected' : '' }}>Número</option>
                        <option value="date" {{ old('type') == 'date' ? 'selected' : '' }}>Data</option>
                        <option value="file" {{ old('type') == 'file' ? 'selected' : '' }}>Arquivo</option>
                    </select>
                    @error('type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Descrição
                </label>
                <textarea id="description" 
                          name="description" 
                          rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex items-center">
                    <input type="checkbox" 
                           id="is_required" 
                           name="is_required" 
                           value="1"
                           {{ old('is_required') ? 'checked' : '' }}
                           class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                    <label for="is_required" class="ml-2 block text-sm text-gray-900">
                        Campo obrigatório
                    </label>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" 
                           id="is_active" 
                           name="is_active" 
                           value="1"
                           {{ old('is_active', true) ? 'checked' : '' }}
                           class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-900">
                        Campo ativo
                    </label>
                </div>
            </div>

            <!-- Configurações Específicas do Campo -->
            <div id="field-specific-settings" class="mt-6 space-y-6" style="display: none;">
                <!-- Configurações para campos de texto -->
                <div id="text-settings" class="field-type-settings" style="display: none;">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Configurações de Texto</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="settings_placeholder" class="block text-sm font-medium text-gray-700 mb-2">
                                Placeholder
                            </label>
                            <input type="text" 
                                   id="settings_placeholder" 
                                   name="settings[placeholder]" 
                                   value="{{ old('settings.placeholder') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        <div>
                            <label for="settings_unit" class="block text-sm font-medium text-gray-700 mb-2">
                                Unidade
                            </label>
                            <input type="text" 
                                   id="settings_unit" 
                                   name="settings[unit]" 
                                   value="{{ old('settings.unit') }}"
                                   placeholder="Ex: cm, mm, kg"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                    </div>
                </div>

                <!-- Configurações para campos numéricos -->
                <div id="number-settings" class="field-type-settings" style="display: none;">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Configurações Numéricas</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="settings_min" class="block text-sm font-medium text-gray-700 mb-2">
                                Valor Mínimo
                            </label>
                            <input type="number" 
                                   id="settings_min" 
                                   name="settings[min]" 
                                   value="{{ old('settings.min') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        <div>
                            <label for="settings_max" class="block text-sm font-medium text-gray-700 mb-2">
                                Valor Máximo
                            </label>
                            <input type="number" 
                                   id="settings_max" 
                                   name="settings[max]" 
                                   value="{{ old('settings.max') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        <div>
                            <label for="settings_step" class="block text-sm font-medium text-gray-700 mb-2">
                                Incremento
                            </label>
                            <input type="number" 
                                   id="settings_step" 
                                   name="settings[step]" 
                                   value="{{ old('settings.step', 1) }}"
                                   step="0.01"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                    </div>
                </div>

                <!-- Configurações para textarea -->
                <div id="textarea-settings" class="field-type-settings" style="display: none;">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Configurações de Área de Texto</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="settings_rows" class="block text-sm font-medium text-gray-700 mb-2">
                                Número de Linhas
                            </label>
                            <input type="number" 
                                   id="settings_rows" 
                                   name="settings[rows]" 
                                   value="{{ old('settings.rows', 3) }}"
                                   min="1"
                                   max="20"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        <div>
                            <label for="settings_placeholder" class="block text-sm font-medium text-gray-700 mb-2">
                                Placeholder
                            </label>
                            <input type="text" 
                                   id="settings_placeholder" 
                                   name="settings[placeholder]" 
                                   value="{{ old('settings.placeholder') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Regras de Validação -->
            <div class="mt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Regras de Validação</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="validation_min_length" class="block text-sm font-medium text-gray-700 mb-2">
                            Comprimento Mínimo
                        </label>
                        <input type="number" 
                               id="validation_min_length" 
                               name="validation_rules[min_length]" 
                               value="{{ old('validation_rules.min_length') }}"
                               min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label for="validation_max_length" class="block text-sm font-medium text-gray-700 mb-2">
                            Comprimento Máximo
                        </label>
                        <input type="number" 
                               id="validation_max_length" 
                               name="validation_rules[max_length]" 
                               value="{{ old('validation_rules.max_length') }}"
                               min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                </div>
            </div>

            <!-- Ordem de Exibição -->
            <div class="mt-6">
                <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                    Ordem de Exibição
                </label>
                <input type="number" 
                       id="sort_order" 
                       name="sort_order" 
                       value="{{ old('sort_order', 0) }}"
                       min="0"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                <p class="text-sm text-gray-500 mt-1">Menor número aparece primeiro</p>
            </div>
        </div>

        <div class="flex justify-end space-x-3 mt-4">
            <a href="{{ route('admin.extra-fields.index') }}" 
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition-colors">
                Cancelar
            </a>
            <button type="submit" 
                    class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg transition-colors">
                Criar Campo
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const fieldSpecificSettings = document.getElementById('field-specific-settings');
    const fieldTypeSettings = document.querySelectorAll('.field-type-settings');
    
    function toggleFieldSettings() {
        const selectedType = typeSelect.value;
        
        // Esconder todas as configurações específicas
        fieldTypeSettings.forEach(setting => {
            setting.style.display = 'none';
        });
        
        // Mostrar configurações específicas baseadas no tipo
        if (selectedType === 'text' || selectedType === 'number') {
            fieldSpecificSettings.style.display = 'block';
            if (selectedType === 'text') {
                document.getElementById('text-settings').style.display = 'block';
            } else if (selectedType === 'number') {
                document.getElementById('number-settings').style.display = 'block';
            }
        } else if (selectedType === 'textarea') {
            fieldSpecificSettings.style.display = 'block';
            document.getElementById('textarea-settings').style.display = 'block';
        } else {
            fieldSpecificSettings.style.display = 'none';
        }
    }
    
    // Event listener para mudança de tipo
    typeSelect.addEventListener('change', toggleFieldSettings);
    
    // Executar na carga inicial
    toggleFieldSettings();
});
</script>
@endsection
