@extends('admin.layout')

@section('title', 'Nova Movimentação de Estoque')
@section('page-title', 'Nova Movimentação de Estoque')

@section('content')
<div class="space-y-6" x-data="stockForm()">
    <div>
        <a href="{{ route('admin.stock.index') }}" class="text-sm text-primary hover:text-red-700">
            <i class="bi bi-arrow-left mr-1"></i> Voltar para Estoque
        </a>
    </div>

    <form action="{{ route('admin.stock.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Dados da Movimentação</h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="product_id" class="block text-sm font-medium text-gray-700 mb-1">Produto *</label>
                        <select name="product_id" id="product_id" 
                                x-model="product_id"
                                @change="updateProductInfo()"
                                required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                            <option value="">Selecione um produto</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" 
                                        data-stock="{{ $product->stock_quantity }}"
                                        data-sku="{{ $product->sku }}"
                                        {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }} (SKU: {{ $product->sku ?? 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Movimentação *</label>
                        <select name="type" id="type" 
                                x-model="type"
                                required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                            <option value="">Selecione o tipo</option>
                            <option value="entrada">Entrada (Compra/Reposição)</option>
                            <option value="saida">Saída (Uso/Perda)</option>
                            <option value="ajuste">Ajuste de Inventário</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Info do Produto Selecionado -->
                <div x-show="product_id" class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">SKU:</span>
                            <span class="font-medium text-gray-900 ml-1" x-text="currentSKU"></span>
                        </div>
                        <div>
                            <span class="text-gray-600">Estoque Atual:</span>
                            <span class="font-bold ml-1" 
                                  :class="currentStock <= 0 ? 'text-red-600' : (currentStock <= 10 ? 'text-yellow-600' : 'text-green-600')"
                                  x-text="currentStock"></span>
                        </div>
                        <div x-show="type === 'entrada'">
                            <span class="text-gray-600">Após Entrada:</span>
                            <span class="font-bold text-green-600 ml-1" x-text="calculatedStock"></span>
                        </div>
                        <div x-show="type === 'saida'">
                            <span class="text-gray-600">Após Saída:</span>
                            <span class="font-bold ml-1" 
                                  :class="calculatedStock < 0 ? 'text-red-600' : 'text-orange-600'"
                                  x-text="calculatedStock"></span>
                        </div>
                        <div x-show="type === 'ajuste'">
                            <span class="text-gray-600">Novo Estoque:</span>
                            <span class="font-bold text-blue-600 ml-1" x-text="quantity || 0"></span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">
                            <span x-show="type === 'ajuste'">Novo Estoque *</span>
                            <span x-show="type !== 'ajuste'">Quantidade *</span>
                        </label>
                        <input type="number" name="quantity" id="quantity" 
                               x-model="quantity"
                               @input="calculateStock()"
                               min="1" 
                               required
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                        @error('quantity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500" x-show="type === 'ajuste'">
                            Digite o novo valor total do estoque
                        </p>
                    </div>

                    <div x-show="type === 'entrada'">
                        <label for="unit_cost" class="block text-sm font-medium text-gray-700 mb-1">Custo Unitário</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">R$</span>
                            <input type="number" name="unit_cost" id="unit_cost" 
                                   step="0.01" 
                                   min="0"
                                   class="w-full pl-10 rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                                   placeholder="0,00">
                        </div>
                        @error('unit_cost')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="reference" class="block text-sm font-medium text-gray-700 mb-1">Referência</label>
                    <input type="text" name="reference" id="reference"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                           placeholder="Nº Nota Fiscal, Pedido, etc.">
                    @error('reference')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Observações</label>
                    <textarea name="notes" id="notes" rows="3"
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                              placeholder="Informações adicionais sobre a movimentação..."></textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Botões -->
        <div class="flex justify-end gap-4">
            <a href="{{ route('admin.stock.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                Cancelar
            </a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-colors">
                Confirmar Movimentação
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function stockForm() {
        return {
            product_id: '{{ request('product_id', '') }}',
            type: '',
            quantity: '',
            currentStock: 0,
            currentSKU: '-',
            calculatedStock: 0,

            init() {
                // Se já tem produto pré-selecionado, atualizar informações
                if (this.product_id) {
                    this.$nextTick(() => {
                        this.updateProductInfo();
                    });
                }
            },

            updateProductInfo() {
                if (!this.product_id) {
                    this.currentStock = 0;
                    this.currentSKU = '-';
                    return;
                }

                const select = document.getElementById('product_id');
                const option = select.options[select.selectedIndex];
                this.currentStock = parseInt(option.dataset.stock) || 0;
                this.currentSKU = option.dataset.sku || '-';
                this.calculateStock();
            },

            calculateStock() {
                const qty = parseInt(this.quantity) || 0;
                
                if (this.type === 'entrada') {
                    this.calculatedStock = this.currentStock + qty;
                } else if (this.type === 'saida') {
                    this.calculatedStock = this.currentStock - qty;
                } else if (this.type === 'ajuste') {
                    this.calculatedStock = qty;
                }
            }
        }
    }
</script>
@endsection

