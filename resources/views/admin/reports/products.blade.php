@extends('admin.layout')

@section('title', 'Relatório de Produtos')
@section('page-title', 'Relatório de Produtos')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb -->
    <div>
        <a href="{{ route('admin.reports.index') }}" class="text-sm text-primary hover:text-red-700">
            <i class="bi bi-arrow-left mr-1"></i> Voltar para Relatórios
        </a>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Filtros</h3>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('admin.reports.products') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Data Inicial</label>
                    <input type="date" name="start_date" id="start_date" value="{{ $startDate }}" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                </div>

                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Data Final</label>
                    <input type="date" name="end_date" id="end_date" value="{{ $endDate }}" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                </div>

                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Categoria</label>
                    <select name="category_id" id="category_id" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                        <option value="all" {{ $categoryId === 'all' ? 'selected' : '' }}>Todas</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $categoryId == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 bg-primary text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                        <i class="bi bi-funnel mr-1"></i> Filtrar
                    </button>
                    <a href="{{ route('admin.reports.products.export', request()->query()) }}" 
                       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="bi bi-download"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Estatísticas Gerais -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="selo rounded-full bg-purple-100 text-purple-600">
                    <i class="bi bi-box text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total de Produtos Ativos</p>
                    <p class="text-3xl font-semibold text-gray-900">{{ number_format($totalProducts, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="selo rounded-full bg-blue-100 text-blue-600">
                    <i class="bi bi-grid-3x3 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total de Categorias</p>
                    <p class="text-3xl font-semibold text-gray-900">{{ number_format($totalCategories, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance por Categoria -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Performance por Categoria</h3>
        </div>
        <div class="p-6">
            @if($categoryPerformance->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoria</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produtos</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qtd. Vendida</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Receita Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Performance</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php $maxRevenue = $categoryPerformance->max('total_revenue'); @endphp
                            @foreach($categoryPerformance as $category)
                                @php
                                    $percentage = $maxRevenue > 0 ? ($category->total_revenue / $maxRevenue) * 100 : 0;
                                @endphp
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $category->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($category->products_count, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($category->total_sold, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">R$ {{ number_format($category->total_revenue, 2, ',', '.') }}</td>
                                    <td class="px-6 py-4">
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full transition-all" style="width: {{ $percentage }}%"></div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 text-center py-4">Nenhuma categoria encontrada</p>
            @endif
        </div>
    </div>

    <!-- Gráfico de Performance por Categoria -->
    @if($categoryPerformance->count() > 0)
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Receita por Categoria</h3>
        </div>
        <div class="p-6">
            <div style="height: 300px;">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>
    @endif

    <!-- Top 20 Produtos Mais Vendidos -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Top 20 Produtos Mais Vendidos</h3>
        </div>
        <div class="p-6">
            @if($bestSellers->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pos.</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qtd.</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Receita</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pedidos</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($bestSellers as $index => $product)
                                <tr class="{{ $index < 3 ? 'bg-yellow-50' : '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center justify-center h-8 w-8 rounded-full {{ $index === 0 ? 'bg-yellow-400 text-white' : ($index === 1 ? 'bg-gray-300 text-gray-800' : ($index === 2 ? 'bg-orange-400 text-white' : 'bg-gray-100 text-gray-800')) }} font-bold">
                                            {{ $index + 1 }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $product->product_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ number_format($product->total_sold, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">R$ {{ number_format($product->total_revenue, 2, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($product->total_orders, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 text-center py-4">Nenhum produto vendido no período</p>
            @endif
        </div>
    </div>

    <!-- Produtos sem Vendas -->
    @if($productsWithoutSales->count() > 0)
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                Produtos sem Vendas no Período
                <span class="ml-2 text-sm font-normal text-gray-500">({{ $productsWithoutSales->count() }} produtos)</span>
            </h3>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoria</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($productsWithoutSales->take(10) as $product)
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $product->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->category->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $product->is_active ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="text-primary hover:text-red-700">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($productsWithoutSales->count() > 10)
                <div class="mt-4 text-center">
                    <p class="text-sm text-gray-500">
                        Mostrando 10 de {{ $productsWithoutSales->count() }} produtos sem vendas
                    </p>
                </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Distribuição de Produtos por Categoria -->
    @if($productsByCategory->count() > 0)
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Distribuição de Produtos por Categoria</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div style="height: 300px;">
                        <canvas id="distributionChart"></canvas>
                    </div>
                </div>
                
                <div>
                    <div class="space-y-3">
                        @foreach($productsByCategory as $item)
                            @if($item->category)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <span class="text-sm font-medium text-gray-900">{{ $item->category->name }}</span>
                                    <span class="text-sm font-bold text-gray-700">{{ $item->total }} produtos</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    @if($categoryPerformance->count() > 0)
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(categoryCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($categoryPerformance->pluck('name')->toArray()) !!},
            datasets: [{
                label: 'Receita (R$)',
                data: {!! json_encode($categoryPerformance->pluck('total_revenue')->toArray()) !!},
                backgroundColor: 'rgba(59, 130, 246, 0.6)',
                borderColor: 'rgb(59, 130, 246)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { callback: value => 'R$ ' + value.toLocaleString('pt-BR') }
                }
            }
        }
    });
    @endif

    @if($productsByCategory->count() > 0)
    const distributionCtx = document.getElementById('distributionChart').getContext('2d');
    const colors = [
        'rgba(255, 99, 132, 0.6)', 'rgba(54, 162, 235, 0.6)', 'rgba(255, 206, 86, 0.6)',
        'rgba(75, 192, 192, 0.6)', 'rgba(153, 102, 255, 0.6)', 'rgba(255, 159, 64, 0.6)',
        'rgba(199, 199, 199, 0.6)', 'rgba(83, 102, 255, 0.6)', 'rgba(255, 99, 255, 0.6)', 'rgba(99, 255, 132, 0.6)'
    ];
    
    new Chart(distributionCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($productsByCategory->map(fn($item) => $item->category->name ?? 'N/A')->toArray()) !!},
            datasets: [{
                data: {!! json_encode($productsByCategory->pluck('total')->toArray()) !!},
                backgroundColor: colors,
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: true, position: 'bottom' } }
        }
    });
    @endif
</script>
@endsection
