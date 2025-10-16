@extends('admin.layout')

@section('title', 'Configurações WhatsApp')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Configurações WhatsApp</h1>
            <p class="text-gray-600 mt-1">Configure a integração com a Evolution API</p>
        </div>
        <a href="{{ route('admin.whatsapp.instances.index') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-all">
            <i class="bi bi-arrow-left mr-2"></i> Voltar
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
            <div class="flex justify-between items-center">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.parentElement.style.display='none'" class="text-green-700 hover:text-green-900">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>
    @endif

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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Configurações da API -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Configurações da Evolution API</h3>
                    <p class="text-sm text-gray-600 mt-1">Configure a conexão com seu servidor Evolution API</p>
                </div>
                
                <form method="POST" action="{{ route('admin.whatsapp.settings.update') }}" class="p-6">
                    @csrf
                    
                    <div class="space-y-6">
                        <div>
                            <label for="evolution_api_base_url" class="block text-sm font-medium text-gray-700 mb-2">
                                URL Base da Evolution API *
                            </label>
                            <input type="url" 
                                   name="evolution_api_base_url" 
                                   id="evolution_api_base_url" 
                                   value="{{ old('evolution_api_base_url', $settings['evolution_api_base_url']) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('evolution_api_base_url') border-red-500 @enderror"
                                   placeholder="https://seu-servidor.com:8080">
                            @error('evolution_api_base_url')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">URL completa do seu servidor Evolution API (ex: https://api.exemplo.com:8080)</p>
                        </div>

                        <div>
                            <label for="evolution_api_key" class="block text-sm font-medium text-gray-700 mb-2">
                                Chave da API *
                            </label>
                            <input type="text" 
                                   name="evolution_api_key" 
                                   id="evolution_api_key" 
                                   value="{{ old('evolution_api_key', $settings['evolution_api_key']) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('evolution_api_key') border-red-500 @enderror"
                                   placeholder="Sua chave da Evolution API">
                            @error('evolution_api_key')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Chave de autenticação fornecida pela Evolution API</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="max_instances" class="block text-sm font-medium text-gray-700 mb-2">
                                    Máximo de Instâncias
                                </label>
                                <input type="number" 
                                       name="max_instances" 
                                       id="max_instances" 
                                       value="{{ old('max_instances', $settings['max_instances']) }}" 
                                       min="1" 
                                       max="10"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('max_instances') border-red-500 @enderror">
                                @error('max_instances')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="default_timeout" class="block text-sm font-medium text-gray-700 mb-2">
                                    Timeout (segundos)
                                </label>
                                <input type="number" 
                                       name="default_timeout" 
                                       id="default_timeout" 
                                       value="{{ old('default_timeout', $settings['default_timeout']) }}" 
                                       min="5" 
                                       max="120"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('default_timeout') border-red-500 @enderror">
                                @error('default_timeout')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="retry_attempts" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tentativas de Reenvio
                                </label>
                                <input type="number" 
                                       name="retry_attempts" 
                                       id="retry_attempts" 
                                       value="{{ old('retry_attempts', $settings['retry_attempts']) }}" 
                                       min="1" 
                                       max="10"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('retry_attempts') border-red-500 @enderror">
                                @error('retry_attempts')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="whatsapp_enabled" 
                                       value="1" 
                                       {{ old('whatsapp_enabled', $settings['whatsapp_enabled']) ? 'checked' : '' }} 
                                       class="rounded border-gray-300 text-primary focus:ring-primary">
                                <span class="ml-2 text-sm text-gray-700">Habilitar notificações WhatsApp</span>
                            </label>
                        </div>

                        <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                            <button type="button" 
                                    onclick="testConnection()" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-all">
                                <i class="bi bi-wifi mr-2"></i> Testar Conexão
                            </button>
                            
                            <button type="submit" 
                                    class="bg-primary hover:opacity-90 text-white px-6 py-2 rounded-lg transition-all shadow-sm hover:shadow-md">
                                <i class="bi bi-save mr-2"></i> Salvar Configurações
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Informações e Status -->
        <div class="space-y-6">
            <!-- Status da Conexão -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Status da Conexão</h3>
                
                <div id="connection-status" class="text-center py-4">
                    <div class="mb-2">
                        <i class="bi bi-question-circle text-3xl text-gray-400"></i>
                    </div>
                    <p class="text-sm text-gray-600">Clique em "Testar Conexão" para verificar</p>
                </div>
            </div>

            <!-- Informações Úteis -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informações Úteis</h3>
                
                <div class="space-y-4 text-sm">
                    <div>
                        <h4 class="font-medium text-gray-900 mb-2">Como obter a Evolution API:</h4>
                        <ul class="text-gray-600 space-y-1">
                            <li>• Instale a Evolution API em seu servidor</li>
                            <li>• Configure o servidor com uma porta específica</li>
                            <li>• Obtenha a chave da API nas configurações</li>
                            <li>• Teste a conexão antes de salvar</li>
                        </ul>
                    </div>
                    
                    
                </div>
            </div>

            <!-- Configurações Atuais -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Configurações Atuais</h3>
                
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">WhatsApp:</span>
                        <span class="font-medium {{ $settings['whatsapp_enabled'] ? 'text-green-600' : 'text-red-600' }}">
                            {{ $settings['whatsapp_enabled'] ? 'Habilitado' : 'Desabilitado' }}
                        </span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Máx. Instâncias:</span>
                        <span class="font-medium text-gray-900">{{ $settings['max_instances'] }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Timeout:</span>
                        <span class="font-medium text-gray-900">{{ $settings['default_timeout'] }}s</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tentativas:</span>
                        <span class="font-medium text-gray-900">{{ $settings['retry_attempts'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function testConnection() {
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="bi bi-arrow-clockwise animate-spin mr-2"></i> Testando...';
    button.disabled = true;
    
    const statusDiv = document.getElementById('connection-status');
    statusDiv.innerHTML = '<div class="text-center py-4"><div class="mb-2"><i class="bi bi-arrow-clockwise animate-spin text-2xl text-blue-500"></i></div><p class="text-sm text-gray-600">Testando conexão...</p></div>';
    
    const formData = {
        evolution_api_base_url: document.getElementById('evolution_api_base_url').value,
        evolution_api_key: document.getElementById('evolution_api_key').value,
        _token: '{{ csrf_token() }}'
    };
    
    fetch('{{ route("admin.whatsapp.settings.test-connection") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            statusDiv.innerHTML = `
                <div class="text-center py-4">
                    <div class="mb-2">
                        <i class="bi bi-check-circle text-3xl text-green-500"></i>
                    </div>
                    <p class="text-sm text-green-600 font-medium">Conexão estabelecida!</p>
                    <p class="text-xs text-gray-600 mt-1">Evolution API está respondendo</p>
                </div>
            `;
        } else {
            statusDiv.innerHTML = `
                <div class="text-center py-4">
                    <div class="mb-2">
                        <i class="bi bi-x-circle text-3xl text-red-500"></i>
                    </div>
                    <p class="text-sm text-red-600 font-medium">Falha na conexão</p>
                    <p class="text-xs text-gray-600 mt-1">${data.message}</p>
                </div>
            `;
        }
    })
    .catch(error => {
        statusDiv.innerHTML = `
            <div class="text-center py-4">
                <div class="mb-2">
                    <i class="bi bi-exclamation-triangle text-3xl text-yellow-500"></i>
                </div>
                <p class="text-sm text-yellow-600 font-medium">Erro de conexão</p>
                <p class="text-xs text-gray-600 mt-1">Verifique a URL e a chave da API</p>
            </div>
        `;
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    });
}
</script>
@endsection

