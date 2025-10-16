@extends('admin.layout')

@section('title', 'Configurações - Gemini AI')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Configurações - Gemini AI</h1>
                <p class="text-gray-600 mt-1">Configure a integração com o Google Gemini AI para geração automática de descrições</p>
            </div>
            <div class="flex items-center space-x-3">
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 rounded-full {{ $settings['gemini_enabled'] ? 'bg-green-500' : 'bg-gray-400' }}"></div>
                    <span class="text-sm text-gray-600">
                        {{ $settings['gemini_enabled'] ? 'Ativo' : 'Inativo' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.settings.gemini.update') }}" x-data="geminiSettings()">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Configurações Principais -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center mb-6">
                    <i class="bi bi-robot text-primary text-xl mr-3"></i>
                    <h3 class="text-lg font-semibold text-gray-900">Configurações Principais</h3>
                </div>
                
                <div class="space-y-6">
                    <!-- Status da Integração -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="gemini_enabled" 
                                   value="1"
                                   {{ $settings['gemini_enabled'] ? 'checked' : '' }}
                                   class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                            <span class="ml-3 text-sm font-medium text-gray-700">
                                Habilitar Gemini AI
                            </span>
                        </label>
                        <p class="text-xs text-gray-500 mt-1">
                            Ative para permitir a geração automática de descrições usando IA
                        </p>
                    </div>

                    <!-- Chave da API -->
                    <div>
                        <label for="gemini_api_key" class="block text-sm font-medium text-gray-700 mb-2">
                            Chave da API Gemini <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   id="gemini_api_key" 
                                   name="gemini_api_key" 
                                   value="{{ old('gemini_api_key', $settings['gemini_api_key']) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent pr-10"
                                   placeholder="Digite sua chave da API do Google Gemini">
                            <button type="button" 
                                    @click="toggleApiKeyVisibility()"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i :class="showApiKey ? 'bi bi-eye-slash' : 'bi bi-eye'" class="text-gray-400"></i>
                            </button>
                        </div>
                        @error('gemini_api_key')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">
                            Obtenha sua chave em: <a href="https://makersuite.google.com/app/apikey" target="_blank" class="text-primary hover:underline">Google AI Studio</a>
                        </p>
                    </div>

                    <!-- Modelo -->
                    <div>
                        <label for="gemini_model" class="block text-sm font-medium text-gray-700 mb-2">
                            Modelo Gemini <span class="text-red-500">*</span>
                        </label>
                        <select id="gemini_model" 
                                name="gemini_model" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="gemini-2.5-flash" {{ $settings['gemini_model'] == 'gemini-2.5-flash' ? 'selected' : '' }}>
                                Gemini 2.5 Flash (Mais recente - Recomendado)
                            </option>
                            <option value="gemini-2.5-pro" {{ $settings['gemini_model'] == 'gemini-2.5-pro' ? 'selected' : '' }}>
                                Gemini 2.5 Pro (Mais avançado)
                            </option>
                            <option value="gemini-2.0-flash" {{ $settings['gemini_model'] == 'gemini-2.0-flash' ? 'selected' : '' }}>
                                Gemini 2.0 Flash (Rápido e econômico)
                            </option>
                            <option value="gemini-2.0-flash-001" {{ $settings['gemini_model'] == 'gemini-2.0-flash-001' ? 'selected' : '' }}>
                                Gemini 2.0 Flash 001 (Versão estável)
                            </option>
                            <option value="gemini-2.5-flash-lite" {{ $settings['gemini_model'] == 'gemini-2.5-flash-lite' ? 'selected' : '' }}>
                                Gemini 2.5 Flash-Lite (Mais leve)
                            </option>
                            <option value="gemini-2.0-flash-lite" {{ $settings['gemini_model'] == 'gemini-2.0-flash-lite' ? 'selected' : '' }}>
                                Gemini 2.0 Flash-Lite (Mais leve)
                            </option>
                        </select>
                        @error('gemini_model')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Configurações Avançadas -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center mb-6">
                    <i class="bi bi-gear text-primary text-xl mr-3"></i>
                    <h3 class="text-lg font-semibold text-gray-900">Configurações Avançadas</h3>
                </div>
                
                <div class="space-y-6">
                    <!-- Temperatura -->
                    <div>
                        <label for="gemini_temperature" class="block text-sm font-medium text-gray-700 mb-2">
                            Temperatura: <span x-text="geminiTemperature"></span>
                        </label>
                        <input type="range" 
                               id="gemini_temperature" 
                               name="gemini_temperature" 
                               x-model="geminiTemperature"
                               min="0" 
                               max="1" 
                               step="0.1"
                               class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer slider">
                        <div class="flex justify-between text-xs text-gray-500 mt-1">
                            <span>Mais focado (0.0)</span>
                            <span>Mais criativo (1.0)</span>
                        </div>
                        @error('gemini_temperature')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">
                            Controla a criatividade das respostas. Valores menores = mais focado, valores maiores = mais criativo.
                        </p>
                    </div>

                    <!-- Máximo de Tokens -->
                    <div>
                        <label for="gemini_max_tokens" class="block text-sm font-medium text-gray-700 mb-2">
                            Máximo de Tokens <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="gemini_max_tokens" 
                               name="gemini_max_tokens" 
                               value="{{ old('gemini_max_tokens', $settings['gemini_max_tokens']) }}"
                               min="1" 
                               max="8192"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        @error('gemini_max_tokens')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">
                            Limite de tokens para as respostas (1-8192). Valores maiores = respostas mais longas.
                        </p>
                    </div>

                    <!-- Teste da API -->
                    <div class="border-t pt-6">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Teste da Integração</h4>
                        
                        <!-- List Available Models -->
                        <button type="button" 
                                @click="listAvailableModels()"
                                :disabled="loadingModels"
                                class="w-full mb-3 bg-purple-600 hover:bg-purple-700 disabled:opacity-50 text-white rounded-lg p-3 transition-colors flex items-center justify-center">
                            <i class="bi bi-arrow-clockwise animate-spin mr-2" x-show="loadingModels"></i>
                            <i class="bi bi-list-ul mr-2" x-show="!loadingModels"></i>
                            <span x-text="loadingModels ? 'Carregando...' : 'Listar Modelos Disponíveis'"></span>
                        </button>
                        
                        <!-- Available Models List -->
                        <div x-show="availableModels && availableModels.length > 0" 
                             x-transition
                             class="mb-3 p-3 bg-blue-50 rounded-lg max-h-60 overflow-y-auto">
                            <p class="text-sm font-medium text-blue-900 mb-2">Modelos Disponíveis:</p>
                            <template x-for="model in availableModels" :key="model.name">
                                <div class="text-xs p-2 mb-1 bg-white rounded border border-blue-200">
                                    <div class="font-mono text-blue-600" x-text="model.name"></div>
                                    <div class="text-gray-600 text-xs mt-1" x-text="model.display_name"></div>
                                </div>
                            </template>
                        </div>
                        
                        <!-- Test Connection -->
                        <button type="button" 
                                @click="testGeminiConnection()"
                                :disabled="testingConnection"
                                class="w-full bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white rounded-lg p-3 transition-colors flex items-center justify-center">
                            <i class="bi bi-arrow-clockwise animate-spin mr-2" x-show="testingConnection"></i>
                            <i class="bi bi-play-circle mr-2" x-show="!testingConnection"></i>
                            <span x-text="testingConnection ? 'Testando...' : 'Testar Conexão'"></span>
                        </button>
                        <div x-show="testResult" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             class="mt-3 p-3 rounded-lg"
                             :class="(testResult && testResult.success) ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                            <div class="flex items-center">
                                <i :class="(testResult && testResult.success) ? 'bi bi-check-circle' : 'bi bi-exclamation-circle'" class="mr-2"></i>
                                <span x-text="testResult ? testResult.message : ''"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botões de Ação -->
        <div class="flex justify-between items-center mt-8">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.settings.general') }}" 
                   class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="bi bi-arrow-left mr-2"></i>
                    Voltar
                </a>
            </div>
            
            <div class="flex space-x-3">
                <button type="button" 
                        @click="resetToDefaults()"
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="bi bi-arrow-clockwise mr-2"></i>
                    Restaurar Padrões
                </button>
                <button type="submit" 
                        class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors">
                    <i class="bi bi-check-lg mr-2"></i>
                    Salvar Configurações
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function geminiSettings() {
    return {
        showApiKey: false,
        geminiTemperature: {{ $settings['gemini_temperature'] }},
        testingConnection: false,
        testResult: null,
        loadingModels: false,
        availableModels: null,

        init() {
            // Inicializar slider customizado
            this.initializeSlider();
        },

        toggleApiKeyVisibility() {
            this.showApiKey = !this.showApiKey;
            const input = document.getElementById('gemini_api_key');
            input.type = this.showApiKey ? 'text' : 'password';
        },

        initializeSlider() {
            const slider = document.querySelector('.slider');
            if (slider) {
                slider.addEventListener('input', (e) => {
                    this.geminiTemperature = parseFloat(e.target.value);
                });
            }
        },

        async listAvailableModels() {
            this.loadingModels = true;
            this.availableModels = null;

            try {
                const response = await fetch('{{ route("admin.settings.gemini.models") }}', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    this.availableModels = data.models;
                    console.log('Available Models:', data.models);
                } else {
                    alert(data.message || 'Erro ao listar modelos');
                }
            } catch (error) {
                alert('Erro ao listar modelos: ' + error.message);
            } finally {
                this.loadingModels = false;
            }
        },

        async testGeminiConnection() {
            this.testingConnection = true;
            this.testResult = null;

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) {
                    this.testResult = {
                        success: false,
                        message: 'Erro: CSRF token não encontrado. Recarregue a página.'
                    };
                    this.testingConnection = false;
                    return;
                }

                const response = await fetch('{{ route("admin.settings.gemini.test") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken.getAttribute('content')
                    }
                });

                const ct = response.headers.get('content-type') || '';
                const data = ct.includes('application/json') ? await response.json() : { success: false, message: 'Erro inesperado do servidor' };

                if (response.ok && data.success) {
                    this.testResult = {
                        success: true,
                        message: data.message || 'Conexão com Gemini AI funcionando perfeitamente!'
                    };
                    
                    // Log diagnostics for debugging
                    if (data.diagnostics) {
                        console.log('Gemini Diagnostics:', data.diagnostics);
                    }
                } else {
                    // Handle validation errors
                    let errorMessage = data.message || 'Erro ao testar conexão';
                    if (data.errors) {
                        const firstError = Object.values(data.errors)[0];
                        errorMessage = Array.isArray(firstError) ? firstError[0] : firstError;
                    }
                    
                    this.testResult = {
                        success: false,
                        message: errorMessage
                    };
                    
                    // Log diagnostics for debugging
                    if (data.diagnostics) {
                        console.log('Gemini Diagnostics:', data.diagnostics);
                    }
                }
            } catch (error) {
                this.testResult = {
                    success: false,
                    message: 'Erro de conexão: ' + error.message
                };
            } finally {
                this.testingConnection = false;
            }
        },

        resetToDefaults() {
            if (confirm('Tem certeza que deseja restaurar as configurações padrão?')) {
                document.getElementById('gemini_model').value = 'gemini-2.5-flash';
                document.getElementById('gemini_temperature').value = 0.7;
                document.getElementById('gemini_max_tokens').value = 1024;
                this.geminiTemperature = 0.7;
                document.querySelector('input[name="gemini_enabled"]').checked = false;
            }
        }
    }
}
</script>

<style>
.slider {
    -webkit-appearance: none;
    appearance: none;
    background: #e5e7eb;
    outline: none;
    border-radius: 8px;
}

.slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #EE0000;
    cursor: pointer;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.slider::-moz-range-thumb {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #EE0000;
    cursor: pointer;
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}
</style>
@endsection
