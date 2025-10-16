@extends('admin.layout')

@section('title', 'Configurações WhatsApp - Laser Link')
@section('page-title', 'Configurações WhatsApp')

@section('content')
<div class="space-y-6">
    <form method="POST" action="{{ route('admin.settings.whatsapp.update') }}" x-data="settingsForm()">
        @csrf
        @method('PUT')
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center mb-6">
                <i class="bi bi-whatsapp text-green-600 text-xl mr-3"></i>
                <h3 class="text-lg font-semibold text-gray-900">Configurações do WhatsApp</h3>
            </div>
            
            <div class="space-y-6">
                <div>
                    <label for="whatsapp_number" class="block text-sm font-medium text-gray-700 mb-2">
                        Número do WhatsApp <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="bi bi-whatsapp text-gray-400"></i>
                        </div>
                        <input type="text" 
                               id="whatsapp_number" 
                               name="whatsapp_number" 
                               value="{{ old('whatsapp_number', $settings['whatsapp_number']) }}"
                               placeholder="5511999999999"
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                               required>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">Formato: 5511999999999 (com código do país)</p>
                    @error('whatsapp_number')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="whatsapp_message" class="block text-sm font-medium text-gray-700 mb-2">
                        Mensagem Padrão <span class="text-red-500">*</span>
                    </label>
                    <textarea id="whatsapp_message" 
                              name="whatsapp_message" 
                              rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                              required>{{ old('whatsapp_message', $settings['whatsapp_message']) }}</textarea>
                    <p class="text-sm text-gray-500 mt-1">Esta mensagem será enviada automaticamente quando o cliente clicar em "Fazer Pedido"</p>
                    @error('whatsapp_message')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="whatsapp_enabled" 
                               value="1"
                               {{ old('whatsapp_enabled', $settings['whatsapp_enabled'] ?? true) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-primary focus:ring-primary">
                        <span class="ml-2 text-sm text-gray-700">Ativar pedidos via WhatsApp</span>
                    </label>
                </div>

                <!-- Preview da mensagem -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Preview da Mensagem:</h4>
                    <div class="bg-white border border-gray-200 rounded-lg p-3">
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                <i class="bi bi-whatsapp text-white text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-600" x-text="whatsappMessage || '{{ old('whatsapp_message', $settings['whatsapp_message']) }}'"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Botões -->
        <div class="flex justify-end space-x-4 mt-4 ">
            <a href="{{ route('admin.dashboard') }}" 
               class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                <i class="bi bi-arrow-left mr-2"></i>Cancelar
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors"
                    :disabled="loading">
                <i class="bi bi-check-lg mr-2" x-show="!loading"></i>
                <i class="bi bi-arrow-clockwise animate-spin mr-2" x-show="loading"></i>
                <span x-text="loading ? 'Salvando...' : 'Salvar Configurações'"></span>
            </button>
        </div>
    </form>
</div>

<script>
function settingsForm() {
    return {
        loading: false,
        whatsappMessage: '{{ old('whatsapp_message', $settings['whatsapp_message']) }}',

        init() {
            // Atualizar preview quando a mensagem mudar
            this.$watch('whatsappMessage', () => {
                // Preview será atualizado automaticamente
            });
        }
    }
}
</script>
@endsection

