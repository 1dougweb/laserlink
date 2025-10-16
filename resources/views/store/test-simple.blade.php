@extends('layouts.store')

@section('title', 'Teste Simples')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Teste Simples</h1>
    
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-semibold mb-4">Teste de Variáveis:</h2>
        <p>Cart: {{ json_encode($cart ?? []) }}</p>
        <p>Total: {{ $total ?? 0 }}</p>
        
        @if(!empty($cart))
            <h3 class="text-lg font-semibold mt-4">Itens no Carrinho:</h3>
            @foreach($cart as $item)
                <div class="border p-2 mb-2">
                    <strong>{{ $item['product_name'] }}</strong><br>
                    Quantidade: {{ $item['quantity'] }}<br>
                    Preço: R$ {{ number_format($item['total_price'], 2, ',', '.') }}
                </div>
            @endforeach
        @else
            <p class="text-gray-600">Carrinho vazio</p>
        @endif
    </div>
</div>
@endsection







