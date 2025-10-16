@extends('admin.layout')

@section('title', 'Atualizações do Sistema - Laser Link')
@section('page-title', 'Atualizações do Sistema')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Changelog / Atualizações</h1>
            <p class="text-gray-600 mt-1">Histórico de versões e atualizações do sistema</p>
        </div>
        <a href="{{ route('admin.changelogs.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors flex items-center gap-2 shadow-md hover:shadow-lg">
            <i class="bi bi-plus-lg"></i>
            Nova Atualização
        </a>
    </div>

    <!-- Changelogs Timeline -->
    <div class="space-y-6">
        @forelse($changelogs as $changelog)
        <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow">
            <!-- Header -->
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-primary/5 to-transparent">
                <div class="flex items-start justify-between">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-primary rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="bi bi-rocket-takeoff-fill text-white text-xl"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <span class="px-3 py-1 bg-primary text-white text-sm font-bold rounded-full">v{{ $changelog->version }}</span>
                                <span class="text-sm text-gray-500">{{ $changelog->release_date->format('d/m/Y') }}</span>
                                @if(!$changelog->is_published)
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded">Rascunho</span>
                                @endif
                            </div>
                            <h2 class="text-xl font-bold text-gray-900 mb-1">{{ $changelog->title }}</h2>
                            @if($changelog->description)
                                <p class="text-gray-600 text-sm">{{ $changelog->description }}</p>
                            @endif
                            @if($changelog->user)
                                <p class="text-xs text-gray-500 mt-2">
                                    <i class="bi bi-person mr-1"></i>Criado por: {{ $changelog->user->name }}
                                </p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.changelogs.edit', $changelog) }}" class="text-primary hover:text-red-700 p-2">
                            <i class="bi bi-pencil text-lg"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.changelogs.destroy', $changelog) }}" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir esta atualização?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 p-2">
                                <i class="bi bi-trash text-lg"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Content -->
            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Novas Funcionalidades -->
                @if($changelog->features && count($changelog->features) > 0)
                <div>
                    <h4 class="font-semibold text-green-800 mb-3 flex items-center">
                        <i class="bi bi-plus-circle-fill text-green-600 mr-2"></i>
                        Novas Funcionalidades
                    </h4>
                    <ul class="space-y-2">
                        @foreach($changelog->features as $feature)
                            <li class="text-sm text-gray-700 flex items-start">
                                <i class="bi bi-check2 text-green-600 mr-2 mt-0.5 flex-shrink-0"></i>
                                <span>{{ $feature }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <!-- Melhorias -->
                @if($changelog->improvements && count($changelog->improvements) > 0)
                <div>
                    <h4 class="font-semibold text-blue-800 mb-3 flex items-center">
                        <i class="bi bi-arrow-up-circle-fill text-blue-600 mr-2"></i>
                        Melhorias
                    </h4>
                    <ul class="space-y-2">
                        @foreach($changelog->improvements as $improvement)
                            <li class="text-sm text-gray-700 flex items-start">
                                <i class="bi bi-chevron-right text-blue-600 mr-2 mt-0.5 flex-shrink-0"></i>
                                <span>{{ $improvement }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <!-- Correções -->
                @if($changelog->fixes && count($changelog->fixes) > 0)
                <div>
                    <h4 class="font-semibold text-orange-800 mb-3 flex items-center">
                        <i class="bi bi-bug-fill text-orange-600 mr-2"></i>
                        Correções de Bugs
                    </h4>
                    <ul class="space-y-2">
                        @foreach($changelog->fixes as $fix)
                            <li class="text-sm text-gray-700 flex items-start">
                                <i class="bi bi-wrench text-orange-600 mr-2 mt-0.5 flex-shrink-0"></i>
                                <span>{{ $fix }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <i class="bi bi-inbox text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Nenhuma atualização registrada</h3>
            <p class="text-gray-600 mb-6">Comece a documentar as atualizações do seu sistema</p>
            <a href="{{ route('admin.changelogs.create') }}" class="inline-flex items-center px-6 py-3 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors">
                <i class="bi bi-plus-lg mr-2"></i>
                Criar Primeira Atualização
            </a>
        </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    @if($changelogs->hasPages())
    <div class="mt-6">
        {{ $changelogs->links() }}
    </div>
    @endif
</div>
@endsection

