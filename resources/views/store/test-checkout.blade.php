@extends('layouts.store')

@section('title', 'Teste Checkout')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Teste Checkout</h1>
    
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-semibold mb-4">Dados do Carrinho:</h2>
        <pre>{{ json_encode(session('cart', []), JSON_PRETTY_PRINT) }}</pre>
        
        <h2 class="text-xl font-semibold mb-4 mt-6">Total:</h2>
        <p class="text-2xl font-bold text-primary">
            R$ {{ number_format(array_sum(array_column(session('cart', []), 'total_price')), 2, ',', '.') }}
        </p>
        
        <div class="mt-6">
            <a href="{{ route('store.checkout') }}" class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-red-700 transition-colors">
                Ir para Checkout
            </a>
        </div>
    </div>
</div>
@endsection







