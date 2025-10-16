@props(['type' => 'info', 'message' => '', 'duration' => 5000])

<div x-data="notification({{ $duration }})"
     x-show="show"
     x-transition:enter="transform ease-out duration-300 transition"
     x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
     x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
     x-transition:leave="transition ease-in duration-100"
     x-transition:leave-start="opacity-100"
     class="max-w-md"
     x-transition:leave-end="opacity-0"
     class="fixed top-4 right-4 z-50 max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden"
     style="display: none;">
    <div class="p-4">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                @if($type === 'success')
                    <i class="bi bi-check-circle-fill text-green-400 text-xl"></i>
                @elseif($type === 'error')
                    <i class="bi bi-exclamation-triangle-fill text-red-400 text-xl"></i>
                @elseif($type === 'warning')
                    <i class="bi bi-exclamation-circle-fill text-yellow-400 text-xl"></i>
                @else
                    <i class="bi bi-info-circle-fill text-blue-400 text-xl"></i>
                @endif
            </div>
            <div class="ml-3 w-0 flex-1 pt-0.5">
                <p class="text-sm font-medium text-gray-900" x-text="message">{{ $message }}</p>
            </div>
            <div class="ml-4 flex-shrink-0 flex">
                <button @click="hide()" class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <span class="sr-only">Fechar</span>
                    <i class="bi bi-x text-lg"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function notification(duration = 5000) {
    return {
        show: false,
        message: '{{ $message }}',
        type: '{{ $type }}',
        
        init() {
            this.show = true;
            if (duration > 0) {
                setTimeout(() => {
                    this.hide();
                }, duration);
            }
        },
        
        hide() {
            this.show = false;
        }
    }
}
</script>
