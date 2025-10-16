@extends('layouts.store')

@section('title', 'Sessão expirada - 419 | ' . config('app.name'))

@section('meta_description', 'Sua sessão expirou por segurança. Por favor, recarregue a página e tente novamente.')

@section('content')

<div class="min-h-screen bg-gradient-to-br from-purple-50 to-gray-100 flex items-center justify-center px-4 py-12">
    <div class="max-w-2xl w-full text-center">
        <!-- Ilustração do erro -->
        <div class="mb-8">
            <div class="relative inline-block">
                <div class="text-9xl font-black text-purple-200 select-none">419</div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <i class="bi bi-hourglass-split text-6xl text-purple-600 opacity-50"></i>
                </div>
            </div>
        </div>

        <!-- Mensagem -->
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
            Sessão expirada
        </h1>
        
        <p class="text-lg text-gray-600 mb-8 max-w-md mx-auto">
            Sua sessão expirou por motivos de segurança. 
            Por favor, recarregue a página e tente novamente.
        </p>

        <!-- Botões de ação -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
            <button onclick="window.location.reload()" 
                    class="inline-flex items-center justify-center px-8 py-3.5 bg-primary hover:bg-red-700 text-white font-semibold rounded-lg transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <i class="bi bi-arrow-clockwise mr-2"></i>
                Recarregar Página
            </button>
            
            <a href="{{ url()->previous() }}" 
               class="inline-flex items-center justify-center px-8 py-3.5 bg-white border-2 border-gray-300 hover:border-primary text-gray-700 hover:text-primary font-semibold rounded-lg transition-all shadow-sm hover:shadow-md">
                <i class="bi bi-arrow-left mr-2"></i>
                Voltar
            </a>
        </div>

        <!-- Explicação -->
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-200 mb-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center justify-center">
                <i class="bi bi-shield-check text-purple-600 mr-2"></i>
                Por que isso aconteceu?
            </h2>
            <div class="text-gray-600 space-y-3 text-sm text-left max-w-lg mx-auto">
                <p class="flex items-start">
                    <i class="bi bi-check-circle text-green-500 mr-2 mt-0.5 flex-shrink-0"></i>
                    <span>Por segurança, mantemos você conectado por um tempo limitado</span>
                </p>
                <p class="flex items-start">
                    <i class="bi bi-check-circle text-green-500 mr-2 mt-0.5 flex-shrink-0"></i>
                    <span>Isso protege seus dados caso você esqueça de fazer logout</span>
                </p>
                <p class="flex items-start">
                    <i class="bi bi-check-circle text-green-500 mr-2 mt-0.5 flex-shrink-0"></i>
                    <span>Basta recarregar a página para continuar navegando</span>
                </p>
            </div>
        </div>

        <!-- Dica -->
        <div class="bg-purple-50 rounded-xl p-6 border border-purple-200">
            <h3 class="font-semibold text-gray-900 mb-2 flex items-center justify-center">
                <i class="bi bi-lightbulb text-yellow-500 mr-2"></i>
                Dica
            </h3>
            <p class="text-sm text-gray-600">
                Se você estava preenchendo um formulário, recarregue a página e preencha novamente. 
                Seus dados não foram salvos por questões de segurança.
            </p>
        </div>
    </div>
</div>

@endsection


