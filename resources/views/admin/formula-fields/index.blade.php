@extends('admin.layout')

@section('title', 'Campos de Fórmula')
@section('page-title', 'Campos de Fórmula')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <div class="bg-primary/10 p-3 rounded-lg">
                <i class="bi bi-calculator text-primary text-2xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Campos de Fórmula</h1>
                <p class="text-gray-600">Gerencie fórmulas matemáticas para cálculo de preços</p>
            </div>
        </div>
        <a href="{{ route('admin.formula-fields.create') }}" 
           class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
            <i class="bi bi-plus-lg mr-2"></i>
            Novo Campo
        </a>
    </div>

    @if($formulaFields->count() > 0)
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Campos de Fórmula</h3>
            </div>
            
            <div class="divide-y divide-gray-200" id="formula-fields-list">
                @foreach($formulaFields as $field)
                    <div class="p-6 hover:bg-gray-50 transition-colors" data-id="{{ $field->id }}">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-calculator text-primary text-xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2">
                                            <h4 class="text-lg font-medium text-gray-900">{{ $field->name }}</h4>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $field->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $field->is_active ? 'Ativo' : 'Inativo' }}
                                            </span>
                                        </div>
                                        
                                        @if($field->description)
                                            <p class="text-sm text-gray-600 mt-1">{{ $field->description }}</p>
                                        @endif
                                        
                                        <div class="mt-2">
                                            <div class="bg-gray-100 rounded-lg p-3 font-mono text-sm">
                                                <code class="text-gray-800">{{ $field->formula }}</code>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-3 flex items-center space-x-4 text-sm text-gray-500">
                                            <span class="flex items-center">
                                                <i class="bi bi-hash mr-1"></i>
                                                {{ $field->slug }}
                                            </span>
                                            <span class="flex items-center">
                                                <i class="bi bi-sort-numeric-up mr-1"></i>
                                                Ordem: {{ $field->sort_order }}
                                            </span>
                                            <span class="flex items-center">
                                                <i class="bi bi-box mr-1"></i>
                                                {{ $field->products->count() }} produtos
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.formula-fields.show', $field) }}" 
                                   class="text-blue-600 hover:text-blue-900 p-2 rounded transition-colors" 
                                   title="Ver detalhes">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.formula-fields.edit', $field) }}" 
                                   class="text-indigo-600 hover:text-indigo-900 p-2 rounded transition-colors" 
                                   title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.formula-fields.destroy', $field) }}" 
                                      class="inline" 
                                      onsubmit="return confirm('Tem certeza que deseja excluir este campo de fórmula?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900 p-2 rounded transition-colors" 
                                            title="Excluir">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mt-4">
            {{ $formulaFields->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <div class="bg-gray-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                <i class="bi bi-calculator text-gray-400 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum campo de fórmula encontrado</h3>
            <p class="text-gray-600 mb-4">Crie seu primeiro campo de fórmula para começar</p>
            <a href="{{ route('admin.formula-fields.create') }}" 
               class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg transition-colors">
                Criar primeiro campo
            </a>
        </div>
    @endif
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script>
$(document).ready(function() {
    // Drag and drop para reordenar
    $("#formula-fields-list").sortable({
        items: "> div",
        cursor: "move",
        opacity: 0.6,
        update: function(event, ui) {
            var order = [];
            $("#formula-fields-list > div").each(function(index) {
                order.push($(this).data('id'));
            });
            
            $.ajax({
                url: '{{ route("admin.formula-fields.reorder") }}',
                method: 'POST',
                data: {
                    order: order,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log('Ordem atualizada com sucesso!');
                },
                error: function(xhr) {
                    console.error('Erro ao atualizar ordem:', xhr.responseText);
                }
            });
        }
    }).disableSelection();
});
</script>
@endpush
@endsection
