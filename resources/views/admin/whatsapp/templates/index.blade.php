@extends('admin.layout')

@section('title', 'Templates WhatsApp')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Templates de Mensagens</h1>
            <p class="text-gray-600 mt-1">Gerencie os templates de notificações WhatsApp</p>
        </div>
        <a href="{{ route('admin.whatsapp.templates.create') }}" 
           class="bg-primary hover:opacity-90 text-white px-4 py-2 rounded-lg transition-all shadow-sm hover:shadow-md">
            <i class="bi bi-plus-circle mr-2"></i> Novo Template
        </a>
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

    @if($templates->isEmpty())
        <div class="text-center py-12">
            <div class="mb-4">
                <i class="bi bi-chat-text text-6xl text-gray-400"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Nenhum template configurado</h3>
            <p class="text-gray-600 mb-6">Crie seu primeiro template para começar a personalizar as mensagens WhatsApp.</p>
            <a href="{{ route('admin.whatsapp.templates.create') }}" 
               class="bg-primary hover:opacity-90 text-white px-6 py-3 rounded-lg transition-all shadow-sm hover:shadow-md">
                <i class="bi bi-plus-circle mr-2"></i> Criar Primeiro Template
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($templates as $template)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $template->name }}</h3>
                                <div class="flex items-center space-x-2">
                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                        {{ $template->type_label }}
                                    </span>
                                    @if($template->is_active)
                                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                            Ativo
                                        </span>
                                    @else
                                        <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                            Inativo
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="space-y-3 mb-4">
                            @if($template->variables && count($template->variables) > 0)
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-2">Variáveis disponíveis:</p>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($template->variables as $variable)
                                            <span class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded">
                                                {{ '{' . $variable . '}' }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div>
                                <p class="text-sm font-medium text-gray-600 mb-2">Preview:</p>
                                <div class="bg-gray-50 p-3 rounded-lg text-sm text-gray-700 max-h-24 overflow-y-auto">
                                    {{ Str::limit($template->getPreview(), 120) }}
                                </div>
                            </div>
                        </div>

                        <div class="flex space-x-2 pt-4 border-t border-gray-200">
                            <a href="{{ route('admin.whatsapp.templates.show', $template) }}" 
                               class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-lg text-sm font-medium transition-colors text-center">
                                <i class="bi bi-eye mr-1"></i> Ver
                            </a>
                            
                            <a href="{{ route('admin.whatsapp.templates.edit', $template) }}" 
                               class="flex-1 bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-2 rounded-lg text-sm font-medium transition-colors text-center">
                                <i class="bi bi-pencil mr-1"></i> Editar
                            </a>
                            
                            <form method="POST" action="{{ route('admin.whatsapp.templates.toggle', $template) }}" class="flex-1">
                                @csrf
                                <button type="submit" 
                                        class="w-full {{ $template->is_active ? 'bg-yellow-100 hover:bg-yellow-200 text-yellow-700' : 'bg-green-100 hover:bg-green-200 text-green-700' }} px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                                    <i class="bi bi-power mr-1"></i> 
                                    {{ $template->is_active ? 'Desativar' : 'Ativar' }}
                                </button>
                            </form>
                        </div>

                        <div class="flex space-x-2 mt-2">
                            <form method="POST" action="{{ route('admin.whatsapp.templates.duplicate', $template) }}" class="flex-1">
                                @csrf
                                <button type="submit" 
                                        class="w-full bg-indigo-100 hover:bg-indigo-200 text-indigo-700 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                                    <i class="bi bi-files mr-1"></i> Duplicar
                                </button>
                            </form>
                            
                            <form method="POST" action="{{ route('admin.whatsapp.templates.destroy', $template) }}" 
                                  onsubmit="return confirm('Tem certeza que deseja deletar este template?')" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full bg-red-100 hover:bg-red-200 text-red-700 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                                    <i class="bi bi-trash mr-1"></i> Deletar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Estatísticas -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="bi bi-chat-text text-2xl text-blue-500"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total de Templates</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $templates->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="bi bi-check-circle text-2xl text-green-500"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Ativos</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $templates->where('is_active', true)->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="bi bi-tags text-2xl text-purple-500"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Tipos Diferentes</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $templates->pluck('type')->unique()->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="bi bi-clock text-2xl text-orange-500"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Última Atualização</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $templates->max('updated_at')?->format('d/m/Y') ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection