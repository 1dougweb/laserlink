@extends('admin.layout')

@section('title', 'Criar Instância')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Criar Nova Instância</h1>
            <p class="text-gray-600 mt-1">Configure uma nova instância WhatsApp da Evolution API</p>
        </div>
        <a href="{{ route('admin.whatsapp.instances.index') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-all">
            <i class="bi bi-arrow-left mr-2"></i> Voltar
        </a>
    </div>

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
            <div class="flex justify-between items-center">
                <span>{{ session('error') }}</span>
                <button onclick="this.parentElement.parentElement.style.display='none'" class="text-red-700 hover:text-red-900">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <form method="POST" action="{{ route('admin.whatsapp.instances.store') }}" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nome da Instância *
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('name') border-red-500 @enderror"
                           placeholder="Ex: Instância Principal">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="purpose" class="block text-sm font-medium text-gray-700 mb-2">
                        Finalidade *
                    </label>
                    <select name="purpose" id="purpose" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('purpose') border-red-500 @enderror">
                        <option value="">Selecione a finalidade</option>
                        <option value="orders" {{ old('purpose') == 'orders' ? 'selected' : '' }}>Pedidos</option>
                        <option value="promotions" {{ old('purpose') == 'promotions' ? 'selected' : '' }}>Promoções</option>
                        <option value="support" {{ old('purpose') == 'support' ? 'selected' : '' }}>Suporte</option>
                    </select>
                    @error('purpose')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="bi bi-info-circle text-blue-500 text-xl mr-3 mt-0.5"></i>
                        <div>
                            <h4 class="text-sm font-medium text-blue-900 mb-2">Como funciona:</h4>
                            <ul class="text-sm text-blue-800 space-y-1">
                                <li>• <strong>Pedidos:</strong> Para notificações automáticas de status de pedidos</li>
                                <li>• <strong>Promoções:</strong> Para envio de campanhas promocionais</li>
                                <li>• <strong>Suporte:</strong> Para atendimento ao cliente</li>
                            </ul>
                            <p class="text-sm text-blue-800 mt-2">
                                Após criar a instância, você receberá um QR Code para conectar o WhatsApp.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <label class="flex items-center">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} 
                           class="rounded border-gray-300 text-primary focus:ring-primary">
                    <span class="ml-2 text-sm text-gray-700">Ativar instância imediatamente</span>
                </label>
            </div>

            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('admin.whatsapp.instances.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-all">
                    Cancelar
                </a>
                <button type="submit" 
                        class="bg-primary hover:opacity-90 text-white px-6 py-2 rounded-lg transition-all shadow-sm hover:shadow-md">
                    <i class="bi bi-plus-circle mr-2"></i> Criar Instância
                </button>
            </div>
        </form>
    </div>
</div>
@endsection