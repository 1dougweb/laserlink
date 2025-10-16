@php
    // Buscar configuração das categorias da home
    $homeCategoriesConfig = \App\Models\Setting::get('home_categories', '[]');
    $homeCategories = json_decode($homeCategoriesConfig, true);
    
    // Buscar dados das categorias
    $categories = collect();
    if (!empty($homeCategories)) {
        foreach ($homeCategories as $config) {
            $category = \App\Models\Category::find($config['category_id']);
            if ($category && $category->is_active) {
                $category->custom_title = $config['title'] ?? $category->name;
                $category->custom_image = $config['image'] ?? $category->image;
                $category->custom_order = $config['order'] ?? 999;
                $categories->push($category);
            }
        }
        $categories = $categories->sortBy('custom_order');
    }
@endphp

@if($categories->count() > 0)
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Título -->
        <div class="text-center mb-8">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Navegue por Categorias</h2>
            <p class="text-lg text-gray-600">Encontre exatamente o que você procura</p>
        </div>

        <!-- Categories Horizontal Slider -->
        <div class="relative">
            <!-- Scroll Container -->
            <div class="overflow-x-auto pb-4">
                <div class="flex space-x-6 min-w-max px-2 justify-center">
                    @foreach($categories as $category)
                        <a href="{{ route('store.category', $category->slug) }}" 
                           class="group flex-shrink-0 text-center min-w-[140px]">
                            <!-- Category Image - Circular -->
                            <div class="relative mb-4">
                                <div class="w-20 h-20 md:w-24 md:h-24 mx-auto relative">
                                    @if($category->custom_image ?? $category->image)
                                        @php
                                            $imagePath = $category->custom_image ?? $category->image;
                                            
                                            // Se já é uma URL completa (http/https)
                                            if (str_starts_with($imagePath, 'http')) {
                                                $imageUrl = $imagePath;
                                            }
                                            // Se começa com /, é um caminho absoluto
                                            elseif (str_starts_with($imagePath, '/')) {
                                                $imageUrl = $imagePath;
                                            }
                                            // Caso contrário, assumir que está em public/images/
                                            else {
                                                $imageUrl = url('images/' . $imagePath);
                                            }
                                        @endphp
                                        <img src="{{ $imageUrl }}" 
                                             alt="{{ $category->custom_title ?? $category->name }}"
                                             class="w-full h-full object-cover rounded-full border-4 border-white shadow-lg group-hover:scale-110 transition-transform duration-300">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-blue-500 to-purple-600 rounded-full border-4 border-white shadow-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                            <i class="bi bi-folder text-white text-2xl md:text-3xl"></i>
                                        </div>
                                    @endif
                                    
                                    <!-- Hover effect ring -->
                                    <div class="absolute inset-0 rounded-full border-2 border-transparent group-hover:border-primary transition-colors duration-300"></div>
                                </div>
                            </div>
                            
                            <!-- Category Name -->
                            <h3 class="text-sm md:text-base font-semibold text-gray-900 group-hover:text-primary transition-colors leading-tight">
                                {{ $category->custom_title ?? $category->name }}
                            </h3>
                            
                            <!-- Product Count -->
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $category->products()->count() }} produtos
                            </p>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Gradient Overlays to indicate more content -->
            <div class="absolute top-0 left-0 w-12 h-full bg-gradient-to-r from-gray-50 to-transparent pointer-events-none z-10"></div>
            <div class="absolute top-0 right-0 w-12 h-full bg-gradient-to-l from-gray-50 to-transparent pointer-events-none z-10"></div>
        </div>

        <!-- View All Categories Link -->
        <div class="text-center mt-8">
            <a href="{{ route('store.products') }}" 
               class="inline-flex items-center px-6 py-3 bg-black text-white rounded-lg hover:bg-primary transition-all duration-200 transform hover:scale-105 shadow-lg">
                <i class="bi bi-grid-3x3-gap mr-2 text-lg"></i>
                <span class="font-medium">Ver Todas as Categorias</span>
            </a>
        </div>
    </div>
</section>

<style>
/* Smooth scrolling */
.overflow-x-auto {
    scroll-behavior: smooth;
}

/* Custom scrollbar styling - Hidden on mobile */
.overflow-x-auto::-webkit-scrollbar {
    height: 0px; /* Hidden on mobile */
}

@media (min-width: 768px) {
    .overflow-x-auto::-webkit-scrollbar {
        height: 12px; /* Visible on desktop */
    }
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: #e2e8f0;
    border-radius: 6px;
    border: 1px solid #cbd5e1;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #3b82f6;
    border-radius: 6px;
    border: 2px solid #e2e8f0;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #2563eb;
    border-color: #cbd5e1;
}

/* Firefox scrollbar - Hidden on mobile */
.overflow-x-auto {
    scrollbar-width: none; /* Hidden on mobile */
}

@media (min-width: 768px) {
    .overflow-x-auto {
        scrollbar-width: thin; /* Visible on desktop */
        scrollbar-color: #3b82f6 #e2e8f0;
    }
}
</style>
@endif
