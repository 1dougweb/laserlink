@extends('admin.layout')

@section('title', 'Configurações SEO - Laser Link')
@section('page-title', 'Configurações SEO')

@section('content')
<div class="space-y-6">
    <form method="POST" action="{{ route('admin.settings.seo.update') }}" x-data="seoForm()">
        @csrf
        @method('PUT')
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center mb-6">
                <i class="bi bi-search text-primary text-xl mr-3"></i>
                <h3 class="text-lg font-semibold text-gray-900">Configurações de SEO</h3>
            </div>
            
            <div class="space-y-6">
                <div>
                    <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">
                        Título da Página (Meta Title)
                    </label>
                    <div class="relative">
                        <input type="text" 
                               id="meta_title" 
                               name="meta_title" 
                               value="{{ old('meta_title', $settings['meta_title'] ?? '') }}"
                               maxlength="60"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                               @input="updateCharacterCount('meta_title', 60)">
                        <div class="absolute right-3 top-2 text-xs text-gray-500">
                            <span x-text="metaTitleCount"></span>/60
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">Recomendado: máximo 60 caracteres</p>
                    @error('meta_title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">
                        Descrição da Página (Meta Description)
                    </label>
                    <div class="relative">
                        <textarea id="meta_description" 
                                  name="meta_description" 
                                  rows="3"
                                  maxlength="160"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                  @input="updateCharacterCount('meta_description', 160)">{{ old('meta_description', $settings['meta_description'] ?? '') }}</textarea>
                        <div class="absolute right-3 top-2 text-xs text-gray-500">
                            <span x-text="metaDescriptionCount"></span>/160
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">Recomendado: máximo 160 caracteres</p>
                    @error('meta_description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-2">
                        Palavras-chave (Meta Keywords)
                    </label>
                    <input type="text" 
                           id="meta_keywords" 
                           name="meta_keywords" 
                           value="{{ old('meta_keywords', $settings['meta_keywords'] ?? '') }}"
                           placeholder="acrílicos, troféus, medalhas, placas, letreiros"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    <p class="text-sm text-gray-500 mt-1">Separe as palavras-chave por vírgula</p>
                    @error('meta_keywords')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="og_title" class="block text-sm font-medium text-gray-700 mb-2">
                        Título para Redes Sociais (Open Graph)
                    </label>
                    <input type="text" 
                           id="og_title" 
                           name="og_title" 
                           value="{{ old('og_title', $settings['og_title'] ?? '') }}"
                           maxlength="60"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    @error('og_title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="og_description" class="block text-sm font-medium text-gray-700 mb-2">
                        Descrição para Redes Sociais (Open Graph)
                    </label>
                    <textarea id="og_description" 
                              name="og_description" 
                              rows="3"
                              maxlength="160"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">{{ old('og_description', $settings['og_description'] ?? '') }}</textarea>
                    @error('og_description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="google_analytics" class="block text-sm font-medium text-gray-700 mb-2">
                        Google Analytics ID
                    </label>
                    <input type="text" 
                           id="google_analytics" 
                           name="google_analytics" 
                           value="{{ old('google_analytics', $settings['google_analytics'] ?? '') }}"
                           placeholder="G-XXXXXXXXXX"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    <p class="text-sm text-gray-500 mt-1">Formato: G-XXXXXXXXXX</p>
                    @error('google_analytics')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="google_search_console" class="block text-sm font-medium text-gray-700 mb-2">
                        Google Search Console
                    </label>
                    <input type="text" 
                           id="google_search_console" 
                           name="google_search_console" 
                           value="{{ old('google_search_console', $settings['google_search_console'] ?? '') }}"
                           placeholder="google-site-verification=XXXXXXXXXX"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    <p class="text-sm text-gray-500 mt-1">Código de verificação do Google Search Console</p>
                    @error('google_search_console')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Schema.org - Informações da Empresa -->
        <div class="bg-white rounded-lg shadow p-6 mt-6">
            <div class="flex items-center mb-6">
                <i class="bi bi-building text-primary text-xl mr-3"></i>
                <h3 class="text-lg font-semibold text-gray-900">Schema.org - Informações da Empresa</h3>
            </div>
            
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="schema_company_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nome da Empresa *
                        </label>
                        <input type="text" 
                               id="schema_company_name" 
                               name="schema_company_name" 
                               value="{{ old('schema_company_name', $settings['schema_company_name'] ?? 'Laser Link') }}"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        @error('schema_company_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="schema_phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Telefone *
                        </label>
                        <input type="text" 
                               id="schema_phone" 
                               name="schema_phone" 
                               value="{{ old('schema_phone', $settings['schema_phone'] ?? '') }}"
                               placeholder="+55-11-99999-9999"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        <p class="text-sm text-gray-500 mt-1">Formato: +55-11-99999-9999</p>
                        @error('schema_phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Endereço Completo *
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <input type="text" 
                                   name="schema_street" 
                                   value="{{ old('schema_street', $settings['schema_street'] ?? '') }}"
                                   placeholder="Rua, Avenida, etc."
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>
                        <div>
                            <input type="text" 
                                   name="schema_city" 
                                   value="{{ old('schema_city', $settings['schema_city'] ?? '') }}"
                                   placeholder="Cidade"
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <input type="text" 
                                   name="schema_state" 
                                   value="{{ old('schema_state', $settings['schema_state'] ?? '') }}"
                                   placeholder="UF"
                                   maxlength="2"
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                            <input type="text" 
                                   name="schema_postal_code" 
                                   value="{{ old('schema_postal_code', $settings['schema_postal_code'] ?? '') }}"
                                   placeholder="CEP"
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="schema_facebook" class="block text-sm font-medium text-gray-700 mb-2">
                            Facebook URL
                        </label>
                        <input type="url" 
                               id="schema_facebook" 
                               name="schema_facebook" 
                               value="{{ old('schema_facebook', $settings['schema_facebook'] ?? '') }}"
                               placeholder="https://www.facebook.com/suapagina"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        @error('schema_facebook')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="schema_instagram" class="block text-sm font-medium text-gray-700 mb-2">
                            Instagram URL
                        </label>
                        <input type="url" 
                               id="schema_instagram" 
                               name="schema_instagram" 
                               value="{{ old('schema_instagram', $settings['schema_instagram'] ?? '') }}"
                               placeholder="https://www.instagram.com/seuperfil"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        @error('schema_instagram')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Horário de Funcionamento
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs text-gray-600 mb-1 block">Abertura</label>
                            <input type="time" 
                                   name="schema_opening_hours_start" 
                                   value="{{ old('schema_opening_hours_start', $settings['schema_opening_hours_start'] ?? '08:00') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>
                        <div>
                            <label class="text-xs text-gray-600 mb-1 block">Fechamento</label>
                            <input type="time" 
                                   name="schema_opening_hours_end" 
                                   value="{{ old('schema_opening_hours_end', $settings['schema_opening_hours_end'] ?? '18:00') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Dias da semana (Segunda a Sexta-feira)</p>
                </div>

                <div>
                    <label for="schema_price_range" class="block text-sm font-medium text-gray-700 mb-2">
                        Faixa de Preço
                    </label>
                    <select id="schema_price_range" 
                            name="schema_price_range" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="$" {{ ($settings['schema_price_range'] ?? '$$') == '$' ? 'selected' : '' }}>$ - Econômico</option>
                        <option value="$$" {{ ($settings['schema_price_range'] ?? '$$') == '$$' ? 'selected' : '' }}>$$ - Moderado</option>
                        <option value="$$$" {{ ($settings['schema_price_range'] ?? '$$') == '$$$' ? 'selected' : '' }}>$$$ - Alto</option>
                        <option value="$$$$" {{ ($settings['schema_price_range'] ?? '$$') == '$$$$' ? 'selected' : '' }}>$$$$ - Muito Alto</option>
                    </select>
                    <p class="text-sm text-gray-500 mt-1">Indicação de preço para o Google</p>
                </div>
            </div>
        </div>

        <!-- Preview do SEO -->
        <div class="bg-white rounded-lg mt-6 shadow p-6">
            <h4 class="text-md font-medium text-gray-900 mb-4"><i class="bi bi-google text-blue-600 mr-2"></i> Preview dos Resultados de Busca</h4>
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="space-y-2">
                    <h5 class="text-blue-600 text-lg hover:underline cursor-pointer" x-text="metaTitle || 'Título da página aparecerá aqui'"></h5>
                    <p class="text-green-600 text-sm" x-text="window.location.origin"></p>
                    <p class="text-gray-600 text-sm" x-text="metaDescription || 'Descrição da página aparecerá aqui'"></p>
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
</div>

<script>
function seoForm() {
    return {
        loading: false,
        metaTitle: '{{ old('meta_title', $settings['meta_title'] ?? '') }}',
        metaDescription: '{{ old('meta_description', $settings['meta_description'] ?? '') }}',
        metaTitleCount: 0,
        metaDescriptionCount: 0,

        init() {
            this.updateCharacterCount('meta_title', 60);
            this.updateCharacterCount('meta_description', 160);
        },

        updateCharacterCount(field, maxLength) {
            const value = this[field] || '';
            const count = value.length;
            
            if (field === 'meta_title') {
                this.metaTitleCount = count;
                this.metaTitle = value;
            } else if (field === 'meta_description') {
                this.metaDescriptionCount = count;
                this.metaDescription = value;
            }
        }
    }
}
</script>
@endsection

