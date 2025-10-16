@extends('admin.layout')

@section('title', 'Campos Extras')
@section('page-title', 'Campos Extras')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Campos Extras</h1>
        <a href="{{ route('admin.extra-fields.create') }}" 
           class="bg-primary hover:opacity-90 text-white px-4 py-2 rounded-lg transition-all shadow-sm hover:shadow-md">
            + Novo Campo
        </a>
    </div>

    <!-- Filtros de Pesquisa -->
    <div class="bg-white rounded-lg shadow mb-6" x-data="{ showFilters: {{ request()->hasAny(['search', 'type', 'status', 'required']) ? 'true' : 'false' }} }">
        <div class="p-4 border-b border-gray-200">
            <button @click="showFilters = !showFilters" 
                    class="flex items-center justify-between w-full text-left">
                <div class="flex items-center space-x-2">
                    <i class="bi bi-funnel text-gray-600"></i>
                    <span class="font-medium text-gray-900">Filtros de Pesquisa</span>
                    @if(request()->hasAny(['search', 'type', 'status', 'required']))
                        <span class="bg-primary bg-opacity-10 text-primary text-xs font-medium px-2.5 py-0.5 rounded-full">
                            Ativos
                        </span>
                    @endif
                </div>
                <i class="bi transition-transform duration-200" 
                   :class="showFilters ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
            </button>
        </div>

        <form method="GET" action="{{ route('admin.extra-fields.index') }}" x-show="showFilters" x-cloak>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Pesquisa -->
                <div class="lg:col-span-2">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="bi bi-search mr-1"></i>
                        Buscar por Nome ou Slug
                    </label>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Digite o nome ou slug..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>

                <!-- Tipo -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="bi bi-gear mr-1"></i>
                        Tipo de Campo
                    </label>
                    <select id="type" 
                            name="type" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">Todos</option>
                        <option value="text" {{ request('type') === 'text' ? 'selected' : '' }}>Texto</option>
                        <option value="textarea" {{ request('type') === 'textarea' ? 'selected' : '' }}>Área de Texto</option>
                        <option value="select" {{ request('type') === 'select' ? 'selected' : '' }}>Seleção (Dropdown)</option>
                        <option value="radio" {{ request('type') === 'radio' ? 'selected' : '' }}>Radio Buttons</option>
                        <option value="checkbox" {{ request('type') === 'checkbox' ? 'selected' : '' }}>Checkboxes</option>
                        <option value="image" {{ request('type') === 'image' ? 'selected' : '' }}>Seleção Visual (Imagens)</option>
                        <option value="color" {{ request('type') === 'color' ? 'selected' : '' }}>Seleção Visual (Cores)</option>
                        <option value="number" {{ request('type') === 'number' ? 'selected' : '' }}>Número</option>
                        <option value="date" {{ request('type') === 'date' ? 'selected' : '' }}>Data</option>
                        <option value="file" {{ request('type') === 'file' ? 'selected' : '' }}>Arquivo</option>
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="bi bi-toggle-on mr-1"></i>
                        Status
                    </label>
                    <select id="status" 
                            name="status" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">Todos</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Ativo</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inativo</option>
                    </select>
                </div>

                <!-- Obrigatório -->
                <div>
                    <label for="required" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="bi bi-asterisk mr-1"></i>
                        Campo Obrigatório
                    </label>
                    <select id="required" 
                            name="required" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">Todos</option>
                        <option value="1" {{ request('required') === '1' ? 'selected' : '' }}>Sim</option>
                        <option value="0" {{ request('required') === '0' ? 'selected' : '' }}>Não</option>
                    </select>
                </div>

                <!-- Ordenação -->
                <div>
                    <label for="sort_by" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="bi bi-sort-down mr-1"></i>
                        Ordenar por
                    </label>
                    <select id="sort_by" 
                            name="sort_by" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="sort_order" {{ request('sort_by', 'sort_order') === 'sort_order' ? 'selected' : '' }}>Ordem Personalizada</option>
                        <option value="name" {{ request('sort_by') === 'name' ? 'selected' : '' }}>Nome</option>
                        <option value="type" {{ request('sort_by') === 'type' ? 'selected' : '' }}>Tipo</option>
                        <option value="created_at" {{ request('sort_by') === 'created_at' ? 'selected' : '' }}>Data de Criação</option>
                    </select>
                </div>
            </div>

            <!-- Botões de Ação -->
            <div class="px-6 pb-6 flex items-center justify-between">
                <a href="{{ route('admin.extra-fields.index') }}" 
                   class="text-sm text-gray-600 hover:text-gray-900 flex items-center">
                    <i class="bi bi-x-circle mr-1"></i>
                    Limpar Filtros
                </a>
                <div class="flex space-x-3">
                    <button type="submit" 
                            class="bg-primary hover:opacity-90 text-white px-6 py-2 rounded-lg transition-all shadow-sm hover:shadow-md flex items-center">
                        <i class="bi bi-search mr-2"></i>
                        Filtrar
                    </button>
                </div>
            </div>
        </form>
    </div>

    @if($fields->count() > 0)
        <!-- Contador de Resultados -->
        <div class="mb-4 flex items-center justify-between">
            <div class="text-sm text-gray-600">
                Mostrando <span class="font-semibold">{{ $fields->firstItem() }}</span> 
                a <span class="font-semibold">{{ $fields->lastItem() }}</span> 
                de <span class="font-semibold">{{ $fields->total() }}</span> campos
            </div>
            @if(request()->hasAny(['search', 'type', 'status', 'required']))
                <div class="text-sm text-primary">
                    <i class="bi bi-info-circle mr-1"></i>
                    Resultados filtrados
                </div>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <i class="bi bi-tag mr-1"></i>
                                Campo
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <i class="bi bi-gear mr-1"></i>
                                Tipo
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <i class="bi bi-list-ul mr-1"></i>
                                Opções
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <i class="bi bi-toggle-on mr-1"></i>
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <i class="bi bi-tools mr-1"></i>
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="sortable-fields">
                        @foreach($fields as $field)
                            <tr data-id="{{ $field->id }}" class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <i class="bi bi-grip-vertical text-gray-400 mr-3 cursor-move"></i>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 flex items-center">
                                                @switch($field->type)
                                                    @case('text')
                                                        <i class="bi bi-type mr-2 text-blue-500"></i>
                                                        @break
                                                    @case('textarea')
                                                        <i class="bi bi-textarea-resize mr-2 text-green-500"></i>
                                                        @break
                                                    @case('select')
                                                        <i class="bi bi-list mr-2 text-purple-500"></i>
                                                        @break
                                                    @case('radio')
                                                        <i class="bi bi-circle mr-2 text-orange-500"></i>
                                                        @break
                                                    @case('checkbox')
                                                        <i class="bi bi-check-square mr-2 text-pink-500"></i>
                                                        @break
                                                    @case('number')
                                                        <i class="bi bi-123 mr-2 text-indigo-500"></i>
                                                        @break
                                                    @case('date')
                                                        <i class="bi bi-calendar mr-2 text-teal-500"></i>
                                                        @break
                                                    @case('file')
                                                        <i class="bi bi-file-earmark mr-2 text-gray-500"></i>
                                                        @break
                                                    @default
                                                        <i class="bi bi-gear mr-2 text-gray-500"></i>
                                                @endswitch
                                                {{ $field->name }}
                                                @if($field->is_required)
                                                    <span class="ml-2 text-red-500" title="Obrigatório">*</span>
                                                @endif
                                            </div>
                                            @if($field->description)
                                                <div class="text-sm text-gray-500 mt-1">{{ Str::limit($field->description, 60) }}</div>
                                            @endif
                                            <div class="text-xs text-gray-400 mt-1">
                                                Slug: <code class="bg-gray-100 px-1 rounded">{{ $field->slug }}</code>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @switch($field->type)
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
                                        {{ ucfirst($field->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center text-sm text-gray-500">
                                        <i class="bi bi-list-ul mr-1"></i>
                                        <span class="font-medium">{{ $field->options_count }}</span>
                                        <span class="ml-1">opções</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <form method="POST" action="{{ route('admin.extra-fields.toggle', $field) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-colors
                                                        {{ $field->is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                                <i class="bi {{ $field->is_active ? 'bi-toggle-on' : 'bi-toggle-off' }} mr-1"></i>
                                                {{ $field->is_active ? 'Ativo' : 'Inativo' }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.extra-fields.show', $field) }}" 
                                           class="text-blue-600 hover:text-blue-900 p-1 rounded transition-colors" 
                                           title="Ver detalhes">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.extra-fields.edit', $field) }}" 
                                           class="text-indigo-600 hover:text-indigo-900 p-1 rounded transition-colors" 
                                           title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="{{ route('admin.extra-fields.options', $field) }}" 
                                           class="text-green-600 hover:text-green-900 p-1 rounded transition-colors" 
                                           title="Gerenciar opções">
                                            <i class="bi bi-list-ul"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.extra-fields.destroy', $field) }}" 
                                              class="inline" 
                                              onsubmit="return confirm('Tem certeza que deseja excluir este campo? Esta ação não pode ser desfeita.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900 p-1 rounded transition-colors" 
                                                    title="Excluir">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $fields->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <div class="text-gray-500 text-lg mb-4">Nenhum campo encontrado</div>
            <a href="{{ route('admin.extra-fields.create') }}" 
               class="inline-block bg-primary hover:opacity-90 text-white px-4 py-2 rounded-lg transition-all shadow-sm hover:shadow-md">
                Criar primeiro campo
            </a>
        </div>
    @endif
</div>

<!-- Scripts para ordenação -->
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script>
$(function() {
    $("#sortable-fields").sortable({
        items: "tr",
        cursor: "move",
        opacity: 0.6,
        update: function(event, ui) {
            var order = $(this).sortable("toArray", { attribute: "data-id" });
            
            console.log('Enviando ordem:', order);
            
            $.ajax({
                url: "{{ route('admin.extra-fields.reorder') }}",
                type: 'POST',
                data: {
                    order: order,
                    _token: '{{ csrf_token() }}'
                },
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    console.log('Resposta recebida:', response);
                    if(response.success) {
                        console.log('Ordem atualizada com sucesso!');
                        // Opcional: mostrar notificação de sucesso
                        // toastr.success('Ordem atualizada com sucesso!');
                    } else {
                        console.error('Erro na resposta:', response);
                        alert('Erro ao atualizar a ordem: ' + (response.message || 'Erro desconhecido'));
                    }
                },
                error: function(xhr) {
                    console.error('Erro na requisição AJAX:', xhr);
                    console.error('Status:', xhr.status);
                    console.error('Response Text:', xhr.responseText);
                    alert('Erro ao atualizar a ordem. Verifique o console para mais detalhes.');
                }
            });
        }
    }).disableSelection();
});
</script>
@endpush
@endsection
