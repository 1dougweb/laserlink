function checkoutData() {
    return {
        loading: false,
        cartItems: [],
        
        init() {
            this.loadCartFromLocalStorage();
            this.setupPhoneMask();
            this.setupZipMask();
        },
        
        loadCartFromLocalStorage() {
            const cart = localStorage.getItem('cart');
            this.cartItems = cart ? JSON.parse(cart) : [];
        },
        
        formatPrice(price) {
            let numPrice = 0;
            if (typeof price === 'string') {
                numPrice = parseFloat(price.replace(',', '.'));
            } else if (typeof price === 'number') {
                numPrice = price;
            }
            
            return 'R$ ' + numPrice.toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        },
        
        getTotal() {
            const total = this.cartItems.reduce((sum, item) => {
                let price = item.price;
                if (typeof price === 'string') {
                    price = parseFloat(price.replace(',', '.'));
                } else if (typeof price === 'number') {
                    price = price;
                } else {
                    price = 0;
                }
                return sum + (price * (item.quantity || 1));
            }, 0);
            
            return total.toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        },
        
        setupPhoneMask() {
            const phoneInput = document.querySelector('input[name="customer_phone"]');
            if (phoneInput) {
                phoneInput.addEventListener('input', (e) => {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.length >= 11) {
                        value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
                    } else if (value.length >= 7) {
                        value = value.replace(/(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
                    } else if (value.length >= 3) {
                        value = value.replace(/(\d{2})(\d{0,5})/, '($1) $2');
                    }
                    e.target.value = value;
                });
            }
        },
        
        setupZipMask() {
            const zipInput = document.querySelector('input[name="shipping_zip"]');
            if (zipInput) {
                zipInput.addEventListener('input', (e) => {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.length > 8) {
                        value = value.substring(0, 8);
                    }
                    if (value.length > 5) {
                        value = value.replace(/(\d{5})(\d{0,3})/, '$1-$2');
                    }
                    e.target.value = value;
                    
                    if (value.replace(/\D/g, '').length === 8) {
                        this.fetchCEP(value.replace(/\D/g, ''));
                    }
                });
            }
        },
        
        async fetchCEP(cep) {
            try {
                const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
                const data = await response.json();
                
                if (!data.erro) {
                    const addressInput = document.querySelector('input[name="shipping_address"]');
                    const neighborhoodInput = document.querySelector('input[name="shipping_neighborhood"]');
                    const cityInput = document.querySelector('input[name="shipping_city"]');
                    const stateInput = document.querySelector('select[name="shipping_state"]');
                    const complementInput = document.querySelector('input[name="shipping_complement"]');
                    
                    if (addressInput) addressInput.value = data.logradouro;
                    if (neighborhoodInput) neighborhoodInput.value = data.bairro;
                    if (cityInput) cityInput.value = data.localidade;
                    if (stateInput) stateInput.value = data.uf;
                    if (complementInput) complementInput.value = data.complemento || '';
                }
            } catch (error) {
                console.error('Erro ao buscar CEP:', error);
            }
        },
        
        async submitForm() {
            if (this.loading) return;
            
            // Verificar se o carrinho está vazio
            if (this.cartItems.length === 0) {
                alert('Seu carrinho está vazio!');
                return;
            }
            
            this.loading = true;
            const form = document.getElementById('checkoutForm');
            const formData = new FormData(form);
            
            // Get cart data from localStorage
            const totalAmount = this.cartItems.reduce((sum, item) => {
                let price = typeof item.price === 'string' ? parseFloat(item.price.replace(',', '.')) : item.price;
                return sum + (price * (item.quantity || 1));
            }, 0);
            
            // Preparar dados do carrinho para o backend
            const cartData = this.cartItems.map(item => ({
                product_id: item.id,
                product_name: item.name,
                quantity: item.quantity,
                unit_price: typeof item.price === 'string' ? parseFloat(item.price.replace(',', '.')) : item.price,
                total_price: (typeof item.price === 'string' ? parseFloat(item.price.replace(',', '.')) : item.price) * item.quantity,
                measurement_description: item.description || '',
                configuration: item.configuration || null,
                price_breakdown: item.price_breakdown || null
            }));
            
            formData.append('cart_data', JSON.stringify(cartData));
            formData.append('total_amount', totalAmount.toFixed(2));
            
            try {
                const response = await fetch(window.checkoutApiUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Limpar carrinho do localStorage
                    localStorage.removeItem('cart');
                    window.dispatchEvent(new Event('cartUpdated'));
                    
                    alert('Pedido enviado com sucesso! Você receberá uma notificação via WhatsApp em breve.');
                    setTimeout(() => {
                        if (data.redirect_url) {
                            window.location.href = data.redirect_url;
                        }
                    }, 1500);
                } else {
                    alert('Erro: ' + (data.message || 'Erro desconhecido'));
                }
            } catch (error) {
                console.error('Erro:', error);
                alert('Erro ao processar pedido');
            } finally {
                this.loading = false;
            }
        }
    }
}








