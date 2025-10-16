@extends('layouts.store')

@section('title', 'Detalhes do Pedido #' . $order->order_number)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Detalhes do Pedido</h1>
                    <p class="mt-2 text-gray-600">Pedido #{{ $order->order_number }}</p>
                </div>
                <a href="{{ route('store.user-orders') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    <i class="bi bi-arrow-left mr-2"></i>
                    Voltar aos Pedidos
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Order Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Order Status -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Status do Pedido</h2>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
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
                        <div class="text-sm text-gray-600">
                            Realizado em {{ $order->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Itens do Pedido</h2>
                    <div class="space-y-4">
                        @foreach($order->items as $item)
                            <div class="flex items-start space-x-4 py-4 border-b border-gray-100 last:border-b-0">
                                <div class="flex-shrink-0">
                                    @if($item->product_image)
                                        <img src="{{ $item->product_image }}" 
                                             alt="{{ $item->product_name }}"
                                             class="w-20 h-20 object-cover rounded-lg bg-gray-100"
                                             onerror="this.src='{{ url('images/general/callback-image.svg') }}'; this.classList.add('object-contain', 'p-2');">
                                    @else
                                        <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <i class="bi bi-box text-3xl text-gray-400"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-base font-medium text-gray-900">
                                        {{ $item->product_name }}
                                    </h3>
                                    
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
                                        <div class="mt-3 bg-blue-50 border-l-4 border-blue-400 p-3 rounded">
                                            <p class="text-sm font-semibold text-blue-900 mb-2">
                                                <i class="bi bi-gear-fill mr-1"></i>Configurações Personalizadas:
                                            </p>
                                            <div class="space-y-1">
                                                @foreach($customization as $key => $value)
                                                    <p class="text-sm text-blue-800">
                                                        <strong>{{ ucfirst($key) }}:</strong> 
                                                        @if(is_array($value))
                                                            {{ implode(', ', $value) }}
                                                        @else
                                                            {{ $value }}
                                                        @endif
                                                    </p>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif
                                    @endif
                                    
                                    <div class="flex items-center mt-3 text-sm text-gray-500">
                                        <span>Quantidade: {{ $item->quantity }}</span>
                                        <span class="mx-2">•</span>
                                        <span>Preço unit.: R$ {{ number_format($item->unit_price, 2, ',', '.') }}</span>
                                        @if($item->extra_cost && $item->extra_cost > 0)
                                            <span class="mx-2">•</span>
                                            <span class="text-green-600 font-medium">+R$ {{ number_format($item->extra_cost, 2, ',', '.') }} extras</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-shrink-0 text-right">
                                    <p class="text-lg font-semibold text-gray-900">
                                        R$ {{ number_format($item->total_price, 2, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Order Notes -->
                @if($order->notes)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Observações</h2>
                        <p class="text-gray-700">{{ $order->notes }}</p>
                    </div>
                @endif
            </div>

            <!-- Order Summary -->
            <div class="space-y-6">
                <!-- Customer Info -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informações do Cliente</h2>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm font-medium text-gray-700">Nome</p>
                            <p class="text-sm text-gray-900">{{ $order->customer_name }}</p>
                        </div>
                        @if($order->customer_email)
                            <div>
                                <p class="text-sm font-medium text-gray-700">E-mail</p>
                                <p class="text-sm text-gray-900">{{ $order->customer_email }}</p>
                            </div>
                        @endif
                        @if($order->customer_phone)
                            <div>
                                <p class="text-sm font-medium text-gray-700">Telefone</p>
                                <p class="text-sm text-gray-900">{{ $order->customer_phone }}</p>
                            </div>
                        @endif
                        @if($order->shipping_address)
                            <div>
                                <p class="text-sm font-medium text-gray-700">Endereço de Entrega</p>
                                <p class="text-sm text-gray-900">{{ $order->shipping_address }}</p>
                                @if($order->shipping_neighborhood)
                                    <p class="text-sm text-gray-900">{{ $order->shipping_neighborhood }}</p>
                                @endif
                                @if($order->shipping_city)
                                    <p class="text-sm text-gray-900">{{ $order->shipping_city }}</p>
                                @endif
                                @if($order->shipping_state)
                                    <p class="text-sm text-gray-900">{{ $order->shipping_state }}</p>
                                @endif
                                @if($order->shipping_zip)
                                    <p class="text-sm text-gray-900">CEP: {{ $order->shipping_zip }}</p>
                                @endif
                                @if($order->shipping_complement)
                                    <p class="text-sm text-gray-900">{{ $order->shipping_complement }}</p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Resumo do Pedido</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Frete</span>
                            <span class="text-gray-900">R$ 0,00</span>
                        </div>
                        <div class="border-t border-gray-200 pt-3">
                            <div class="flex justify-between text-base font-semibold">
                                <span class="text-gray-900">Total</span>
                                <span class="text-gray-900">R$ {{ number_format($order->total_amount, 2, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- WhatsApp Contact -->
                @if($order->whatsapp_message_id)
                    <div class="bg-green-50 rounded-lg border border-green-200 p-6">
                        <div class="flex items-center">
                            <i class="bi bi-whatsapp text-green-600 text-2xl mr-3"></i>
                            <div>
                                <h3 class="text-sm font-medium text-green-800">Mensagem Enviada</h3>
                                <p class="text-sm text-green-700 mt-1">Confirmação enviada via WhatsApp</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
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
