@extends('admin.layout')

@section('title', 'Fornecedores')
@section('page-title', 'Fornecedores')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <h1 class="text-2xl font-bold text-gray-900">Fornecedores</h1>
            <!-- Botão de exclusão múltipla -->
            <button type="button" 
                    id="deleteSelectedBtn"
                    onclick="confirmDeleteSelected()"
                    class="hidden bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                <i class="bi bi-trash mr-2"></i>
                Excluir Selecionados (<span id="selectedCount">0</span>)
            </button>
        </div>
        <a href="{{ route('admin.suppliers.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
            <i class="bi bi-plus mr-2"></i>Novo Fornecedor
        </a>
    </div>

    <!-- Filtros de Pesquisa -->
    <div class="bg-white rounded-lg shadow" x-data="{ showFilters: {{ request()->hasAny(['search', 'status']) ? 'true' : 'false' }} }">
        <div class="p-4 border-b border-gray-200">
            <button @click="showFilters = !showFilters" 
                    class="flex items-center justify-between w-full text-left">
                <div class="flex items-center space-x-2">
                    <i class="bi bi-funnel text-gray-600"></i>
                    <span class="font-medium text-gray-900">Filtros de Pesquisa</span>
                    @if(request()->hasAny(['search', 'status']))
                        <span class="bg-primary bg-opacity-10 text-primary text-xs font-medium px-2.5 py-0.5 rounded-full">
                            Ativos
                        </span>
                    @endif
                </div>
                <i class="bi transition-transform duration-200" 
                   :class="showFilters ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
            </button>
        </div>

        <form method="GET" action="{{ route('admin.suppliers.index') }}" x-show="showFilters" x-cloak>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Busca -->
                <div class="lg:col-span-2">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="bi bi-search mr-1"></i>
                        Buscar por Nome, CNPJ ou E-mail
                    </label>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Digite o nome, CNPJ ou e-mail do fornecedor..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
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
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Ativos</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inativos</option>
                    </select>
                </div>
            </div>

            <!-- Botões de Ação -->
            <div class="px-6 pb-6 flex justify-between items-center">
                <a href="{{ route('admin.suppliers.index') }}" 
                   class="text-sm text-gray-600 hover:text-gray-900">
                    <i class="bi bi-x-circle mr-1"></i>
                    Limpar Filtros
                </a>
                <button type="submit" 
                        class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-colors">
                    <i class="bi bi-check-circle mr-1"></i>
                    Aplicar Filtros
                </button>
            </div>
        </form>
    </div>

    <!-- Tabela de Fornecedores -->
    <div class="bg-white rounded-lg shadow">
        <!-- Indicador de Checkboxes -->
        <div class="bg-blue-50 border-b border-blue-100 px-4 py-2 text-sm text-blue-700">
            <i class="bi bi-info-circle mr-2"></i>
            <strong>Novo:</strong> Use os checkboxes ☑️ na primeira coluna para selecionar múltiplos fornecedores
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left" style="width: 60px; background-color: #f9fafb;">
                            <div style="display: flex; align-items: center; justify-content: center;">
                                <input type="checkbox" 
                                       id="selectAll"
                                       onchange="toggleSelectAll(this)"
                                       style="width: 20px; height: 20px; cursor: pointer; display: block !important; opacity: 1 !important; position: relative !important; visibility: visible !important; margin: 0 auto;"
                                       class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary focus:ring-2">
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CNPJ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contato</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produtos</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($suppliers as $supplier)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-4" style="width: 60px; text-align: center;">
                            <div style="display: flex; align-items: center; justify-content: center;">
                                <input type="checkbox" 
                                       class="supplier-checkbox w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary focus:ring-2"
                                       style="width: 20px; height: 20px; cursor: pointer; display: block !important; opacity: 1 !important; position: relative !important; visibility: visible !important; margin: 0 auto;"
                                       value="{{ $supplier->id }}"
                                       onchange="updateSelectedCount()">
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $supplier->name }}</div>
                            @if($supplier->company_name)
                                <div class="text-xs text-gray-500">{{ $supplier->company_name }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $supplier->cnpj ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $supplier->email ?? '-' }}</div>
                            @if($supplier->phone)
                                <div class="text-xs text-gray-500">{{ $supplier->phone }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $supplier->products_count }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $supplier->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $supplier->is_active ? 'Ativo' : 'Inativo' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('admin.suppliers.show', $supplier) }}" class="text-primary hover:text-red-700" title="Visualizar">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.suppliers.edit', $supplier) }}" class="text-primary hover:text-red-700" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button type="button" 
                                    onclick="confirmDelete({{ $supplier->id }}, '{{ addslashes($supplier->name) }}')" 
                                    class="text-red-600 hover:text-red-900"
                                    title="Excluir">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            Nenhum fornecedor encontrado
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($suppliers->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $suppliers->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal de Confirmação de Exclusão Individual -->
<div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
        <div class="mt-3">
            <!-- Ícone -->
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="bi bi-exclamation-triangle text-red-600 text-2xl"></i>
            </div>
            <!-- Título -->
            <h3 class="text-lg font-medium text-gray-900 text-center mt-4">
                Confirmar Exclusão
            </h3>
            <!-- Mensagem -->
            <div class="mt-2 px-4 py-3">
                <p class="text-sm text-gray-600 text-center" id="deleteMessage">
                    Tem certeza que deseja excluir este fornecedor?
                </p>
                <p class="text-xs text-gray-500 text-center mt-2">
                    Esta ação não pode ser desfeita.
                </p>
            </div>
            <!-- Botões -->
            <div class="flex gap-3 mt-4">
                <button type="button" 
                        onclick="closeDeleteModal()"
                        class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400">
                    Cancelar
                </button>
                <form id="deleteForm" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        Excluir
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação de Exclusão Múltipla -->
<div id="deleteMultipleModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
        <div class="mt-3">
            <!-- Ícone -->
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="bi bi-exclamation-triangle text-red-600 text-2xl"></i>
            </div>
            <!-- Título -->
            <h3 class="text-lg font-medium text-gray-900 text-center mt-4">
                Confirmar Exclusão Múltipla
            </h3>
            <!-- Mensagem -->
            <div class="mt-2 px-4 py-3">
                <p class="text-sm text-gray-600 text-center">
                    Tem certeza que deseja excluir <span id="deleteMultipleCount" class="font-bold text-red-600">0</span> fornecedor(es)?
                </p>
                <p class="text-xs text-gray-500 text-center mt-2">
                    Esta ação não pode ser desfeita.
                </p>
            </div>
            <!-- Botões -->
            <div class="flex gap-3 mt-4">
                <button type="button" 
                        onclick="closeDeleteMultipleModal()"
                        class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400">
                    Cancelar
                </button>
                <button type="button"
                        onclick="deleteSelected()"
                        class="flex-1 px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                    Excluir Todos
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
console.log('Script de fornecedores carregado!');

// Função para selecionar/desselecionar todos os checkboxes
function toggleSelectAll(checkbox) {
    const checkboxes = document.querySelectorAll('.supplier-checkbox');
    console.log('Total de checkboxes encontrados:', checkboxes.length);
    checkboxes.forEach(cb => {
        cb.checked = checkbox.checked;
    });
    updateSelectedCount();
}

// Atualizar contador de selecionados
function updateSelectedCount() {
    const checkboxes = document.querySelectorAll('.supplier-checkbox:checked');
    const count = checkboxes.length;
    const deleteBtn = document.getElementById('deleteSelectedBtn');
    const countSpan = document.getElementById('selectedCount');
    
    countSpan.textContent = count;
    
    if (count > 0) {
        deleteBtn.classList.remove('hidden');
    } else {
        deleteBtn.classList.add('hidden');
    }
    
    // Atualizar estado do checkbox "Selecionar Todos"
    const allCheckboxes = document.querySelectorAll('.supplier-checkbox');
    const selectAllCheckbox = document.getElementById('selectAll');
    selectAllCheckbox.checked = allCheckboxes.length > 0 && count === allCheckboxes.length;
}

// Confirmar exclusão individual
function confirmDelete(supplierId, supplierName) {
    const modal = document.getElementById('deleteModal');
    const form = document.getElementById('deleteForm');
    const message = document.getElementById('deleteMessage');
    
    message.innerHTML = `Tem certeza que deseja excluir o fornecedor <strong>${supplierName}</strong>?`;
    form.action = `/admin/fornecedores/${supplierId}`;
    
    modal.classList.remove('hidden');
}

// Fechar modal de exclusão individual
function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Confirmar exclusão múltipla
function confirmDeleteSelected() {
    const checkboxes = document.querySelectorAll('.supplier-checkbox:checked');
    const count = checkboxes.length;
    
    if (count === 0) {
        alert('Selecione pelo menos um fornecedor para excluir.');
        return;
    }
    
    const modal = document.getElementById('deleteMultipleModal');
    const countSpan = document.getElementById('deleteMultipleCount');
    
    countSpan.textContent = count;
    modal.classList.remove('hidden');
}

// Fechar modal de exclusão múltipla
function closeDeleteMultipleModal() {
    document.getElementById('deleteMultipleModal').classList.add('hidden');
}

// Executar exclusão múltipla
function deleteSelected() {
    const checkboxes = document.querySelectorAll('.supplier-checkbox:checked');
    const ids = Array.from(checkboxes).map(cb => cb.value);
    
    if (ids.length === 0) {
        return;
    }
    
    // Criar formulário para enviar os IDs
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route('admin.suppliers.bulk-delete') }}';
    
    // Token CSRF
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = '{{ csrf_token() }}';
    form.appendChild(csrfInput);
    
    // Adicionar IDs
    ids.forEach(id => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'ids[]';
        input.value = id;
        form.appendChild(input);
    });
    
    document.body.appendChild(form);
    form.submit();
}

// Fechar modais ao clicar fora
window.onclick = function(event) {
    const deleteModal = document.getElementById('deleteModal');
    const deleteMultipleModal = document.getElementById('deleteMultipleModal');
    
    if (event.target === deleteModal) {
        closeDeleteModal();
    }
    if (event.target === deleteMultipleModal) {
        closeDeleteMultipleModal();
    }
}

// Fechar modais com tecla ESC
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeDeleteModal();
        closeDeleteMultipleModal();
    }
});

// Verificar elementos ao carregar a página
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== DEBUG FORNECEDORES ===');
    console.log('Checkbox "Selecionar Todos":', document.getElementById('selectAll'));
    console.log('Checkboxes individuais:', document.querySelectorAll('.supplier-checkbox').length);
    console.log('Botão de exclusão múltipla:', document.getElementById('deleteSelectedBtn'));
    console.log('========================');
});
</script>
@endpush
@endsection

