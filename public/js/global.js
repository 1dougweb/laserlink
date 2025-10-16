// Configurações globais e supressão de logs
(function() {
    // Suprimir avisos do Tailwind CDN
    const originalWarn = console.warn;
    console.warn = function(...args) {
        const message = args.join(' ');
        if (message.includes('cdn.tailwindcss.com') || 
            message.includes('should not be used in production')) {
            return;
        }
        originalWarn.apply(console, args);
    };

    // Suprimir erros de recursos 403/404
    const originalError = console.error;
    console.error = function(...args) {
        const message = args.join(' ');
        if (message.includes('403') || 
            message.includes('404') || 
            message.includes('Failed to load resource') ||
            message.includes('/storage/')) {
            return;
        }
        originalError.apply(console, args);
    };

    // Interceptar erros de rede
    window.addEventListener('error', function(e) {
        if (e.target && 
            (e.target.tagName === 'IMG' || e.target.tagName === 'SCRIPT') && 
            (e.target.src.includes('/storage/') || e.target.src.includes('/images/'))) {
            e.preventDefault();
            return false;
        }
    }, true);

    // Interceptar erros de fetch
    const originalFetch = window.fetch;
    window.fetch = function(...args) {
        return originalFetch.apply(this, args)
            .catch(error => {
                if (error.message && (
                    error.message.includes('403') || 
                    error.message.includes('404'))) {
                    return Promise.reject(new Error('Resource not found'));
                }
                return Promise.reject(error);
            });
    };
})();