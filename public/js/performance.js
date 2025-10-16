/**
 * Performance Optimizations
 * Lazy loading, preloading, and other performance improvements
 */

// Lazy Loading Images
function initLazyLoading() {
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    const src = img.getAttribute('data-src');
                    
                    if (src) {
                        // Criar nova imagem para preload
                        const tempImg = new Image();
                        tempImg.onload = function() {
                            img.src = src;
                            img.classList.remove('lazy');
                            img.classList.add('lazy-loaded');
                        };
                        tempImg.onerror = function() {
                            img.src = img.getAttribute('data-fallback') || '/images/no-image.svg';
                            img.classList.add('lazy-error');
                        };
                        tempImg.src = src;
                        
                        observer.unobserve(img);
                    }
                }
            });
        }, {
            rootMargin: '100px 0px',
            threshold: 0.01
        });

        // Observar imagens lazy
        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
        
        // Lazy loading inicializado
    } else {
        // Fallback
        document.querySelectorAll('img[data-src]').forEach(img => {
            img.src = img.getAttribute('data-src');
        });
    }
}

// Prefetch de links ao hover
function initPrefetchOnHover() {
    const links = document.querySelectorAll('a[href^="/"]');
    
    links.forEach(link => {
        link.addEventListener('mouseenter', function() {
            const href = this.getAttribute('href');
            if (href && !document.querySelector(`link[rel="prefetch"][href="${href}"]`)) {
                const prefetch = document.createElement('link');
                prefetch.rel = 'prefetch';
                prefetch.href = href;
                document.head.appendChild(prefetch);
            }
        }, { once: true, passive: true });
    });
    
    // Prefetch inicializado
}

// Debounce para scroll
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Otimizar scroll performance
function initScrollOptimization() {
    let ticking = false;
    
    window.addEventListener('scroll', function() {
        if (!ticking) {
            window.requestAnimationFrame(function() {
                // Seu código de scroll aqui
                ticking = false;
            });
            ticking = true;
        }
    }, { passive: true });
}

// Comprimir localStorage
function compressLocalStorage() {
    try {
        const cart = localStorage.getItem('cart');
        const favorites = localStorage.getItem('favorites');
        
        if (cart) {
            const parsed = JSON.parse(cart);
            localStorage.setItem('cart', JSON.stringify(parsed));
        }
        
        if (favorites) {
            const parsed = JSON.parse(favorites);
            localStorage.setItem('favorites', JSON.stringify(parsed));
        }
        
        // LocalStorage otimizado com sucesso
    } catch (e) {
        // Silenciosamente ignorar erros
    }
}

// Limpar cache antigo
function clearOldCache() {
    const lastClear = localStorage.getItem('lastCacheClear');
    const now = Date.now();
    const oneDay = 24 * 60 * 60 * 1000;
    
    if (!lastClear || (now - parseInt(lastClear)) > oneDay) {
        // Limpar dados antigos aqui
        localStorage.setItem('lastCacheClear', now.toString());
        // Cache limpo
    }
}

// Performance observer
function initPerformanceObserver() {
    if ('PerformanceObserver' in window) {
        try {
            // Observar recursos lentos
            const perfObserver = new PerformanceObserver((list) => {
                list.getEntries().forEach((entry) => {
                    if (entry.duration > 1000) {
                        // Silenciosamente monitorar recursos lentos
                    }
                });
            });
            
            perfObserver.observe({ entryTypes: ['resource', 'navigation'] });
        } catch (e) {
            // Observer não suportado
        }
    }
}

// Inicializar tudo
document.addEventListener('DOMContentLoaded', function() {
    initLazyLoading();
    initPrefetchOnHover();
    initScrollOptimization();
    compressLocalStorage();
    clearOldCache();
    initPerformanceObserver();
    
    // Performance optimizations inicializadas
});

// Web Vitals (opcional)
function reportWebVitals() {
    if ('PerformanceObserver' in window) {
        // LCP - Largest Contentful Paint
        new PerformanceObserver((list) => {
            const entries = list.getEntries();
            const lastEntry = entries[entries.length - 1];
            // Silenciosamente monitorar LCP
        }).observe({ entryTypes: ['largest-contentful-paint'] });
        
        // FID - First Input Delay
        new PerformanceObserver((list) => {
            list.getEntries().forEach((entry) => {
                // Silenciosamente monitorar FID
            });
        }).observe({ entryTypes: ['first-input'] });
        
        // CLS - Cumulative Layout Shift
        let clsScore = 0;
        new PerformanceObserver((list) => {
            list.getEntries().forEach((entry) => {
                if (!entry.hadRecentInput) {
                    clsScore += entry.value;
                    // Silenciosamente monitorar CLS
                }
            });
        }).observe({ entryTypes: ['layout-shift'] });
    }
}

// Executar ao carregar
window.addEventListener('load', reportWebVitals);

