@extends('admin.layout')

@section('title', 'Configurações Gerais - Laser Link')
@section('page-title', 'Configurações Gerais')

@section('content')
<div class="space-y-6">
    <form method="POST" action="{{ route('admin.settings.general.update') }}" x-data="settingsForm()">
        @csrf
        @method('PUT')
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center mb-6">
                <i class="bi bi-gear text-primary text-xl mr-3"></i>
                <h3 class="text-lg font-semibold text-gray-900">Configurações Gerais</h3>
            </div>
            
            <div class="space-y-6">
                <div>
                    <label for="site_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nome do Site <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="site_name" 
                           name="site_name" 
                           value="{{ old('site_name', $settings['site_name']) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                           required>
                    @error('site_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="site_description" class="block text-sm font-medium text-gray-700 mb-2">
                        Descrição do Site <span class="text-red-500">*</span>
                    </label>
                    <textarea id="site_description" 
                              name="site_description" 
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                              required>{{ old('site_description', $settings['site_description']) }}</textarea>
                    @error('site_description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="site_email" class="block text-sm font-medium text-gray-700 mb-2">
                        E-mail de Contato
                    </label>
                    <input type="email" 
                           id="site_email" 
                           name="site_email" 
                           value="{{ old('site_email', $settings['site_email'] ?? '') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    @error('site_email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="site_phone" class="block text-sm font-medium text-gray-700 mb-2">
                        Telefone de Contato
                    </label>
                    <input type="text" 
                           id="site_phone" 
                           name="site_phone" 
                           value="{{ old('site_phone', $settings['site_phone'] ?? '') }}"
                           placeholder="(11) 99999-9999"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    @error('site_phone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="site_address" class="block text-sm font-medium text-gray-700 mb-2">
                        Endereço da Empresa
                    </label>
                    <textarea id="site_address" 
                              name="site_address" 
                              rows="3"
                              placeholder="Rua, Número, Bairro, Cidade - Estado"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">{{ old('site_address', $settings['site_address'] ?? '') }}</textarea>
                    @error('site_address')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Configurações do Rodapé -->
                <div class="pt-6 border-t border-gray-200">
                    <h4 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                        <i class="bi bi-layout-text-window-reverse mr-2 text-primary"></i>
                        Configurações do Rodapé
                    </h4>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="footer_extra_text" class="block text-sm font-medium text-gray-700 mb-2">
                                Texto Adicional do Rodapé
                            </label>
                            <textarea id="footer_extra_text" 
                                      name="footer_extra_text" 
                                      rows="4"
                                      placeholder="Texto que aparece abaixo do copyright no rodapé..."
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">{{ old('footer_extra_text', $settings['footer_extra_text'] ?? '') }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="bi bi-info-circle mr-1"></i>
                                Este texto aparecerá abaixo do copyright no rodapé do site
                            </p>
                            @error('footer_extra_text')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Configurações de Acesso -->
                <div class="pt-6 border-t border-gray-200">
                    <h4 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                        <i class="bi bi-shield-lock mr-2 text-primary"></i>
                        Configurações de Acesso
                    </h4>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <h5 class="text-sm font-medium text-gray-900">Registro de Administradores</h5>
                                <p class="text-sm text-gray-500">Permitir criação de novas contas de administrador via /admin/register</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" 
                                       name="admin_register_enabled" 
                                       value="1"
                                       {{ old('admin_register_enabled', $settings['admin_register_enabled'] ?? false) ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Botões -->
        <div class="flex justify-end space-x-4 mt-4">
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

    <!-- Card Informativo sobre o Sistema -->
    <div class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 rounded-lg shadow-xl overflow-hidden">
        <div class="p-8">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-4">
                        <div class="bg-primary rounded-lg p-3 mr-4">
                            <i class="bi bi-code-square text-white text-3xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-white">Sistema de Gestão LaserLink</h3>
                            <p class="text-gray-400 text-sm mt-1">Plataforma completa para comunicação visual</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                        <!-- Tecnologias -->
                        <div>
                            <h4 class="text-sm font-semibold text-gray-300 uppercase tracking-wider mb-4 flex items-center">
                                <i class="bi bi-stack mr-2 text-primary"></i>
                                Tecnologias
                            </h4>
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-red-500 rounded-full mr-3"></div>
                                    <span class="text-gray-300">Laravel 12 - PHP Framework</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                                    <span class="text-gray-300">Tailwind CSS - Design System</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                                    <span class="text-gray-300">Alpine.js - Interatividade</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-purple-500 rounded-full mr-3"></div>
                                    <span class="text-gray-300">MySQL - Banco de Dados</span>
                                </div>
                            </div>
                        </div>

                        <!-- Recursos -->
                        <div>
                            <h4 class="text-sm font-semibold text-gray-300 uppercase tracking-wider mb-4 flex items-center">
                                <i class="bi bi-star mr-2 text-primary"></i>
                                Recursos Principais
                            </h4>
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <i class="bi bi-check-circle text-green-500 mr-3"></i>
                                    <span class="text-gray-300">Gestão de Produtos Dinâmica</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="bi bi-check-circle text-green-500 mr-3"></i>
                                    <span class="text-gray-300">Controle de Estoque Avançado</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="bi bi-check-circle text-green-500 mr-3"></i>
                                    <span class="text-gray-300">Sistema de Campos Customizáveis</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="bi bi-check-circle text-green-500 mr-3"></i>
                                    <span class="text-gray-300">Integração com IA (Gemini)</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Laravel Info -->
                    <div class="mt-8 pt-6 border-t border-gray-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-400 text-sm mb-2">Desenvolvido com</p>
                                <div class="flex items-center space-x-4">
                                    <div class="flex items-center">
                                        <svg class="h-8 w-8 mr-2" viewBox="0 0 50 52" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M49.626 11.564a.809.809 0 0 1 .028.209v10.972a.8.8 0 0 1-.402.694l-9.209 5.302V39.25c0 .286-.152.55-.4.694L20.42 51.01c-.044.025-.092.041-.14.058-.018.006-.035.017-.054.022a.805.805 0 0 1-.41 0c-.022-.006-.042-.018-.063-.026-.044-.016-.09-.03-.132-.054L.402 39.944A.801.801 0 0 1 0 39.25V6.334c0-.072.01-.142.028-.21.006-.023.02-.044.028-.067.015-.042.029-.085.051-.124.015-.026.037-.047.055-.071.023-.032.044-.065.071-.093.023-.023.053-.04.079-.06.029-.024.055-.05.088-.069h.001l9.61-5.533a.802.802 0 0 1 .8 0l9.61 5.533h.002c.032.02.059.045.088.068.026.02.055.038.078.06.028.029.048.062.072.094.017.024.04.045.054.071.023.04.036.082.052.124.008.023.022.044.028.068a.809.809 0 0 1 .028.209v20.559l8.008-4.611v-10.51c0-.07.01-.141.028-.208.007-.024.02-.045.028-.068.016-.042.03-.085.052-.124.015-.026.037-.047.054-.071.024-.032.044-.065.072-.093.023-.023.052-.04.078-.06.03-.024.056-.05.088-.069h.001l9.611-5.533a.801.801 0 0 1 .8 0l9.61 5.533c.034.02.06.045.09.068.025.02.054.038.077.06.028.029.048.062.072.094.018.024.04.045.054.071.023.039.036.082.052.124.009.023.022.044.028.068zm-1.574 10.718v-9.124l-3.363 1.936-4.646 2.675v9.124l8.01-4.611zm-9.61 16.505v-9.13l-4.57 2.61-13.05 7.448v9.216l17.62-10.144zM1.602 7.719v31.068L19.22 48.93v-9.214l-9.204-5.209-.003-.002-.004-.002c-.031-.018-.057-.044-.086-.066-.025-.02-.054-.036-.076-.058l-.002-.003c-.026-.025-.044-.056-.066-.084-.02-.027-.044-.05-.06-.078l-.001-.003c-.018-.03-.029-.066-.042-.1-.013-.03-.03-.058-.038-.09v-.001c-.01-.038-.012-.078-.016-.117-.004-.03-.012-.06-.012-.09v-.002-21.481L4.965 9.654 1.602 7.72zm8.81-5.994L2.405 6.334l8.005 4.609 8.006-4.61-8.006-4.608zm4.164 28.764l4.645-2.674V7.719l-3.363 1.936-4.646 2.675v20.096l3.364-1.937zM39.243 7.164l-8.006 4.609 8.006 4.609 8.005-4.61-8.005-4.608zm-.801 10.605l-4.646-2.675-3.363-1.936v9.124l4.645 2.674 3.364 1.937v-9.124zM20.02 38.33l11.743-6.704 5.87-3.35-8-4.606-9.211 5.303-8.395 4.833 7.993 4.524z" fill="#FF2D20"/>
                                        </svg>
                                        <span class="text-white font-semibold text-lg">Laravel 12</span>
                                    </div>
                                    <div class="h-6 w-px bg-gray-600"></div>
                                    <div class="flex items-center text-gray-300">
                                        <i class="bi bi-shield-check text-green-500 mr-2"></i>
                                        <span class="text-sm">Arquitetura Robusta & Segura</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-gray-400 text-sm mb-2">Desenvolvido por</p>
                                <a href="https://www.nicedesigns.com.br" target="_blank" rel="noopener noreferrer" 
                                   class="inline-flex items-center space-x-2 bg-white/10 hover:bg-white/20 px-4 py-2 rounded-lg transition-all group">
                                    <i class="bi bi-heart-fill text-red-500 group-hover:scale-110 transition-transform"></i>
                                    <span class="text-white font-semibold">Nice Designs</span>
                                    <i class="bi bi-box-arrow-up-right text-gray-400 text-xs group-hover:text-white transition-colors"></i>
                                </a>
                                <p class="text-gray-500 text-xs mt-2">nicedesigns.com.br</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Badge de Versão -->
                <div class="ml-6 flex-shrink-0">
                    <div class="bg-primary/20 border-2 border-primary rounded-lg p-4 text-center min-w-[120px]">
                        <i class="bi bi-trophy text-primary text-3xl mb-2"></i>
                        <p class="text-xs text-gray-400 uppercase tracking-wider">Versão</p>
                        <p class="text-2xl font-bold text-white mt-1">2025</p>
                        <div class="mt-3 pt-3 border-t border-gray-700">
                            <div class="flex items-center justify-center text-xs text-green-400">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                                Online
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8 pt-6 border-t border-gray-700">
                <div class="bg-white/5 rounded-lg p-4 hover:bg-white/10 transition-colors">
                    <div class="flex items-center mb-2">
                        <i class="bi bi-lightning-charge text-yellow-500 text-xl mr-2"></i>
                        <h5 class="font-semibold text-white">Alta Performance</h5>
                    </div>
                    <p class="text-gray-400 text-sm">Sistema otimizado para processar grandes volumes de dados com rapidez</p>
                </div>

                <div class="bg-white/5 rounded-lg p-4 hover:bg-white/10 transition-colors">
                    <div class="flex items-center mb-2">
                        <i class="bi bi-lock-fill text-blue-500 text-xl mr-2"></i>
                        <h5 class="font-semibold text-white">Segurança Avançada</h5>
                    </div>
                    <p class="text-gray-400 text-sm">Proteção contra CSRF, XSS e SQL Injection com autenticação robusta</p>
                </div>

                <div class="bg-white/5 rounded-lg p-4 hover:bg-white/10 transition-colors">
                    <div class="flex items-center mb-2">
                        <i class="bi bi-arrows-angle-expand text-purple-500 text-xl mr-2"></i>
                        <h5 class="font-semibold text-white">100% Escalável</h5>
                    </div>
                    <p class="text-gray-400 text-sm">Arquitetura modular preparada para crescimento do seu negócio</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function settingsForm() {
    return {
        loading: false
    }
}
</script>
@endsection

