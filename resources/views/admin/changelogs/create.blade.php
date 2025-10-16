@extends('admin.layout')

@section('title', 'Nova Atualização - Laser Link')
@section('page-title', 'Nova Atualização')

@section('content')
<div class="space-y-6" x-data="changelogForm()">
    <form method="POST" action="{{ route('admin.changelogs.store') }}">
        @csrf
        
        <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
            <div class="flex items-center mb-6">
                <i class="bi bi-info-circle text-primary text-xl mr-3"></i>
                <h3 class="text-lg font-semibold text-gray-900">Informações Básicas</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="version" class="block text-sm font-medium text-gray-700 mb-2">
                        Versão <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="version" 
                           name="version" 
                           value="{{ old('version') }}"
                           placeholder="ex: 1.0.0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('version') border-red-500 @enderror"
                           required>
                    @error('version')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="release_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Data de Lançamento <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           id="release_date" 
                           name="release_date" 
                           value="{{ old('release_date', date('Y-m-d')) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('release_date') border-red-500 @enderror"
                           required>
                    @error('release_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Título da Atualização <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           value="{{ old('title') }}"
                           placeholder="ex: Sistema de Subcategorias e Melhorias de Performance"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('title') border-red-500 @enderror"
                           required>
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Descrição
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="3"
                              placeholder="Breve descrição desta atualização..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="md:col-span-2">
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="is_published" 
                               name="is_published" 
                               value="1"
                               {{ old('is_published', true) ? 'checked' : '' }}
                               class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                        <label for="is_published" class="ml-2 block text-sm text-gray-900">
                            Publicar atualização (visível para todos)
                        </label>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Novas Funcionalidades -->
        <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <i class="bi bi-plus-circle-fill text-green-600 text-xl mr-3"></i>
                    <h3 class="text-lg font-semibold text-gray-900">Novas Funcionalidades</h3>
                </div>
                <button type="button" @click="addFeature()" class="text-primary hover:text-red-700 font-medium text-sm flex items-center gap-1">
                    <i class="bi bi-plus-circle"></i>
                    Adicionar
                </button>
            </div>
            
            <div class="space-y-3">
                <template x-for="(feature, index) in features" :key="'feature-' + index">
                    <div class="flex items-center gap-3">
                        <i class="bi bi-grip-vertical text-gray-400"></i>
                        <input type="text" 
                               x-bind:name="'features[' + index + ']'"
                               x-model="features[index]"
                               placeholder="Descreva a nova funcionalidade..."
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        <button type="button" @click="removeFeature(index)" class="text-red-600 hover:text-red-800 p-2">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </template>
                
                <div x-show="features.length === 0" class="text-center py-4 text-gray-500 text-sm">
                    Nenhuma funcionalidade adicionada
                </div>
            </div>
        </div>
        
        <!-- Melhorias -->
        <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <i class="bi bi-arrow-up-circle-fill text-blue-600 text-xl mr-3"></i>
                    <h3 class="text-lg font-semibold text-gray-900">Melhorias</h3>
                </div>
                <button type="button" @click="addImprovement()" class="text-primary hover:text-red-700 font-medium text-sm flex items-center gap-1">
                    <i class="bi bi-plus-circle"></i>
                    Adicionar
                </button>
            </div>
            
            <div class="space-y-3">
                <template x-for="(improvement, index) in improvements" :key="'improvement-' + index">
                    <div class="flex items-center gap-3">
                        <i class="bi bi-grip-vertical text-gray-400"></i>
                        <input type="text" 
                               x-bind:name="'improvements[' + index + ']'"
                               x-model="improvements[index]"
                               placeholder="Descreva a melhoria..."
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        <button type="button" @click="removeImprovement(index)" class="text-red-600 hover:text-red-800 p-2">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </template>
                
                <div x-show="improvements.length === 0" class="text-center py-4 text-gray-500 text-sm">
                    Nenhuma melhoria adicionada
                </div>
            </div>
        </div>
        
        <!-- Correções de Bugs -->
        <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <i class="bi bi-bug-fill text-orange-600 text-xl mr-3"></i>
                    <h3 class="text-lg font-semibold text-gray-900">Correções de Bugs</h3>
                </div>
                <button type="button" @click="addFix()" class="text-primary hover:text-red-700 font-medium text-sm flex items-center gap-1">
                    <i class="bi bi-plus-circle"></i>
                    Adicionar
                </button>
            </div>
            
            <div class="space-y-3">
                <template x-for="(fix, index) in fixes" :key="'fix-' + index">
                    <div class="flex items-center gap-3">
                        <i class="bi bi-grip-vertical text-gray-400"></i>
                        <input type="text" 
                               x-bind:name="'fixes[' + index + ']'"
                               x-model="fixes[index]"
                               placeholder="Descreva o bug corrigido..."
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        <button type="button" @click="removeFix(index)" class="text-red-600 hover:text-red-800 p-2">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </template>
                
                <div x-show="fixes.length === 0" class="text-center py-4 text-gray-500 text-sm">
                    Nenhuma correção adicionada
                </div>
            </div>
        </div>
        
        <!-- Botões -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.changelogs.index') }}" 
               class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                <i class="bi bi-arrow-left mr-2"></i>Cancelar
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors">
                <i class="bi bi-check-lg mr-2"></i>Salvar Atualização
            </button>
        </div>
    </form>
</div>

<script>
function changelogForm() {
    return {
        features: [],
        improvements: [],
        fixes: [],
        
        addFeature() {
            this.features.push('');
        },
        
        removeFeature(index) {
            this.features.splice(index, 1);
        },
        
        addImprovement() {
            this.improvements.push('');
        },
        
        removeImprovement(index) {
            this.improvements.splice(index, 1);
        },
        
        addFix() {
            this.fixes.push('');
        },
        
        removeFix(index) {
            this.fixes.splice(index, 1);
        }
    }
}
</script>
@endsection

