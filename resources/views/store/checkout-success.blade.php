@extends('layouts.store')

@section('title', 'Pedido Realizado com Sucesso')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
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
                    <a href="{{ route('store.checkout') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-primary md:ml-2">Checkout</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="bi bi-chevron-right text-gray-400 mx-1"></i>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Sucesso</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="text-center">
        <!-- Ícone de Sucesso -->
        <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-green-100 mb-6">
            <i class="fas fa-check text-4xl text-green-600"></i>
        </div>

        <!-- Título -->
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Pedido Realizado com Sucesso!</h1>
        
        <!-- Mensagem -->
        <p class="text-lg text-gray-600 mb-8">
            Obrigado pela sua compra! Seu pedido foi recebido e está sendo processado.
        </p>

        <!-- Informações do Pedido -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8 max-w-md mx-auto">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Informações do Pedido</h2>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Número do Pedido:</span>
                    <span class="font-medium">{{ $order->order_number ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Data:</span>
                    <span class="font-medium">{{ $order ? $order->created_at->format('d/m/Y H:i') : date('d/m/Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Status:</span>
                    <span class="font-medium text-green-600">Confirmado</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Total:</span>
                    <span class="font-medium text-primary">R$ {{ $order ? number_format($order->total, 2, ',', '.') : '0,00' }}</span>
                </div>
            </div>
        </div>

        <!-- Próximos Passos -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
            <h3 class="text-lg font-semibold text-blue-900 mb-3">Próximos Passos</h3>
            <ul class="text-left text-blue-800 space-y-2">
                <li class="flex items-start">
                    <i class="fas fa-envelope text-blue-600 mr-3 mt-1"></i>
                    <span>Você receberá um e-mail de confirmação em breve</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-clock text-blue-600 mr-3 mt-1"></i>
                    <span>Processaremos seu pedido em até 2 dias úteis</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-truck text-blue-600 mr-3 mt-1"></i>
                    <span>Enviaremos as informações de rastreamento por e-mail</span>
                </li>
            </ul>
        </div>

        <!-- Botões de Ação -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <!-- Removido botão do WhatsApp pois agora as mensagens são enviadas automaticamente -->
            <div class="text-sm text-gray-600 mb-4">
                Em breve você receberá uma mensagem no WhatsApp com os detalhes do seu pedido.
            </div>
            <a href="{{ route('store.index') }}" 
               class="inline-flex items-center px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                <i class="fas fa-home mr-2"></i>
                Voltar ao Início
            </a>
            <a href="{{ route('store.products') }}" 
               class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                <i class="fas fa-shopping-bag mr-2"></i>
                Continuar Comprando
            </a>
        </div>

        <!-- Contato -->
        <div class="mt-12 p-6 bg-gray-50 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Precisa de Ajuda?</h3>
            <p class="text-gray-600 mb-4">
                Nossa equipe está pronta para ajudar com qualquer dúvida sobre seu pedido.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="https://wa.me/5511999999999" target="_blank"
                   class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fab fa-whatsapp mr-2"></i>
                    WhatsApp
                </a>
                <a href="mailto:contato@laserlink.com.br"
                   class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-envelope mr-2"></i>
                    E-mail
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Limpar carrinho após sucesso
    localStorage.removeItem('cart');
    
    // Atualizar contador do carrinho
    window.dispatchEvent(new CustomEvent('cartUpdated'));
});
</script>
@endpush
