<div class="relative" x-data="{ open: false }" @click.away="open = false">
    <!-- Botão de Notificações -->
    <button @click="open = !open" 
            class="relative bg-white p-2 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
        <i class="bi bi-bell text-xl"></i>
        @if($unreadCount > 0)
            <span class="absolute top-0 right-0 block h-4 w-4 transform -translate-y-1/2 translate-x-1/2 rounded-full bg-red-500 text-xs text-white font-bold flex items-center justify-center">
                {{ $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Dropdown de Notificações -->
    <div x-show="open"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none z-50"
         style="display: none;">
        
        <div class="p-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Notificações</h3>
                @if($unreadCount > 0)
                    <button wire:click="markAllAsRead" class="text-sm text-gray-500 hover:text-gray-700">
                        Marcar todas como lidas
                    </button>
                @endif
            </div>

            <div class="space-y-4 max-h-96 overflow-y-auto">
                @forelse($notifications as $notification)
                    <div class="flex items-start gap-4 {{ $notification->read_at ? 'opacity-75' : '' }} p-3 hover:bg-gray-50 rounded-lg">
                        <div class="flex-shrink-0">
                            <i class="{{ $notification->icon ?? 'bi bi-info-circle' }} text-2xl {{ $notification->read_at ? 'text-gray-400' : 'text-primary' }}"></i>
                        </div>
                        <div class="flex-grow min-w-0">
                            <div class="flex items-center justify-between">
                                <p class="font-medium text-gray-900 truncate">{{ $notification->title }}</p>
                                <small class="text-xs text-gray-500 whitespace-nowrap ml-2">{{ $notification->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mt-1 text-sm text-gray-600">{{ $notification->message }}</p>
                            @if($notification->link)
                                <a href="{{ $notification->link }}" class="inline-block mt-2 text-sm font-medium text-primary hover:text-primary-dark">
                                    Ver detalhes
                                </a>
                            @endif
                        </div>
                        @unless($notification->read_at)
                            <button wire:click="markAsRead({{ $notification->id }})" 
                                    class="flex-shrink-0 text-gray-400 hover:text-gray-500"
                                    title="Marcar como lida">
                                <i class="bi bi-check2"></i>
                            </button>
                        @endunless
                    </div>
                @empty
                    <p class="text-sm text-gray-500 text-center py-4">
                        Nenhuma notificação no momento
                    </p>
                @endforelse
            </div>

            @if($notifications->isNotEmpty())
                <div class="mt-6 text-center">
                    <a href="{{ route('store.notifications') }}" class="text-sm font-medium text-primary hover:text-primary-dark">
                        Ver todas as notificações
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>