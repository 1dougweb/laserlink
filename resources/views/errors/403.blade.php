@extends('layouts.store')

@section('title', 'Acesso negado - 403 | ' . config('app.name'))

@section('meta_description', 'Você não tem permissão para acessar esta página.')

@section('content')

<div class="min-h-screen bg-gradient-to-br from-orange-50 to-gray-100 flex items-center justify-center px-4 py-12">
    <div class="max-w-2xl w-full text-center">
        <!-- Ilustração do erro -->
        <div class="mb-8">
            <div class="relative inline-block">
                <div class="text-9xl font-black text-orange-200 select-none">403</div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <i class="bi bi-shield-lock text-6xl text-orange-600 opacity-50"></i>
                </div>
            </div>
        </div>

        <!-- Mensagem -->
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
            Acesso negado
        </h1>
        
        <p class="text-lg text-gray-600 mb-8 max-w-md mx-auto">
            Você não tem permissão para acessar esta página. 
            Se você acredita que isso é um erro, entre em contato conosco.
        </p>

        <!-- Botões de ação -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
            <a href="{{ url('/') }}" 
               class="inline-flex items-center justify-center px-8 py-3.5 bg-primary hover:bg-red-700 text-white font-semibold rounded-lg transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <i class="bi bi-house-door mr-2"></i>
                Ir para Início
            </a>
            
            @auth
                <a href="{{ route('dashboard') }}" 
                   class="inline-flex items-center justify-center px-8 py-3.5 bg-white border-2 border-gray-300 hover:border-primary text-gray-700 hover:text-primary font-semibold rounded-lg transition-all shadow-sm hover:shadow-md">
                    <i class="bi bi-person-circle mr-2"></i>
                    Minha Conta
                </a>
            @else
                <a href="{{ route('login') }}" 
                   class="inline-flex items-center justify-center px-8 py-3.5 bg-white border-2 border-gray-300 hover:border-primary text-gray-700 hover:text-primary font-semibold rounded-lg transition-all shadow-sm hover:shadow-md">
                    <i class="bi bi-box-arrow-in-right mr-2"></i>
                    Fazer Login
                </a>
            @endauth
        </div>

        <!-- Possíveis razões -->
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Possíveis motivos:</h2>
            <ul class="text-left text-gray-600 space-y-2 max-w-md mx-auto">
                <li class="flex items-start">
                    <i class="bi bi-x-circle text-orange-500 mr-2 mt-1 flex-shrink-0"></i>
                    <span>Você precisa estar autenticado para acessar esta área</span>
                </li>
                <li class="flex items-start">
                    <i class="bi bi-x-circle text-orange-500 mr-2 mt-1 flex-shrink-0"></i>
                    <span>Sua conta não possui permissão para este recurso</span>
                </li>
                <li class="flex items-start">
                    <i class="bi bi-x-circle text-orange-500 mr-2 mt-1 flex-shrink-0"></i>
                    <span>O link pode estar incorreto ou expirado</span>
                </li>
            </ul>
        </div>

        <!-- Contato -->
        <div class="mt-8">
            <p class="text-sm text-gray-500 mb-3">Precisa de ajuda?</p>
            <a href="{{ route('contact.index') }}" 
               class="inline-flex items-center text-primary hover:text-red-700 font-medium transition-colors">
                <i class="bi bi-envelope mr-2"></i>
                Entre em contato conosco
            </a>
        </div>
    </div>
</div>

@endsection

