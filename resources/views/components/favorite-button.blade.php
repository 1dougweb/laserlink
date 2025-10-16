@props(['product'])

<button 
    @click="toggleFavorite({{ json_encode($product) }})"
    class="favorite-btn relative text-gray-600 hover:text-red-500 transition-colors"
    :class="{ 'text-red-500': isFavorite({{ $product->id }}) }"
    x-data="{ 
        isFavorite: false,
        init() {
            this.isFavorite = window.isFavorite({{ $product->id }});
            window.addEventListener('favoritesUpdated', () => {
                this.isFavorite = window.isFavorite({{ $product->id }});
            });
        },
        toggleFavorite(product) {
            const wasAdded = window.toggleFavorite(product);
            this.isFavorite = !this.isFavorite;
            
            // Feedback visual
            if (wasAdded) {
                this.$el.classList.add('animate-pulse');
                setTimeout(() => this.$el.classList.remove('animate-pulse'), 1000);
            }
        }
    }"
    title="Adicionar aos favoritos">
    
    <i :class="isFavorite ? 'bi bi-heart-fill' : 'bi bi-heart'" class="text-xl"></i>
    
    <!-- Tooltip -->
    <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 pointer-events-none transition-opacity duration-200"
         x-show="false"
         x-text="isFavorite ? 'Remover dos favoritos' : 'Adicionar aos favoritos'">
    </div>
</button>
