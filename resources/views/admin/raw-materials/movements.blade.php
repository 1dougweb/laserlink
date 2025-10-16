@extends('admin.layout')

@section('title', 'Histórico de Movimentações')
@section('page-title', 'Histórico - Matéria-Prima')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <a href="{{ route('admin.raw-materials.index') }}" class="text-sm text-primary hover:text-red-700">
            <i class="bi bi-arrow-left mr-1"></i> Voltar
        </a>
        <a href="{{ route('admin.raw-materials.movements.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
            <i class="bi bi-plus mr-2"></i>Nova Movimentação
        </a>
    </div>

    <!-- Filtros de Pesquisa -->
    <div class="bg-white rounded-lg shadow" x-data="{ showFilters: {{ request()->hasAny(['material_id', 'type', 'start_date', 'end_date']) ? 'true' : 'false' }} }">
        <div class="p-4 border-b border-gray-200">
            <button @click="showFilters = !showFilters" 
                    class="flex items-center justify-between w-full text-left">
                <div class="flex items-center space-x-2">
                    <i class="bi bi-funnel text-gray-600"></i>
                    <span class="font-medium text-gray-900">Filtros de Pesquisa</span>
                    @if(request()->hasAny(['material_id', 'type', 'start_date', 'end_date']))
                        <span class="bg-primary bg-opacity-10 text-primary text-xs font-medium px-2.5 py-0.5 rounded-full">
                            Ativos
                        </span>
                    @endif
                </div>
                <i class="bi transition-transform duration-200" 
                   :class="showFilters ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
            </button>
        </div>

        <form method="GET" action="{{ route('admin.raw-materials.movements') }}" x-show="showFilters" x-cloak>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Material -->
                <div>
                    <label for="material_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="bi bi-layers mr-1"></i>
                        Material
                    </label>
                    <select id="material_id" 
                            name="material_id" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">Todos</option>
                        @foreach($materials as $material)
                            <option value="{{ $material->id }}" {{ request('material_id') == $material->id ? 'selected' : '' }}>
                                [{{ $material->code }}] {{ $material->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tipo -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="bi bi-arrow-left-right mr-1"></i>
                        Tipo de Movimentação
                    </label>
                    <select id="type" 
                            name="type" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">Todos</option>
                        <option value="entrada" {{ request('type') === 'entrada' ? 'selected' : '' }}>Entrada</option>
                        <option value="saida" {{ request('type') === 'saida' ? 'selected' : '' }}>Saída</option>
                        <option value="ajuste" {{ request('type') === 'ajuste' ? 'selected' : '' }}>Ajuste</option>
                        <option value="producao" {{ request('type') === 'producao' ? 'selected' : '' }}>Produção</option>
                        <option value="devolucao" {{ request('type') === 'devolucao' ? 'selected' : '' }}>Devolução</option>
                    </select>
                </div>

                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="bi bi-calendar mr-1"></i>
                        Data Inicial
                    </label>
                    <input type="date" 
                           id="start_date" 
                           name="start_date" 
                           value="{{ request('start_date') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>

                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="bi bi-calendar mr-1"></i>
                        Data Final
                    </label>
                    <input type="date" 
                           id="end_date" 
                           name="end_date" 
                           value="{{ request('end_date') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
            </div>

            <!-- Botões de Ação -->
            <div class="px-6 pb-6 flex justify-between items-center">
                <a href="{{ route('admin.raw-materials.movements') }}" 
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

    <!-- Tabela -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Movimentações</h3>
        </div>
        @if($movements->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data/Hora</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Material</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantidade</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Antes</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Após</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Custo Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuário</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ref.</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($movements as $movement)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div>{{ $movement->created_at->format('d/m/Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $movement->created_at->format('H:i:s') }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                {{ $movement->rawMaterial->name ?? 'N/A' }}
                                <div class="text-xs text-gray-500">{{ $movement->rawMaterial->code ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $movement->type_color }}">
                                    {{ $movement->type_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold {{ $movement->quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $movement->quantity > 0 ? '+' : '' }}{{ number_format($movement->quantity, 3, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ number_format($movement->stock_before, 3, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                {{ number_format($movement->stock_after, 3, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $movement->total_cost ? 'R$ ' . number_format($movement->total_cost, 2, ',', '.') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $movement->user->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $movement->reference ?? '-' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $movements->links() }}
            </div>
        @else
            <div class="p-6">
                <p class="text-gray-500 text-center">Nenhuma movimentação encontrada</p>
            </div>
        @endif
    </div>
</div>
@endsection
