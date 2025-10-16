@extends('layouts.store')

@section('title', 'Carrinho de Compras')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('store.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary">
                    <i class="bi bi-home w-4 h-4 mr-2"></i>
                    Início
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="bi bi-chevron-right text-gray-400 mx-1"></i>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Carrinho</span>
                </div>
            </li>
        </ol>
    </nav>

    <div x-data="cartApp()" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Itens do Carrinho -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-6">Carrinho de Compras</h1>
                
                <!-- Carrinho Vazio -->
                <div x-show="cartItems.length === 0" class="text-center py-12">
                    <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-6"></i>
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Seu carrinho está vazio</h2>
                    <p class="text-gray-600 mb-6">Adicione produtos ao carrinho para continuar</p>
                    <a href="{{ route('store.products') }}" 
                       class="inline-flex items-center px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                        <i class="fas fa-shopping-bag mr-2"></i>
                        Continuar Comprando
                    </a>
                        </div>

                <!-- Itens do Carrinho -->
                <div x-show="cartItems.length > 0" class="space-y-4">
                    <template x-for="item in cartItems" :key="item.id">
                        <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg">
                            <!-- Imagem do Produto -->
                            <div class="w-20 h-20 relative overflow-hidden rounded-lg">
                                <img :src="item.image || '{{ url('images/general/callback-image.svg') }}'" 
                                     :alt="item.name"
                                     class="w-full h-full object-cover bg-white"
                                     onerror="this.src='{{ url('images/general/callback-image.svg') }}'; this.classList.add('object-contain', 'bg-gray-100');">
                            </div>

                            <!-- Informações do Produto -->
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-medium text-gray-900" x-text="item.product_name || item.name"></h3>
                                <p x-show="item.description" class="text-sm text-gray-600" x-text="item.description"></p>
                                
                                <!-- Customizações -->
                                <div x-show="item.customization && Object.keys(item.customization).length > 0" class="mt-2">
                                    <p class="text-xs text-gray-500 bg-gray-50 px-2 py-1 rounded">
                                        <span x-text="formatCustomization(item.customization)"></span>
                                    </p>
                                </div>
                                
                                <!-- Preço -->
                                <div class="mt-2">
                                    <p class="text-lg font-bold text-primary" x-text="formatPrice(item.unit_price || item.price)"></p>
                                    <div x-show="item.extra_cost > 0" class="text-xs text-gray-500">
                                        Base: <span x-text="formatPrice(item.base_price)"></span> + 
                                        Extras: <span x-text="formatPrice(item.extra_cost)"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Controles de Quantidade -->
                            <div class="flex items-center space-x-3">
                                <button @click="updateQuantity(item.id, item.quantity - 1)" 
                                        :disabled="item.quantity <= 1"
                                        class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <i class="fas fa-minus text-sm"></i>
                                </button>
                                <span class="w-8 text-center font-medium" x-text="item.quantity"></span>
                                <button @click="updateQuantity(item.id, item.quantity + 1)" 
                                        class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-50">
                                    <i class="fas fa-plus text-sm"></i>
                                </button>
                            </div>

                            <!-- Subtotal -->
                            <div class="text-right">
                                <p class="text-lg font-bold text-gray-900" x-text="formatPrice(item.total_price || (item.unit_price || item.price) * item.quantity)"></p>
                            </div>

                            <!-- Botão Remover -->
                            <button @click="removeItem(item.id)" 
                                    class="text-red-600 hover:text-red-700 p-2">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Resumo do Pedido -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-lg p-6 sticky top-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Resumo do Pedido</h2>
                
                <!-- Totais -->
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Subtotal:</span>
                        <span class="font-medium" x-text="formatPrice(subtotal)"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Frete:</span>
                        <span class="font-medium text-green-600">Grátis</span>
                    </div>
                    <div class="flex justify-between items-center border-t pt-3">
                        <span class="text-lg font-bold text-gray-900">Total:</span>
                        <span class="text-lg font-bold text-primary" x-text="formatPrice(subtotal)"></span>
                    </div>
                </div>

                <!-- Botões de Ação -->
                <div x-show="cartItems.length > 0" class="space-y-3">
                    <a href="{{ route('store.checkout') }}" 
                       class="w-full bg-primary text-white py-3 px-6 rounded-lg font-semibold hover:bg-primary-dark transition-colors text-center block">
                        <i class="fas fa-lock mr-2"></i>
                        Finalizar Pedido
                    </a>
                    <a href="{{ route('store.products') }}" 
                       class="w-full border border-gray-300 text-gray-700 py-3 px-6 rounded-lg font-semibold hover:bg-gray-50 transition-colors text-center block">
                        <i class="fas fa-shopping-bag mr-2"></i>
                        Continuar Comprando
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function cartApp() {
    return {
        cartItems: [],
        subtotal: 0,

        init() {
            this.loadCart();
            this.calculateSubtotal();
            
            // Escutar atualizações do carrinho
            window.addEventListener('cartUpdated', () => {
                this.loadCart();
                this.calculateSubtotal();
            });
        },

        loadCart() {
            try {
                const cart = JSON.parse(localStorage.getItem('cart') || '[]');
                this.cartItems = cart;
            } catch (error) {
                // Erro ao carregar carrinho
                this.cartItems = [];
            }
        },

        updateQuantity(itemId, newQuantity) {
            const itemIndex = this.cartItems.findIndex(item => item.id === itemId);
            if (itemIndex !== -1) {
                if (newQuantity <= 0) {
                    this.removeItem(itemId);
                } else {
                    this.cartItems[itemIndex].quantity = newQuantity;
                    this.cartItems[itemIndex].total_price = this.cartItems[itemIndex].unit_price * newQuantity;
                    this.saveCart();
                    this.calculateSubtotal();
                }
            }
        },

        removeItem(itemId) {
            this.cartItems = this.cartItems.filter(item => item.id !== itemId);
            this.saveCart();
            this.calculateSubtotal();
        },

        clearCart() {
            this.cartItems = [];
            this.saveCart();
            this.calculateSubtotal();
        },
        
        saveCart() {
            localStorage.setItem('cart', JSON.stringify(this.cartItems));
            window.dispatchEvent(new Event('cartUpdated'));
        },

        calculateSubtotal() {
            this.subtotal = this.cartItems.reduce((total, item) => total + (item.total_price || item.price * item.quantity), 0);
        },

        formatPrice(price) {
            return new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            }).format(price);
        },
        
        formatCustomization(customization) {
            if (!customization || Object.keys(customization).length === 0) {
                return '';
            }
            
            const parts = [];
            for (const [key, value] of Object.entries(customization)) {
                if (Array.isArray(value)) {
                    if (value.length > 0) {
                        parts.push(`${key}: ${value.join(', ')}`);
                    }
                } else if (value) {
                    parts.push(`${key}: ${value}`);
                }
            }
            
            return parts.join(' | ');
        }
    }
}
</script>
@endpush