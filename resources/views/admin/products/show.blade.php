@extends('admin.layout')

@section('title', 'Visualizar Produto - Laser Link')
@section('page-title', 'Visualizar Produto')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.products') }}" 
               class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                <i class="bi bi-arrow-left mr-2"></i>Voltar
            </a>
            <h1 class="text-2xl font-bold text-gray-900">{{ $product->name }}</h1>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('store.product', $product->slug) }}" 
               target="_blank"
               class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                <i class="bi bi-eye mr-2"></i>Visualizar na Loja
            </a>
            <a href="{{ route('admin.products.edit', $product) }}" 
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                <i class="bi bi-pencil mr-2"></i>Editar
            </a>
            <button type="button" 
                    onclick="openDeleteModal('{{ $product->id }}', '{{ $product->name }}')"
                    class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                <i class="bi bi-trash mr-2"></i>Excluir
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="bi bi-info-circle mr-2"></i>Informações Básicas
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nome</label>
                        <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded">{{ $product->name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Categoria</label>
                        <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded">{{ $product->category->name ?? 'N/A' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Produto</label>
                        <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded">{{ $product->productType->name ?? 'N/A' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        @if($product->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="bi bi-check-circle mr-1"></i>Ativo
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="bi bi-x-circle mr-1"></i>Inativo
                            </span>
                        @endif
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Descrição</label>
                        <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded">
                            {{ $product->description ?? 'Nenhuma descrição fornecida.' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Material Information -->
            @if($product->material)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="bi bi-layers mr-2"></i>Informações do Material
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Material</label>
                        <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded">{{ $product->material->name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Densidade</label>
                        <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded">{{ $product->material->density_g_cm3 }} g/cm³</p>
                    </div>
                    
                    @if($product->width && $product->height)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Dimensões</label>
                        <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded">{{ $product->width }}cm x {{ $product->height }}cm</p>
                    </div>
                    @endif
                    
                    @if($product->thickness)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Espessura</label>
                        <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded">{{ $product->thickness }}{{ str_contains($product->thickness, 'mm') ? '' : 'mm' }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Pricing Information -->
            @if($product->base_price || $product->material)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="bi bi-currency-dollar mr-2"></i>Informações de Preço
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($product->base_price)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Preço Base</label>
                        <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded">R$ {{ number_format($product->base_price, 2, ',', '.') }}</p>
                    </div>
                    @endif
                    
                    @if($product->material)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Peso Estimado</label>
                        <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded">{{ number_format($product->getTotalWeight(), 2, ',', '.') }} kg</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Statistics -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="bi bi-graph-up mr-2"></i>Estatísticas
                </h3>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Criado em</span>
                        <span class="text-sm text-gray-900">{{ $product->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Última atualização</span>
                        <span class="text-sm text-gray-900">{{ $product->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="bi bi-lightning mr-2"></i>Ações Rápidas
                </h3>
                
                <div class="space-y-3">
                    <a href="{{ route('admin.products.edit', $product) }}" 
                       class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center">
                        <i class="bi bi-pencil mr-2"></i>Editar Produto
                    </a>
                    
                    <form action="{{ route('admin.products.delete', $product) }}" 
                          method="POST" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors flex items-center justify-center"
                                onclick="return confirm('Tem certeza que deseja excluir este produto?')">
                            <i class="bi bi-trash mr-2"></i>Excluir Produto
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação de Exclusão -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden">
    <!-- Backdrop com backdrop-filter -->
    <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity duration-300" onclick="closeDeleteModal()"></div>
    
    <!-- Modal -->
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0" 
             id="modalContent">
            <div class="p-6">
                <!-- Ícone de Aviso -->
                <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-red-100 rounded-full">
                    <i class="bi bi-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                
                <!-- Título -->
                <h3 class="text-lg font-medium text-gray-900 text-center mb-2">
                    Confirmar Exclusão
                </h3>
                
                <!-- Mensagem -->
                <p class="text-sm text-gray-600 text-center mb-6">
                    Tem certeza que deseja excluir o produto 
                    <span class="font-medium text-gray-900" id="deleteProductName"></span>?
                    <br><br>
                    <span class="text-red-600 font-medium">Esta ação não pode ser desfeita.</span>
                </p>
                
                <!-- Botões -->
                <div class="flex space-x-3">
                    <button type="button" 
                            onclick="closeDeleteModal()" 
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancelar
                    </button>
                    <form id="deleteForm" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            Excluir
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openDeleteModal(id, name) {
    const modal = document.getElementById('deleteModal');
    const modalContent = document.getElementById('modalContent');
    const deleteForm = document.getElementById('deleteForm');
    const deleteProductName = document.getElementById('deleteProductName');
    
    // Configurar dados do modal
    deleteProductName.textContent = name;
    deleteForm.action = `/admin/produtos/${id}`;
    
    // Mostrar modal com animação
    modal.classList.remove('hidden');
    
    // Forçar reflow para aplicar transform inicial
    modalContent.offsetHeight;
    
    // Aplicar animação de entrada
    modalContent.classList.remove('scale-95', 'opacity-0');
    modalContent.classList.add('scale-100', 'opacity-100');
    
    // Focar no modal para acessibilidade
    modalContent.focus();
    
    // Prevenir scroll do body
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    const modalContent = document.getElementById('modalContent');
    
    // Aplicar animação de saída
    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');
    
    // Esconder modal após animação
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }, 300);
}

// Fechar modal com ESC
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeDeleteModal();
    }
});
</script>
@endsection

