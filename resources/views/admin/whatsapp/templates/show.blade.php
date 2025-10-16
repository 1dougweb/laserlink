@extends('admin.layout')

@section('title', 'Visualizar Template')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $template->name }}</h1>
            <p class="text-gray-600 mt-1">{{ $template->type_label }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.whatsapp.templates.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-all">
                <i class="bi bi-arrow-left mr-2"></i> Voltar
            </a>
            <a href="{{ route('admin.whatsapp.templates.edit', $template) }}" 
               class="bg-primary hover:opacity-90 text-white px-4 py-2 rounded-lg transition-all">
                <i class="bi bi-pencil mr-2"></i> Editar
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Informações do Template -->
        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informações do Template</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Nome</label>
                        <p class="text-sm text-gray-900 mt-1">{{ $template->name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Tipo</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-1">
                            {{ $template->type_label }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Status</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $template->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }} mt-1">
                            {{ $template->is_active ? 'Ativo' : 'Inativo' }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Criado em</label>
                        <p class="text-sm text-gray-900 mt-1">{{ $template->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Última atualização</label>
                        <p class="text-sm text-gray-900 mt-1">{{ $template->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Variáveis -->
            @if($template->variables && count($template->variables) > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Variáveis Disponíveis</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($template->variables as $variable)
                            <div class="flex items-center space-x-2">
                                <code class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-sm">{{ '{' . $variable . '}' }}</code>
                                <span class="text-sm text-gray-600">{{ $variable }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Preview da Mensagem -->
        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Preview da Mensagem</h3>
                
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <i class="bi bi-whatsapp text-white text-sm"></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="text-sm text-gray-600 mb-1">WhatsApp</div>
                                <div class="text-sm text-gray-900 whitespace-pre-wrap">{{ $template->getPreview() }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ações -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Ações</h3>
                
                <div class="space-y-3">
                    <a href="{{ route('admin.whatsapp.templates.edit', $template) }}" 
                       class="w-full bg-primary hover:opacity-90 text-white px-4 py-2 rounded-lg transition-all text-sm font-medium text-center block">
                        <i class="bi bi-pencil mr-2"></i> Editar Template
                    </a>
                    
                    <form method="POST" action="{{ route('admin.whatsapp.templates.toggle', $template) }}">
                        @csrf
                        <button type="submit" 
                                class="w-full {{ $template->is_active ? 'bg-yellow-100 hover:bg-yellow-200 text-yellow-700' : 'bg-green-100 hover:bg-green-200 text-green-700' }} px-4 py-2 rounded-lg transition-all text-sm font-medium">
                            <i class="bi bi-power mr-2"></i> 
                            {{ $template->is_active ? 'Desativar' : 'Ativar' }} Template
                        </button>
                    </form>
                    
                    <form method="POST" action="{{ route('admin.whatsapp.templates.duplicate', $template) }}">
                        @csrf
                        <button type="submit" 
                                class="w-full bg-indigo-100 hover:bg-indigo-200 text-indigo-700 px-4 py-2 rounded-lg transition-all text-sm font-medium">
                            <i class="bi bi-files mr-2"></i> Duplicar Template
                        </button>
                    </form>
                    
                    <form method="POST" action="{{ route('admin.whatsapp.templates.destroy', $template) }}" 
                          onsubmit="return confirm('Tem certeza que deseja deletar este template?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full bg-red-100 hover:bg-red-200 text-red-700 px-4 py-2 rounded-lg transition-all text-sm font-medium">
                            <i class="bi bi-trash mr-2"></i> Deletar Template
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

