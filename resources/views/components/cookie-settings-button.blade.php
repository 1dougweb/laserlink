<!-- Botão Flutuante Discreto para Gerenciar Cookies -->
<div x-data="cookieSettingsButton()" 
     x-show="hasConsent"
     class="fixed left-4 sm:left-6 z-[9998]"
     style="display: none; bottom: calc(4.5rem + 1.5rem);"
     x-cloak>
    
    <!-- Botão Principal - Discreto -->
    <button @click="openModal()" 
            class="flex items-center justify-center w-10 h-10 bg-gray-600 hover:bg-gray-700 text-white rounded-lg shadow-md transition-all duration-300 hover:scale-105 opacity-60 hover:opacity-100 group"
            title="Gerenciar Cookies">
        <i class="bi bi-cookie text-base group-hover:scale-110 transition-transform"></i>
    </button>
    
    <!-- Modal de Configurações -->
    <div x-show="showModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[10000] flex items-center justify-center p-4 bg-black bg-opacity-50"
         style="display: none;"
         @click.self="closeModal()">
        
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto"
             @click.stop>
            <!-- Header -->
            <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class="bi bi-cookie text-red-600 mr-3"></i>
                    Preferências de Cookies
                </h2>
                <button @click="closeModal()" 
                        class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="bi bi-x-lg text-2xl"></i>
                </button>
            </div>
            
            <!-- Conteúdo -->
            <div class="p-6 space-y-6">
                <p class="text-gray-600 leading-relaxed">
                    Usamos cookies para melhorar sua experiência. Você pode escolher quais tipos de cookies permitir. 
                    Os cookies essenciais são sempre necessários para o funcionamento do site.
                </p>
                
                <!-- Cookies Essenciais -->
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900 flex items-center mb-1">
                                <i class="bi bi-shield-check text-green-600 mr-2"></i>
                                Cookies Essenciais
                                <span class="ml-2 px-2 py-0.5 bg-gray-400 text-white text-xs rounded-full">Sempre Ativo</span>
                            </h3>
                            <p class="text-sm text-gray-600">
                                Necessários para funcionalidades básicas como carrinho de compras, autenticação e navegação.
                            </p>
                        </div>
                        <input type="checkbox" 
                               checked 
                               disabled 
                               class="mt-1 w-5 h-5 text-gray-400 border-gray-300 rounded cursor-not-allowed">
                    </div>
                    <div class="text-xs text-gray-500 mt-2">
                        <strong>Exemplos:</strong> Sessão, CSRF Token, Carrinho de Compras
                    </div>
                </div>
                
                <!-- Cookies de Análise -->
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900 flex items-center mb-1">
                                <i class="bi bi-graph-up text-blue-600 mr-2"></i>
                                Cookies de Análise
                            </h3>
                            <p class="text-sm text-gray-600">
                                Coletam informações sobre como você usa nosso site para melhorarmos continuamente.
                            </p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer mt-1">
                            <input type="checkbox" 
                                   x-model="preferences.analytics" 
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600"></div>
                        </label>
                    </div>
                    <div class="text-xs text-gray-500 mt-2">
                        <strong>Exemplos:</strong> Google Analytics, Contagem de Visualizações
                    </div>
                </div>
                
                <!-- Cookies de Marketing -->
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900 flex items-center mb-1">
                                <i class="bi bi-megaphone text-purple-600 mr-2"></i>
                                Cookies de Marketing
                            </h3>
                            <p class="text-sm text-gray-600">
                                Utilizados para personalizar anúncios e exibir conteúdo relevante baseado em seus interesses.
                            </p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer mt-1">
                            <input type="checkbox" 
                                   x-model="preferences.marketing" 
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600"></div>
                        </label>
                    </div>
                    <div class="text-xs text-gray-500 mt-2">
                        <strong>Exemplos:</strong> Facebook Pixel, Google Ads
                    </div>
                </div>
                
                <!-- Informações Adicionais -->
                <div class="bg-blue-50 rounded-xl p-4 border border-blue-200">
                    <h4 class="font-semibold text-gray-900 mb-2 flex items-center">
                        <i class="bi bi-info-circle text-blue-600 mr-2"></i>
                        Mais Informações
                    </h4>
                    <p class="text-sm text-gray-600 mb-3">
                        Para mais detalhes sobre como usamos cookies e protegemos seus dados, consulte nossa política de privacidade.
                    </p>
                    <a href="{{ route('page.show', 'politica-de-privacidade') }}" 
                       target="_blank"
                       class="inline-flex items-center text-blue-700 hover:text-blue-800 font-medium text-sm">
                        <i class="bi bi-arrow-right mr-1"></i>
                        Ler Política de Privacidade
                    </a>
                </div>
            </div>
            
            <!-- Footer do Modal -->
            <div class="p-6 border-t border-gray-200 bg-gray-50 flex flex-col sm:flex-row gap-3">
                <button @click="savePreferences()" 
                        class="flex-1 px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-colors">
                    <i class="bi bi-check-circle mr-2"></i>
                    Salvar Preferências
                </button>
                <button @click="closeModal()" 
                        class="px-6 py-3 bg-white border-2 border-gray-300 hover:border-gray-400 text-gray-700 font-semibold rounded-lg transition-all">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function cookieSettingsButton() {
        return {
            hasConsent: localStorage.getItem('cookieConsent') !== null,
            showModal: false,
            preferences: {
                essential: true,
                analytics: true,
                marketing: true
            },
            
            init() {
                // Carregar preferências salvas
                const saved = localStorage.getItem('cookiePreferences');
                if (saved) {
                    this.preferences = JSON.parse(saved);
                }
                
                // Escutar mudanças de outras abas
                window.addEventListener('storage', (e) => {
                    if (e.key === 'cookieConsent') {
                        this.hasConsent = e.newValue !== null;
                    }
                    if (e.key === 'cookiePreferences' && e.newValue) {
                        this.preferences = JSON.parse(e.newValue);
                    }
                });
            },
            
            openModal() {
                this.showModal = true;
                document.body.style.overflow = 'hidden';
            },
            
            closeModal() {
                this.showModal = false;
                document.body.style.overflow = '';
            },
            
            savePreferences() {
                localStorage.setItem('cookiePreferences', JSON.stringify(this.preferences));
                localStorage.setItem('cookieConsentDate', new Date().toISOString());
                
                window.dispatchEvent(new CustomEvent('cookieConsentChanged', { 
                    detail: this.preferences 
                }));
                
                this.closeModal();
                
                // Mostrar feedback
                alert('✅ Preferências de cookies salvas com sucesso!');
            }
        }
    }
</script>

