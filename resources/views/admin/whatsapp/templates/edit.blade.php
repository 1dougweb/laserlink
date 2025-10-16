@extends('admin.layout')

@section('title', 'Editar Template WhatsApp')
@section('page-title', 'Editar Template WhatsApp')

@section('content')
<div class="space-y-6">
    <form method="POST" action="{{ route('admin.whatsapp.templates.update', $template->id) }}" x-data="templateForm()">
        @csrf
        @method('PUT')
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center mb-6">
                <i class="bi bi-whatsapp text-primary text-xl mr-3"></i>
                <h3 class="text-lg font-semibold text-gray-900">Editar Template</h3>
            </div>
            
            <div class="space-y-6">
                <!-- Nome do Template -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nome do Template <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           placeholder="Ex: Confirmação de Pedido"
                           value="{{ old('name', $template->name) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('name') border-red-300 @enderror"
                           required>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="bi bi-info-circle mr-1"></i>
                        Nome para identificar este template no painel administrativo
                    </p>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tipo do Template -->
                <div>
                    <label for="template_type" class="block text-sm font-medium text-gray-700 mb-2">
                        Tipo do Template <span class="text-red-500">*</span>
                    </label>
                    <select id="template_type" 
                            name="template_type" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('template_type') border-red-300 @enderror"
                            required>
                        <option value="">Selecione um tipo...</option>
                        @foreach($templateTypes as $value => $label)
                            <option value="{{ $value }}" {{ old('template_type', $template->template_type) == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="bi bi-info-circle mr-1"></i>
                        Define quando este template será utilizado automaticamente
                    </p>
                    @error('template_type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Conteúdo do Template -->
                <div>
                    <label for="message_template" class="block text-sm font-medium text-gray-700 mb-2">
                        Mensagem do Template <span class="text-red-500">*</span>
                    </label>
                    <textarea id="message_template" 
                              name="message_template" 
                              rows="10"
                              placeholder="Ex: Olá {customer_name}! Seu pedido #{order_number} foi confirmado..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('message_template') border-red-300 @enderror"
                              required>{{ old('message_template', $template->message_template) }}</textarea>
                    @error('message_template')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-3">
                        <p class="text-sm text-blue-800 font-medium mb-2">
                            <i class="bi bi-info-circle-fill mr-1"></i>
                            Variáveis Disponíveis
                        </p>
                        <ul class="text-sm text-blue-700 space-y-1 ml-6 list-disc">
                            <li><code class="bg-blue-100 px-1.5 py-0.5 rounded">{customer_name}</code> - Nome do cliente</li>
                            <li><code class="bg-blue-100 px-1.5 py-0.5 rounded">{order_number}</code> - Número do pedido</li>
                            <li><code class="bg-blue-100 px-1.5 py-0.5 rounded">{order_total}</code> - Valor total do pedido</li>
                            <li><code class="bg-blue-100 px-1.5 py-0.5 rounded">{shipping_address}</code> - Endereço de entrega</li>
                            <li><code class="bg-blue-100 px-1.5 py-0.5 rounded">{company_name}</code> - Nome da empresa</li>
                            <li><code class="bg-blue-100 px-1.5 py-0.5 rounded">{status}</code> - Status do pedido</li>
                            <li><code class="bg-blue-100 px-1.5 py-0.5 rounded">{tracking_code}</code> - Código de rastreamento</li>
                        </ul>
                    </div>
                </div>

                <!-- Status do Template -->
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <h5 class="text-sm font-medium text-gray-900">Status do Template</h5>
                        <p class="text-sm text-gray-500">Ativar/desativar o envio deste template</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" 
                               name="is_active" 
                               value="1"
                               {{ old('is_active', $template->is_active) ? 'checked' : '' }}
                               class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Botões -->
        <div class="flex justify-end space-x-4 mt-4">
            <a href="{{ route('admin.whatsapp.templates.index') }}" 
               class="inline-flex items-center px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                <i class="bi bi-arrow-left mr-2"></i>Voltar
            </a>
            <button type="submit" 
                    class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg transition-colors">
                Salvar Opções
            </button>
        </div>
    </form>
</div>

@if(isset($preview))
<div class="space-y-6 mt-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center mb-6">
            <i class="bi bi-eye text-primary text-xl mr-3"></i>
            <h3 class="text-lg font-semibold text-gray-900">Preview do Template</h3>
        </div>
        <pre class="bg-gray-50 p-4 rounded-lg text-sm text-gray-700 whitespace-pre-wrap">{{ $preview }}</pre>
    </div>
</div>
@endif
@stop

@section('js')
<script>
function templateForm() {
    return {
        loading: false,
        init() {
            const messageTemplate = document.getElementById('message_template');
            const preview = document.querySelector('.preview-content');
            
            if (messageTemplate && preview) {
                messageTemplate.addEventListener('input', function() {
                    // Implementar preview ao vivo aqui se necessário
                });
            }
        }
    }
}
</script>
@stop