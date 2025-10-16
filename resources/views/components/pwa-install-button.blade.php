{{-- PWA Install Button --}}
<div x-data="pwaInstall()" x-init="init()" x-show="canInstall" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform translate-y-full"
     x-transition:enter-end="opacity-100 transform translate-y-0"
     class="md:hidden fixed bottom-20 right-4 z-30">
    
    <button @click="install()" 
            class="flex items-center space-x-2 px-4 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-200 active:scale-95">
        <i class="bi bi-download text-lg"></i>
        <span class="font-semibold text-sm">Instalar App</span>
    </button>
    
    <button @click="dismiss()" 
            class="absolute -top-1 -right-1 w-6 h-6 bg-gray-800 text-white rounded-full flex items-center justify-center text-xs hover:bg-gray-900">
        <i class="bi bi-x"></i>
    </button>
</div>

<script>
function pwaInstall() {
    return {
        canInstall: false,
        deferredPrompt: null,
        
        init() {
            // Verificar se já está instalado
            if (window.matchMedia('(display-mode: standalone)').matches) {
                this.canInstall = false;
                return;
            }
            
            // Verificar se já foi dispensado
            if (localStorage.getItem('pwa-install-dismissed')) {
                this.canInstall = false;
                return;
            }
            
            // Escutar evento de instalação
            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                this.deferredPrompt = e;
                this.canInstall = true;
                console.log('💡 PWA: Prompt de instalação disponível');
            });
            
            // Verificar se foi instalado
            window.addEventListener('appinstalled', () => {
                console.log('🎉 PWA: App instalado com sucesso!');
                this.canInstall = false;
                this.deferredPrompt = null;
            });
        },
        
        async install() {
            if (!this.deferredPrompt) {
                console.log('⚠️ PWA: Prompt não disponível');
                return;
            }
            
            this.deferredPrompt.prompt();
            
            const { outcome } = await this.deferredPrompt.userChoice;
            console.log(`👤 PWA: Usuário ${outcome === 'accepted' ? 'aceitou' : 'recusou'} a instalação`);
            
            if (outcome === 'accepted') {
                this.canInstall = false;
            }
            
            this.deferredPrompt = null;
        },
        
        dismiss() {
            this.canInstall = false;
            localStorage.setItem('pwa-install-dismissed', 'true');
            console.log('❌ PWA: Instalação dispensada pelo usuário');
        }
    }
}
</script>

