@extends('admin.layout')

@section('title', 'Pedido #' . $order->order_number)
@section('page-title', 'Detalhes do Pedido')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.orders') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
            <i class="bi bi-arrow-left mr-2"></i>
            Voltar para Pedidos
        </a>
        
        <div class="flex gap-3">
            <button onclick="window.print()" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                <i class="bi bi-printer mr-2"></i>Imprimir
            </button>
            @if($order->whatsapp_message)
            <a href="{{ $order->whatsapp_url ?? '#' }}" target="_blank" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                <i class="bi bi-whatsapp mr-2"></i>WhatsApp
            </a>
            @endif
        </div>
    </div>

    <!-- Order Info -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Header -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Pedido #{{ $order->order_number }}</h2>
                        <p class="text-sm text-gray-600">Criado em {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $order->status_color }}">
                            {{ $order->status_label }}
                        </span>
                    </div>
                </div>

                <!-- Status Update -->
                <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="mt-4">
                    @csrf
                    @method('PUT')
                    <div class="flex gap-3">
                        <select name="status" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pendente</option>
                            <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmado</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processando</option>
                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Enviado</option>
                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Entregue</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                        <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors">
                            Atualizar Status
                        </button>
                    </div>
                </form>
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Itens do Pedido</h3>
                
                <div class="space-y-4">
                    @foreach($order->items as $item)
                    <div class="flex items-start gap-4 p-4 border border-gray-200 rounded-lg">
                        <!-- Image -->
                        <div class="w-20 h-20 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                            @if($item->product_image)
                                <img src="{{ $item->product_image }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <i class="bi bi-image text-3xl"></i>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Details -->
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">{{ $item->product_name }}</h4>
                            
                            @if($item->product_description)
                            <p class="text-sm text-gray-600 mt-1">{{ $item->product_description }}</p>
                            @endif
                            
                            <!-- Customizations -->
                            @if($item->customization)
                                @php
                                    $customization = is_string($item->customization) ? json_decode($item->customization, true) : $item->customization;
                                @endphp
                                @if($customization && is_array($customization))
                                <div class="mt-2 bg-blue-50 border-l-4 border-blue-400 p-3 rounded">
                                    <p class="text-sm font-semibold text-blue-900 mb-1">
                                        <i class="bi bi-gear-fill mr-1"></i>Configurações:
                                    </p>
                                    <ul class="text-sm text-blue-800 space-y-1">
                                        @foreach($customization as $key => $value)
                                            <li>
                                                <strong>{{ ucfirst($key) }}:</strong> 
                                                @if(is_array($value))
                                                    {{ implode(', ', $value) }}
                                                @else
                                                    {{ $value }}
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                            @endif
                            
                            <!-- Pricing -->
                            <div class="mt-3">
                                <div class="flex items-center gap-4 text-sm">
                                    <span class="text-gray-600">Quantidade: <strong>{{ $item->quantity }}</strong></span>
                                    <span class="text-gray-600">Preço unit.: <strong>R$ {{ number_format($item->unit_price, 2, ',', '.') }}</strong></span>
                                </div>
                                
                                @if($item->extra_cost && $item->extra_cost > 0)
                                <div class="mt-2 text-sm text-gray-600">
                                    <span>Base: R$ {{ number_format($item->base_price, 2, ',', '.') }}</span>
                                    <span class="text-green-600 ml-2">+ Extras: R$ {{ number_format($item->extra_cost, 2, ',', '.') }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Total -->
                        <div class="text-right">
                            <p class="text-lg font-bold text-gray-900">
                                R$ {{ number_format($item->total_price, 2, ',', '.') }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Shipping Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="bi bi-truck mr-2 text-primary"></i>Endereço de Entrega
                </h3>
                <div class="text-gray-700">
                    <p>{{ $order->shipping_street }}, {{ $order->shipping_number }}</p>
                    @if($order->shipping_complement)
                    <p>{{ $order->shipping_complement }}</p>
                    @endif
                    <p>{{ $order->shipping_neighborhood }}</p>
                    <p>{{ $order->shipping_city }} - {{ $order->shipping_state }}</p>
                    <p>CEP: {{ $order->shipping_cep }}</p>
                </div>
            </div>

            <!-- Notes -->
            @if($order->notes)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="bi bi-chat-left-text mr-2 text-primary"></i>Observações
                </h3>
                <p class="text-gray-700">{{ $order->notes }}</p>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Customer Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="bi bi-person-fill mr-2 text-primary"></i>Informações do Cliente
                </h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm text-gray-600">Nome:</label>
                        <p class="font-medium text-gray-900">{{ $order->customer_name }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Email:</label>
                        <p class="font-medium text-gray-900">{{ $order->customer_email }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Telefone:</label>
                        <p class="font-medium text-gray-900">{{ $order->customer_phone }}</p>
                    </div>
                    @if($order->customer_cpf)
                    <div>
                        <label class="text-sm text-gray-600">CPF:</label>
                        <p class="font-medium text-gray-900">{{ $order->customer_cpf }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Order Summary -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="bi bi-receipt mr-2 text-primary"></i>Resumo do Pedido
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-gray-700">
                        <span>Subtotal:</span>
                        <span class="font-medium">R$ {{ number_format($order->subtotal, 2, ',', '.') }}</span>
                    </div>
                    @if($order->shipping_cost > 0)
                    <div class="flex justify-between text-gray-700">
                        <span>Frete:</span>
                        <span class="font-medium">R$ {{ number_format($order->shipping_cost, 2, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between text-lg font-bold text-gray-900 pt-3 border-t">
                        <span>Total:</span>
                        <span class="text-primary">R$ {{ number_format($order->total, 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="bi bi-clock-history mr-2 text-primary"></i>Histórico
                </h3>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-2 h-2 rounded-full bg-primary mt-2 mr-3"></div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">Pedido Criado</p>
                            <p class="text-xs text-gray-600">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    
                    @if($order->updated_at != $order->created_at)
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-2 h-2 rounded-full bg-blue-500 mt-2 mr-3"></div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">Última Atualização</p>
                            <p class="text-xs text-gray-600">{{ $order->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
@media print {
    .no-print, nav, aside, .flex.items-center.justify-between {
        display: none !important;
    }
    body {
        background: white;
    }
}
</style>
@endpush
@endsection

