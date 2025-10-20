@php
    $siteName = App\Models\Setting::get('site_name', 'Laser Link');
    $siteDescription = App\Models\Setting::get('site_description', 'Especialistas em comunicação visual e produtos personalizados');
    $sitePhone = App\Models\Setting::get('site_phone', '');
    $siteEmail = App\Models\Setting::get('site_email', '');
    $siteAddress = App\Models\Setting::get('site_address', '');
    $whatsappNumber = App\Models\Setting::get('whatsapp_number', '');
    $footerLogoPath = App\Models\Setting::get('footer_logo_path', '');
    $footerExtraText = App\Models\Setting::get('footer_extra_text', '');
    
    // Redes Sociais
    $socialMedia = [
        'facebook' => [
            'url' => App\Models\Setting::get('social_facebook', ''),
            'icon' => 'bi-facebook',
            'color' => 'hover:bg-blue-600'
        ],
        'instagram' => [
            'url' => App\Models\Setting::get('social_instagram', ''),
            'icon' => 'bi-instagram',
            'color' => 'hover:bg-gradient-to-r hover:from-purple-600 hover:to-pink-600'
        ],
        'twitter' => [
            'url' => App\Models\Setting::get('social_twitter', ''),
            'icon' => 'bi-twitter-x',
            'color' => 'hover:bg-black'
        ],
        'linkedin' => [
            'url' => App\Models\Setting::get('social_linkedin', ''),
            'icon' => 'bi-linkedin',
            'color' => 'hover:bg-blue-700'
        ],
        'youtube' => [
            'url' => App\Models\Setting::get('social_youtube', ''),
            'icon' => 'bi-youtube',
            'color' => 'hover:bg-red-600'
        ],
        'tiktok' => [
            'url' => App\Models\Setting::get('social_tiktok', ''),
            'icon' => 'bi-tiktok',
            'color' => 'hover:bg-black'
        ],
        'pinterest' => [
            'url' => App\Models\Setting::get('social_pinterest', ''),
            'icon' => 'bi-pinterest',
            'color' => 'hover:bg-red-700'
        ],
    ];
    
    // Filtrar apenas redes sociais configuradas
    $socialMedia = array_filter($socialMedia, function($social) {
        return !empty($social['url']);
    });
    
    // Gerar URL correta do logo do rodapé
    $footerLogoUrl = null;
    if ($footerLogoPath) {
        if (filter_var($footerLogoPath, FILTER_VALIDATE_URL)) {
            $footerLogoUrl = $footerLogoPath;
        } elseif (str_starts_with($footerLogoPath, '/')) {
            $footerLogoUrl = $footerLogoPath;
        } else {
            $footerLogoUrl = url('images/' . $footerLogoPath);
        }
    }
@endphp

