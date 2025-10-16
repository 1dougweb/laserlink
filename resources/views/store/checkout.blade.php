@extends('layouts.checkout')

@section('title', 'Finalizar Pedido')

@section('progress')
<!-- Progress Steps -->
<div class="bg-white border-b">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex items-center justify-center space-x-4">
            <div class="flex items-center">
                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-primary text-white font-semibold">
                    <i class="bi bi-cart-check"></i>
                </div>
                <span class="ml-2 text-sm font-medium text-gray-900">Carrinho</span>
            </div>
            <div class="flex-1 h-0.5 bg-primary max-w-[100px]"></div>
            <div class="flex items-center">
                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-primary text-white font-semibold">
                    2
                </div>
                <span class="ml-2 text-sm font-medium text-gray-900">Checkout</span>
            </div>
            <div class="flex-1 h-0.5 bg-gray-300 max-w-[100px]"></div>
            <div class="flex items-center">
                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-300 text-gray-600 font-semibold">
                    3
                </div>
                <span class="ml-2 text-sm font-medium text-gray-500">Confirmação</span>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div x-data="checkoutApp()" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Formulário de Checkout -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-6">Finalizar Pedido</h1>
                
                <!-- Dados Pessoais -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Dados para Contato</h3>
                        <p class="text-sm text-gray-600 mt-1">Informe seus dados para criação da conta e contato</p>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-900 mb-2">Nome <span class="text-red-500">*</span></label>
                                <input type="text" 
                                       id="first_name" 
                                       x-model="form.first_name" 
                                       required
                                       placeholder="Seu primeiro nome"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500/30 outline-none focus:border-red-500 shadow-sm">
                            </div>
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-900 mb-2">Sobrenome <span class="text-red-500">*</span></label>
                                <input type="text" 
                                       id="last_name" 
                                       x-model="form.last_name" 
                                       required
                                       placeholder="Seu sobrenome"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500/30 outline-none focus:border-red-500 shadow-sm">
                            </div>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-900 mb-2">E-mail <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="bi bi-envelope text-gray-500"></i>
                                </div>
                                <input type="email" 
                                       id="email" 
                                       x-model="form.email" 
                                       required
                                       placeholder="seu@email.com"
                                       class="w-full pl-10 px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500/30 outline-none focus:border-red-500 shadow-sm">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="bi bi-info-circle mr-1"></i>
                                Você receberá os dados de acesso à plataforma neste e-mail
                            </p>
                        </div>

                        <div>
                            <label for="whatsapp" class="block text-sm font-medium text-gray-900 mb-2">WhatsApp <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="bi bi-whatsapp text-gray-500"></i>
                                </div>
                                <input type="tel" 
                                       id="whatsapp" 
                                       x-model="form.whatsapp" 
                                       required
                                       x-mask="(99) 99999-9999"
                                       placeholder="(11) 99999-9999"
                                       class="w-full pl-10 px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500/30 outline-none focus:border-red-500 shadow-sm">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="bi bi-info-circle mr-1"></i>
                                Você receberá atualizações sobre seu pedido via WhatsApp
                            </p>
                        </div>

                        <!-- Informações sobre a conta -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <i class="bi bi-info-circle-fill text-blue-600 text-lg mr-3 mt-0.5"></i>
                                <div>
                                    <h4 class="font-semibold text-blue-900 mb-1">Sua conta será criada automaticamente</h4>
                                    <p class="text-sm text-blue-800">
                                        Os dados de acesso à sua conta serão enviados para o seu e-mail após a finalização do pedido.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>

                <!-- Informações do Pedido -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 mt-6">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Informações do Pedido</h3>
                        <p class="text-sm text-gray-600 mt-1">Entenda como funciona o processo de compra</p>
                    </div>
                    
                    <div class="p-6">
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <i class="bi bi-whatsapp text-green-600 text-xl mr-3 mt-0.5"></i>
                                <div>
                                    <h4 class="font-semibold text-green-900 mb-2">Como funciona o pedido?</h4>
                                    <ul class="text-green-800 space-y-2 text-sm">
                                        <li class="flex items-start">
                                            <i class="bi bi-check2 text-green-600 mr-2 mt-0.5"></i>
                                            <span>Criaremos uma conta para você automaticamente</span>
                                        </li>
                                        <li class="flex items-start">
                                            <i class="bi bi-check2 text-green-600 mr-2 mt-0.5"></i>
                                            <span>Enviaremos os detalhes do pedido por WhatsApp</span>
                                        </li>
                                        <li class="flex items-start">
                                            <i class="bi bi-check2 text-green-600 mr-2 mt-0.5"></i>
                                            <span>Você receberá um email de confirmação</span>
                                        </li>
                                        <li class="flex items-start">
                                            <i class="bi bi-check2 text-green-600 mr-2 mt-0.5"></i>
                                            <span>Combinaremos o pagamento e entrega pelo WhatsApp</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Observações -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 mt-6">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Observações</h3>
                        <p class="text-sm text-gray-600 mt-1">Adicione informações extras sobre seu pedido</p>
                    </div>
                    
                    <div class="p-6">
                        <textarea id="notes" 
                                 x-model="form.notes" 
                                 rows="3" 
                                 class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500/30 outline-none focus:border-red-500 shadow-sm"
                                 placeholder="Alguma observação especial sobre seu pedido?"></textarea>
                    </div>
                </div>
            </div>

        <!-- Resumo do Pedido -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-lg p-6 sticky top-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Resumo do Pedido</h2>
                
                <!-- Itens do Carrinho -->
                <div class="space-y-4 mb-6" x-show="cartItems.length > 0">
                    <template x-for="item in cartItems" :key="item.id">
                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="w-12 h-12 relative overflow-hidden rounded-md">
                                <img :src="item.image || '{{ url('images/general/callback-image.svg') }}'" 
                                     :alt="item.name"
                                     class="w-full h-full object-cover bg-white"
                                     onerror="this.src='{{ url('images/general/callback-image.svg') }}'; this.classList.add('object-contain', 'bg-gray-100');">
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-medium text-gray-900 truncate" x-text="item.name"></h3>
                                <p class="text-sm text-gray-500" x-text="'Qtd: ' + item.quantity"></p>
                                <p class="text-sm font-bold text-primary" x-text="formatPrice(item.price * item.quantity)"></p>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Totais -->
                <div class="border-t pt-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600">Subtotal:</span>
                        <span class="font-medium" x-text="formatPrice(subtotal)"></span>
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600">Frete:</span>
                        <span class="font-medium" x-text="formatPrice(shipping)"></span>
                    </div>
                    <div class="flex justify-between items-center mb-4 border-t pt-2">
                        <span class="text-lg font-bold text-gray-900">Total:</span>
                        <span class="text-lg font-bold text-primary" x-text="formatPrice(total)"></span>
                    </div>
                </div>

                <!-- Botão Finalizar -->
                <button @click="processCheckout()" 
                        :disabled="!isFormValid || processing"
                        class="w-full bg-primary text-white py-4 px-6 rounded-lg font-semibold hover:bg-primary-dark transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-lock mr-2"></i>
                    <span x-text="processing ? 'Processando...' : 'Finalizar Pedido'"></span>
                </button>

                <!-- Carrinho Vazio -->
                <div x-show="cartItems.length === 0" class="text-center py-8">
                    <i class="fas fa-shopping-cart text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">Seu carrinho está vazio</p>
                    <a href="{{ route('store.products') }}" 
                       class="mt-4 inline-block px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
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
function checkoutApp() {
    return {
        cartItems: [],
        form: {
            first_name: '',
            last_name: '',
            email: '',
            whatsapp: '',
            notes: ''
        },
        shipping: 0,
        processing: false,

        init() {
            this.loadCart();
            this.calculateShipping();
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

        get subtotal() {
            return this.cartItems.reduce((total, item) => total + (item.price * item.quantity), 0);
        },

        get total() {
            return this.subtotal + this.shipping;
        },

        get isFormValid() {
            return this.cartItems.length > 0 && 
                   this.form.first_name && 
                   this.form.last_name && 
                   this.form.email &&
                   this.form.whatsapp;
        },

        formatPrice(price) {
            return new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            }).format(price);
        },

        // Removed address-related functions

        calculateShipping() {
            // Valor fixo temporário enquanto não temos dados de endereço
            this.shipping = 0;
        },

        async processCheckout() {
            if (!this.isFormValid || this.processing) return;

            this.processing = true;

            try {
                // Verificar se o token CSRF existe
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) {
                    throw new Error('Token CSRF não encontrado. Por favor, recarregue a página.');
                }

                const orderData = {
                    items: this.cartItems,
                    customer: {
                        name: `${this.form.first_name} ${this.form.last_name}`,
                        email: this.form.email,
                        phone: this.form.whatsapp,
                        street: 'Endereço não informado',
                        number: '0',
                        neighborhood: 'Não informado',
                        city: 'Não informado',
                        state: 'SP',
                        cep: '00000-000',
                        complement: '',
                        notes: this.form.notes
                    },
                    subtotal: this.subtotal,
                    shipping: this.shipping,
                    total: this.total
                };

                // Enviando pedido

                const response = await fetch('{{ route("store.checkout.process") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(orderData),
                    credentials: 'same-origin'
                });

                // Resposta HTTP

                // Verificar se a resposta é JSON
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    const text = await response.text();
                    // Resposta não é JSON
                    throw new Error('Erro no servidor. Por favor, tente novamente.');
                }

                const result = await response.json();
                // Resultado

                if (response.ok && result.success) {
                    // Limpar carrinho
                    localStorage.removeItem('cart');
                    
                    // Redirecionar para página de sucesso com ID do pedido
                    window.location.href = `{{ route("store.checkout.success") }}?order=${result.order_id}`;
                } else {
                    throw new Error(result.message || 'Erro ao processar pedido');
                }

            } catch (error) {
                // Erro ao processar pedido
                
                // Mensagem mais amigável
                let errorMessage = 'Erro ao processar pedido. ';
                if (error.message.includes('CSRF')) {
                    errorMessage += 'Por favor, recarregue a página e tente novamente.';
                } else if (error.message.includes('servidor')) {
                    errorMessage += 'Tente novamente em alguns instantes.';
                } else {
                    errorMessage += error.message;
                }
                
                alert(errorMessage);
            } finally {
                this.processing = false;
            }
        }
    }
}
</script>
@endpush