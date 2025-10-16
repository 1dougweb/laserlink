@extends('admin.layout')

@section('title', 'Pedidos - Laser Link')
@section('page-title', 'Pedidos')

@section('content')
<div class="space-y-6">
    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="status_filter" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="status_filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value="">Todos</option>
                    <option value="pending">Pendente</option>
                    <option value="confirmed">Confirmado</option>
                    <option value="processing">Processando</option>
                    <option value="shipped">Enviado</option>
                    <option value="delivered">Entregue</option>
                    <option value="cancelled">Cancelado</option>
                </select>
            </div>
            <div>
                <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">Data Inicial</label>
                <input type="date" id="date_from" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
            </div>
            <div>
                <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">Data Final</label>
                <input type="date" id="date_to" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
            </div>
            <div class="flex items-end">
                <button class="w-full bg-primary text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                    <i class="bi bi-search mr-2"></i>Filtrar
                </button>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pedido</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($orders as $order)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            #{{ $order->order_number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $order->customer_name }}</div>
                                <div class="text-sm text-gray-500">{{ $order->customer_phone }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $order->status_color }}">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            R$ {{ number_format($order->total, 2, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $order->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-primary hover:text-red-700">
                                <i class="bi bi-eye"></i>
                            </a>
                            <button onclick="updateStatus({{ $order->id }})" class="text-yellow-600 hover:text-yellow-900">
                                <i class="bi bi-pencil"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Nenhum pedido encontrado
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $orders->links() }}
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div id="statusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Atualizar Status</h3>
            </div>
            <form id="statusForm" method="POST">
                @csrf
                @method('PUT')
                <div class="px-6 py-4">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Novo Status</label>
                    <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="pending">Pendente</option>
                        <option value="confirmed">Confirmado</option>
                        <option value="processing">Processando</option>
                        <option value="shipped">Enviado</option>
                        <option value="delivered">Entregue</option>
                        <option value="cancelled">Cancelado</option>
                    </select>
                </div>
                <div class="px-6 py-4 border-t flex justify-end space-x-2">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-red-700">
                        Atualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function updateStatus(orderId) {
    document.getElementById('statusForm').action = `/admin/pedidos/${orderId}/status`;
    document.getElementById('statusModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('statusModal').classList.add('hidden');
}
</script>
@endsection













