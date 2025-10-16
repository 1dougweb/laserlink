@extends('admin.layout')

@section('title', 'Relatórios')
@section('page-title', 'Relatórios')

@section('content')
<div class="space-y-6">
    <!-- Estatísticas Gerais -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total de Pedidos -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="selo rounded-full bg-blue-100 text-blue-600">
                    <i class="bi bi-cart-check text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total de Pedidos</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($totalOrders, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ number_format($monthOrders, 0, ',', '.') }} este mês</p>
                </div>
            </div>
        </div>

        <!-- Receita Total -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="selo rounded-full bg-green-100 text-green-600">
                    <i class="bi bi-currency-dollar text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Receita Total</p>
                    <p class="text-2xl font-semibold text-gray-900">R$ {{ number_format($totalRevenue, 2, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 mt-1">R$ {{ number_format($monthRevenue, 2, ',', '.') }} este mês</p>
                </div>
            </div>
        </div>

        <!-- Total de Produtos -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="selo rounded-full bg-purple-100 text-purple-600">
                    <i class="bi bi-box text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Produtos Ativos</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($totalProducts, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 mt-1">Catálogo completo</p>
                </div>
            </div>
        </div>

        <!-- Total de Orçamentos -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="selo rounded-full bg-yellow-100 text-yellow-600">
                    <i class="bi bi-file-text text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total de Orçamentos</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($totalBudgets, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $budgetStats->get('approved', 0) }} aprovados</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Links para Relatórios Detalhados -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Relatório de Vendas -->
        <a href="{{ route('admin.reports.sales') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Relatório de Vendas</h3>
                    <p class="text-sm text-gray-600">
                        Análise completa de vendas, pedidos, receitas e performance por período
                    </p>
                    <span class="inline-flex items-center text-sm font-medium text-primary hover:text-red-700 mt-3">
                        Ver relatório completo
                        <i class="bi bi-arrow-right ml-1"></i>
                    </span>
                </div>
                <div class="selo rounded-full bg-blue-100 text-blue-600">
                    <i class="bi bi-graph-up text-2xl"></i>
                </div>
            </div>
        </a>

        <!-- Relatório de Produtos -->
        <a href="{{ route('admin.reports.products') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Relatório de Produtos</h3>
                    <p class="text-sm text-gray-600">
                        Performance de produtos, categorias, mais vendidos e análises de estoque
                    </p>
                    <span class="inline-flex items-center text-sm font-medium text-primary hover:text-red-700 mt-3">
                        Ver relatório completo
                        <i class="bi bi-arrow-right ml-1"></i>
                    </span>
                </div>
                <div class="selo rounded-full bg-purple-100 text-purple-600">
                    <i class="bi bi-box-seam text-2xl"></i>
                </div>
            </div>
        </a>
    </div>

    <!-- Top 5 Produtos Mais Vendidos -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Top 5 Produtos Mais Vendidos</h3>
        </div>
        <div class="p-6">
            @if($topProducts->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Posição</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantidade</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($topProducts as $index => $product)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center justify-center h-8 w-8 rounded-full {{ $index === 0 ? 'bg-yellow-100 text-yellow-800' : ($index === 1 ? 'bg-gray-100 text-gray-800' : 'bg-orange-100 text-orange-800') }} font-semibold">
                                            {{ $index + 1 }}º
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $product->product_name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ number_format($product->total_quantity, 0, ',', '.') }} unidades</div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 text-center py-4">Nenhum produto vendido ainda</p>
            @endif
        </div>
    </div>

    <!-- Status dos Orçamentos -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Status dos Orçamentos</h3>
        </div>
        <div class="p-6">
            <div class="flex gap-4 mb-6">
                <div class="flex-1 text-center p-4 bg-gray-50 rounded-lg">
                    <div class="text-3xl font-bold text-gray-600">{{ $budgetStats->get('draft', 0) }}</div>
                    <div class="text-sm text-gray-500 mt-1">Rascunhos</div>
                </div>
                <div class="flex-1 text-center p-4 bg-blue-50 rounded-lg">
                    <div class="text-3xl font-bold text-blue-600">{{ $budgetStats->get('sent', 0) }}</div>
                    <div class="text-sm text-gray-500 mt-1">Enviados</div>
                </div>
                <div class="flex-1 text-center p-4 bg-green-50 rounded-lg">
                    <div class="text-3xl font-bold text-green-600">{{ $budgetStats->get('approved', 0) }}</div>
                    <div class="text-sm text-gray-500 mt-1">Aprovados</div>
                </div>
                <div class="flex-1 text-center p-4 bg-red-50 rounded-lg">
                    <div class="text-3xl font-bold text-red-600">{{ $budgetStats->get('rejected', 0) }}</div>
                    <div class="text-sm text-gray-500 mt-1">Rejeitados</div>
                </div>
                <div class="flex-1 text-center p-4 bg-yellow-50 rounded-lg">
                    <div class="text-3xl font-bold text-yellow-600">{{ $budgetStats->get('expired', 0) }}</div>
                    <div class="text-sm text-gray-500 mt-1">Expirados</div>
                </div>
            </div>

            @php
                $totalBudgetCount = $budgetStats->sum();
                $approvalRate = $totalBudgetCount > 0 ? ($budgetStats->get('approved', 0) / $totalBudgetCount) * 100 : 0;
            @endphp

            <div class="pt-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">Taxa de Aprovação</span>
                    <span class="text-sm font-bold text-green-600">{{ number_format($approvalRate, 1) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-600 h-2 rounded-full transition-all" style="width: {{ $approvalRate }}%"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
