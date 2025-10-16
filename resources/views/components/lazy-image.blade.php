@props([
    'src' => '',
    'alt' => '',
    'class' => '',
    'fallback' => url('/images/general/callback-image.svg')
])

<img 
    data-src="{{ $src }}" 
    src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 400 300'%3E%3Crect width='400' height='300' fill='%23f0f0f0'/%3E%3C/svg%3E"
    alt="{{ $alt }}" 
    class="lazy {{ $class }}"
    loading="lazy"
    onerror="this.onerror=null; this.src='{{ url('/images/general/callback-image.svg') }}'; this.classList.add('image-error');"
    {{ $attributes }}
/>

<script>
// Lazy loading de imagens
document.addEventListener('DOMContentLoaded', function() {
    // Verificar se o navegador suporta IntersectionObserver
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    const src = img.getAttribute('data-src');
                    
                    if (src) {
                        img.src = src;
                        img.classList.remove('lazy');
                        img.classList.add('lazy-loaded');
                        observer.unobserve(img);
                    }
                }
            });
        }, {
            rootMargin: '50px 0px', // Carregar 50px antes de aparecer
            threshold: 0.01
        });

        // Observar todas as imagens lazy
        document.querySelectorAll('img.lazy').forEach(img => {
            imageObserver.observe(img);
        });
    } else {
        // Fallback para navegadores antigos
        document.querySelectorAll('img.lazy').forEach(img => {
            const src = img.getAttribute('data-src');
            if (src) {
                img.src = src;
            }
        });
    }
});
</script>

<style>
/* Estilo para imagens sendo carregadas */
img.lazy {
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
}

img.lazy-loaded {
    opacity: 1;
}

/* Efeito de pulse durante carregamento */
img.lazy:not(.lazy-loaded) {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

/* Container para imagens com erro */
img.image-error {
    object-fit: contain;
    background-color: #f3f4f6;
    padding: 20px;
}
</style>

