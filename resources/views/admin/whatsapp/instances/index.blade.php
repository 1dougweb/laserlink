@extends('admin.layout')

@section('title', 'Instâncias WhatsApp')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Instâncias WhatsApp</h1>
            <p class="text-gray-600 mt-1">Gerencie suas instâncias da Evolution API (Máximo: 3)</p>
        </div>
        <div>
            @if($canCreateNew)
                <a href="{{ route('admin.whatsapp.instances.create') }}" 
                   class="bg-primary hover:opacity-90 text-white px-4 py-2 rounded-lg transition-all shadow-sm hover:shadow-md">
                    <i class="bi bi-plus-circle mr-2"></i> Nova Instância
                </a>
            @else
                <button class="bg-gray-400 text-white px-4 py-2 rounded-lg cursor-not-allowed" 
                        disabled title="Limite máximo atingido">
                    <i class="bi bi-plus-circle mr-2"></i> Nova Instância
                </button>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
            <div class="flex justify-between items-center">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.parentElement.style.display='none'" class="text-green-700 hover:text-green-900">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
            <div class="flex justify-between items-center">
                <span>{{ session('error') }}</span>
                <button onclick="this.parentElement.parentElement.style.display='none'" class="text-red-700 hover:text-red-900">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>
    @endif

    @if($instances->isEmpty())
        <div class="text-center py-12">
            <div class="mb-4">
                <i class="bi bi-whatsapp text-6xl text-gray-400"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Nenhuma instância configurada</h3>
            <p class="text-gray-600 mb-6">Crie sua primeira instância WhatsApp para começar a enviar notificações.</p>
            @if($canCreateNew)
                <a href="{{ route('admin.whatsapp.instances.create') }}" 
                   class="bg-primary hover:opacity-90 text-white px-6 py-3 rounded-lg transition-all shadow-sm hover:shadow-md">
                    <i class="bi bi-plus-circle mr-2"></i> Criar Primeira Instância
                </a>
            @endif
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($instances as $instance)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $instance->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $instance->purpose_label }}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                @if($instance->is_active)
                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                        Ativa
                                    </span>
                                @else
                                    <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                        Inativa
                                    </span>
                                @endif
                                
                                <div class="relative" x-data="{ showActions: false }">
                                    <button @click="showActions = !showActions" 
                                            class="text-gray-400 hover:text-gray-600 p-1">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    
                                    <div x-show="showActions" 
                                         @click.away="showActions = false"
                                         x-cloak
                                         class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-10">
                                        <a href="{{ route('admin.whatsapp.instances.show', $instance) }}" 
                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="bi bi-eye mr-2"></i> Ver Detalhes
                                        </a>
                                        <form method="POST" action="{{ route('admin.whatsapp.instances.toggle', $instance) }}" class="block">
                                            @csrf
                                            <button type="submit" 
                                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                <i class="bi bi-power mr-2"></i> 
                                                {{ $instance->is_active ? 'Desativar' : 'Ativar' }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.whatsapp.instances.destroy', $instance) }}" 
                                              onsubmit="return confirm('Tem certeza que deseja deletar esta instância?')"
                                              class="block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                                <i class="bi bi-trash mr-2"></i> Deletar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Status:</span>
                                <span class="text-sm font-medium {{ $instance->status_color }}">
                                    {{ $instance->status_label }}
                                </span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Mensagens:</span>
                                <span class="text-sm font-medium">{{ $instance->notifications_count }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Última atualização:</span>
                                <span class="text-sm text-gray-900">{{ $instance->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>

                        @if($instance->status === 'disconnected' && $instance->is_active)
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-orange-600">
                                        <i class="bi bi-exclamation-triangle mr-1"></i>
                                        QR Code necessário
                                    </span>
                                    <a href="{{ route('admin.whatsapp.instances.show', $instance) }}" 
                                       class="text-primary hover:text-primary-dark text-sm font-medium">
                                        Conectar
                                    </a>
                                </div>
                            </div>
                        @elseif($instance->status === 'connected')
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <div class="flex items-center text-green-600">
                                    <i class="bi bi-check-circle mr-2"></i>
                                    <span class="text-sm font-medium">Conectada e funcionando</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Estatísticas Gerais -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="bi bi-whatsapp text-2xl text-green-500"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total de Instâncias</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $instances->count() }}/3</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="bi bi-check-circle text-2xl text-green-500"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Conectadas</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $instances->where('status', 'connected')->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="bi bi-chat-dots text-2xl text-blue-500"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total de Mensagens</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $instances->sum('notifications_count') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="bi bi-power text-2xl text-green-500"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Ativas</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $instances->where('is_active', true)->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection