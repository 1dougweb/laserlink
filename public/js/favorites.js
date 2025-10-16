/**
 * Sistema de Favoritos
 * Gerencia favoritos usando localStorage
 */

class FavoritesManager {
    constructor() {
        this.storageKey = 'favorites';
        this.favorites = this.loadFavorites();
    }

    /**
     * Carrega favoritos do localStorage
     */
    loadFavorites() {
        try {
            const stored = localStorage.getItem(this.storageKey);
            return stored ? JSON.parse(stored) : [];
        } catch (error) {
            // Erro ao carregar favoritos, retornar array vazio
            return [];
        }
    }

    /**
     * Salva favoritos no localStorage
     */
    saveFavorites() {
        try {
            localStorage.setItem(this.storageKey, JSON.stringify(this.favorites));
            this.dispatchUpdateEvent();
        } catch (error) {
            // Silenciosamente ignorar erro ao salvar
        }
    }

    /**
     * Adiciona produto aos favoritos
     */
    addToFavorites(product) {
        const exists = this.favorites.find(fav => fav.id === product.id);
        if (!exists) {
            this.favorites.push({
                id: product.id,
                name: product.name,
                slug: product.slug,
                price: product.price,
                image: product.image_url || '/images/no-image.png',
                added_at: new Date().toISOString()
            });
            this.saveFavorites();
            return true;
        }
        return false;
    }

    /**
     * Remove produto dos favoritos
     */
    removeFromFavorites(productId) {
        const initialLength = this.favorites.length;
        this.favorites = this.favorites.filter(fav => fav.id !== productId);
        
        if (this.favorites.length !== initialLength) {
            this.saveFavorites();
            return true;
        }
        return false;
    }

    /**
     * Verifica se produto está nos favoritos
     */
    isFavorite(productId) {
        return this.favorites.some(fav => fav.id === productId);
    }

    /**
     * Toggle favorito (adiciona se não existe, remove se existe)
     */
    toggleFavorite(product) {
        if (this.isFavorite(product.id)) {
            return this.removeFromFavorites(product.id);
        } else {
            return this.addToFavorites(product);
        }
    }

    /**
     * Retorna todos os favoritos
     */
    getAllFavorites() {
        return this.favorites;
    }

    /**
     * Retorna quantidade de favoritos
     */
    getCount() {
        return this.favorites.length;
    }

    /**
     * Limpa todos os favoritos
     */
    clearFavorites() {
        this.favorites = [];
        this.saveFavorites();
    }

    /**
     * Dispara evento de atualização
     */
    dispatchUpdateEvent() {
        window.dispatchEvent(new CustomEvent('favoritesUpdated', {
            detail: {
                count: this.getCount(),
                favorites: this.getAllFavorites()
            }
        }));
    }
}

// Instância global
window.favoritesManager = new FavoritesManager();

// Funções globais para uso em botões
window.addToFavorites = function(product) {
    return window.favoritesManager.addToFavorites(product);
};

window.removeFromFavorites = function(productId) {
    return window.favoritesManager.removeFromFavorites(productId);
};

window.toggleFavorite = function(product) {
    return window.favoritesManager.toggleFavorite(product);
};

window.isFavorite = function(productId) {
    return window.favoritesManager.isFavorite(productId);
};

window.getFavoritesCount = function() {
    return window.favoritesManager.getCount();
};
