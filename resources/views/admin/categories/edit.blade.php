@extends('admin.layout')

@section('title', 'Editar Categoria - Laser Link')
@section('page-title', 'Editar Categoria')

@section('content')
<div class="space-y-6">
    <form method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nome da Categoria *
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $category->name) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('name') border-red-500 @enderror"
                           required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Descrição
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('description') border-red-500 @enderror">{{ old('description', $category->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="md:col-span-2">
                    <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Categoria Pai (Deixe em branco para categoria principal)
                    </label>
                    <select id="parent_id" 
                            name="parent_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('parent_id') border-red-500 @enderror">
                        <option value="">Nenhuma (Categoria Principal)</option>
                        @foreach($parentCategories as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                                {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('parent_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Selecione uma categoria pai para tornar esta uma subcategoria</p>
                </div>
                
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                        Imagem
                    </label>
                    
                    @if($category->image)
                        <div class="mb-4">
                            <img src="{{ asset('storage/' . $category->image) }}" 
                                 alt="{{ $category->name }}" 
                                 class="h-20 w-20 object-cover rounded-lg">
                            <div class="mt-2">
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="remove_image" 
                                           value="1"
                                           class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-red-600">Remover imagem atual</span>
                                </label>
                            </div>
                        </div>
                    @endif
                    
                    <input type="file" 
                           id="image" 
                           name="image" 
                           accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('image') border-red-500 @enderror">
                    <p class="text-xs text-gray-500 mt-1">Tamanho máximo: 2MB</p>
                    @error('image')
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
                           value="{{ old('sort_order', $category->sort_order) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('sort_order') border-red-500 @enderror">
                    @error('sort_order')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="home_order" class="block text-sm font-medium text-gray-700 mb-2">
                        Ordem na Home
                    </label>
                    <input type="number" 
                           id="home_order" 
                           name="home_order" 
                           value="{{ old('home_order', $category->home_order) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('home_order') border-red-500 @enderror">
                    @error('home_order')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="md:col-span-2">
                    <div class="flex flex-col space-y-4">
                        <div class="flex items-center">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" 
                                   id="is_active" 
                                   name="is_active" 
                                   value="1"
                                   {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                                   class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                Categoria ativa
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="hidden" name="show_in_home" value="0">
                            <input type="checkbox" 
                                   id="show_in_home" 
                                   name="show_in_home" 
                                   value="1"
                                   {{ old('show_in_home', $category->show_in_home) ? 'checked' : '' }}
                                   class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                            <label for="show_in_home" class="ml-2 block text-sm text-gray-900">
                                Exibir na página inicial
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Botões -->
        <div class="flex justify-end space-x-4 mt-4">
            <a href="{{ route('admin.categories.index') }}" 
               class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                Cancelar
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors">
                Atualizar Categoria
            </button>
        </div>
    </form>
</div>
@endsection