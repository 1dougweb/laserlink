@extends('layouts.store')

@section('title', 'Meus Pedidos')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Meus Pedidos</h1>
            <p class="mt-2 text-gray-600">Acompanhe o status dos seus pedidos</p>
        </div>

        @if($orders->count() > 0)
            <!-- Orders List -->
            <div class="space-y-6">
                @foreach($orders as $order)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <!-- Order Header -->
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        Pedido #{{ $order->order_number }}
                                    </h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Realizado em {{ $order->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                                <div class="mt-3 sm:mt-0 sm:ml-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($order->status === 'confirmed') bg-blue-100 text-blue-800
                                        @elseif($order->status === 'processing') bg-purple-100 text-purple-800
                                        @elseif($order->status === 'shipped') bg-indigo-100 text-indigo-800
                                        @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        @switch($order->status)
                                            @case('pending') Pendente @break
                                            @case('confirmed') Confirmado @break
                                            @case('processing') Em Processamento @break
                                            @case('shipped') Enviado @break
                                            @case('delivered') Entregue @break
                                            @case('cancelled') Cancelado @break
                                            @default {{ ucfirst($order->status) }} @break
                                        @endswitch
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Order Items -->
                        <div class="px-6 py-4">
                            <div class="space-y-4">
                                @foreach($order->items as $item)
                                    <div class="flex items-start space-x-4 py-3 border-b border-gray-100 last:border-b-0">
                                        <div class="flex-shrink-0">
                                            @if($item->product_image)
                                                <img src="{{ $item->product_image }}" 
                                                     alt="{{ $item->product_name }}"
                                                     class="w-16 h-16 object-cover rounded-lg bg-gray-100"
                                                     onerror="this.src='{{ url('images/general/callback-image.svg') }}'; this.classList.add('object-contain', 'p-2');">
                                            @else
                                                <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                                    <i class="bi bi-box text-2xl text-gray-400"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-sm font-medium text-gray-900">
                                                {{ $item->product_name }}
                                            </h4>
                                            
                                            @if($item->product_description)
                                                <p class="text-sm text-gray-600 mt-1">
                                                    {{ $item->product_description }}
                                                </p>
                                            @endif
                                            
                                            <!-- Customizations -->
                                            @if($item->customization)
                                                @php
                                                    $customization = is_string($item->customization) ? json_decode($item->customization, true) : $item->customization;
                                                @endphp
                                                @if($customization && is_array($customization))
                                                <div class="mt-2 flex flex-wrap gap-2">
                                                    @foreach($customization as $key => $value)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            <i class="bi bi-gear-fill mr-1"></i>
                                                            {{ ucfirst($key) }}: 
                                                            @if(is_array($value))
                                                                {{ implode(', ', $value) }}
                                                            @else
                                                                {{ $value }}
                                                            @endif
                                                        </span>
                                                    @endforeach
                                                </div>
                                                @endif
                                            @endif
                                            
                                            <div class="flex items-center mt-2 text-sm text-gray-500">
                                                <span>Qtd: {{ $item->quantity }}</span>
                                                <span class="mx-2">•</span>
                                                <span>R$ {{ number_format($item->unit_price, 2, ',', '.') }}</span>
                                                @if($item->extra_cost && $item->extra_cost > 0)
                                                    <span class="mx-2">•</span>
                                                    <span class="text-green-600">+R$ {{ number_format($item->extra_cost, 2, ',', '.') }} extras</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0 text-right">
                                            <p class="text-sm font-medium text-gray-900">
                                                R$ {{ number_format($item->total_price, 2, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Order Footer -->
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                <div class="text-sm text-gray-600">
                                    <p><strong>Total do Pedido:</strong> R$ {{ number_format($order->total_amount, 2, ',', '.') }}</p>
                                    @if($order->customer_phone)
                                        <p class="mt-1"><strong>Telefone:</strong> {{ $order->customer_phone }}</p>
                                    @endif
                                </div>
                                <div class="mt-3 sm:mt-0">
                                    <a href="{{ route('store.order-details', $order->id) }}" 
                                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                                        <i class="bi bi-eye mr-2"></i>
                                        Ver Detalhes
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($orders->hasPages())
                <div class="mt-8">
                    {{ $orders->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="bi bi-box text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum pedido encontrado</h3>
                <p class="text-gray-600 mb-6">Você ainda não fez nenhum pedido em nossa loja.</p>
                <a href="{{ route('store.index') }}" 
                   class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    <i class="bi bi-arrow-left mr-2"></i>
                    Continuar Comprando
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    .bi {
        display: inline-block;
        font-family: "bootstrap-icons" !important;
        font-style: normal;
        font-variant: normal;
        text-rendering: auto;
        line-height: 1;
    }
</style>
@endpush
