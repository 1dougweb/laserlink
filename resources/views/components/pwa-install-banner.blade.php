<!-- PWA Install Banner -->
<div x-data="pwaInstall()" 
     x-show="showBanner" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform translate-y-4"
     x-transition:enter-end="opacity-100 transform translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed bottom-4 left-4 right-4 md:left-auto md:right-4 md:w-96 z-50"
     style="display: none;">
    
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border-1 border-gray-200">
        <div class="flex items-center p-4 gap-4">
            <!-- Imagem/Ícone à Esquerda -->
            <div class="flex-shrink-0">
                <div class="w-16 h-16 from-primary to-red-700 rounded-xl flex items-center justify-center shadow-lg">
                    <img src="{{ url('images/icon-144x144.png') }}" alt="Laser Link" class="w-full h-full">
                </div>
            </div>

            <!-- Conteúdo à Direita -->
            <div class="flex-1 min-w-0">
                <h3 class="text-lg font-bold text-gray-900 mb-1">
                    Instalar aplicativo
                </h3>
                <!-- <p class="text-sm text-gray-600 mb-3">
                    Acesse mais rápido e receba notificações de ofertas!
                </p> -->

                <!-- Botões -->
                <div class="flex gap-2">
                    <button @click="installPWA()" 
                            :disabled="installing"
                            class="flex-1 bg-primary hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200 text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!installing">
                            <i class="bi bi-download mr-1"></i>
                            Instalar
                        </span>
                        <span x-show="installing">
                            Instalando...
                        </span>
                    </button>
                    
                    <button @click="dismissBanner()" 
                            class="px-3 py-2 text-gray-500 hover:text-gray-700 transition-colors duration-200">
                        <i class="bi bi-x-lg text-lg"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Indicador de Progresso -->
        <div x-show="installing" class="h-1 bg-gray-200">
            <div class="h-1 bg-primary animate-pulse"></div>
        </div>
    </div>
</div>

<script>
function pwaInstall() {
    return {
        showBanner: false,
        installing: false,
        deferredPrompt: null,

        init() {
            // Verificar se é dispositivo móvel
            const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) || 
                           window.innerWidth <= 768;

            // Verificar se já foi dispensado
            const dismissed = localStorage.getItem('pwa_banner_dismissed');
            const dismissedAt = localStorage.getItem('pwa_banner_dismissed_at');
            
            // Mostrar novamente após 7 dias
            if (dismissed && dismissedAt) {
                const daysSinceDismissed = (Date.now() - parseInt(dismissedAt)) / (1000 * 60 * 60 * 24);
                if (daysSinceDismissed < 7) {
                    return;
                }
            }

            // Aguardar evento beforeinstallprompt
            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                this.deferredPrompt = e;
                
                // Mostrar banner após 3 segundos
                setTimeout(() => {
                    this.showBanner = true;
                    console.log('📱 Banner PWA exibido');
                }, 3000);
            });

            // Detectar quando o app foi instalado
            window.addEventListener('appinstalled', () => {
                console.log('🎉 PWA instalado com sucesso!');
                this.showBanner = false;
                this.showSuccessNotification();
            });

            // Para iOS Safari (não tem beforeinstallprompt)
            const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
            const isStandalone = window.navigator.standalone;
            
            if (isIOS && !isStandalone && !dismissed) {
                // Mostrar banner customizado para iOS após 3 segundos
                setTimeout(() => {
                    this.showIOSBanner();
                }, 3000);
            }
        },

        async installPWA() {
            if (!this.deferredPrompt) {
                console.log('⚠️ Evento beforeinstallprompt não disponível');
                
                // Verificar se é iOS
                const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent);
                if (isIOS) {
                    this.showIOSInstructions();
                } else {
                    this.showHTTPSWarning();
                }
                return;
            }

            this.installing = true;

            try {
                // Mostrar prompt de instalação
                this.deferredPrompt.prompt();
                
                // Aguardar escolha do usuário
                const { outcome } = await this.deferredPrompt.userChoice;
                
                console.log(`📱 Usuário ${outcome === 'accepted' ? 'aceitou' : 'recusou'} a instalação`);

                if (outcome === 'accepted') {
                    this.showSuccessNotification();
                }

                this.deferredPrompt = null;
                this.showBanner = false;
                
            } catch (error) {
                console.error('❌ Erro ao instalar PWA:', error);
                this.showErrorNotification();
            } finally {
                this.installing = false;
            }
        },

        dismissBanner() {
            this.showBanner = false;
            localStorage.setItem('pwa_banner_dismissed', 'true');
            localStorage.setItem('pwa_banner_dismissed_at', Date.now().toString());
            console.log('📱 Banner dispensado');
        },

        showIOSBanner() {
            // Criar banner customizado para iOS
            this.showBanner = true;
        },

        showIOSInstructions() {
            alert('Para instalar no iPhone/iPad:\n\n1. Toque no botão de Compartilhar (ícone com seta para cima)\n2. Role para baixo e toque em "Adicionar à Tela de Início"\n3. Toque em "Adicionar"');
        },

        showHTTPSWarning() {
            alert('⚠️ Para instalar o app, é necessário:\n\n• Acessar via HTTPS (conexão segura)\n• Ou acessar através de localhost\n\nEm produção com domínio próprio, a instalação funcionará automaticamente!');
        },

        showSuccessNotification() {
            // Criar notificação de sucesso
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 animate-bounce';
            notification.innerHTML = `
                <div class="flex items-center gap-3">
                    <i class="bi bi-check-circle text-2xl"></i>
                    <div>
                        <div class="font-bold">App Instalado!</div>
                        <div class="text-sm">Acesse pelo ícone na tela inicial</div>
                    </div>
                </div>
            `;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.remove();
            }, 5000);
        },

        showErrorNotification() {
            alert('❌ Erro ao instalar o aplicativo. Tente novamente.');
        }
    };
}
</script>

<style>
@keyframes bounce {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-10px);
    }
}

.animate-bounce {
    animation: bounce 1s ease-in-out 3;
}
</style>