<footer class="bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8">
            
            <!-- Informações da Empresa -->
            <div class="col-span-1 md:col-span-2 lg:col-span-2">
                @if($footerLogoUrl)
                    <!-- Logo do Rodapé -->
                    <div class="mb-4">
                        <img src="{{ $footerLogoUrl }}" 
                             alt="{{ $siteName }}" 
                             class="h-16 max-w-[200px] object-contain"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <!-- Fallback caso a imagem não carregue -->
                        <div class="hidden items-center space-x-2">
                            <div class="h-8 w-8 bg-red-600 rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold text-sm">LL</span>
                            </div>
                            <span class="text-xl font-bold">{{ $siteName }}</span>
                        </div>
                    </div>
                @else
                    <!-- Logo padrão (ícone + nome) -->
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="h-8 w-8 bg-red-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-sm">LL</span>
                        </div>
                        <span class="text-xl font-bold">{{ $siteName }}</span>
                    </div>
                @endif
                
                <p class="text-gray-300 mb-4 max-w-md">{{ $siteDescription }}</p>
                
                @if($sitePhone || $siteEmail || $siteAddress)
                    <div class="space-y-2">
                        @if($sitePhone)
                            <div class="flex items-center space-x-2">
                                <i class="bi bi-telephone text-red-400"></i>
                                <span class="text-gray-300">{{ $sitePhone }}</span>
                            </div>
                        @endif
                        
                        @if($siteEmail)
                            <div class="flex items-center space-x-2">
                                <i class="bi bi-envelope text-red-400"></i>
                                <a href="mailto:{{ $siteEmail }}" class="text-gray-300 hover:text-white">{{ $siteEmail }}</a>
                            </div>
                        @endif
                        
                        @if($siteAddress)
                            <div class="flex items-center space-x-2">
                                <i class="bi bi-geo-alt text-red-400"></i>
                                <span class="text-gray-300">{{ $siteAddress }}</span>
                            </div>
                        @endif
                    </div>
                @endif
                
                <!-- Redes Sociais -->
                @if(count($socialMedia) > 0)
                    <div class="mt-6">
                        <h4 class="text-sm font-semibold mb-3 text-gray-200">Siga-nos</h4>
                        <div class="flex items-center gap-3">
                            @foreach($socialMedia as $name => $social)
                                <a href="{{ $social['url'] }}" 
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="flex items-center justify-center w-10 h-10 bg-gray-800 rounded-full text-white transition-all duration-300 {{ $social['color'] }} hover:scale-110 hover:shadow-lg"
                                   title="{{ ucfirst($name) }}">
                                    <i class="bi {{ $social['icon'] }} text-lg"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Links Rápidos -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Links Rápidos</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('store.index') }}" 
                           class="text-gray-300 hover:text-white transition-colors flex items-center">
                            <i class="bi bi-house-door mr-2 text-red-400"></i>
                            Início
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('store.products') }}" 
                           class="text-gray-300 hover:text-white transition-colors flex items-center">
                            <i class="bi bi-box-seam mr-2 text-red-400"></i>
                            Produtos
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('blog.index') }}" 
                           class="text-gray-300 hover:text-white transition-colors flex items-center">
                            <i class="bi bi-newspaper mr-2 text-red-400"></i>
                            Blog
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('store.cart') }}" 
                           class="text-gray-300 hover:text-white transition-colors flex items-center">
                            <i class="bi bi-cart mr-2 text-red-400"></i>
                            Carrinho
                        </a>
                    </li>
                    @auth
                        <li>
                            <a href="{{ route('store.user-orders') }}" 
                               class="text-gray-300 hover:text-white transition-colors flex items-center">
                                <i class="bi bi-receipt mr-2 text-red-400"></i>
                                Meus Pedidos
                            </a>
                        </li>
                    @endauth
                    <li>
                        <a href="{{ route('contact.index') }}" 
                           class="text-gray-300 hover:text-white transition-colors flex items-center">
                            <i class="bi bi-envelope mr-2 text-red-400"></i>
                            Contato
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Categorias -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Categorias</h3>
                <ul class="space-y-2">
                    @php
                        $categories = App\Models\Category::where('is_active', true)->orderBy('name')->limit(5)->get();
                    @endphp
                    @foreach($categories as $category)
                        <li>
                            <a href="{{ route('store.category', $category->slug) }}" 
                               class="text-gray-300 hover:text-white transition-colors flex items-center">
                                <i class="bi bi-tag mr-2 text-red-400"></i>
                                {{ $category->name }}
                            </a>
                        </li>
                    @endforeach
                    <li>
                        <a href="{{ route('store.products') }}" 
                           class="text-red-400 hover:text-red-300 transition-colors flex items-center font-medium">
                            <i class="bi bi-arrow-right mr-2"></i>
                            Ver todos os produtos
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Institucional -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Institucional</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('page.show', 'sobre-nos') }}" 
                           class="text-gray-300 hover:text-white transition-colors flex items-center">
                            <i class="bi bi-info-circle mr-2 text-red-400"></i>
                            Sobre Nós
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('page.show', 'politica-de-privacidade') }}" 
                           class="text-gray-300 hover:text-white transition-colors flex items-center">
                            <i class="bi bi-shield-check mr-2 text-red-400"></i>
                            Política de Privacidade
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('page.show', 'termos-de-uso') }}" 
                           class="text-gray-300 hover:text-white transition-colors flex items-center">
                            <i class="bi bi-file-text mr-2 text-red-400"></i>
                            Termos de Uso
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('page.show', 'politica-de-troca-e-devolucao') }}" 
                           class="text-gray-300 hover:text-white transition-colors flex items-center">
                            <i class="bi bi-arrow-left-right mr-2 text-red-400"></i>
                            Trocas e Devoluções
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('page.show', 'politica-de-entrega') }}" 
                           class="text-gray-300 hover:text-white transition-colors flex items-center">
                            <i class="bi bi-truck mr-2 text-red-400"></i>
                            Política de Entrega
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('page.show', 'perguntas-frequentes') }}" 
                           class="text-gray-300 hover:text-white transition-colors flex items-center">
                            <i class="bi bi-question-circle mr-2 text-red-400"></i>
                            FAQ
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- WhatsApp Flutuante -->
        @if($whatsappNumber)
            <div class="fixed bottom-6 md:bottom-6 right-6 z-40 transition-all duration-300" style="bottom: calc(4rem + 64px);">
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $whatsappNumber) }}" 
                   target="_blank"
                   class="z-998 flex items-center justify-center bg-green-500 hover:bg-green-600 text-white w-14 h-14 rounded-full shadow-lg transition-all duration-300 hover:scale-110 active:scale-95">
                    <i class="bi bi-whatsapp text-3xl"></i>
                </a>
            </div>
        @endif

        <!-- Copyright -->
        <div class="border-t border-gray-800 mt-8 pt-8 text-center">
            <p class="text-gray-400">
                © {{ date('Y') }} {{ $siteName }}. Todos os direitos reservados.
            </p>
            
            @if($footerExtraText)
                <div class="mt-4 text-gray-300 text-sm leading-relaxed">
                    {!! nl2br(e($footerExtraText)) !!}
                </div>
            @endif
            
            <p class="text-gray-500 text-sm mt-4">
                Desenvolvido com <i class="bi bi-cup-hot text-red-400"></i> by <a href="https://www.nicedesigns.com.br" target="_blank" class="text-red-400 hover:text-red-300 transition-colors">Nice Designs</a>
            </p>
        </div>
    </div>
</footer>