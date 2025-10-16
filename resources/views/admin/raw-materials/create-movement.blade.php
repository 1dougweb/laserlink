@extends('admin.layout')

@section('title', 'Nova Movimentação')
@section('page-title', 'Nova Movimentação')

@section('content')
<div class="space-y-6" x-data="materialStockForm()">
    <form action="{{ route('admin.raw-materials.movements.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-6">Dados da Movimentação</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="raw_material_id" class="block text-sm font-medium text-gray-700 mb-2">Material *</label>
                    <select name="raw_material_id" id="raw_material_id" 
                            x-model="material_id"
                            @change="updateMaterialInfo()"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('raw_material_id') border-red-500 @enderror">
                        <option value="">Selecione um material</option>
                        @foreach($materials as $material)
                            <option value="{{ $material->id }}" 
                                    data-stock="{{ $material->stock_quantity }}"
                                    data-unit="{{ $material->unit_label }}"
                                    data-code="{{ $material->code }}">
                                [{{ $material->code }}] {{ $material->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('raw_material_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Movimentação *</label>
                    <select name="type" id="type" 
                            x-model="type"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('type') border-red-500 @enderror">
                        <option value="">Selecione o tipo</option>
                        <option value="entrada">Entrada (Compra)</option>
                        <option value="saida">Saída (Uso)</option>
                        <option value="producao">Produção (Consumo)</option>
                        <option value="ajuste">Ajuste de Inventário</option>
                    </select>
                    @error('type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Info do Material Selecionado -->
            <div x-show="material_id" class="p-4 bg-blue-50 rounded-lg border border-blue-200 mt-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600">Código:</span>
                        <span class="font-medium text-gray-900 ml-1" x-text="currentCode"></span>
                    </div>
                    <div>
                        <span class="text-gray-600">Estoque Atual:</span>
                        <span class="font-bold ml-1" 
                              :class="currentStock <= 0 ? 'text-red-600' : (currentStock <= 10 ? 'text-yellow-600' : 'text-green-600')">
                            <span x-text="parseFloat(currentStock).toFixed(3)"></span>
                            <span x-text="currentUnit" class="text-xs ml-1"></span>
                        </span>
                    </div>
                    <div x-show="type === 'entrada'">
                        <span class="text-gray-600">Após Entrada:</span>
                        <span class="font-bold text-green-600 ml-1">
                            <span x-text="calculatedStock.toFixed(3)"></span>
                            <span x-text="currentUnit" class="text-xs ml-1"></span>
                        </span>
                    </div>
                    <div x-show="type === 'saida' || type === 'producao'">
                        <span class="text-gray-600">Após Saída:</span>
                        <span class="font-bold ml-1" 
                              :class="calculatedStock < 0 ? 'text-red-600' : 'text-orange-600'">
                            <span x-text="calculatedStock.toFixed(3)"></span>
                            <span x-text="currentUnit" class="text-xs ml-1"></span>
                        </span>
                    </div>
                    <div x-show="type === 'ajuste'">
                        <span class="text-gray-600">Novo Estoque:</span>
                        <span class="font-bold text-blue-600 ml-1">
                            <span x-text="(parseFloat(quantity) || 0).toFixed(3)"></span>
                            <span x-text="currentUnit" class="text-xs ml-1"></span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                        <span x-show="type === 'ajuste'">Novo Estoque Total *</span>
                        <span x-show="type !== 'ajuste'">Quantidade *</span>
                    </label>
                    <input type="number" name="quantity" id="quantity" 
                           x-model="quantity"
                           @input="calculateStock()"
                           step="0.001" 
                           min="0.001" 
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('quantity') border-red-500 @enderror">
                    @error('quantity')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1" x-show="type === 'ajuste'">
                        Digite o novo valor total do estoque
                    </p>
                </div>

                <div x-show="type === 'entrada'">
                    <label for="unit_cost" class="block text-sm font-medium text-gray-700 mb-2">Custo Unitário</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">R$</span>
                        <input type="number" name="unit_cost" id="unit_cost" 
                               step="0.01" 
                               min="0"
                               class="w-full pl-10 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('unit_cost') border-red-500 @enderror"
                               placeholder="0,00">
                    </div>
                    @error('unit_cost')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Deixe em branco para usar o custo cadastrado</p>
                </div>
            </div>

            <div class="mt-6">
                <label for="reference" class="block text-sm font-medium text-gray-700 mb-2">Referência</label>
                <input type="text" name="reference" id="reference"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('reference') border-red-500 @enderror"
                       placeholder="Nº Nota Fiscal, Ordem de Produção, etc.">
                @error('reference')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Observações</label>
                <textarea name="notes" id="notes" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('notes') border-red-500 @enderror"
                          placeholder="Informações adicionais..."></textarea>
                @error('notes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex justify-end gap-4">
            <a href="{{ route('admin.raw-materials.index') }}" 
               class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                Cancelar
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors">
                Confirmar Movimentação
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function materialStockForm() {
        return {
            material_id: '',
            type: '',
            quantity: '',
            currentStock: 0,
            currentUnit: '',
            currentCode: '',
            calculatedStock: 0,

            updateMaterialInfo() {
                if (!this.material_id) {
                    this.currentStock = 0;
                    this.currentUnit = '';
                    this.currentCode = '';
                    return;
                }

                const select = document.getElementById('raw_material_id');
                const option = select.options[select.selectedIndex];
                this.currentStock = parseFloat(option.dataset.stock) || 0;
                this.currentUnit = option.dataset.unit || '';
                this.currentCode = option.dataset.code || '';
                this.calculateStock();
            },

            calculateStock() {
                const qty = parseFloat(this.quantity) || 0;
                
                if (this.type === 'entrada') {
                    this.calculatedStock = this.currentStock + qty;
                } else if (this.type === 'saida' || this.type === 'producao') {
                    this.calculatedStock = this.currentStock - qty;
                } else if (this.type === 'ajuste') {
                    this.calculatedStock = qty;
                }
            }
        }
    }
</script>
@endsection
