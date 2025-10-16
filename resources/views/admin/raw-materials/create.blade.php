@extends('admin.layout')

@section('title', 'Novo Material')
@section('page-title', 'Novo Material')

@section('content')
<div class="space-y-6">
    <form action="{{ route('admin.raw-materials.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-6">Informações Básicas</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-2">Código/SKU *</label>
                    <input type="text" name="code" id="code" value="{{ old('code') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('code') border-red-500 @enderror"
                           placeholder="Ex: ACR-3MM-TRANSP">
                    @error('code')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nome do Material *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('name') border-red-500 @enderror"
                           placeholder="Ex: Acrílico 3mm Transparente">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Categoria *</label>
                    <select name="category" id="category" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('category') border-red-500 @enderror">
                        <option value="">Selecione...</option>
                        @foreach(App\Models\RawMaterial::CATEGORIES as $key => $label)
                            <option value="{{ $key }}" {{ old('category') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('category')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="supplier_id" class="block text-sm font-medium text-gray-700 mb-2">Fornecedor</label>
                    <select name="supplier_id" id="supplier_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('supplier_id') border-red-500 @enderror">
                        <option value="">Nenhum</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('supplier_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Descrição</label>
                <textarea name="description" id="description" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('description') border-red-500 @enderror"
                          placeholder="Descrição do material...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6">
                <label for="specifications" class="block text-sm font-medium text-gray-700 mb-2">Especificações Técnicas</label>
                <textarea name="specifications" id="specifications" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('specifications') border-red-500 @enderror"
                          placeholder="Dimensões, peso, características técnicas...">{{ old('specifications') }}</textarea>
                @error('specifications')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-6">Controle de Estoque</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="unit" class="block text-sm font-medium text-gray-700 mb-2">Unidade de Medida *</label>
                    <select name="unit" id="unit" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('unit') border-red-500 @enderror">
                        @foreach(App\Models\RawMaterial::UNITS as $key => $label)
                            <option value="{{ $key }}" {{ old('unit') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('unit')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="unit_cost" class="block text-sm font-medium text-gray-700 mb-2">Custo por Unidade *</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">R$</span>
                        <input type="number" name="unit_cost" id="unit_cost" value="{{ old('unit_cost', 0) }}" 
                               step="0.01" min="0" required
                               class="w-full pl-10 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('unit_cost') border-red-500 @enderror">
                    </div>
                    @error('unit_cost')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                <div>
                    <label for="stock_quantity" class="block text-sm font-medium text-gray-700 mb-2">Estoque Inicial *</label>
                    <input type="number" name="stock_quantity" id="stock_quantity" value="{{ old('stock_quantity', 0) }}" 
                           step="0.001" min="0" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('stock_quantity') border-red-500 @enderror">
                    @error('stock_quantity')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="stock_min" class="block text-sm font-medium text-gray-700 mb-2">Estoque Mínimo *</label>
                    <input type="number" name="stock_min" id="stock_min" value="{{ old('stock_min', 0) }}" 
                           step="0.001" min="0" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('stock_min') border-red-500 @enderror">
                    @error('stock_min')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="stock_max" class="block text-sm font-medium text-gray-700 mb-2">Estoque Máximo</label>
                    <input type="number" name="stock_max" id="stock_max" value="{{ old('stock_max') }}" 
                           step="0.001" min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('stock_max') border-red-500 @enderror">
                    @error('stock_max')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <label class="flex items-center">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                       class="rounded border-gray-300 text-primary focus:ring-primary">
                <span class="ml-2 text-sm text-gray-700">Material ativo</span>
            </label>
        </div>

        <div class="flex justify-end gap-4">
            <a href="{{ route('admin.raw-materials.index') }}" 
               class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                Cancelar
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors">
                Salvar Material
            </button>
        </div>
    </form>
</div>
@endsection
