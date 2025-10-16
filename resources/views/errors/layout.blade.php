@extends('layouts.store')

@section('title', 'Erro ' . ($__exception->getStatusCode() ?? '500') . ' | ' . config('app.name'))

@section('content')

<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center px-4 py-12">
    <div class="max-w-2xl w-full text-center">
        <!-- Ilustração do erro -->
        <div class="mb-8">
            <div class="relative inline-block">
                <div class="text-9xl font-black text-gray-200 select-none">
                    {{ $__exception->getStatusCode() ?? '500' }}
                </div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <i class="bi bi-exclamation-triangle text-6xl text-primary opacity-50"></i>
                </div>
            </div>
        </div>

        <!-- Mensagem -->
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
            {{ $exception->getMessage() ?: 'Ocorreu um erro' }}
        </h1>
        
        <p class="text-lg text-gray-600 mb-8 max-w-md mx-auto">
            Encontramos um problema ao processar sua solicitação. 
            Por favor, tente novamente ou volte para a página inicial.
        </p>

        <!-- Botões de ação -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
            <button onclick="window.history.back()" 
                    class="inline-flex items-center justify-center px-8 py-3.5 bg-primary hover:bg-red-700 text-white font-semibold rounded-lg transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <i class="bi bi-arrow-left mr-2"></i>
                Voltar
            </button>
            
            <a href="{{ url('/') }}" 
               class="inline-flex items-center justify-center px-8 py-3.5 bg-white border-2 border-gray-300 hover:border-primary text-gray-700 hover:text-primary font-semibold rounded-lg transition-all shadow-sm hover:shadow-md">
                <i class="bi bi-house-door mr-2"></i>
                Ir para Início
            </a>
        </div>

        <!-- Contato -->
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900 mb-3">
                <i class="bi bi-headset mr-2"></i>
                Precisa de ajuda?
            </h2>
            <p class="text-gray-600 mb-4 text-sm">
                Se o problema persistir, entre em contato com nossa equipe.
            </p>
            <a href="{{ route('contact.index') }}" 
               class="inline-flex items-center px-6 py-2.5 bg-primary hover:bg-red-700 text-white rounded-lg transition-colors">
                <i class="bi bi-envelope mr-2"></i>
                Falar com o Suporte
            </a>
        </div>
    </div>
</div>

@endsection


