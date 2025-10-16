@extends('admin.layout')

@section('title', $rawMaterial->name)
@section('page-title', $rawMaterial->name)

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.raw-materials.index') }}" class="text-sm text-primary hover:text-red-700">
            <i class="bi bi-arrow-left mr-1"></i> Voltar
        </a>
        <a href="{{ route('admin.raw-materials.edit', $rawMaterial) }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
            <i class="bi bi-pencil mr-1"></i> Editar
        </a>
    </div>

    <!-- Info do Material -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Informações do Material</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="text-sm font-medium text-gray-500">Código</label>
                    <p class="mt-1 text-base font-medium text-gray-900">{{ $rawMaterial->code }}</p>
                </div>
                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-gray-500">Nome</label>
                    <p class="mt-1 text-base text-gray-900">{{ $rawMaterial->name }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Categoria</label>
                    <p class="mt-1 text-base text-gray-900">{{ $rawMaterial->category_label }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Fornecedor</label>
                    <p class="mt-1 text-base text-gray-900">
                        @if($rawMaterial->supplier)
                            <a href="{{ route('admin.suppliers.show', $rawMaterial->supplier) }}" class="text-primary hover:text-red-700">
                                {{ $rawMaterial->supplier->name }}
                            </a>
                        @else
                            -
                        @endif
                    </p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Estoque Atual</label>
                    <p class="mt-1 text-2xl font-bold {{ $rawMaterial->isOutOfStock() ? 'text-red-600' : ($rawMaterial->isLowStock() ? 'text-yellow-600' : 'text-green-600') }}">
                        {{ number_format($rawMaterial->stock_quantity, 3, ',', '.') }} {{ $rawMaterial->unit_label }}
                    </p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Custo Unitário</label>
                    <p class="mt-1 text-base text-gray-900">R$ {{ number_format($rawMaterial->unit_cost, 2, ',', '.') }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Valor Total</label>
                    <p class="mt-1 text-base font-semibold text-green-600">
                        R$ {{ number_format($rawMaterial->stock_quantity * $rawMaterial->unit_cost, 2, ',', '.') }}
                    </p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Limites</label>
                    <p class="mt-1 text-sm text-gray-900">
                        Mín: {{ number_format($rawMaterial->stock_min, 2, ',', '.') }}
                        @if($rawMaterial->stock_max)
                            | Máx: {{ number_format($rawMaterial->stock_max, 2, ',', '.') }}
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Movimentações Recentes -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Movimentações Recentes</h3>
        </div>
        @if($recentMovements->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qtd.</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Antes</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Após</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Custo Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuário</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($recentMovements as $movement)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $movement->created_at->format('d/m/Y H:i') }}
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
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-6">
                <p class="text-gray-500 text-center">Nenhuma movimentação registrada</p>
            </div>
        @endif
    </div>
</div>
@endsection
