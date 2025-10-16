@extends('admin.layout')

@section('title', 'Controle de Estoque')
@section('page-title', 'Controle de Estoque')

@section('content')
<div class="space-y-6" x-data="stockPage()">
    <!-- Header com Ações -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Controle de Estoque</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.stock.movements') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                <i class="bi bi-clock-history mr-1"></i> Histórico
            </a>
            <a href="{{ route('admin.stock.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                <i class="bi bi-plus mr-2"></i>Nova Movimentação
            </a>
        </div>
    </div>

    <!-- Cards de Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="bi bi-box-seam text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total de Produtos</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($totalProducts, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <i class="bi bi-exclamation-triangle text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Sem Estoque</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($outOfStockProducts, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="bi bi-dash-circle text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Estoque Baixo</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($lowStockProducts->count(), 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="bi bi-currency-dollar text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Valor Total</p>
                    <p class="text-2xl font-semibold text-gray-900">R$ {{ number_format($totalStockValue ?? 0, 2, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Produtos com Estoque Baixo -->
    @if($lowStockProducts->count() > 0)
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="bi bi-exclamation-triangle text-yellow-500 mr-2"></i>
                Produtos com Estoque Baixo
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoria</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estoque Atual</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estoque Mín.</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($lowStockProducts as $product)
                    <tr class="{{ $product->isOutOfStock() ? 'bg-red-50' : ($product->isLowStock() ? 'bg-yellow-50' : '') }}">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            {{ $product->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $product->sku ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $product->category->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold {{ $product->isOutOfStock() ? 'text-red-600' : ($product->isLowStock() ? 'text-yellow-600' : 'text-gray-900') }}">
                            {{ number_format($product->stock_quantity, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($product->stock_min ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($product->isOutOfStock())
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    <i class="bi bi-x-circle mr-1"></i> Sem Estoque
                                </span>
                            @elseif($product->isLowStock())
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    <i class="bi bi-exclamation-triangle mr-1"></i> Estoque Baixo
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="bi bi-check-circle mr-1"></i> OK
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button @click="openStockModal({{ $product->id }}, '{{ $product->name }}', {{ $product->stock_quantity }}, {{ $product->stock_min ?? 0 }})" 
                                    class="text-green-600 hover:text-green-700 mr-3" 
                                    title="Ajustar estoque">
                                <i class="bi bi-box-arrow-in-down"></i>
                            </button>
                            <a href="{{ route('admin.products.edit', $product) }}" 
                               class="text-primary hover:text-red-700" 
                               title="Editar produto">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="bg-white rounded-lg shadow">
        <div class="p-8 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 mb-4">
                <i class="bi bi-check-circle text-3xl text-green-600"></i>
            </div>
            <h4 class="text-lg font-medium text-gray-900 mb-2">Nenhum produto com estoque baixo</h4>
            <p class="text-gray-500">Todos os produtos com controle de estoque estão em níveis adequados.</p>
        </div>
    </div>
    @endif

    <!-- Todos os Produtos com Estoque -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="bi bi-box-seam text-blue-500 mr-2"></i>
                    Todos os Produtos com Controle de Estoque
                </h3>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <input type="text" 
                               placeholder="Buscar produtos..." 
                               class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                               x-model="searchQuery"
                               @input="filterProducts()">
                        <i class="bi bi-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    <select x-model="statusFilter" @change="filterProducts()" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">Todos os status</option>
                        <option value="ok">Estoque OK</option>
                        <option value="low">Estoque Baixo</option>
                        <option value="out">Sem Estoque</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoria</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estoque Atual</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estoque Mín.</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($allProducts as $product)
                    <tr x-show="isProductVisible({{ $product->id }})" class="product-row {{ $product->isOutOfStock() ? 'bg-red-50' : ($product->isLowStock() ? 'bg-yellow-50' : '') }}">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            {{ $product->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $product->sku ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $product->category->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold {{ $product->isOutOfStock() ? 'text-red-600' : ($product->isLowStock() ? 'text-yellow-600' : 'text-gray-900') }}">
                            <span class="stock-quantity" data-product-id="{{ $product->id }}">{{ number_format($product->stock_quantity, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($product->stock_min ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($product->isOutOfStock())
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    <i class="bi bi-x-circle mr-1"></i> Sem Estoque
                                </span>
                            @elseif($product->isLowStock())
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    <i class="bi bi-exclamation-triangle mr-1"></i> Estoque Baixo
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="bi bi-check-circle mr-1"></i> OK
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button @click="openStockModal({{ $product->id }}, '{{ $product->name }}', {{ $product->stock_quantity }}, {{ $product->stock_min ?? 0 }})" 
                                    class="text-green-600 hover:text-green-700 mr-3" 
                                    title="Ajustar estoque">
                                <i class="bi bi-box-arrow-in-down"></i>
                            </button>
                            <a href="{{ route('admin.products.edit', $product) }}" 
                               class="text-primary hover:text-red-700" 
                               title="Editar produto">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Movimentações Recentes -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-medium text-gray-900">Movimentações Recentes</h3>
            <a href="{{ route('admin.stock.movements') }}" class="text-sm text-primary hover:text-red-700">
                Ver todas <i class="bi bi-arrow-right ml-1"></i>
            </a>
        </div>
        @if($recentMovements->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantidade</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuário</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Referência</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($recentMovements as $movement)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $movement->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                {{ $movement->product->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $movement->type_color }}">
                                    {{ $movement->type_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold {{ $movement->quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $movement->quantity > 0 ? '+' : '' }}{{ $movement->quantity }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $movement->user->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $movement->reference ?? '-' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-6">
                <p class="text-gray-500 text-center">Nenhuma movimentação recente</p>
            </div>
        @endif
    </div>

    <!-- Modal de Ações Rápidas de Estoque -->
    <div x-show="showStockModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showStockModal = false"></div>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form @submit.prevent="submitStockAdjustment()">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="bi bi-box-arrow-in-down text-green-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Ajustar Estoque
                                </h3>
                                <div class="mt-4">
                                    <p class="text-sm text-gray-500 mb-4">
                                        Produto: <span class="font-medium" x-text="selectedProduct.name"></span>
                                    </p>
                                    
                                    <div class="grid grid-cols-2 gap-4 mb-4 p-3 bg-gray-50 rounded-lg">
                                        <div>
                                            <span class="text-sm text-gray-600">Estoque Atual:</span>
                                            <span class="block font-bold text-lg" x-text="selectedProduct.currentStock"></span>
                                        </div>
                                        <div>
                                            <span class="text-sm text-gray-600">Estoque Mínimo:</span>
                                            <span class="block font-bold text-lg" x-text="selectedProduct.minStock"></span>
                                        </div>
                                    </div>

                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Tipo de Movimentação
                                            </label>
                                            <select x-model="stockForm.type" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-transparent">
                                                <option value="entrada">Entrada (Adicionar)</option>
                                                <option value="saida">Saída (Remover)</option>
                                                <option value="ajuste">Ajuste (Definir valor)</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Quantidade
                                            </label>
                                            <input type="number" 
                                                   x-model="stockForm.quantity" 
                                                   min="1"
                                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-transparent"
                                                   placeholder="Digite a quantidade">
                                        </div>

                                        <div x-show="stockForm.type === 'entrada'">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Custo Unitário (opcional)
                                            </label>
                                            <input type="number" 
                                                   x-model="stockForm.unitCost" 
                                                   step="0.01"
                                                   min="0"
                                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-transparent"
                                                   placeholder="0,00">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Referência (opcional)
                                            </label>
                                            <input type="text" 
                                                   x-model="stockForm.reference" 
                                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-transparent"
                                                   placeholder="Ex: Compra #123, Inventário, etc.">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Observações (opcional)
                                            </label>
                                            <textarea x-model="stockForm.notes" 
                                                      rows="3"
                                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-transparent"
                                                      placeholder="Informações adicionais..."></textarea>
                                        </div>

                                        <!-- Preview do Resultado -->
                                        <div x-show="stockForm.quantity" class="p-3 bg-blue-50 rounded-lg border border-blue-200">
                                            <span class="text-sm text-gray-600">Estoque após movimentação:</span>
                                            <span class="block font-bold text-lg" x-text="calculateNewStock()"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" 
                                :disabled="!stockForm.quantity || stockForm.quantity <= 0 || isSubmitting"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                            <span x-show="!isSubmitting">Confirmar</span>
                            <span x-show="isSubmitting" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Processando...
                            </span>
                        </button>
                        <button type="button" 
                                @click="showStockModal = false"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('stockPage', () => ({
        // Modal state
        showStockModal: false,
        selectedProduct: {
            id: null,
            name: '',
            currentStock: 0,
            minStock: 0
        },
        
        // Form data
        stockForm: {
            type: 'entrada',
            quantity: '',
            unitCost: '',
            reference: '',
            notes: ''
        },
        
        isSubmitting: false,
        
        // Filter state
        searchQuery: '',
        statusFilter: '',
        visibleProducts: new Set(),
        
        init() {
            this.initializeVisibleProducts();
        },
        
        initializeVisibleProducts() {
            // Mark all products as visible initially
            document.querySelectorAll('.product-row').forEach(row => {
                const productId = row.querySelector('[data-product-id]')?.getAttribute('data-product-id');
                if (productId) {
                    this.visibleProducts.add(parseInt(productId));
                }
            });
        },
        
        openStockModal(productId, productName, currentStock, minStock) {
            this.selectedProduct = {
                id: productId,
                name: productName,
                currentStock: currentStock,
                minStock: minStock
            };
            
            this.stockForm = {
                type: 'entrada',
                quantity: '',
                unitCost: '',
                reference: '',
                notes: ''
            };
            
            this.showStockModal = true;
        },
        
        calculateNewStock() {
            const quantity = parseInt(this.stockForm.quantity) || 0;
            const current = this.selectedProduct.currentStock;
            
            switch(this.stockForm.type) {
                case 'entrada':
                    return current + quantity;
                case 'saida':
                    return Math.max(0, current - quantity);
                case 'ajuste':
                    return quantity;
                default:
                    return current;
            }
        },
        
        async submitStockAdjustment() {
            if (!this.stockForm.quantity || this.stockForm.quantity <= 0) {
                alert('Por favor, informe uma quantidade válida.');
                return;
            }
            
            this.isSubmitting = true;
            
            try {
                const formData = new FormData();
                formData.append('product_id', this.selectedProduct.id);
                formData.append('type', this.stockForm.type);
                formData.append('quantity', this.stockForm.quantity);
                formData.append('unit_cost', this.stockForm.unitCost || '');
                formData.append('reference', this.stockForm.reference || '');
                formData.append('notes', this.stockForm.notes || '');
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                
                const response = await fetch('{{ route("admin.stock.store") }}', {
                    method: 'POST',
                    body: formData
                });
                
                if (response.ok) {
                    // Update the stock quantity in the table
                    const stockElement = document.querySelector(`[data-product-id="${this.selectedProduct.id}"]`);
                    if (stockElement) {
                        const newStock = this.calculateNewStock();
                        stockElement.textContent = new Intl.NumberFormat('pt-BR').format(newStock);
                        
                        // Update the selected product's current stock
                        this.selectedProduct.currentStock = newStock;
                    }
                    
                    this.showStockModal = false;
                    
                    // Show success message
                    this.showNotification('Estoque atualizado com sucesso!', 'success');
                    
                    // Reload page to update all data
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    const errorData = await response.json();
                    this.showNotification(errorData.message || 'Erro ao atualizar estoque.', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                this.showNotification('Erro ao atualizar estoque.', 'error');
            } finally {
                this.isSubmitting = false;
            }
        },
        
        isProductVisible(productId) {
            return this.visibleProducts.has(productId);
        },
        
        filterProducts() {
            const query = this.searchQuery.toLowerCase();
            const status = this.statusFilter;
            
            document.querySelectorAll('.product-row').forEach(row => {
                const productId = parseInt(row.querySelector('[data-product-id]')?.getAttribute('data-product-id'));
                const productName = row.querySelector('td:first-child').textContent.toLowerCase();
                const statusElement = row.querySelector('td:nth-child(6) span');
                const statusText = statusElement ? statusElement.textContent.toLowerCase() : '';
                
                let matchesSearch = !query || productName.includes(query);
                let matchesStatus = !status || (
                    (status === 'ok' && statusText.includes('ok')) ||
                    (status === 'low' && statusText.includes('baixo')) ||
                    (status === 'out' && statusText.includes('sem'))
                );
                
                if (matchesSearch && matchesStatus) {
                    this.visibleProducts.add(productId);
                } else {
                    this.visibleProducts.delete(productId);
                }
            });
        },
        
        showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg ${
                type === 'success' ? 'bg-green-500 text-white' :
                type === 'error' ? 'bg-red-500 text-white' :
                'bg-blue-500 text-white'
            }`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            // Remove after 3 seconds
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
    }));
});
</script>
@endsection

