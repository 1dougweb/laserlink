@extends('admin.layout')

@section('title', 'Relatório de Vendas')
@section('page-title', 'Relatório de Vendas')

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
            <form method="GET" action="{{ route('admin.reports.sales') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" id="status" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                        <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Todos</option>
                        <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pendente</option>
                        <option value="confirmed" {{ $status === 'confirmed' ? 'selected' : '' }}>Confirmado</option>
                        <option value="processing" {{ $status === 'processing' ? 'selected' : '' }}>Processando</option>
                        <option value="shipped" {{ $status === 'shipped' ? 'selected' : '' }}>Enviado</option>
                        <option value="delivered" {{ $status === 'delivered' ? 'selected' : '' }}>Entregue</option>
                        <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 bg-primary text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                        <i class="bi bi-funnel mr-1"></i> Filtrar
                    </button>
                    <a href="{{ route('admin.reports.sales.export', request()->query()) }}" 
                       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="bi bi-download"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Estatísticas do Período -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="selo rounded-full bg-blue-100 text-blue-600">
                    <i class="bi bi-cart-check text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total de Pedidos</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($totalOrders, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="selo rounded-full bg-green-100 text-green-600">
                    <i class="bi bi-currency-dollar text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Receita Total</p>
                    <p class="text-2xl font-semibold text-gray-900">R$ {{ number_format($totalRevenue, 2, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="selo rounded-full bg-purple-100 text-purple-600">
                    <i class="bi bi-calculator text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Ticket Médio</p>
                    <p class="text-2xl font-semibold text-gray-900">R$ {{ number_format($averageTicket, 2, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Pedidos por Status -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Pedidos por Status</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <div class="text-center p-4 bg-yellow-50 rounded-lg">
                    <div class="text-2xl font-bold text-yellow-700">{{ $ordersByStatus->get('pending', 0) }}</div>
                    <div class="text-sm text-gray-600 mt-1">Pendente</div>
                </div>
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <div class="text-2xl font-bold text-blue-700">{{ $ordersByStatus->get('confirmed', 0) }}</div>
                    <div class="text-sm text-gray-600 mt-1">Confirmado</div>
                </div>
                <div class="text-center p-4 bg-purple-50 rounded-lg">
                    <div class="text-2xl font-bold text-purple-700">{{ $ordersByStatus->get('processing', 0) }}</div>
                    <div class="text-sm text-gray-600 mt-1">Processando</div>
                </div>
                <div class="text-center p-4 bg-indigo-50 rounded-lg">
                    <div class="text-2xl font-bold text-indigo-700">{{ $ordersByStatus->get('shipped', 0) }}</div>
                    <div class="text-sm text-gray-600 mt-1">Enviado</div>
                </div>
                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <div class="text-2xl font-bold text-green-700">{{ $ordersByStatus->get('delivered', 0) }}</div>
                    <div class="text-sm text-gray-600 mt-1">Entregue</div>
                </div>
                <div class="text-center p-4 bg-red-50 rounded-lg">
                    <div class="text-2xl font-bold text-red-700">{{ $ordersByStatus->get('cancelled', 0) }}</div>
                    <div class="text-sm text-gray-600 mt-1">Cancelado</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfico de Vendas por Dia -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Vendas Diárias</h3>
        </div>
        <div class="p-6">
            @if($salesByDay->count() > 0)
                <div style="height: 300px;">
                    <canvas id="salesChart"></canvas>
                </div>
            @else
                <p class="text-gray-500 text-center py-8">Nenhuma venda no período selecionado</p>
            @endif
        </div>
    </div>

    <!-- Vendas Mensais -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Vendas Mensais (Últimos 12 Meses)</h3>
        </div>
        <div class="p-6">
            @if($monthlySales->count() > 0)
                <div style="height: 300px;">
                    <canvas id="monthlyChart"></canvas>
                </div>
            @else
                <p class="text-gray-500 text-center py-8">Nenhuma venda nos últimos 12 meses</p>
            @endif
        </div>
    </div>

    <!-- Top 10 Produtos -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Top 10 Produtos Mais Vendidos</h3>
        </div>
        <div class="p-6">
            @if($topProducts->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pos.</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qtd.</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Receita</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($topProducts as $index => $product)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center justify-center h-8 w-8 rounded-full {{ $index < 3 ? 'bg-yellow-100 text-yellow-800 font-bold' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $index + 1 }}º
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $product->product_name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($product->total_quantity, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">
                                        R$ {{ number_format($product->total_revenue, 2, ',', '.') }}
                                    </td>
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

    <!-- Lista de Pedidos -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Pedidos do Período</h3>
        </div>
        @if($orders->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Número</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($orders as $order)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    #{{ $order->order_number }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $order->customer_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $order->status_color }}">
                                        {{ $order->status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                    R$ {{ number_format($order->total_amount, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="text-primary hover:text-red-700">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $orders->links() }}
            </div>
        @else
            <div class="p-6">
                <p class="text-gray-500 text-center py-4">Nenhum pedido encontrado no período</p>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    @if($salesByDay->count() > 0)
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($salesByDay->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))->toArray()) !!},
            datasets: [{
                label: 'Vendas Diárias (R$)',
                data: {!! json_encode($salesByDay->pluck('total')->toArray()) !!},
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: true, position: 'top' } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { callback: value => 'R$ ' + value.toLocaleString('pt-BR') }
                }
            }
        }
    });
    @endif

    @if($monthlySales->count() > 0)
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($monthlySales->map(fn($s) => str_pad($s->month, 2, '0', STR_PAD_LEFT) . '/' . $s->year)->toArray()) !!},
            datasets: [{
                label: 'Receita Mensal (R$)',
                data: {!! json_encode($monthlySales->pluck('total_revenue')->toArray()) !!},
                backgroundColor: 'rgba(34, 197, 94, 0.6)',
                borderColor: 'rgb(34, 197, 94)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: true, position: 'top' } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { callback: value => 'R$ ' + value.toLocaleString('pt-BR') }
                }
            }
        }
    });
    @endif
</script>
@endsection
