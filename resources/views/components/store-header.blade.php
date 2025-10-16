@php
    $siteName = App\Models\Setting::get('site_name', 'Laser Link');
    $rawSiteLogo = App\Models\Setting::get('site_logo_path');
    $rawMainLogo = App\Models\Setting::get('logo_path');

    $logoUrl = null;
    if ($rawSiteLogo && Illuminate\Support\Facades\Storage::disk('public')->exists($rawSiteLogo)) {
        $logoUrl = url('images/' . $rawSiteLogo);
    } elseif ($rawMainLogo && Illuminate\Support\Facades\Storage::disk('public')->exists($rawMainLogo)) {
        $logoUrl = url('images/' . $rawMainLogo);
    }
    
    $user = Auth::user();
    $isAdmin = false;
    if ($user) {
        $isAdmin = $user->id === 1 || $user->email === 'admin@laserlink.com' || 
                   (method_exists($user, 'hasRole') && $user->hasRole(['admin', 'vendedor']));
    }
@endphp

<div x-data="storeHeader()" x-init="init()">
    {{-- Header Principal --}}
    <header class="bg-white shadow-sm border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            
                {{-- Menu Mobile Button --}}
                <button @click="toggleMenu()" class="md:hidden p-2 text-gray-600 hover:text-gray-900">
                    <i class="bi bi-list text-2xl"></i>
                </button>
                
                {{-- Logo --}}
                <div class="flex-shrink-0 flex items-center">
                <a href="{{ route('store.index') }}" class="flex items-center space-x-2">
                    @if($logoUrl)
                            <img src="{{ $logoUrl }}" alt="{{ $siteName }}" class="h-10 w-auto">
                    @else
                        <div class="h-10 w-10 bg-red-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-lg">LL</span>
                        </div>
                        <span class="hidden sm:block text-xl font-bold text-gray-900">{{ $siteName }}</span>
                    @endif
                </a>
            </div>

                {{-- Search Bar (Desktop) --}}
                <div class="hidden md:flex flex-1 max-w-lg mx-8" x-data="searchBar()">
                    <div class="relative w-full">
                        <input type="text" 
                               x-model="searchQuery"
                               @input.debounce.300ms="search()"
                               @focus="showResults = true"
                               @keydown.escape="showResults = false"
                               placeholder="Buscar produtos..."
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        
                        {{-- Loading Spinner --}}
                        <div x-show="searching" class="absolute right-3 top-1/2 transform -translate-y-1/2">
                            <i class="bi bi-arrow-repeat animate-spin text-gray-400"></i>
                        </div>
                        
                        {{-- Results Dropdown --}}
                        <div x-show="showResults && searchResults.length > 0" 
                             @click.away="showResults = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             class="absolute top-full mt-2 w-full bg-white border border-gray-200 rounded-lg shadow-xl z-50 max-h-96 overflow-y-auto">
                            <template x-for="product in searchResults" :key="product.id">
                                <a :href="`/produto/${product.slug}`" 
                                   class="flex items-center p-3 hover:bg-gray-50 border-b border-gray-100 last:border-b-0">
                                    <img :src="product.image_url" 
                                         :alt="product.name"
                                         class="w-12 h-12 object-cover rounded-lg mr-3"
                                         onerror="this.src='{{ url('images/general/callback-image.svg') }}'">
                                    <div class="flex-1 min-w-0">
                                        <div class="text-sm font-medium text-gray-900 truncate" x-text="product.name"></div>
                                        <div class="text-xs text-gray-500" x-text="product.category?.name"></div>
                                        <div class="text-sm font-bold text-primary" x-text="product.price_formatted"></div>
                                    </div>
                                </a>
                            </template>
                        </div>
                        
                        {{-- No Results --}}
                        <div x-show="showResults && searchQuery.length >= 2 && searchResults.length === 0 && !searching"
                             @click.away="showResults = false"
                             class="absolute top-full mt-2 w-full bg-white border border-gray-200 rounded-lg shadow-xl z-50 p-4 text-center text-gray-500 text-sm">
                            <i class="bi bi-search text-2xl mb-2"></i>
                            <div>Nenhum produto encontrado</div>
                        </div>
                    </div>
                </div>

                {{-- Desktop Navigation --}}
                <div class="hidden md:flex items-center space-x-4">
                    {{-- Account Dropdown --}}
                    <div x-data="{ open: false }" class="relative">
                        @auth
                            <button @click="open = !open" class="flex items-center space-x-2 p-2 text-gray-600 hover:text-gray-900">
                                <i class="bi bi-person-circle text-xl"></i>
                                <span class="text-sm font-medium">{{ $user->name }}</span>
                                <i class="bi bi-chevron-down text-xs"></i>
                            </button>
                            
                            <div x-show="open" @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
                                @if($isAdmin)
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="bi bi-speedometer2 mr-2"></i>Painel Admin
                                    </a>
                                @else
                                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="bi bi-house-door mr-2"></i>Minha Conta
                                    </a>
                                @endif
                                <a href="{{ route('store.user-orders') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="bi bi-box-seam mr-2"></i>Meus Pedidos
                                </a>
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="bi bi-person-gear mr-2"></i>Perfil
                                </a>
                                <hr>
                                <form method="POST" action="{{ $isAdmin ? route('admin.logout') : route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="bi bi-box-arrow-right mr-2"></i>Sair
                                    </button>
                                </form>
                            </div>
                        @endauth
                        
                        @guest
                            <a href="{{ route('login') }}" class="flex items-center space-x-2 p-2 text-gray-600 hover:text-gray-900">
                                <i class="bi bi-person-circle text-xl"></i>
                                <span class="text-sm font-medium">Entrar</span>
                            </a>
                        @endguest
                    </div>

                    {{-- Favorites --}}
                    <a href="{{ route('store.favorites') }}" class="relative p-2 text-gray-600 hover:text-red-500">
                        <i class="bi bi-heart text-xl"></i>
                        <span x-show="favoritesCount > 0" x-text="favoritesCount" 
                              class="absolute -top-1 -right-1 bg-red-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"></span>
                    </a>

                    {{-- Cart --}}
                    <button @click="openCart()" class="relative p-2 text-gray-600 hover:text-red-600">
                        <i class="bi bi-cart3 text-xl"></i>
                        <span x-show="cartCount > 0" x-text="cartCount" 
                              class="absolute -top-1 -right-1 bg-red-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"></span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    {{-- Tab Bar Mobile (Bottom Navigation) - Apenas Mobile --}}
    <nav class="lg:hidden fixed bottom-0 left-0 right-0 z-40 bg-white border-t border-gray-200 shadow-lg safe-area-inset-bottom">
        <div class="grid grid-cols-5 h-16" style="padding-bottom: env(safe-area-inset-bottom, 0);">
            {{-- Home --}}
            <a href="{{ route('store.index') }}" 
               :class="isCurrentPage('store.index') ? 'text-red-600' : 'text-gray-600'"
               class="tab-item flex flex-col items-center justify-center space-y-1 transition-all duration-200 active:scale-95">
                <i class="bi bi-house-door-fill text-2xl" x-show="isCurrentPage('store.index')"></i>
                <i class="bi bi-house-door text-2xl" x-show="!isCurrentPage('store.index')"></i>
                <span class="text-[10px] font-semibold">Início</span>
            </a>
            
            {{-- Products --}}
            <a href="{{ route('store.products') }}" 
               :class="isCurrentPage('store.products') ? 'text-red-600' : 'text-gray-600'"
               class="tab-item flex flex-col items-center justify-center space-y-1 transition-all duration-200 active:scale-95">
                <i class="bi bi-grid-3x3-gap-fill text-2xl" x-show="isCurrentPage('store.products')"></i>
                <i class="bi bi-grid-3x3-gap text-2xl" x-show="!isCurrentPage('store.products')"></i>
                <span class="text-[10px] font-semibold">Produtos</span>
            </a>
            
            {{-- Cart --}}
            <button @click="openCart()" 
                    class="tab-item flex flex-col items-center justify-center space-y-1 transition-all duration-200 active:scale-95 relative"
                    :class="cartOpen ? 'text-red-600 bg-red-50' : 'text-gray-600'">
                <div class="relative">
                    <i class="bi bi-cart-fill text-2xl" x-show="cartOpen"></i>
                    <i class="bi bi-cart3 text-2xl" x-show="!cartOpen"></i>
                    <span x-show="cartCount > 0" x-text="cartCount"
                          x-transition
                          class="absolute -top-2 -right-3 bg-red-600 text-white text-[9px] rounded-full h-4 min-w-[16px] px-1 flex items-center justify-center font-bold shadow-md"></span>
                </div>
                <span class="text-[10px] font-semibold">Carrinho</span>
            </button>
            
            {{-- Favorites --}}
            <a href="{{ route('store.favorites') }}" 
               :class="isCurrentPage('store.favorites') ? 'text-red-600' : 'text-gray-600'"
               class="tab-item flex flex-col items-center justify-center space-y-1 transition-all duration-200 active:scale-95 relative">
                <div class="relative">
                    <i class="bi bi-heart-fill text-2xl" x-show="isCurrentPage('store.favorites')"></i>
                    <i class="bi bi-heart text-2xl" x-show="!isCurrentPage('store.favorites')"></i>
                    <span x-show="favoritesCount > 0" x-text="favoritesCount"
                          x-transition
                          class="absolute -top-2 -right-3 bg-red-600 text-white text-[9px] rounded-full h-4 min-w-[16px] px-1 flex items-center justify-center font-bold shadow-md"></span>
                </div>
                <span class="text-[10px] font-semibold">Favoritos</span>
            </a>
            
            {{-- Menu --}}
            <button @click="toggleMenu()" 
                    class="tab-item flex flex-col items-center justify-center space-y-1 transition-all duration-200 active:scale-95"
                    :class="showMenu ? 'text-red-600 bg-red-50' : 'text-gray-600'">
                <i class="bi bi-list text-2xl" x-show="!showMenu"></i>
                <i class="bi bi-x-lg text-2xl" x-show="showMenu"></i>
                <span class="text-[10px] font-semibold">Menu</span>
            </button>
        </div>
    </nav>

    {{-- Menu Offcanvas Mobile --}}
    <div x-show="showMenu" @click.away="closeMenu()" x-transition
         class="fixed inset-0 z-50 md:hidden">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="closeMenu()"></div>
        
        <div x-transition:enter="transition-transform duration-300"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition-transform duration-200"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="relative w-80 max-w-[85%] bg-white shadow-xl h-full flex flex-col">
            
            {{-- Header --}}
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <div class="flex items-center space-x-2">
                    @if($logoUrl)
                        <img src="{{ $logoUrl }}" alt="{{ $siteName }}" class="h-8 w-auto">
                    @else
                        <span class="text-lg font-bold text-gray-900">{{ $siteName }}</span>
                    @endif
                </div>
                <button @click="closeMenu()" class="p-2 text-gray-400 hover:text-gray-600">
                    <i class="bi bi-x-lg text-xl"></i>
                </button>
            </div>
            
            {{-- Search Bar Mobile --}}
            <div class="p-4 border-b border-gray-200" x-data="searchBar()">
                <div class="relative">
                    <input type="text" 
                           x-model="searchQuery"
                           @input.debounce.300ms="search()"
                           @focus="showResults = true"
                           @keydown.escape="showResults = false; closeMenu()"
                           placeholder="Buscar produtos..."
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    
                    {{-- Loading Spinner --}}
                    <div x-show="searching" class="absolute right-3 top-1/2 transform -translate-y-1/2">
                        <i class="bi bi-arrow-repeat animate-spin text-gray-400"></i>
                    </div>
                </div>
                
                {{-- Results --}}
                <div x-show="showResults && searchResults.length > 0" 
                     class="mt-2 max-h-64 overflow-y-auto space-y-1">
                    <template x-for="product in searchResults" :key="product.id">
                        <a :href="`/produto/${product.slug}`" 
                           @click="closeMenu()"
                           class="flex items-center p-2 hover:bg-gray-50 rounded-lg border border-gray-100">
                            <img :src="product.image_url" 
                                 :alt="product.name"
                                 class="w-10 h-10 object-cover rounded-lg mr-3"
                                 onerror="this.src='{{ url('images/general/callback-image.svg') }}'">
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-medium text-gray-900 truncate" x-text="product.name"></div>
                                <div class="text-xs font-bold text-primary" x-text="product.price_formatted"></div>
                            </div>
                        </a>
                    </template>
                </div>
                
                {{-- No Results --}}
                <div x-show="showResults && searchQuery.length >= 2 && searchResults.length === 0 && !searching"
                     class="mt-2 p-3 bg-gray-50 rounded-lg text-center text-gray-500 text-sm">
                    <i class="bi bi-search mb-1"></i>
                    <div>Nenhum produto encontrado</div>
                </div>
            </div>
            
            {{-- Menu Items --}}
            <div class="flex-1 overflow-y-auto p-4 space-y-2">
                <a href="{{ route('store.index') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100">
                    <i class="bi bi-house-door text-gray-600"></i>
                    <span class="text-gray-900">Início</span>
                </a>
                
                <a href="{{ route('store.products') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100">
                    <i class="bi bi-grid text-gray-600"></i>
                    <span class="text-gray-900">Produtos</span>
                </a>
                
                <a href="{{ route('contact.index') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100">
                    <i class="bi bi-envelope text-gray-600"></i>
                    <span class="text-gray-900">Contato</span>
                </a>
                
                <hr class="my-4">
                
                @auth
                    <div class="px-3 py-2 mb-2">
                        <p class="text-xs text-gray-500 uppercase font-semibold">Minha Conta</p>
                        <p class="text-sm text-gray-900 font-medium mt-1">{{ $user->name }}</p>
                    </div>
                    
                    @if($isAdmin)
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100">
                            <i class="bi bi-speedometer2 text-gray-600"></i>
                            <span class="text-gray-900">Painel Admin</span>
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100">
                            <i class="bi bi-house-door text-gray-600"></i>
                            <span class="text-gray-900">Minha Conta</span>
                        </a>
                    @endif
                    
                    <a href="{{ route('store.user-orders') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100">
                        <i class="bi bi-box-seam text-gray-600"></i>
                        <span class="text-gray-900">Meus Pedidos</span>
                    </a>
                    
                    <a href="{{ route('profile.edit') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100">
                        <i class="bi bi-person-gear text-gray-600"></i>
                        <span class="text-gray-900">Perfil</span>
                    </a>
                    
                    <hr class="my-4">
                    
                    <form method="POST" action="{{ $isAdmin ? route('admin.logout') : route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center space-x-3 p-3 rounded-lg hover:bg-red-50 text-red-600">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Sair</span>
                        </button>
                    </form>
                @endauth
            </div>
            
            {{-- Footer --}}
            <div class="border-t border-gray-200 bg-gray-50 p-4 space-y-3">
                {{-- Login/Account --}}
                @auth
                    <div class="flex items-center space-x-3 p-3 bg-white rounded-lg border">
                        <div class="h-10 w-10 bg-red-600 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-white font-semibold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                        </div>
                    </div>
                @endauth
                
                @guest
                    <a href="{{ route('login') }}" 
                       class="flex items-center justify-center space-x-2 p-3 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        <i class="bi bi-person-circle text-xl"></i>
                        <span class="font-semibold">Entrar ou Criar Conta</span>
                    </a>
                @endguest
            </div>
        </div>
    </div>

    {{-- Cart Offcanvas --}}
    <div x-show="cartOpen" @click.away="closeCart()" x-transition
     class="fixed inset-0 z-50 flex justify-end">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="closeCart()"></div>
    
        <div x-transition:enter="transition-transform duration-300"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition-transform duration-200"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full"
             class="z-999 relative w-full max-w-md bg-white shadow-xl flex flex-col h-full">
        
            {{-- Header --}}
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-900">Carrinho</h2>
        <button @click="closeCart()" class="p-2 text-gray-400 hover:text-gray-600">
            <i class="bi bi-x-lg text-xl"></i>
        </button>
    </div>

            {{-- Items --}}
            <div class="flex-1 overflow-y-auto p-4">
        <div x-show="cartItems.length === 0" class="text-center py-8">
            <i class="bi bi-cart-x text-4xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">Seu carrinho está vazio</p>
                    <button @click="closeCart()" class="mt-4 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                Continuar Comprando
            </button>
        </div>

        <div x-show="cartItems.length > 0" class="space-y-4">
            <template x-for="item in cartItems" :key="item.id">
                        <div class="flex items-start space-x-4 p-4 border rounded-lg">
                            <div class="w-20 h-20 margin-none rounded-lg overflow-hidden flex-shrink-0 relative">
                                <img :src="item.image" :alt="item.name"
                                     class="w-full h-full object-cover"
                                     onerror="this.src='{{ url('images/general/callback-image.svg') }}'; this.classList.add('object-contain', 'bg-gray-100');">
                    </div>
                            
                    <div class="flex-1 min-w-0">
                                <h3 class="font-medium text-gray-900 truncate mb-1" x-text="item.name"></h3>
                        <p class="text-sm text-gray-500 mb-2" x-text="item.description"></p>
                                <p class="font-bold text-red-600" x-text="formatPrice(item.price * item.quantity)"></p>
                                <p class="text-xs text-gray-500" x-show="item.quantity > 1">
                                    <span x-text="item.quantity"></span>x <span x-text="formatPrice(item.price)"></span>
                                </p>
                    </div>
                            
                    <div class="flex flex-col items-end space-y-2">
                        <div class="flex items-center space-x-2">
                            <button @click="updateQuantity(item.id, item.quantity - 1)" 
                                            class="w-8 h-8 rounded-full border flex items-center justify-center hover:bg-gray-50">
                                <i class="bi bi-dash text-sm"></i>
                            </button>
                            <span class="w-8 text-center text-sm font-medium" x-text="item.quantity"></span>
                            <button @click="updateQuantity(item.id, item.quantity + 1)" 
                                            class="w-8 h-8 rounded-full border flex items-center justify-center hover:bg-gray-50">
                                <i class="bi bi-plus text-sm"></i>
                            </button>
                        </div>
                                <button @click="removeItem(item.id)" 
                                        class="w-8 h-8 rounded-full border border-red-300 text-red-600 flex items-center justify-center hover:bg-red-50">
                            <i class="bi bi-trash text-sm"></i>
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>

            {{-- Footer --}}
            <div x-show="cartItems.length > 0" class="border-t p-4 bg-white shadow-lg">
        <div class="flex justify-between items-center mb-4 p-3 bg-gray-50 rounded-lg">
            <span class="text-xl font-semibold text-gray-900">Total:</span>
                    <span class="text-xl font-bold text-red-600" x-text="formatPrice(cartTotal)"></span>
        </div>
        <div class="space-y-2">
            <button @click="goToCheckout()" 
                            class="w-full bg-red-600 text-white py-3 rounded-lg font-semibold hover:bg-red-700">
                Finalizar Compra
            </button>
            <button @click="closeCart()" 
                            class="w-full border border-gray-300 text-gray-700 py-2 rounded-lg hover:bg-gray-50">
                Continuar Comprando
            </button>
        </div>
    </div>
        </div>
    </div>
