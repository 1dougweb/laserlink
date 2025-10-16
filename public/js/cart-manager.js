/**
 * Gerenciador Global do Carrinho
 * Compatível com produtos customizados e campos extras
 */
window.CartManager = {
    cartItems: [],
    cartKey: 'laserlink_cart',
    
    init() {
        this.loadCart();
        this.setupEventListeners();
    },
    
    setupEventListeners() {
        // Escutar eventos de atualização do carrinho
        window.addEventListener('cartUpdated', () => {
            this.loadCart();
            this.updateCartCount();
        });
    },
    
    loadCart() {
        try {
            const cartData = localStorage.getItem(this.cartKey);
            this.cartItems = cartData ? JSON.parse(cartData) : [];
            this.updateCartCount();
        } catch (error) {
            console.error('Erro ao carregar carrinho:', error);
            this.cartItems = [];
        }
    },
    
    saveCart() {
        try {
            localStorage.setItem(this.cartKey, JSON.stringify(this.cartItems));
            this.updateCartCount();
            window.dispatchEvent(new CustomEvent('cartUpdated', { 
                detail: { items: this.cartItems, count: this.cartItems.length }
            }));
        } catch (error) {
            console.error('Erro ao salvar carrinho:', error);
        }
    },
    
    async addItem(itemData) {
        try {
            // Validar dados do item
            if (!itemData.product_id || !itemData.quantity || !itemData.unit_price) {
                throw new Error('Dados do item incompletos');
            }
            
            // Verificar se o item já existe (mesmo produto + mesmas customizações)
            const existingItemIndex = this.findExistingItem(itemData);
            
            if (existingItemIndex !== -1) {
                // Atualizar quantidade do item existente
                this.cartItems[existingItemIndex].quantity += itemData.quantity;
                this.cartItems[existingItemIndex].total_price = 
                    this.cartItems[existingItemIndex].quantity * this.cartItems[existingItemIndex].unit_price;
            } else {
                // Adicionar novo item
                const cartItem = {
                    id: itemData.id || this.generateItemId(),
                    product_id: itemData.product_id,
                    product_name: itemData.product_name,
                    product_slug: itemData.product_slug,
                    product_image: itemData.product_image,
                    base_price: itemData.base_price || itemData.unit_price,
                    extra_cost: itemData.extra_cost || 0,
                    unit_price: itemData.unit_price,
                    quantity: itemData.quantity,
                    total_price: itemData.unit_price * itemData.quantity,
                    customization: itemData.customization || {},
                    added_at: itemData.added_at || new Date().toISOString()
                };
                
                this.cartItems.push(cartItem);
            }
            
            this.saveCart();
            return { success: true, message: 'Produto adicionado ao carrinho!' };
            
        } catch (error) {
            console.error('Erro ao adicionar item ao carrinho:', error);
            return { success: false, message: 'Erro ao adicionar produto ao carrinho' };
        }
    },
    
    findExistingItem(newItem) {
        return this.cartItems.findIndex(item => 
            item.product_id === newItem.product_id && 
            JSON.stringify(item.customization) === JSON.stringify(newItem.customization)
        );
    },
    
    generateItemId() {
        return 'cart_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
    },
    
    updateQuantity(itemId, newQuantity) {
        const itemIndex = this.cartItems.findIndex(item => item.id === itemId);
        
        if (itemIndex !== -1) {
            if (newQuantity <= 0) {
                this.removeItem(itemId);
            } else {
                this.cartItems[itemIndex].quantity = newQuantity;
                this.cartItems[itemIndex].total_price = 
                    this.cartItems[itemIndex].quantity * this.cartItems[itemIndex].unit_price;
                this.saveCart();
            }
        }
    },
    
    removeItem(itemId) {
        this.cartItems = this.cartItems.filter(item => item.id !== itemId);
        this.saveCart();
    },
    
    clearCart() {
        this.cartItems = [];
        this.saveCart();
    },
    
    getCartItems() {
        return this.cartItems;
    },
    
    getCartCount() {
        return this.cartItems.reduce((total, item) => total + item.quantity, 0);
    },
    
    getCartTotal() {
        return this.cartItems.reduce((total, item) => total + item.total_price, 0);
    },
    
    updateCartCount() {
        const count = this.getCartCount();
        const cartCountElements = document.querySelectorAll('.cart-count, [data-cart-count]');
        
        cartCountElements.forEach(element => {
            element.textContent = count;
            element.style.display = count > 0 ? 'inline' : 'none';
        });
        
        // Atualizar badge do carrinho
        const cartBadge = document.querySelector('.cart-badge');
        if (cartBadge) {
            cartBadge.textContent = count;
            cartBadge.style.display = count > 0 ? 'flex' : 'none';
        }
    },
    
    formatPrice(price) {
        return new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        }).format(price);
    },
    
    formatCustomization(customization) {
        if (!customization || Object.keys(customization).length === 0) {
            return '';
        }
        
        const parts = [];
        for (const [key, value] of Object.entries(customization)) {
            if (Array.isArray(value)) {
                if (value.length > 0) {
                    parts.push(`${key}: ${value.join(', ')}`);
                }
            } else if (value) {
                parts.push(`${key}: ${value}`);
            }
        }
        
        return parts.join(' | ');
    }
};

// Inicializar o gerenciador quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', () => {
    window.CartManager.init();
});

// Função global para compatibilidade
window.updateCartCount = () => {
    window.CartManager.updateCartCount();
};
