{{-- Page Loader - Global Loading Overlay --}}
<div x-data="pageLoader()" 
     x-show="loading" 
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-[9999] bg-white/90 backdrop-blur-sm flex items-center justify-center"
     style="display: none;">
    
    <div class="text-center">
        <div class="relative w-16 h-16 mx-auto mb-4">
            <div class="absolute inset-0 rounded-full border-4 border-gray-200"></div>
            <div class="absolute inset-0 rounded-full border-4 border-red-600 border-t-transparent animate-spin"></div>
        </div>
        
        <p class="text-lg font-semibold text-gray-900 mb-1" x-text="message"></p>
        <p class="text-sm text-gray-500">Aguarde um momento...</p>
    </div>
</div>

<script>
function pageLoader() {
    return {
        loading: false,
        message: 'Carregando',
        
        init() {
            // Escutar eventos customizados
            window.addEventListener('loading:show', (e) => {
                this.show(e.detail?.message || 'Carregando');
            });
            
            window.addEventListener('loading:hide', () => {
                this.hide();
            });
            
            // Auto-hide ao carregar página
            window.addEventListener('load', () => {
                this.hide();
            });
        },
        
        show(msg = 'Carregando') {
            this.message = msg;
            this.loading = true;
            document.body.style.overflow = 'hidden';
        },
        
        hide() {
            this.loading = false;
            document.body.style.overflow = '';
        }
    }
}

// Helper functions globais
window.showLoading = function(message = 'Carregando') {
    window.dispatchEvent(new CustomEvent('loading:show', { detail: { message } }));
};

window.hideLoading = function() {
    window.dispatchEvent(new CustomEvent('loading:hide'));
};
</script>

<style>
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.animate-spin {
    animation: spin 1s linear infinite;
}
</style>

