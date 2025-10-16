@extends('admin.layout')

@section('title', 'Configurações - Sitemap')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Gerador de Sitemap</h1>
        <p class="text-gray-600 mt-1">Gere e gerencie o sitemap.xml para Google Search Console</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Informações do Sitemap -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center mb-6">
                <i class="bi bi-file-earmark-code text-primary text-xl mr-3"></i>
                <h3 class="text-lg font-semibold text-gray-900">Status do Sitemap</h3>
            </div>

            @if($sitemapData['exists'])
                <div class="space-y-4">
                    <div class="flex items-center justify-between py-3 border-b border-gray-200">
                        <span class="text-sm font-medium text-gray-700">Status</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <i class="bi bi-check-circle mr-1"></i>Gerado
                        </span>
                    </div>

                    <div class="flex items-center justify-between py-3 border-b border-gray-200">
                        <span class="text-sm font-medium text-gray-700">Última Atualização</span>
                        <span class="text-sm text-gray-900">{{ $sitemapData['last_generated'] }}</span>
                    </div>

                    <div class="flex items-center justify-between py-3 border-b border-gray-200">
                        <span class="text-sm font-medium text-gray-700">Total de URLs</span>
                        <span class="text-sm font-semibold text-primary">{{ $sitemapData['url_count'] }} páginas</span>
                    </div>

                    <div class="flex items-center justify-between py-3 border-b border-gray-200">
                        <span class="text-sm font-medium text-gray-700">Tamanho do Arquivo</span>
                        <span class="text-sm text-gray-900">{{ $sitemapData['file_size'] }}</span>
                    </div>

                    <div class="flex items-center justify-between py-3">
                        <span class="text-sm font-medium text-gray-700">URL do Sitemap</span>
                        <a href="{{ $sitemapData['sitemap_url'] }}" target="_blank" class="text-sm text-primary hover:text-red-700 font-medium">
                            Ver sitemap.xml <i class="bi bi-box-arrow-up-right ml-1"></i>
                        </a>
                    </div>
                </div>
            @else
                <div class="text-center py-8">
                    <i class="bi bi-file-earmark-x text-6xl text-gray-300 mb-4"></i>
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Sitemap não encontrado</h4>
                    <p class="text-sm text-gray-600 mb-6">Clique no botão abaixo para gerar seu primeiro sitemap</p>
                </div>
            @endif

            <!-- Botão Gerar/Regenerar -->
            <form action="{{ route('admin.settings.sitemap.generate') }}" method="POST" class="mt-6">
                @csrf
                <button type="submit" 
                        class="w-full px-6 py-3 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors font-semibold shadow-md hover:shadow-lg">
                    <i class="bi bi-arrow-clockwise mr-2"></i>{{ $sitemapData['exists'] ? 'Regenerar Sitemap' : 'Gerar Sitemap' }}
                </button>
            </form>
        </div>

        <!-- Instruções -->
        <div class="space-y-6">
            
            <!-- Google Search Console -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center mb-4">
                    <i class="bi bi-google text-xl mr-3" style="color: #4285f4;"></i>
                    <h3 class="text-lg font-semibold text-gray-900">Google Search Console</h3>
                </div>
                
                <div class="space-y-3 text-sm text-gray-700">
                    <p class="font-medium">Como adicionar o sitemap:</p>
                    <ol class="list-decimal list-inside space-y-3 ml-2">
                        <li>Acesse <a href="https://search.google.com/search-console" target="_blank" class="text-primary hover:underline">Google Search Console</a></li>
                        <li>Selecione sua propriedade (site)</li>
                        <li>Vá em "Sitemaps" no menu lateral</li>
                        <li>
                            <span class="block mb-2">Cole a URL do sitemap:</span>
                            <div x-data="sitemapCopy()" class="relative">
                                <div class="flex items-center gap-2 bg-gray-900 text-white px-4 py-3 rounded-lg font-mono text-xs">
                                    <input type="text" 
                                           x-ref="sitemapUrl"
                                           value="{{ $sitemapData['sitemap_url'] }}" 
                                           readonly
                                           class="flex-1 bg-transparent border-none outline-none text-white break-all cursor-text">
                                    <button @click="copyToClipboard()" 
                                            class="flex-shrink-0 px-3 py-1.5 bg-primary hover:bg-red-700 rounded transition-colors flex items-center gap-2 whitespace-nowrap">
                                        <i class="bi" :class="copied ? 'bi-check-lg' : 'bi-clipboard'"></i>
                                        <span x-text="copied ? 'Copiado!' : 'Copiar'"></span>
                                    </button>
                                </div>
                                <div x-show="copied" 
                                     x-transition
                                     class="absolute -top-8 right-0 bg-green-600 text-white text-xs px-3 py-1 rounded shadow-lg">
                                    ✓ URL copiada!
                                </div>
                            </div>
                        </li>
                        <li>Clique em "Enviar"</li>
                    </ol>
                </div>
            </div>

            <!-- O que está incluído -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <h4 class="text-sm font-semibold text-blue-900 mb-3">
                    <i class="bi bi-info-circle mr-1"></i>O que está incluído no sitemap:
                </h4>
                <ul class="space-y-2 text-sm text-blue-800">
                    <li class="flex items-center">
                        <i class="bi bi-check2 text-blue-600 mr-2"></i>
                        Página inicial e páginas principais
                    </li>
                    <li class="flex items-center">
                        <i class="bi bi-check2 text-blue-600 mr-2"></i>
                        Todos os produtos ativos
                    </li>
                    <li class="flex items-center">
                        <i class="bi bi-check2 text-blue-600 mr-2"></i>
                        Todas as categorias ativas
                    </li>
                    <li class="flex items-center">
                        <i class="bi bi-check2 text-blue-600 mr-2"></i>
                        Posts do blog publicados
                    </li>
                </ul>
            </div>

            <!-- Dicas -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                <h4 class="text-sm font-semibold text-yellow-900 mb-3">
                    <i class="bi bi-lightbulb mr-1"></i>Dicas importantes:
                </h4>
                <ul class="space-y-2 text-sm text-yellow-800">
                    <li>• Regenere o sitemap sempre que adicionar/editar produtos ou categorias</li>
                    <li>• O Google pode levar alguns dias para processar o sitemap</li>
                    <li>• Verifique regularmente no Search Console se há erros</li>
                </ul>
            </div>

        </div>
    </div>

</div>

@push('scripts')
<script>
function sitemapCopy() {
    return {
        copied: false,
        
        copyToClipboard() {
            const input = this.$refs.sitemapUrl;
            
            // Método 1: Tentar usar Clipboard API (moderno)
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(input.value)
                    .then(() => {
                        this.showCopied();
                    })
                    .catch(() => {
                        this.fallbackCopy(input);
                    });
            } else {
                // Método 2: Fallback para navegadores antigos ou HTTP
                this.fallbackCopy(input);
            }
        },
        
        fallbackCopy(input) {
            try {
                // Selecionar o texto
                input.select();
                input.setSelectionRange(0, 99999); // Para mobile
                
                // Copiar usando execCommand
                document.execCommand('copy');
                
                this.showCopied();
            } catch (err) {
                console.error('Erro ao copiar:', err);
                alert('Erro ao copiar. Por favor, copie manualmente: ' + input.value);
            }
        },
        
        showCopied() {
            this.copied = true;
            setTimeout(() => {
                this.copied = false;
            }, 2000);
        }
    }
}
</script>
@endpush
@endsection