</div>

<script>
function storeHeader() {
    return {
        showMenu: false,
        showStickyHeader: false,
        cartOpen: false,
        cartItems: [],
        favoritesCount: 0,
        
        init() {
            this.loadCart();
            this.loadFavorites();
            this.setupListeners();
            this.setupScroll();
        },
        
        // Cart Methods
        loadCart() {
            try {
                const cart = JSON.parse(localStorage.getItem('cart') || '[]');
                // Garante que todos os itens tenham quantidade e sejam números válidos
                this.cartItems = cart.filter(item => {
                    if (!item || item.price === undefined || item.price === null) return false;
                    item.quantity = parseInt(item.quantity) || 1;
                    item.price = parseFloat(item.price) || 0;
                    return true;
                });
                
                if (cart.length !== this.cartItems.length) {
                    this.saveCart();
                }
            } catch (error) {
                console.error('Erro ao carregar carrinho:', error);
                this.cartItems = [];
                this.saveCart();
            }
        },
        
        saveCart() {
            localStorage.setItem('cart', JSON.stringify(this.cartItems));
            window.dispatchEvent(new CustomEvent('cartUpdated'));
        },
        
        updateQuantity(productId, newQuantity) {
            if (newQuantity <= 0) {
                this.removeItem(productId);
                return;
            }
            
            const maxQuantity = 99; // Define um limite máximo de itens
            newQuantity = Math.min(newQuantity, maxQuantity); // Limita a quantidade máxima
            
            const item = this.cartItems.find(item => item.id === productId);
            if (item) {
                item.quantity = newQuantity;
                this.cartItems = [...this.cartItems]; // Força o rerender do Alpine
                this.saveCart();
            }
        },
        
        removeItem(productId) {
            this.cartItems = this.cartItems.filter(item => item.id !== productId);
            this.saveCart();
        },
        
        goToCheckout() {
            this.closeCart();
            window.location.href = '/checkout';
        },
        
        // Favorites Methods
        loadFavorites() {
            this.favoritesCount = window.getFavoritesCount ? window.getFavoritesCount() : 0;
        },
        
        // UI Methods
        toggleMenu() {
            this.showMenu = !this.showMenu;
            // Não aplicar overflow-hidden em desktop/tablet
            if (window.innerWidth < 1024) {
                document.body.classList.toggle('overflow-hidden', this.showMenu);
            }
        },
        
        closeMenu() {
            this.showMenu = false;
            if (window.innerWidth < 1024) {
                document.body.classList.remove('overflow-hidden');
            }
        },
        
        openCart() {
            this.cartOpen = true;
            // Não aplicar overflow-hidden em desktop/tablet
            if (window.innerWidth < 1024) {
                document.body.classList.add('overflow-hidden');
            }
        },
        
        closeCart() {
            this.cartOpen = false;
            if (window.innerWidth < 1024) {
                document.body.classList.remove('overflow-hidden');
            }
        },
        
        // Setup Methods
        setupListeners() {
            window.addEventListener('storage', (e) => {
                if (e.key === 'cart') this.loadCart();
                if (e.key === 'favorites') this.loadFavorites();
            });
            
            window.addEventListener('cartUpdated', () => this.loadCart());
            window.addEventListener('favoritesUpdated', () => this.loadFavorites());
        },
        
        setupScroll() {
            window.addEventListener('scroll', () => {
                this.showStickyHeader = window.pageYOffset > 200;
            }, { passive: true });
        },
        
        isCurrentPage(routeName) {
            const currentPath = window.location.pathname;
            const routes = {
                'store.index': '/',
                'store.products': '/produtos',
                'store.favorites': '/favoritos',
                'store.cart': '/carrinho'
            };
            
            const route = routes[routeName];
            
            // Para a home, verificar se é exatamente '/'
            if (routeName === 'store.index') {
                return currentPath === '/' || currentPath === '/index';
            }
            
            // Para outras rotas, verificar se começa com a rota
            return currentPath === route || currentPath.startsWith(route + '/');
        },
        
        // Computed Properties
        get cartCount() {
            return this.cartItems.reduce((sum, item) => sum + item.quantity, 0);
        },
        
        get cartTotal() {
            return this.cartItems.reduce((sum, item) => {
                const price = parseFloat(item.price) || 0;
                return sum + (price * item.quantity);
            }, 0);
        },
            
        // Helpers
        formatPrice(price) {
            const numPrice = parseFloat(price) || 0;
            return 'R$ ' + numPrice.toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }
    }
}

// Search Bar Component
function searchBar() {
    return {
        searchQuery: '',
        searchResults: [],
        showResults: false,
        searching: false,
        
        async search() {
            // Limpar se query muito curta
            if (this.searchQuery.length < 2) {
                this.searchResults = [];
                this.showResults = false;
                return;
            }
            
            this.searching = true;
            
            try {
                const response = await fetch(`/api/search?q=${encodeURIComponent(this.searchQuery)}`);
                const data = await response.json();
                
                // O controller retorna os produtos diretamente, não em data.products
                this.searchResults = Array.isArray(data) ? data : [];
                this.showResults = true;
                
                console.log('🔍 Busca realizada:', this.searchQuery, '- Resultados:', this.searchResults.length);
            } catch (error) {
                console.error('❌ Erro na busca:', error);
                this.searchResults = [];
            } finally {
                this.searching = false;
            }
        }
    };
}
</script>

