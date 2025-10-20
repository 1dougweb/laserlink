@extends('layouts.store')

@section('title', 'Produtos')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="productsPage">
    <!-- Breadcrumb -->
    <nav class="flex mb-6 text-sm" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('store.index') }}" class="text-gray-600 hover:text-gray-900">
                    <i class="bi bi-house-door mr-2"></i>Início
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="bi bi-chevron-right text-gray-400 mx-2"></i>
                    <span class="text-gray-900 font-medium">Produtos</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="flex flex-col lg:flex-row ">
        <!-- Sidebar - Filtros -->
        <aside class="lg:w-64 flex-shrink-0 mr-8">
            <!-- Mobile Filter Button -->
            <button @click="showFilters = !showFilters" 
                    class="lg:hidden w-full mb-4 px-4 py-3 bg-gray-900 text-white rounded-lg flex items-center justify-between">
                <span class="flex items-center">
                    <i class="bi bi-funnel mr-2"></i>
                    Filtros
                </span>
                <i class="bi" :class="showFilters ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
            </button>

            <!-- Filters Container -->
            <div :class="{'hidden lg:block': !showFilters}" class="space-y-6">
                <form method="GET" action="{{ route('store.products') }}" id="filter-form" @submit.prevent="submitForm">
                    <!-- Categorias -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4" x-data="{ openCategories: true }">
                        <button @click="openCategories = !openCategories" type="button" class="w-full flex items-center justify-between font-semibold text-gray-900 mb-3">
                            <div class="flex items-center">
                                <i class="bi bi-grid mr-2 text-red-600"></i>
                                <span>Categorias</span>
                            </div>
                            <i class="bi transition-transform text-red-600" :class="openCategories ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                        </button>
                        <div x-show="openCategories" x-collapse class="space-y-2">
                            <!-- Todas as Categorias -->
                            <div class="mb-3 pb-3 border-b border-gray-200">
                                <a href="{{ route('store.products') }}" 
                                   class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded transition-colors {{ !request('categoria') ? 'bg-red-50' : '' }}">
                                    <i class="bi bi-grid-3x3-gap text-red-600 mr-2"></i>
                                    <span class="text-sm font-semibold {{ !request('categoria') ? 'text-red-600' : 'text-gray-900' }}">
                                        Todas as Categorias
                                    </span>
                                </a>
                            </div>

                            @foreach($parentCategories as $parentCategory)
                            <!-- Categoria Principal -->
                            @php
                                $parentIsSelected = (is_array(request('categoria')) && in_array($parentCategory->slug, request('categoria'))) || (!is_array(request('categoria')) && request('categoria') === $parentCategory->slug);
                                $hasSelectedChild = false;
                                if ($parentCategory->activeChildren && request('categoria')) {
                                    $childSlugs = $parentCategory->activeChildren->pluck('slug')->toArray();
                                    $selectedCategories = is_array(request('categoria')) ? request('categoria') : [request('categoria')];
                                    $hasSelectedChild = count(array_intersect($childSlugs, array_filter($selectedCategories))) > 0;
                                }
                                $shouldBeOpen = $parentIsSelected || $hasSelectedChild;
                            @endphp
                            <div class="space-y-1" x-data="{ 
                                openSubcategory_{{ $parentCategory->id }}: {{ $shouldBeOpen ? 'true' : 'false' }}
                            }">
                                <div class="flex items-center justify-between">
                                    <label class="flex items-center cursor-pointer flex-1" @click="openSubcategory_{{ $parentCategory->id }} = true">
                                        <input type="checkbox" 
                                               name="categoria[]" 
                                               value="{{ $parentCategory->slug }}" 
                                               {{ is_array(request('categoria')) && in_array($parentCategory->slug, request('categoria')) ? 'checked' : '' }}
                                               {{ !is_array(request('categoria')) && request('categoria') === $parentCategory->slug ? 'checked' : '' }}
                                               class="text-red-600 focus:ring-red-500 rounded"
                                               @change="applyFilters()">
                                        <span class="ml-2 text-sm font-medium text-gray-900">{{ $parentCategory->name }}</span>
                                    </label>
                                    
                                    @if($parentCategory->activeChildren && $parentCategory->activeChildren->count() > 0)
                                    <button @click="openSubcategory_{{ $parentCategory->id }} = !openSubcategory_{{ $parentCategory->id }}" 
                                            type="button" 
                                            class="ml-2 p-1 hover:bg-gray-100 rounded transition-colors">
                                        <i class="bi transition-transform text-red-600" 
                                           :class="openSubcategory_{{ $parentCategory->id }} ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                                    </button>
                                    @endif
                                </div>
                                
                                <!-- Subcategorias -->
                                @if($parentCategory->activeChildren && $parentCategory->activeChildren->count() > 0)
                                <div x-show="openSubcategory_{{ $parentCategory->id }}" x-collapse class="ml-6 space-y-1 mt-1">
                                    @foreach($parentCategory->activeChildren as $subCategory)
                                    <label class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded transition-colors">
                                        <input type="checkbox" 
                                               name="categoria[]" 
                                               value="{{ $subCategory->slug }}" 
                                               {{ is_array(request('categoria')) && in_array($subCategory->slug, request('categoria')) ? 'checked' : '' }}
                                               {{ !is_array(request('categoria')) && request('categoria') === $subCategory->slug ? 'checked' : '' }}
                                               class="text-red-600 focus:ring-red-500 rounded"
                                               @change="applyFilters()">
                                        <span class="ml-2 text-sm text-gray-700">{{ $subCategory->name }}</span>
                                    </label>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Faixa de Preço -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4" x-data="{ openPrice: true }">
                        <button @click="openPrice = !openPrice" type="button" class="w-full flex items-center justify-between font-semibold text-gray-900 mb-3">
                            <div class="flex items-center">
                                <i class="bi bi-currency-dollar mr-2 text-red-600"></i>
                                <span>Faixa de Preço</span>
                            </div>
                            <i class="bi transition-transform" :class="openPrice ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                        </button>
                        <div x-show="openPrice" x-collapse class="space-y-3">
                            <div>
                                <label class="text-xs text-gray-600">Preço mínimo</label>
                                <input type="number" 
                                       name="preco_min" 
                                       value="{{ request('preco_min') }}"
                                       min="{{ $minPrice }}"
                                       max="{{ $maxPrice }}"
                                       step="0.01"
                                       placeholder="R$ {{ number_format($minPrice, 2, ',', '.') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-sm">
                            </div>
                            <div>
                                <label class="text-xs text-gray-600">Preço máximo</label>
                                <input type="number" 
                                       name="preco_max" 
                                       value="{{ request('preco_max') }}"
                                       min="{{ $minPrice }}"
                                       max="{{ $maxPrice }}"
                                       step="0.01"
                                       placeholder="R$ {{ number_format($maxPrice, 2, ',', '.') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-sm">
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4 space-y-2">
                        <button type="submit" 
                                class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                            <i class="bi bi-funnel mr-2"></i>Aplicar Filtros
                        </button>
                        @if(request()->hasAny(['busca', 'categoria', 'preco_min', 'preco_max', 'tipo', 'ordenar']))
                        <a href="{{ route('store.products') }}" 
                           class="block w-full px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors text-center font-medium">
                            <i class="bi bi-x-circle mr-2"></i>Limpar Filtros
                        </a>
                        @endif
                    </div>

                    <!-- Tipo de Produto -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4" x-data="{ openType: true }">
                        <button @click="openType = !openType" type="button" class="w-full flex items-center justify-between font-semibold text-gray-900 mb-3">
                            <div class="flex items-center">
                                <i class="bi bi-star mr-2 text-red-600"></i>
                                <span>Tipo</span>
                            </div>
                            <i class="bi transition-transform" :class="openType ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                        </button>
                        <div x-show="openType" x-collapse class="space-y-2">
                            <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded">
                                <input type="radio" 
                                       name="tipo" 
                                       value="" 
                                       {{ !request('tipo') ? 'checked' : '' }}
                                       class="text-red-600 focus:ring-red-500"
                                       onchange="this.form.submit()">
                                <span class="ml-2 text-sm text-gray-700">Todos</span>
                            </label>
                            <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded">
                                <input type="radio" 
                                       name="tipo" 
                                       value="novos" 
                                       {{ request('tipo') === 'novos' ? 'checked' : '' }}
                                       class="text-red-600 focus:ring-red-500"
                                       onchange="this.form.submit()">
                                <span class="ml-2 text-sm text-gray-700">
                                    <i class="bi bi-star-fill text-yellow-500 mr-1"></i>Novos
                                </span>
                            </label>
                            <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded">
                                <input type="radio" 
                                       name="tipo" 
                                       value="promocoes" 
                                       {{ request('tipo') === 'promocoes' ? 'checked' : '' }}
                                       class="text-red-600 focus:ring-red-500"
                                       onchange="this.form.submit()">
                                <span class="ml-2 text-sm text-gray-700">
                                    <i class="bi bi-tag-fill text-red-500 mr-1"></i>Promoções
                                </span>
                            </label>
                            <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded">
                                <input type="radio" 
                                       name="tipo" 
                                       value="destaques" 
                                       {{ request('tipo') === 'destaques' ? 'checked' : '' }}
                                       class="text-red-600 focus:ring-red-500"
                                       onchange="this.form.submit()">
                                <span class="ml-2 text-sm text-gray-700">
                                    <i class="bi bi-trophy-fill text-yellow-600 mr-1"></i>Destaques
                                </span>
                            </label>
                        </div>
                    </div>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1">
            <!-- Search Bar -->
            <div class="mb-6">
                <div class="relative">
                    <input type="text" 
                           x-model="searchQuery"
                           @input.debounce.500ms="performSearch()"
                           placeholder="Buscar produtos..."
                           class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent shadow-sm">
                    <i class="bi bi-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-xl"></i>
                    <button type="button" 
                            x-show="searchQuery.length > 0"
                            @click="clearSearch()"
                            class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <i class="bi bi-x-circle text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Header with Results Count and Sort -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        @if(request('categoria'))
                            @php
                                $selectedCategories = is_array(request('categoria')) ? request('categoria') : [request('categoria')];
                                $categoryNames = $categories->whereIn('slug', $selectedCategories)->pluck('name')->toArray();
                            @endphp
                            @if(count($categoryNames) === 1)
                                {{ $categoryNames[0] }}
                            @else
                                Categorias Selecionadas
                            @endif
                        @else
                            Todos os Produtos
                        @endif
                    </h1>
                    <p class="text-sm text-gray-600 mt-1">
                        {{ $products->total() }} {{ $products->total() === 1 ? 'produto encontrado' : 'produtos encontrados' }}
                    </p>
                </div>

                <!-- Ordenação -->
                <div class="flex items-center gap-2">
                    <label class="text-sm text-gray-600">Ordenar por:</label>
                    <form method="GET" action="{{ route('store.products') }}" class="inline-block">
                        @foreach(request()->except('ordenar') as $key => $value)
                            @if(is_array($value))
                                @foreach($value as $item)
                                    <input type="hidden" name="{{ $key }}[]" value="{{ $item }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        <select name="ordenar" 
                                onchange="this.form.submit()"
                                class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-sm">
                            <option value="recentes" {{ request('ordenar') === 'recentes' ? 'selected' : '' }}>Mais recentes</option>
                            <option value="menor_preco" {{ request('ordenar') === 'menor_preco' ? 'selected' : '' }}>Menor preço</option>
                            <option value="maior_preco" {{ request('ordenar') === 'maior_preco' ? 'selected' : '' }}>Maior preço</option>
                            <option value="nome_az" {{ request('ordenar') === 'nome_az' ? 'selected' : '' }}>Nome (A-Z)</option>
                            <option value="nome_za" {{ request('ordenar') === 'nome_za' ? 'selected' : '' }}>Nome (Z-A)</option>
                        </select>
                    </form>
                </div>
            </div>

            <!-- Active Filters -->
            @if(request()->hasAny(['busca', 'categoria', 'preco_min', 'preco_max', 'tipo']))
            <div class="mb-6 flex flex-wrap gap-2">
                <span class="text-sm text-gray-600">Filtros ativos:</span>
                
                @if(request('busca'))
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-red-100 text-red-800">
                    Busca: "{{ request('busca') }}"
                    <a href="{{ request()->fullUrlWithQuery(['busca' => null]) }}" class="ml-2 hover:text-red-900">
                        <i class="bi bi-x"></i>
                    </a>
                </span>
                @endif

                @if(request('categoria'))
                    @php
                        $selectedCategories = is_array(request('categoria')) ? request('categoria') : [request('categoria')];
                    @endphp
                    @foreach($selectedCategories as $categorySlug)
                        @php
                            $category = $categories->firstWhere('slug', $categorySlug);
                            $remainingCategories = array_diff($selectedCategories, [$categorySlug]);
                        @endphp
                        @if($category)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800">
                            {{ $category->name }}
                            <a href="{{ request()->fullUrlWithQuery(['categoria' => count($remainingCategories) > 0 ? array_values($remainingCategories) : null]) }}" class="ml-2 hover:text-blue-900">
                                <i class="bi bi-x"></i>
                            </a>
                        </span>
                        @endif
                    @endforeach
                @endif

                @if(request('preco_min') || request('preco_max'))
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-green-100 text-green-800">
                    Preço: R$ {{ number_format(request('preco_min', $minPrice), 2, ',', '.') }} - R$ {{ number_format(request('preco_max', $maxPrice), 2, ',', '.') }}
                    <a href="{{ request()->fullUrlWithQuery(['preco_min' => null, 'preco_max' => null]) }}" class="ml-2 hover:text-green-900">
                        <i class="bi bi-x"></i>
                    </a>
                </span>
                @endif

                @if(request('tipo'))
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-purple-100 text-purple-800">
                    {{ ucfirst(request('tipo')) }}
                    <a href="{{ request()->fullUrlWithQuery(['tipo' => null]) }}" class="ml-2 hover:text-purple-900">
                        <i class="bi bi-x"></i>
                    </a>
                </span>
                @endif
            </div>
            @endif

            <!-- Products Grid -->
            @if($products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($products as $product)
                <div class="relative group w-full sm:max-w-[300px] sm:mx-auto flex flex-col bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow"
                     x-data="{
                        product: {{ json_encode([
                            'id' => $product->id,
                            'name' => $product->name,
                            'slug' => $product->slug,
                            'final_price' => $product->auto_calculate_price ? (float) ($product->min_price ?? 0) : (float) ($product->sale_price ?: $product->price ?: 0),
                            'original_price' => $product->sale_price ? (float) $product->price : null,
                            'featured_image' => $product->featured_image ? url('images/' . $product->featured_image) : null,
                            'is_new' => (bool) $product->is_new,
                            'is_on_sale' => (bool) $product->is_on_sale,
                            'is_featured' => (bool) $product->is_featured,
                            'auto_calculate_price' => (bool) $product->auto_calculate_price,
                            'discount_percentage' => $product->is_on_sale && $product->sale_price ? round((($product->price - $product->sale_price) / $product->price) * 100) : 0,
                            'whatsapp_quote_enabled' => (bool) $product->whatsapp_quote_enabled,
                            'whatsapp_quote_text' => $product->whatsapp_quote_text,
                        ]) }},
                        adding: false, 
                        inCart: false, 
                        removing: false,
                        init() {
                            const cart = JSON.parse(localStorage.getItem('cart') || '[]');
                            this.inCart = cart.some(item => item.id === this.product.id);
                        },
                        handleCartClick() {
                            if (!this.inCart) {
                                this.adding = true;
                                
                                let cart = JSON.parse(localStorage.getItem('cart') || '[]');
                                const existingItem = cart.find(item => item.id === this.product.id);
                                
                                if (existingItem) {
                                    existingItem.quantity += 1;
                                } else {
                                    cart.push({
                                        id: this.product.id,
                                        name: this.product.name,
                                        slug: this.product.slug,
                                        price: this.product.final_price,
                                        image: this.product.featured_image || '{{ url('images/general/callback-image.svg') }}',
                                        quantity: 1
                                    });
                                }
                                
                                localStorage.setItem('cart', JSON.stringify(cart));
                                window.dispatchEvent(new Event('cartUpdated'));
                                
                                setTimeout(() => {
                                    this.adding = false;
                                    this.inCart = true;
                                }, 800);
                            } else if (this.removing) {
                                this.adding = true;
                                let cart = JSON.parse(localStorage.getItem('cart') || '[]');
                                cart = cart.filter(item => item.id !== this.product.id);
                                localStorage.setItem('cart', JSON.stringify(cart));
                                window.dispatchEvent(new Event('cartUpdated'));
                                
                                setTimeout(() => {
                                    this.adding = false;
                                    this.inCart = false;
                                    this.removing = false;
                                }, 500);
                            }
                        }
                     }"
                     x-init="window.addEventListener('cartUpdated', () => {
                        const cart = JSON.parse(localStorage.getItem('cart') || '[]');
                        inCart = cart.some(item => item.id === product.id);
                     })">
                    
                    <!-- Product Image -->
                    <div class="relative w-full aspect-square mb-4 flex-shrink-0 overflow-hidden">
                        <a href="{{ route('store.product', $product->slug) }}" class="block w-full h-full">
                            @if($product->featured_image)
                                <img src="{{ url('images/' . $product->featured_image) }}" 
                                     alt="{{ $product->name }} - {{ $product->category->name }} - Laser Link"
                                     class="w-full h-full object-cover bg-white"
                                     onerror="this.src='{{ url('images/general/callback-image.svg') }}'; this.classList.add('object-contain', 'bg-gray-100');">
                            @else
                                <img src="{{ url('images/general/callback-image.svg') }}" 
                                     alt="{{ $product->name }} - Produto Laser Link"
                                     class="w-full h-full object-contain bg-gray-100">
                            @endif
                        </a>
                        
                        <!-- Badges -->
                        <div class="absolute left-3 top-3 flex flex-col gap-2 z-10">
                            @if($product->is_new)
                            <span class="px-2 py-1 rounded-md bg-blue-500 text-white text-xs font-semibold uppercase shadow-md w-fit">Novo</span>
                            @endif
                            
                            @if($product->is_on_sale && $product->sale_price && $product->price > $product->sale_price)
                            <span class="px-2 py-1 rounded-md bg-red-500 text-white text-xs font-semibold shadow-md w-fit">
                                -{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                            </span>
                            @endif
                            
                            @if($product->is_featured)
                            <span class="px-2 py-1 rounded-md bg-amber-600 text-white text-xs font-semibold uppercase shadow-md w-fit">Destaque</span>
                            @endif
                        </div>
                        
                        <!-- Favorite Button -->
                        <button @click="toggleFavorite({{ $product->id }})" 
                                class="absolute right-3 top-3 w-10 h-10 rounded-md bg-white hover:bg-gray-50 flex items-center justify-center transition-all shadow-sm z-10">
                            <i :class="isFavorite({{ $product->id }}) ? 'bi bi-heart-fill text-red-500' : 'bi bi-heart text-gray-700'" 
                               class="text-xl transition-all duration-200"></i>
                        </button>
                    </div>
                    
                    <!-- Product Info -->
                    <div class="flex flex-col flex-grow space-y-2 p-4 pt-0">
                        <!-- Title -->
                        <a href="{{ route('store.product', $product->slug) }}" 
                           class="text-base font-medium capitalize hover:underline line-clamp-2 flex items-start text-[#272343]">
                            {{ $product->name }}
                        </a>
                        
                        <!-- Price and Rating -->
                        <div class="flex items-center justify-between gap-2 min-h-[2rem]">
                            <div class="flex items-center gap-2">
                                @if($product->auto_calculate_price)
                                    <p class="text-lg font-semibold text-green-600">
                                        <i class="bi bi-calculator mr-1"></i>
                                        A partir de R$ {{ number_format($product->min_price ?? 0, 2, ',', '.') }}
                                    </p>
                                @elseif($product->sale_price && $product->sale_price > 0)
                                    <p class="text-lg font-semibold text-[#272343]">
                                        R$ {{ number_format($product->sale_price, 2, ',', '.') }}
                                    </p>
                                    @if($product->price > $product->sale_price)
                                    <p class="text-sm text-[#9a9caa] line-through">
                                        R$ {{ number_format($product->price, 2, ',', '.') }}
                                    </p>
                                    @endif
                                @elseif($product->price && $product->price > 0)
                                    <p class="text-lg font-semibold text-[#272343]">
                                        R$ {{ number_format($product->price, 2, ',', '.') }}
                                    </p>
                                @else
                                    <p class="text-lg font-semibold text-gray-400">
                                        Preço sob consulta
                                    </p>
                                @endif
                            </div>
                            
                            <x-product-rating 
                                :rating="$product->rating_average ?? 0" 
                                :count="$product->rating_count ?? 0"
                                :showCount="false"
                                size="md"
                            />
                        </div>
                        
                        <!-- Add to Cart Button Full Width or WhatsApp Quote Button -->
                        <template x-if="product.whatsapp_quote_enabled">
                            <a :href="`https://wa.me/{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('whatsapp_number', '5511999999999')) }}?text=${encodeURIComponent('Olá! Gostaria de solicitar uma cotação para o produto: ' + product.name + ' - ' + window.location.origin + '/produto/' + product.slug)}`"
                               target="_blank"
                               class="w-full py-2 rounded-lg text-sm font-medium transition-all flex items-center justify-center gap-2 mt-auto bg-green-500 hover:bg-green-600 text-white">
                                <i class="bi bi-whatsapp text-base"></i>
                                <span x-text="product.whatsapp_quote_text || 'Cotar pelo WhatsApp'"></span>
                            </a>
                        </template>
                        <template x-if="!product.whatsapp_quote_enabled">
                            <button @click="handleCartClick()"
                                    @mouseenter="if(inCart) removing = true"
                                    @mouseleave="removing = false"
                                    class="w-full py-2 rounded-lg text-sm font-medium transition-all flex items-center justify-center gap-2 mt-auto"
                                    :class="inCart && !removing ? 'bg-green-500 text-white transition-colors duration-200' : inCart && removing ? 'bg-red-500 text-white transition-colors duration-200' : 'bg-gray-900 hover:bg-black text-white transition-colors duration-200'">
                                <!-- Static state - Adicionar -->
                                <template x-if="!adding && !inCart">
                                    <div class="flex items-center gap-2">
                                        <i class="bi bi-cart-plus text-base"></i>
                                        <span>Adicionar ao Carrinho</span>
                                    </div>
                                </template>
                            
                            <!-- Loading before adding -->
                            <template x-if="adding">
                                <div class="flex items-center gap-2">
                                    <i class="bi bi-arrow-clockwise text-base animate-spin"></i>
                                    <span>Adicionando...</span>
                                </div>
                            </template>
                            
                            <!-- Added to cart -->
                            <template x-if="!adding && inCart && !removing">
                                <div class="flex items-center gap-2">
                                    <i class="bi bi-cart-check-fill text-base"></i>
                                    <span>Produto Adicionado</span>
                                </div>
                            </template>
                            
                            <!-- Remove on hover -->
                            <template x-if="!adding && inCart && removing">
                                <div class="flex items-center gap-2">
                                    <i class="bi bi-cart-x-fill text-base"></i>
                                    <span>Remover do Carrinho</span>
                                </div>
                            </template>
                            </button>
                        </template>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $products->links() }}
            </div>

            @else
            <!-- Empty State -->
            <div class="text-center py-20">
                <i class="bi bi-inbox text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Nenhum produto encontrado</h3>
                <p class="text-gray-600 mb-6">Tente ajustar os filtros ou realizar uma nova busca.</p>
                <a href="{{ route('store.products') }}" 
                   class="inline-block px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Ver todos os produtos
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('productsPage', () => ({
        showFilters: false,
        favorites: [],
        filterTimeout: null,
        loading: false,
        searchQuery: '{{ request("busca") ?? "" }}',
        
        init() {
            this.syncFavorites();
            
            window.addEventListener('favoritesUpdated', () => {
                this.syncFavorites();
            });
        },
        
        syncFavorites() {
            if (window.favoritesManager) {
                const favs = window.favoritesManager.getAllFavorites();
                this.favorites = favs.map(f => f.id);
            }
        },
        
        isFavorite(productId) {
            return this.favorites.includes(productId);
        },
        
        toggleFavorite(productId) {
            if (!window.favoritesManager) return;
            
            // Buscar dados do produto do DOM
            const productCard = event.target.closest('[x-data]');
            if (!productCard) return;
            
            // Pegar dados do Alpine.js
            const alpineData = Alpine.$data(productCard);
            if (!alpineData || !alpineData.product) return;
            
            const product = alpineData.product;
            const productData = {
                id: product.id,
                name: product.name,
                slug: product.slug,
                price: product.final_price,
                image_url: product.featured_image || '{{ url('images/general/callback-image.svg') }}'
            };
            
            window.favoritesManager.toggleFavorite(productData);
            this.syncFavorites();
        },
        
        applyFilters() {
            // Debounce para evitar múltiplas submissões rápidas
            clearTimeout(this.filterTimeout);
            this.filterTimeout = setTimeout(() => {
                this.submitForm();
            }, 300);
        },
        
        submitForm() {
            const form = document.getElementById('filter-form');
            if (!form) return;
            
            // Mostrar que está carregando
            this.loading = true;
            
            // Construir URL com parâmetros
            const formData = new FormData(form);
            const params = new URLSearchParams();
            
            for (const [key, value] of formData.entries()) {
                if (value) {
                    params.append(key, value);
                }
            }
            
            const url = '{{ route("store.products") }}' + (params.toString() ? '?' + params.toString() : '');
            
            // Atualizar URL e recarregar
            window.location.href = url;
        },
        
        performSearch() {
            const form = document.getElementById('filter-form');
            if (!form) return;
            
            // Criar FormData do formulário de filtros
            const formData = new FormData(form);
            const params = new URLSearchParams();
            
            // Adicionar parâmetros do formulário
            for (const [key, value] of formData.entries()) {
                if (value) {
                    params.append(key, value);
                }
            }
            
            // Adicionar busca
            if (this.searchQuery.trim()) {
                params.set('busca', this.searchQuery.trim());
            } else {
                params.delete('busca');
            }
            
            // Atualizar URL e recarregar
            const url = '{{ route("store.products") }}' + (params.toString() ? '?' + params.toString() : '');
            window.location.href = url;
        },
        
        clearSearch() {
            this.searchQuery = '';
            this.performSearch();
        }
    }));
});
</script>
@endpush

