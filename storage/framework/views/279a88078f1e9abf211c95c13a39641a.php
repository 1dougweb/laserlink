<!-- Cookie Consent Banner - Versão Simples -->
<div x-data="cookieConsent()" 
     x-show="!hasConsent" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform translate-x-[-100%]"
     x-transition:enter-end="opacity-100 transform translate-x-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 transform translate-x-0"
     x-transition:leave-end="opacity-0 transform translate-x-[-100%]"
     class="fixed bottom-6 left-6 z-[9999] max-w-[380px]"
     style="display: none;"
     x-cloak>
    
    <div class="bg-white rounded-lg shadow-lg border border-gray-300">
        <div class="p-4">
            <div class="flex items-start gap-3 mb-3">
                <i class="bi bi-cookie text-gray-700 text-xl flex-shrink-0 mt-0.5"></i>
                <p class="text-sm text-gray-700 leading-relaxed flex-1">
                    Este site usa cookies para garantir que você tenha a melhor experiência em nosso site, 
                    <a href="<?php echo e(route('page.show', 'politica-de-privacidade')); ?>" 
                       class="text-red-600 hover:text-red-700 font-medium underline"
                       target="_blank">
                        clique aqui
                    </a> 
                    para ler mais.
                </p>
            </div>
            
            <!-- Botões -->
            <div class="flex gap-2">
                <button @click="acceptCookies()" 
                        class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                    Aceitar
                </button>
                
                <button @click="openSettings()" 
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors"
                        title="Personalizar">
                    <i class="bi bi-sliders"></i>
                </button>
                
                <button @click="rejectCookies()" 
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors"
                        title="Recusar">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>
                
    </div>
    
    <!-- Modal de Configurações (expandível) -->
    <div x-show="showSettings" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[10000] flex items-center justify-center p-4 bg-black bg-opacity-50"
         style="display: none;"
         @click.self="showSettings = false">
        
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto"
             @click.stop>
            <!-- Header -->
            <div class="p-6 border-b border-gray-200 flex items-center justify-between bg-gray-50">
                <h2 class="text-xl font-bold text-gray-900 flex items-center">
                    <i class="bi bi-cookie text-gray-700 mr-2"></i>
                    Preferências de Cookies
                </h2>
                <button @click="showSettings = false" 
                        class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="bi bi-x-lg text-xl"></i>
                </button>
            </div>
            
            <!-- Conteúdo do Modal -->
            <div class="p-6 space-y-4">
                <p class="text-gray-600 text-sm leading-relaxed">
                    Usamos cookies para melhorar sua experiência. Escolha quais tipos de cookies permitir:
                </p>
                
                <!-- Cookies Essenciais -->
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center mb-1">
                                <h3 class="font-semibold text-gray-900 text-sm">Cookies Essenciais</h3>
                                <span class="ml-2 px-2 py-0.5 bg-gray-500 text-white text-xs rounded-full">Obrigatório</span>
                            </div>
                            <p class="text-xs text-gray-600">
                                Necessários para carrinho, login e funcionamento básico.
                            </p>
                        </div>
                        <input type="checkbox" 
                               checked 
                               disabled 
                               class="mt-1 w-5 h-5 text-gray-400 border-gray-300 rounded cursor-not-allowed">
                    </div>
                </div>
                
                <!-- Cookies de Análise -->
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900 mb-1 text-sm">Cookies de Análise</h3>
                            <p class="text-xs text-gray-600">
                                Google Analytics para melhorar o site.
                            </p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer mt-1">
                            <input type="checkbox" 
                                   x-model="preferences.analytics" 
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600"></div>
                        </label>
                    </div>
                </div>
                
                <!-- Cookies de Marketing -->
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900 mb-1 text-sm">Cookies de Marketing</h3>
                            <p class="text-xs text-gray-600">
                                Anúncios personalizados e conteúdo relevante.
                            </p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer mt-1">
                            <input type="checkbox" 
                                   x-model="preferences.marketing" 
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600"></div>
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- Footer do Modal -->
            <div class="p-6 border-t border-gray-200 bg-gray-50 flex gap-3">
                <button @click="savePreferences()" 
                        class="flex-1 px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-colors text-sm">
                    <i class="bi bi-check-circle mr-2"></i>
                    Salvar
                </button>
                <button @click="showSettings = false" 
                        class="px-6 py-3 bg-white border-2 border-gray-300 hover:border-gray-400 text-gray-700 font-semibold rounded-lg transition-all text-sm">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function cookieConsent() {
        return {
            hasConsent: localStorage.getItem('cookieConsent') !== null,
            showSettings: false,
            preferences: {
                essential: true, // Sempre true
                analytics: true,
                marketing: true
            },
            
            init() {
                // Carregar preferências salvas
                const saved = localStorage.getItem('cookiePreferences');
                if (saved) {
                    this.preferences = JSON.parse(saved);
                }
            },
            
            acceptCookies() {
                this.preferences = {
                    essential: true,
                    analytics: true,
                    marketing: true
                };
                this.saveCookieConsent();
            },
            
            rejectCookies() {
                this.preferences = {
                    essential: true,
                    analytics: false,
                    marketing: false
                };
                this.saveCookieConsent();
            },
            
            openSettings() {
                this.showSettings = true;
            },
            
            savePreferences() {
                this.saveCookieConsent();
                this.showSettings = false;
            },
            
            saveCookieConsent() {
                localStorage.setItem('cookieConsent', 'true');
                localStorage.setItem('cookiePreferences', JSON.stringify(this.preferences));
                localStorage.setItem('cookieConsentDate', new Date().toISOString());
                this.hasConsent = true;
                
                // Disparar evento para outras partes do app
                window.dispatchEvent(new CustomEvent('cookieConsentChanged', { 
                    detail: this.preferences 
                }));
                
                // Se analytics foi aceito, pode inicializar Google Analytics aqui
                if (this.preferences.analytics) {
                    this.initAnalytics();
                }
            },
            
            initAnalytics() {
                // Inicializar Google Analytics ou outras ferramentas
                // Se tiver GA4 configurado, ativar aqui
                console.log('Analytics habilitado');
            }
        }
    }
</script>

<style>
    [x-cloak] {
        display: none !important;
    }
</style>

<?php /**PATH C:\xampp\htdocs\resources\views/components/cookie-consent.blade.php ENDPATH**/ ?>