@extends('admin.layout')

@section('title', 'Campos Extras - ' . $product->name)

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Campos Extras</h1>
            <p class="text-gray-600">{{ $product->name }}</p>
        </div>
        <a href="{{ route('admin.products') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
            ← Voltar aos Produtos
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Campos Associados -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4"> Campos Associados</h2>
            
            @if($product->extraFields->count() > 0)
                <div class="space-y-4" id="associated-fields">
                    @foreach($product->extraFields as $field)
                        <div class="border rounded-lg p-4" data-field-id="{{ $field->id }}">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2">
                                        <h3 class="font-medium text-gray-900">{{ $field->name }}</h3>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ ucfirst($field->type) }}
                                        </span>
                                        @if($field->pivot->is_required)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Obrigatório
                                            </span>
                                        @endif
                                    </div>
                                    @if($field->description)
                                        <p class="text-sm text-gray-600 mt-1">{{ $field->description }}</p>
                                    @endif
                                    <p class="text-xs text-gray-500 mt-1">{{ $field->options->count() }} opções</p>
                                </div>
                                <div class="flex space-x-2">
                                    <button onclick="editField({{ $field->id }})" 
                                            class="text-indigo-600 hover:text-indigo-900 text-sm">
                                        Editar
                                    </button>
                                    <form method="POST" action="{{ route('admin.products.extra-fields.destroy', [$product, $field->id]) }}" 
                                          class="inline" 
                                          onsubmit="return confirm('Remover este campo do produto?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 text-sm">
                                            Remover
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <p>Nenhum campo associado</p>
                    <p class="text-sm">Use o formulário ao lado para adicionar campos</p>
                </div>
            @endif
        </div>

        <!-- Adicionar Novo Campo -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Adicionar Campo</h2>
            
            @if($availableFields->count() > 0)
                <form method="POST" action="{{ route('admin.products.extra-fields.store', $product) }}">
                    @csrf
                    
                    <div class="space-y-4">
                        <div>
                            <label for="extra_field_id" class="block text-sm font-medium text-gray-700">Campo</label>
                            <select id="extra_field_id" 
                                    name="extra_field_id" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    required>
                                <option value="">Selecione um campo</option>
                                @foreach($availableFields as $field)
                                    <option value="{{ $field->id }}">
                                        {{ $field->name }} ({{ ucfirst($field->type) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="is_required" 
                                   name="is_required" 
                                   value="1"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_required" class="ml-2 block text-sm text-gray-900">
                                Campo obrigatório
                            </label>
                        </div>

                        <div>
                            <button type="submit" 
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                                Associar Campo
                            </button>
                        </div>
                    </div>
                </form>
            @else
                <div class="text-center py-8 text-gray-500">
                    <p>Todos os campos já estão associados</p>
                    <a href="{{ route('admin.extra-fields.create') }}" 
                       class="text-blue-600 hover:text-blue-700 text-sm">
                        Criar novo campo
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal de Edição -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Editar Configuração</h3>
                
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="edit_is_required" 
                                   name="is_required" 
                                   value="1"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="edit_is_required" class="ml-2 block text-sm text-gray-900">
                                Campo obrigatório
                            </label>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <button type="button" 
                                    onclick="closeModal()"
                                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition-colors">
                                Cancelar
                            </button>
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                                Salvar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function editField(fieldId) {
    const form = document.getElementById('editForm');
    // Construir URL dinamicamente sem usar route helper com parâmetro vazio
    form.action = `/admin/products/{{ $product->id }}/extra-fields/${fieldId}`;
    
    // Aqui você pode carregar os dados atuais do campo se necessário
    
    document.getElementById('editModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('editModal').classList.add('hidden');
}
</script>
@endsection