@extends('admin.layout')

@section('title', 'Ver Campo Extra')
@section('page-title', 'Detalhes do Campo')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center">
                @switch($extraField->type)
                    @case('text')
                        <i class="bi bi-type text-4xl text-blue-500 mr-4"></i>
                        @break
                    @case('textarea')
                        <i class="bi bi-textarea-resize text-4xl text-green-500 mr-4"></i>
                        @break
                    @case('select')
                        <i class="bi bi-list text-4xl text-purple-500 mr-4"></i>
                        @break
                    @case('radio')
                        <i class="bi bi-circle text-4xl text-orange-500 mr-4"></i>
                        @break
                    @case('checkbox')
                        <i class="bi bi-check-square text-4xl text-pink-500 mr-4"></i>
                        @break
                    @case('number')
                        <i class="bi bi-123 text-4xl text-indigo-500 mr-4"></i>
                        @break
                    @case('date')
                        <i class="bi bi-calendar text-4xl text-teal-500 mr-4"></i>
                        @break
                    @case('file')
                        <i class="bi bi-file-earmark text-4xl text-gray-500 mr-4"></i>
                        @break
                    @default
                        <i class="bi bi-gear text-4xl text-gray-500 mr-4"></i>
                @endswitch
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                        {{ $extraField->name }}
                        @if($extraField->is_required)
                            <span class="ml-2 text-red-500" title="Obrigatório">*</span>
                        @endif
                    </h1>
                    <p class="text-sm text-gray-600 mt-1">{{ $extraField->description }}</p>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('admin.extra-fields.edit', $extraField) }}" 
                   class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
                    <i class="bi bi-pencil mr-2"></i>
                    Editar
                </a>
                <a href="{{ route('admin.extra-fields.options', $extraField) }}" 
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
                    <i class="bi bi-list-ul mr-2"></i>
                    Opções
                </a>
                <a href="{{ route('admin.extra-fields.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
                    <i class="bi bi-arrow-left mr-2"></i>
                    Voltar
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Informações Básicas -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                <i class="bi bi-info-circle text-primary mr-2"></i>
                Informações Básicas
            </h2>
            
            <dl class="space-y-4">
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <dt class="text-sm font-medium text-gray-500 flex items-center">
                        <i class="bi bi-tag mr-2"></i>
                        Nome
                    </dt>
                    <dd class="text-sm text-gray-900 font-medium">{{ $extraField->name }}</dd>
                </div>
                
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <dt class="text-sm font-medium text-gray-500 flex items-center">
                        <i class="bi bi-link-45deg mr-2"></i>
                        Slug
                    </dt>
                    <dd class="text-sm text-gray-900 font-mono bg-gray-100 px-2 py-1 rounded">{{ $extraField->slug }}</dd>
                </div>
                
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <dt class="text-sm font-medium text-gray-500 flex items-center">
                        <i class="bi bi-gear mr-2"></i>
                        Tipo
                    </dt>
                    <dd class="mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            @switch($extraField->type)
                                @case('text') bg-blue-100 text-blue-800 @break
                                @case('textarea') bg-green-100 text-green-800 @break
                                @case('select') bg-purple-100 text-purple-800 @break
                                @case('radio') bg-orange-100 text-orange-800 @break
                                @case('checkbox') bg-pink-100 text-pink-800 @break
                                @case('number') bg-indigo-100 text-indigo-800 @break
                                @case('date') bg-teal-100 text-teal-800 @break
                                @case('file') bg-gray-100 text-gray-800 @break
                                @default bg-gray-100 text-gray-800
                            @endswitch">
                            {{ ucfirst($extraField->type) }}
                        </span>
                    </dd>
                </div>
                
                @if($extraField->description)
                    <div class="py-2 border-b border-gray-100">
                        <dt class="text-sm font-medium text-gray-500 flex items-center mb-2">
                            <i class="bi bi-chat-text mr-2"></i>
                            Descrição
                        </dt>
                        <dd class="text-sm text-gray-900">{{ $extraField->description }}</dd>
                    </div>
                @endif
                
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <dt class="text-sm font-medium text-gray-500 flex items-center">
                        <i class="bi bi-toggle-on mr-2"></i>
                        Status
                    </dt>
                    <dd class="mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $extraField->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <i class="bi {{ $extraField->is_active ? 'bi-check-circle' : 'bi-x-circle' }} mr-1"></i>
                            {{ $extraField->is_active ? 'Ativo' : 'Inativo' }}
                        </span>
                    </dd>
                </div>
                
                <div class="flex items-center justify-between py-2">
                    <dt class="text-sm font-medium text-gray-500 flex items-center">
                        <i class="bi bi-exclamation-triangle mr-2"></i>
                        Obrigatório
                    </dt>
                    <dd class="mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $extraField->is_required ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                            <i class="bi {{ $extraField->is_required ? 'bi-check' : 'bi-dash' }} mr-1"></i>
                            {{ $extraField->is_required ? 'Sim' : 'Não' }}
                        </span>
                    </dd>
                </div>
            </dl>
        </div>

        <!-- Opções do Campo -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="bi bi-list-ul text-primary mr-2"></i>
                    Opções do Campo
                </h2>
                <a href="{{ route('admin.extra-fields.options', $extraField) }}" 
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm transition-colors flex items-center">
                    <i class="bi bi-gear mr-1"></i>
                    Gerenciar
                </a>
            </div>
            
            @if($extraField->options->count() > 0)
                <div class="space-y-3">
                    @foreach($extraField->options as $option)
                        <div class="border rounded-lg p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="font-medium text-gray-900 flex items-center">
                                        <i class="bi bi-circle-fill text-xs text-gray-400 mr-2"></i>
                                        {{ $option->label }}
                                        @if($option->is_active)
                                            <span class="ml-2 text-green-600 text-xs" title="Ativo">
                                                <i class="bi bi-check-circle"></i>
                                            </span>
                                        @else
                                            <span class="ml-2 text-red-600 text-xs" title="Inativo">
                                                <i class="bi bi-x-circle"></i>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-500 mt-1">
                                        <i class="bi bi-tag mr-1"></i>
                                        Valor: <code class="bg-gray-100 px-1 rounded">{{ $option->value }}</code>
                                    </div>
                                    @if($option->description)
                                        <div class="text-sm text-gray-600 mt-2 flex items-start">
                                            <i class="bi bi-chat-text mr-1 mt-0.5"></i>
                                            {{ $option->description }}
                                        </div>
                                    @endif
                                </div>
                                <div class="text-right ml-4">
                                    <div class="text-sm font-medium text-gray-900 flex items-center">
                                        <i class="bi bi-currency-dollar mr-1"></i>
                                        R$ {{ number_format($option->price, 2, ',', '.') }}
                                    </div>
                                    <div class="text-xs text-gray-500 flex items-center">
                                        <i class="bi bi-info-circle mr-1"></i>
                                        {{ ucfirst($option->price_type) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <div class="text-gray-400 text-4xl mb-4">
                        <i class="bi bi-list-ul"></i>
                    </div>
                    <p class="text-lg font-medium mb-2">Nenhuma opção configurada</p>
                    <p class="text-sm mb-4">Configure as opções para este campo</p>
                    <a href="{{ route('admin.extra-fields.options', $extraField) }}" 
                       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm transition-colors inline-flex items-center">
                        <i class="bi bi-plus-circle mr-1"></i>
                        Adicionar opções
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Configurações Avançadas -->
    @if($extraField->settings || $extraField->validation_rules)
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                <i class="bi bi-sliders text-primary mr-2"></i>
                Configurações Avançadas
            </h2>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @if($extraField->settings)
                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-3 flex items-center">
                            <i class="bi bi-gear mr-2"></i>
                            Configurações do Campo
                        </h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            @if(isset($extraField->settings['placeholder']))
                                <div class="mb-3">
                                    <span class="text-xs font-medium text-gray-500">Placeholder:</span>
                                    <div class="text-sm text-gray-900 bg-white p-2 rounded border">{{ $extraField->settings['placeholder'] }}</div>
                                </div>
                            @endif
                            @if(isset($extraField->settings['unit']))
                                <div class="mb-3">
                                    <span class="text-xs font-medium text-gray-500">Unidade:</span>
                                    <div class="text-sm text-gray-900 bg-white p-2 rounded border">{{ $extraField->settings['unit'] }}</div>
                                </div>
                            @endif
                            @if(isset($extraField->settings['min']))
                                <div class="mb-3">
                                    <span class="text-xs font-medium text-gray-500">Valor Mínimo:</span>
                                    <div class="text-sm text-gray-900 bg-white p-2 rounded border">{{ $extraField->settings['min'] }}</div>
                                </div>
                            @endif
                            @if(isset($extraField->settings['max']))
                                <div class="mb-3">
                                    <span class="text-xs font-medium text-gray-500">Valor Máximo:</span>
                                    <div class="text-sm text-gray-900 bg-white p-2 rounded border">{{ $extraField->settings['max'] }}</div>
                                </div>
                            @endif
                            @if(isset($extraField->settings['step']))
                                <div class="mb-3">
                                    <span class="text-xs font-medium text-gray-500">Incremento:</span>
                                    <div class="text-sm text-gray-900 bg-white p-2 rounded border">{{ $extraField->settings['step'] }}</div>
                                </div>
                            @endif
                            @if(isset($extraField->settings['rows']))
                                <div class="mb-3">
                                    <span class="text-xs font-medium text-gray-500">Número de Linhas:</span>
                                    <div class="text-sm text-gray-900 bg-white p-2 rounded border">{{ $extraField->settings['rows'] }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
                
                @if($extraField->validation_rules)
                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-3 flex items-center">
                            <i class="bi bi-shield-check mr-2"></i>
                            Regras de Validação
                        </h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            @if(isset($extraField->validation_rules['min_length']))
                                <div class="mb-3">
                                    <span class="text-xs font-medium text-gray-500">Comprimento Mínimo:</span>
                                    <div class="text-sm text-gray-900 bg-white p-2 rounded border">{{ $extraField->validation_rules['min_length'] }} caracteres</div>
                                </div>
                            @endif
                            @if(isset($extraField->validation_rules['max_length']))
                                <div class="mb-3">
                                    <span class="text-xs font-medium text-gray-500">Comprimento Máximo:</span>
                                    <div class="text-sm text-gray-900 bg-white p-2 rounded border">{{ $extraField->validation_rules['max_length'] }} caracteres</div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection
