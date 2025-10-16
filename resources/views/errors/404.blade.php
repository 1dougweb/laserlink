@extends('layouts.store')

@section('title', 'Página não encontrada - 404 | ' . config('app.name'))

@section('meta_description', 'A página que você está procurando não foi encontrada. Volte para a página inicial ou navegue pelo nosso catálogo de produtos.')

@section('content')

<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center px-4 py-12">
    <div class="max-w-2xl w-full text-center">
        <!-- Ilustração do erro -->
        <div class="mb-8">
            <div class="relative inline-block">
                <div class="text-9xl font-black text-gray-200 select-none">404</div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <i class="bi bi-search text-6xl text-primary opacity-50"></i>
                </div>
            </div>
        </div>

        <!-- Mensagem -->
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
            Página não encontrada
        </h1>
        
        <p class="text-lg text-gray-600 mb-8 max-w-md mx-auto">
            Desculpe, a página que você está procurando não existe ou foi removida. 
            Que tal explorar nossos produtos ou voltar para a página inicial?
        </p>

        <!-- Botões de ação -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
            <a href="{{ url('/') }}" 
               class="inline-flex items-center justify-center px-8 py-3.5 bg-primary hover:bg-red-700 text-white font-semibold rounded-lg transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <i class="bi bi-house-door mr-2"></i>
                Ir para Início
            </a>
            
            <a href="{{ route('store.products') }}" 
               class="inline-flex items-center justify-center px-8 py-3.5 bg-white border-2 border-gray-300 hover:border-primary text-gray-700 hover:text-primary font-semibold rounded-lg transition-all shadow-sm hover:shadow-md">
                <i class="bi bi-box-seam mr-2"></i>
                Ver Produtos
            </a>
        </div>

        <!-- Links úteis -->
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Links úteis</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('store.products') }}" class="text-gray-600 hover:text-primary transition-colors">
                    <i class="bi bi-box-seam block text-2xl mb-2"></i>
                    <span class="text-sm">Produtos</span>
                </a>
                <a href="{{ route('blog.index') }}" class="text-gray-600 hover:text-primary transition-colors">
                    <i class="bi bi-newspaper block text-2xl mb-2"></i>
                    <span class="text-sm">Blog</span>
                </a>
                <a href="{{ route('contact.index') }}" class="text-gray-600 hover:text-primary transition-colors">
                    <i class="bi bi-envelope block text-2xl mb-2"></i>
                    <span class="text-sm">Contato</span>
                </a>
                <a href="{{ route('page.show', 'sobre-nos') }}" class="text-gray-600 hover:text-primary transition-colors">
                    <i class="bi bi-info-circle block text-2xl mb-2"></i>
                    <span class="text-sm">Sobre Nós</span>
                </a>
            </div>
        </div>

        <!-- Busca -->
        <div class="mt-8">
            <p class="text-sm text-gray-500 mb-3">Ou tente buscar o que procura:</p>
            <form action="{{ route('store.products') }}" method="GET" class="max-w-md mx-auto">
                <div class="flex gap-2">
                    <input type="text" 
                           name="search" 
                           placeholder="Buscar produtos..." 
                           class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    <button type="submit" 
                            class="px-6 py-3 bg-primary hover:bg-red-700 text-white rounded-lg transition-colors">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection




