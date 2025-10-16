@extends('admin.layout')

@section('title', 'Criar Template')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Criar Template</h1>
            <p class="text-gray-600 mt-1">Configure um novo template de mensagem WhatsApp</p>
        </div>
        <a href="{{ route('admin.whatsapp.templates.index') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-all">
            <i class="bi bi-arrow-left mr-2"></i> Voltar
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <form method="POST" action="{{ route('admin.whatsapp.templates.store') }}" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nome do Template *
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('name') border-red-500 @enderror"
                           placeholder="Ex: Confirmação de Pedido">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                        Tipo *
                    </label>
                    <select name="type" id="type" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('type') border-red-500 @enderror">
                        <option value="">Selecione o tipo</option>
                        <option value="order_status" {{ old('type') == 'order_status' ? 'selected' : '' }}>Status do Pedido</option>
                        <option value="promotion" {{ old('type') == 'promotion' ? 'selected' : '' }}>Promoção</option>
                        <option value="cart_abandonment" {{ old('type') == 'cart_abandonment' ? 'selected' : '' }}>Carrinho Abandonado</option>
                        <option value="custom" {{ old('type') == 'custom' ? 'selected' : '' }}>Personalizada</option>
                    </select>
                    @error('type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <label for="message_template" class="block text-sm font-medium text-gray-700 mb-2">
                    Conteúdo da Mensagem *
                </label>
                <textarea name="message_template" id="message_template" rows="8" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('message_template') border-red-500 @enderror"
                          placeholder="Digite o conteúdo da mensagem... Use variáveis como {customer_name}, {order_number}, etc.">{{ old('message_template') }}</textarea>
                @error('message_template')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Variáveis Disponíveis
                </label>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-sm">
                        <div class="flex items-center space-x-2">
                            <code class="bg-white px-2 py-1 rounded text-blue-600">{customer_name}</code>
                            <span class="text-gray-600">Nome do cliente</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <code class="bg-white px-2 py-1 rounded text-blue-600">{order_number}</code>
                            <span class="text-gray-600">Número do pedido</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <code class="bg-white px-2 py-1 rounded text-blue-600">{order_status}</code>
                            <span class="text-gray-600">Status do pedido</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <code class="bg-white px-2 py-1 rounded text-blue-600">{total_amount}</code>
                            <span class="text-gray-600">Valor total</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <code class="bg-white px-2 py-1 rounded text-blue-600">{shipping_address}</code>
                            <span class="text-gray-600">Endereço</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <code class="bg-white px-2 py-1 rounded text-blue-600">{store_name}</code>
                            <span class="text-gray-600">Nome da loja</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <label class="flex items-center">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} 
                           class="rounded border-gray-300 text-primary focus:ring-primary">
                    <span class="ml-2 text-sm text-gray-700">Template ativo (pode ser usado para envio)</span>
                </label>
            </div>

            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('admin.whatsapp.templates.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-all">
                    Cancelar
                </a>
                <button type="submit" 
                        class="bg-primary hover:opacity-90 text-white px-6 py-2 rounded-lg transition-all shadow-sm hover:shadow-md">
                    <i class="bi bi-save mr-2"></i> Salvar Template
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

