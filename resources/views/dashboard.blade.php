@extends('layouts.dashboard')

@section('title', 'Dashboard - Laser Link')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Message -->
    <div class="bg-gradient-to-r from-primary to-red-600 rounded-lg p-6 text-white">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="bi bi-person-circle text-4xl text-gray-900"></i>
            </div>
            <div class="ml-4">
                <h2 class="text-2xl text-gray-900 font-bold">Bem-vindo, {{ Auth::user()->name }}!</h2>
                <p class="text-red-50">Gerencie seus pedidos e perfil na Laser Link</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Orders -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="bi bi-bag text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total de Pedidos</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalOrders ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="bi bi-clock text-yellow-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Pedidos Pendentes</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $pendingOrders ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Completed Orders -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="bi bi-check-circle text-green-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Pedidos Concluídos</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $completedOrders ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Total Spent -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="bi bi-currency-dollar text-purple-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Gasto</p>
                    <p class="text-2xl font-semibold text-gray-900">R$ {{ number_format($totalSpent ?? 0, 2, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bi bi-lightning mr-2 text-primary"></i>
                Ações Rápidas
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('store.index') }}" 
                   class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="bi bi-shop text-2xl text-primary mr-3"></i>
                    <div>
                        <p class="font-medium text-gray-900">Navegar Loja</p>
                        <p class="text-sm text-gray-500">Ver produtos</p>
                    </div>
                </a>

                <a href="{{ route('store.cart') }}" 
                   class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="bi bi-cart text-2xl text-primary mr-3"></i>
                    <div>
                        <p class="font-medium text-gray-900">Meu Carrinho</p>
                        <p class="text-sm text-gray-500">Ver itens</p>
                    </div>
                </a>

                <a href="{{ route('store.user-orders') }}" 
                   class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="bi bi-bag text-2xl text-primary mr-3"></i>
                    <div>
                        <p class="font-medium text-gray-900">Meus Pedidos</p>
                        <p class="text-sm text-gray-500">Histórico</p>
                    </div>
                </a>

                <a href="{{ route('profile.edit') }}" 
                   class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="bi bi-person text-2xl text-primary mr-3"></i>
                    <div>
                        <p class="font-medium text-gray-900">Meu Perfil</p>
                        <p class="text-sm text-gray-500">Configurações</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bi bi-clock-history mr-2 text-primary"></i>
                Pedidos Recentes
            </h3>
        </div>
        <div class="overflow-hidden">
            @if(isset($recentOrders) && $recentOrders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pedido
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Data
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ações
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentOrders as $order)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        #{{ $order->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $order->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                            @elseif($order->status === 'completed') bg-green-100 text-green-800
                                            @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            @switch($order->status)
                                                @case('pending') Pendente @break
                                                @case('processing') Processando @break
                                                @case('completed') Concluído @break
                                                @case('cancelled') Cancelado @break
                                                @default {{ ucfirst($order->status) }}
                                            @endswitch
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        R$ {{ number_format($order->total_amount, 2, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('store.order-details', $order->id) }}" 
                                           class="text-primary hover:text-red-700">
                                            Ver detalhes
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <i class="bi bi-bag text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum pedido encontrado</h3>
                    <p class="text-gray-500 mb-4">Você ainda não fez nenhum pedido.</p>
                    <a href="{{ route('store.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors">
                        <i class="bi bi-shop mr-2"></i>
                        Começar a comprar
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection