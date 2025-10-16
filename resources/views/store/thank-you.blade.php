@extends('layouts.checkout')

@section('title', 'Pedido Realizado com Sucesso')

@section('progress')
<!-- Progress Steps -->
<div class="bg-white border-b">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex items-center justify-center space-x-4">
            <div class="flex items-center">
                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-green-500 text-white font-semibold">
                    <i class="bi bi-check2"></i>
                </div>
                <span class="ml-2 text-sm font-medium text-gray-900">Carrinho</span>
            </div>
            <div class="flex-1 h-0.5 bg-green-500 max-w-[100px]"></div>
            <div class="flex items-center">
                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-green-500 text-white font-semibold">
                    <i class="bi bi-check2"></i>
                </div>
                <span class="ml-2 text-sm font-medium text-gray-900">Checkout</span>
            </div>
            <div class="flex-1 h-0.5 bg-green-500 max-w-[100px]"></div>
            <div class="flex items-center">
                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-green-500 text-white font-semibold">
                    <i class="bi bi-check2"></i>
                </div>
                <span class="ml-2 text-sm font-medium text-gray-900">Confirmação</span>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
        <!-- Success Header -->
        <div class="bg-gradient-to-r from-green-500 to-green-600 px-8 py-12 text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-white shadow-lg mb-4">
                <i class="bi bi-check-circle-fill text-green-500 text-5xl"></i>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">Pedido Realizado com Sucesso!</h1>
            <p class="text-green-50 text-lg">Obrigado pela sua confiança</p>
        </div>

        <!-- Order Details -->
        <div class="px-8 py-8">
            @if(session('order_number'))
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-600 font-medium">Número do Pedido:</span>
                    <span class="text-2xl font-bold text-primary">{{ session('order_number') }}</span>
                </div>
                @if(session('order_total'))
                <div class="flex items-center justify-between border-t pt-4 mt-4">
                    <span class="text-gray-600 font-medium">Valor Total:</span>
                    <span class="text-xl font-bold text-gray-900">R$ {{ number_format(session('order_total'), 2, ',', '.') }}</span>
                </div>
                @endif
            </div>
            @endif

            <!-- WhatsApp Notification -->
            <div class="bg-green-50 border-2 border-green-200 rounded-lg p-6 mb-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="bi bi-whatsapp text-green-600 text-3xl"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Notificação via WhatsApp</h3>
                        <p class="text-gray-600 mb-4">
                            Enviamos uma notificação com os detalhes do seu pedido para o WhatsApp informado durante o checkout.
                        </p>
                        <div class="flex items-center text-green-600">
                            <i class="bi bi-check-circle-fill mr-2"></i>
                            <span class="font-medium">Notificação enviada automaticamente</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Information Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="bg-blue-50 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="bi bi-envelope-fill text-blue-600 text-2xl mr-3"></i>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-1">Email de Confirmação</h4>
                            <p class="text-sm text-gray-600">Enviamos um email com os detalhes do seu pedido{{ auth()->check() ? '' : ' e suas credenciais de acesso' }}.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-purple-50 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="bi bi-person-circle text-purple-600 text-2xl mr-3"></i>
                        <div>
                            @if(auth()->check())
                                <h4 class="font-semibold text-gray-900 mb-1">Você está conectado!</h4>
                                <p class="text-sm text-gray-600">Acesse "Meus Pedidos" para acompanhar o status.</p>
                            @else
                                <h4 class="font-semibold text-gray-900 mb-1">Conta Criada</h4>
                                <p class="text-sm text-gray-600">Uma conta foi criada para você. Confira seu email para os dados de acesso.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Next Steps -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="bi bi-list-check text-primary mr-2"></i>
                    Próximos Passos
                </h3>
                <ol class="space-y-3">
                    <li class="flex items-start">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-sm font-semibold mr-3 flex-shrink-0">1</span>
                        <p class="text-gray-700">Continue a conversa pelo WhatsApp para confirmar os detalhes do pedido</p>
                    </li>
                    <li class="flex items-start">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-sm font-semibold mr-3 flex-shrink-0">2</span>
                        <p class="text-gray-700">Verifique seu email para acessar sua conta e acompanhar o pedido</p>
                    </li>
                    <li class="flex items-start">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-sm font-semibold mr-3 flex-shrink-0">3</span>
                        <p class="text-gray-700">Nossa equipe entrará em contato para confirmar o pagamento e prazo de entrega</p>
                    </li>
                </ol>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 mt-8">
                @auth
                <a href="{{ route('store.user-orders') }}" 
                   class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-primary text-white font-semibold rounded-lg hover:bg-red-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                    <i class="bi bi-box-seam mr-2"></i>
                    Ver Meus Pedidos
                </a>
                @endauth
                
                <a href="{{ route('store.index') }}" 
                   class="flex-1 inline-flex items-center justify-center px-6 py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-all duration-300">
                    <i class="bi bi-house-door mr-2"></i>
                    Voltar para Loja
                </a>
            </div>
        </div>

        <!-- Support Info -->
        <div class="bg-gray-50 px-8 py-6 border-t">
            <div class="flex items-center justify-center text-center">
                <div>
                    <p class="text-gray-600 mb-2">
                        <i class="bi bi-headset text-primary mr-2"></i>
                        Precisa de ajuda?
                    </p>
                    <p class="text-sm text-gray-500">
                        Entre em contato: 
                        <a href="tel:{{ \App\Models\Setting::get('site_phone') }}" class="text-primary hover:underline font-medium">
                            {{ \App\Models\Setting::get('site_phone') }}
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Limpar dados da sessão após exibir
    @if(session('order_number'))
        // Opcional: Adicionar analytics ou tracking aqui
        // Pedido realizado
    @endif
</script>
@endpush
@endsection
