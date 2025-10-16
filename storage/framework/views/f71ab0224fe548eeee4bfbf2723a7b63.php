<div x-data="notificationContainer()" class="fixed top-5 right-5 z-50 space-y-3">
    <template x-for="notification in notifications" :key="notification.id">
        <div x-show="notification.show"
             x-transition:enter="transform ease-out duration-300"
             x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
             x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="max-w-sm w-full rounded-lg pointer-events-auto shadow-lg overflow-hidden border"
             :class="{
                'bg-green-500 text-white border-green-600': notification.type === 'success',
                'bg-red-500 text-white border-red-600': notification.type === 'error',
                'bg-yellow-500 text-white border-yellow-600': notification.type === 'warning',
                'bg-blue-500 text-white border-blue-600': notification.type === 'info'
             }">
            <div class="p-4 flex items-start">
                <div class="flex-shrink-0 mt-0.5">
                    <i x-show="notification.type === 'success'" class="bi bi-check-circle-fill"></i>
                    <i x-show="notification.type === 'error'" class="bi bi-exclamation-triangle-fill"></i>
                    <i x-show="notification.type === 'warning'" class="bi bi-exclamation-circle-fill"></i>
                    <i x-show="notification.type === 'info'" class="bi bi-info-circle-fill"></i>
                </div>
                <div class="ml-3 w-0 flex-1 pt-0.5">
                    <p class="text-sm font-medium" x-text="notification.message"></p>
                </div>
                <div class="ml-4 flex-shrink-0">
                    <button @click="remove(notification.id)" class="rounded-md/none text-white/90 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white/50">
                        <span class="sr-only">Fechar</span>
                        <i class="bi bi-x text-lg"></i>
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>

<script>
function notificationContainer() {
    return {
        notifications: [],
        
        init() {
            // Escutar eventos de notificação
            window.addEventListener('show-notification', (event) => {
                this.add(event.detail.message, event.detail.type, event.detail.duration);
            });
        },
        
        add(message, type = 'info', duration = 4000) {
            const id = Date.now() + Math.random();
            const notification = {
                id,
                message,
                type,
                show: true
            };
            
            this.notifications.push(notification);
            
            if (duration > 0) {
                setTimeout(() => {
                    this.remove(id);
                }, duration);
            }
        },
        
        remove(id) {
            const index = this.notifications.findIndex(n => n.id === id);
            if (index > -1) {
                this.notifications[index].show = false;
                setTimeout(() => {
                    this.notifications.splice(index, 1);
                }, 200);
            }
        }
    }
}

// Função global para mostrar notificações
window.showNotification = function(message, type = 'info', duration = 4000) {
    window.dispatchEvent(new CustomEvent('show-notification', {
        detail: { message, type, duration }
    }));
};
</script>
<?php /**PATH C:\xampp\htdocs\resources\views/components/notification-container.blade.php ENDPATH**/ ?>