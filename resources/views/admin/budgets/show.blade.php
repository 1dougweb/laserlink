@extends('admin.layout')

@section('title', 'Visualizar Orçamento - Laser Link')
@section('page-title', 'Orçamento')

@section('content')
<div class="min-h-screen flex items-center justify-center p-6">
    <div class="max-w-2xl w-full">
        <!-- Banner de Desenvolvimento -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <!-- Header com gradiente -->
            <div class="bg-gradient-to-r from-yellow-400 to-orange-500 p-8 text-center">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-white rounded-full mb-4 shadow-lg">
                    <i class="bi bi-exclamation-triangle text-yellow-500 text-5xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-500 mb-2">
                    Módulo em Desenvolvimento
                </h1>
                <p class="text-gray-500 text-lg">
                    Sistema de Orçamentos
                </p>
            </div>
            
            <!-- Conteúdo -->
            <div class="p-8 text-center">
                <p class="text-gray-700 text-lg mb-6">
                    Esta funcionalidade está sendo desenvolvida e estará disponível em breve.
                </p>
                <a href="{{ route('admin.dashboard') }}" 
                   class="inline-block bg-primary hover:opacity-90 text-white px-6 py-3 rounded-lg transition-all shadow-sm hover:shadow-md">
                    <i class="bi bi-arrow-left mr-2"></i>
                    Voltar ao Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
