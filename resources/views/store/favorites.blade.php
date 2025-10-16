@extends('layouts.store')

@section('title', 'Favoritos')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Meus Favoritos</h1>
        <button id="clear-favorites" class="text-sm text-red-600 hover:text-red-700">Limpar favoritos</button>
    </div>

    <div id="favorites-loading" class="hidden text-center py-20">
        <x-loading-spinner size="lg" text="Carregando favoritos..." />
    </div>

    <div id="favorites-empty" class="hidden text-center py-20">
        <i class="bi bi-heart text-5xl text-gray-300"></i>
        <p class="mt-4 text-gray-600">Você ainda não adicionou produtos aos favoritos.</p>
        <a href="{{ route('store.index') }}" class="mt-6 inline-block px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700">Explorar produtos</a>
    </div>

    <div id="favorites-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8"></div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/product-card.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const grid = document.getElementById('favorites-grid');
    const emptyState = document.getElementById('favorites-empty');
    const loadingState = document.getElementById('favorites-loading');
    const clearBtn = document.getElementById('clear-favorites');

    function getFavorites() {
        try {
            const stored = localStorage.getItem('favorites');
            return stored ? JSON.parse(stored) : [];
        } catch (error) {
            return [];
        }
    }

    function setFavorites(favorites) {
        localStorage.setItem('favorites', JSON.stringify(favorites));
        window.dispatchEvent(new CustomEvent('favoritesUpdated', {
            detail: { count: favorites.length, favorites: favorites }
        }));
    }

    function fetchProducts(favorites) {
        const ids = favorites.map(fav => fav.id).join(',');
        
        if (!ids) {
            return Promise.resolve([]);
        }
        
        const url = `{{ route('api.favorites') }}?ids=${encodeURIComponent(ids)}`;
        
        return fetch(url)
            .then(r => r.json())
            .then(data => data.products || [])
            .catch(() => []);
    }

    function render() {
        const favorites = getFavorites();
        
        // Esconder tudo primeiro
        emptyState.classList.add('hidden');
        loadingState.classList.add('hidden');
        grid.innerHTML = '';
        
        if (!favorites.length) {
            emptyState.classList.remove('hidden');
            clearBtn.classList.add('hidden');
            return;
        }
        
        // Mostrar loading
        loadingState.classList.remove('hidden');
        clearBtn.classList.remove('hidden');
        
        fetchProducts(favorites).then(products => {
            loadingState.classList.add('hidden');
            
            // Manter ordem conforme favoritos
            const productById = new Map(products.map(p => [p.id, p]));
            const ordered = favorites
                .map(fav => productById.get(fav.id))
                .filter(Boolean);
            
            if (ordered.length === 0) {
                emptyState.classList.remove('hidden');
                clearBtn.classList.add('hidden');
            } else {
                // Usar componente ProductCard
                grid.innerHTML = ordered.map(product => 
                    window.ProductCard.render(product, {
                        favoriteButtonType: 'remove',
                        showAddToCart: false
                    })
                ).join('');
                
                // Anexar event listeners
                window.ProductCard.attachEventListeners(grid);
            }
        }).catch(() => {
            loadingState.classList.add('hidden');
            emptyState.classList.remove('hidden');
        });
    }

    clearBtn.addEventListener('click', function () {
        if (confirm('Tem certeza que deseja limpar todos os favoritos?')) {
            setFavorites([]);
            render();
        }
    });

    // Atualizar quando favoritos mudarem em outra aba/página
    window.addEventListener('favoritesUpdated', function() {
        render();
    });

    // Atualizar quando storage mudar (outras abas)
    window.addEventListener('storage', function(e) {
        if (e.key === 'favorites') {
            render();
        }
    });

    render();
});
</script>
@endpush

