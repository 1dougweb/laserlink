@extends('admin.layout')

@section('title', 'Matéria-Prima')
@section('page-title', 'Matéria-Prima')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Matéria-Prima</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.raw-materials.movements') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                <i class="bi bi-clock-history mr-1"></i> Movimentações
            </a>
            <a href="{{ route('admin.raw-materials.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                <i class="bi bi-plus mr-2"></i>Novo Material
            </a>
        </div>
    </div>

    <!-- Cards de Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="selo rounded-full bg-red-100 text-red-600">
                    <i class="bi bi-exclamation-triangle text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Materiais Sem Estoque</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $outOfStockCount }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="selo rounded-full bg-yellow-100 text-yellow-600">
                    <i class="bi bi-dash-circle text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Estoque Baixo</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $lowStockCount }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="selo rounded-full bg-green-100 text-green-600">
                    <i class="bi bi-currency-dollar text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Valor em Estoque</p>
                    <p class="text-2xl font-semibold text-gray-900">R$ {{ number_format($totalValue, 2, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros de Pesquisa -->
    <div class="bg-white rounded-lg shadow" x-data="{ showFilters: {{ request()->hasAny(['search', 'category', 'stock_status', 'is_active']) ? 'true' : 'false' }} }">
        <div class="p-4 border-b border-gray-200">
            <button @click="showFilters = !showFilters" 
                    class="flex items-center justify-between w-full text-left">
                <div class="flex items-center space-x-2">
                    <i class="bi bi-funnel text-gray-600"></i>
                    <span class="font-medium text-gray-900">Filtros de Pesquisa</span>
                    @if(request()->hasAny(['search', 'category', 'stock_status', 'is_active']))
                        <span class="bg-primary bg-opacity-10 text-primary text-xs font-medium px-2.5 py-0.5 rounded-full">
                            Ativos
                        </span>
                    @endif
                </div>
                <i class="bi transition-transform duration-200" 
                   :class="showFilters ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
            </button>
        </div>

        <form method="GET" action="{{ route('admin.raw-materials.index') }}" x-show="showFilters" x-cloak>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Busca -->
                <div class="lg:col-span-2">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="bi bi-search mr-1"></i>
                        Buscar por Nome ou Código
                    </label>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Digite o nome ou código do material..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>

                <!-- Categoria -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="bi bi-tag mr-1"></i>
                        Categoria
                    </label>
                    <select id="category" 
                            name="category" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">Todas</option>
                        @foreach(App\Models\RawMaterial::CATEGORIES as $key => $label)
                            <option value="{{ $key }}" {{ request('category') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Situação do Estoque -->
                <div>
                    <label for="stock_status" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="bi bi-exclamation-triangle mr-1"></i>
                        Situação do Estoque
                    </label>
                    <select id="stock_status" 
                            name="stock_status" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">Todos</option>
                        <option value="low_stock" {{ request('stock_status') === 'low_stock' ? 'selected' : '' }}>Estoque Baixo</option>
                        <option value="out_of_stock" {{ request('stock_status') === 'out_of_stock' ? 'selected' : '' }}>Sem Estoque</option>
                    </select>
                </div>

                <!-- Status Ativo/Inativo -->
                <div>
                    <label for="is_active" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="bi bi-toggle-on mr-1"></i>
                        Status
                    </label>
                    <select id="is_active" 
                            name="is_active" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">Todos</option>
                        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Ativos</option>
                        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inativos</option>
                    </select>
                </div>
            </div>

            <!-- Botões de Ação -->
            <div class="px-6 pb-6 flex justify-between items-center">
                <a href="{{ route('admin.raw-materials.index') }}" 
                   class="text-sm text-gray-600 hover:text-gray-900">
                    <i class="bi bi-x-circle mr-1"></i>
                    Limpar Filtros
                </a>
                <button type="submit" 
                        class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-colors">
                    <i class="bi bi-check-circle mr-1"></i>
                    Aplicar Filtros
                </button>
            </div>
        </form>
    </div>

    <!-- Tabela de Materiais -->
    <div class="bg-white rounded-lg shadow">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Material</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoria</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estoque</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unidade</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Custo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fornecedor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Situação</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($materials as $material)
                    <tr class="{{ $material->isOutOfStock() ? 'bg-red-50' : ($material->isLowStock() ? 'bg-yellow-50' : '') }}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $material->code }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            {{ $material->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $material->category_label }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold {{ $material->isOutOfStock() ? 'text-red-600' : ($material->isLowStock() ? 'text-yellow-600' : 'text-green-600') }}">
                            {{ number_format($material->stock_quantity, 2, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $material->unit_label }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            R$ {{ number_format($material->unit_cost, 2, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $material->supplier->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($material->isOutOfStock())
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Sem Estoque
                                </span>
                            @elseif($material->isLowStock())
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Estoque Baixo
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    OK
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($material->is_active)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Ativo
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Inativo
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('admin.raw-materials.show', $material) }}" class="text-primary hover:text-red-700">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.raw-materials.edit', $material) }}" class="text-primary hover:text-red-700">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.raw-materials.destroy', $material) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Tem certeza?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-4 text-center text-gray-500">
                            Nenhum material cadastrado
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($materials->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $materials->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
