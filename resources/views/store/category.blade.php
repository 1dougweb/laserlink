@extends('layouts.store')

@section('title', $productType->name . ' - LaserLink')

@section('content')
<div x-data="{ mobileMenuOpen: false }">
    <!-- Category Header -->
    <div class="bg-gray-100 py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav aria-label="breadcrumb">
                <ol class="flex items-center space-x-2 text-sm text-gray-500">
                    <li>
                        <a href="{{ route('store.index') }}" class="hover:text-red-600">Início</a>
                    </li>
                    <li class="flex items-center">
                        <i class="bi bi-chevron-right mx-2"></i>
                        <span class="text-gray-900 font-medium">{{ $productType->name }}</span>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Category Info -->
    <section class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex items-center mb-4 md:mb-0">
                    <i class="{{ $productType->icon ?? 'bi bi-box' }} text-red-600 mr-4 text-4xl"></i>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $productType->name }}</h1>
                        <p class="text-gray-600">{{ $productType->description }}</p>
                    </div>
                </div>
                <div class="text-center md:text-right">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                        {{ $products->count() }} produtos disponíveis
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Grid -->
    <section class="pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($products->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($products as $product)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
                        <div class="p-6 flex flex-col h-full">
                            <div class="mb-4">
                                <div class="flex flex-wrap gap-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $product->category->name ?? 'Sem categoria' }}
                                    </span>
                                    @if($product->is_featured)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Destaque
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ $product->name }}</h3>
                            
                            <p class="text-gray-600 flex-grow mb-4">
                                {{ Str::limit($product->short_description, 120) }}
                            </p>
                            
                            @if($product->productType && $product->productType->fields)
                                <div class="mb-4">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach(array_slice($product->productType->fields, 0, 3) as $field)
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                                {{ $field['label'] ?? $field['name'] }}
                                            </span>
                                        @endforeach
                                        @if(count($product->productType->fields) > 3)
                                            <span class="text-gray-500 text-xs">+{{ count($product->productType->fields) - 3 }} mais</span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            
                            <div class="mt-auto">
                                <div class="mb-4">
                                    @if($product->auto_calculate_price)
                                        <div class="text-sm text-gray-500 mb-1">Preço calculado</div>
                                        <div class="text-green-600 font-semibold text-lg">
                                            <i class="bi bi-calculator mr-1"></i>
                                            A partir de R$ {{ number_format($product->min_price ?? 0, 2, ',', '.') }}
                                        </div>
                                    @else
                                        <div class="flex items-center">
                                            @if($product->sale_price && $product->sale_price < $product->price)
                                                <span class="text-lg line-through text-gray-400 mr-2">
                                                    R$ {{ number_format($product->price, 2, ',', '.') }}
                                                </span>
                                                <span class="text-green-600 font-semibold text-lg">
                                                    R$ {{ number_format($product->sale_price, 2, ',', '.') }}
                                                </span>
                                            @else
                                                <span class="text-red-600 font-semibold text-lg">
                                                    R$ {{ number_format($product->price, 2, ',', '.') }}
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="space-y-2">
                                    <a href="{{ route('store.configurator', ['slug' => $product->slug]) }}" 
                                       class="w-full bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition-colors duration-200 flex items-center justify-center">
                                        <i class="bi bi-gear-fill mr-2"></i>
                                        Configurar Produto
                                    </a>
                                    
                                    @if($product->auto_calculate_price && $product->material)
                                        <div class="text-center text-sm text-gray-500">
                                            <i class="bi bi-info-circle mr-1"></i>
                                            Material: {{ $product->material->name }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-16">
                    <i class="bi bi-box text-gray-400 text-6xl mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Nenhum produto encontrado</h3>
                    <p class="text-gray-600 mb-6">
                        Não há produtos disponíveis nesta categoria no momento.
                    </p>
                    <a href="{{ route('store.index') }}" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors duration-200">
                        <i class="bi bi-arrow-left mr-2"></i>
                        Voltar ao Início
                    </a>
                </div>
            @endif
        </div>
    </section>

    <!-- Category Features -->
    @if($productType->fields && count($productType->fields) > 0)
    <section class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h3 class="text-center text-2xl font-bold text-gray-900 mb-8">Opções de Configuração</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach(array_slice($productType->fields, 0, 6) as $field)
                <div class="flex items-center">
                    <div class="bg-red-600 text-white rounded-full w-10 h-10 flex items-center justify-center mr-3">
                        <i class="bi bi-{{ $this->getFieldIcon($field['type']) }}"></i>
                    </div>
                    <div>
                        <h6 class="font-semibold text-gray-900 mb-1">{{ $field['label'] ?? $field['name'] }}</h6>
                        <p class="text-sm text-gray-600">
                            {{ $this->getFieldDescription($field['type'], $field['calculation']) }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
            @if(count($productType->fields) > 6)
                <div class="text-center mt-8">
                    <p class="text-gray-600">
                        E mais {{ count($productType->fields) - 6 }} opções de personalização
                    </p>
                </div>
            @endif
        </div>
    </section>
    @endif
</div>

@php
    function getFieldIcon($type) {
        return match($type) {
            'text' => 'type',
            'number' => '123',
            'select' => 'list-ul',
            'textarea' => 'card-text',
            'checkbox' => 'check-square',
            default => 'gear'
        };
    }
    
    function getFieldDescription($type, $calculation) {
        $descriptions = [
            'text' => 'Campo de texto personalizado',
            'number' => 'Valor numérico para cálculos',
            'select' => 'Seleção de opções predefinidas',
            'textarea' => 'Texto longo com múltiplas linhas',
            'checkbox' => 'Opção de ativação/desativação'
        ];
        
        $base = $descriptions[$type] ?? 'Campo personalizado';
        
        if ($calculation) {
            $base .= ' (usado no cálculo de preço)';
        }
        
        return $base;
    }
@endphp
@endsection