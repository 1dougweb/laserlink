@props(['userId' => null])

@php
    $unreadCount = 0; // Por enquanto, sem sistema de notificações implementado
    $notifications = collect([]); // Lista vazia por enquanto
@endphp

<x-dropdown align="right" width="96">
    <x-slot name="trigger">
        <button class="relative inline-flex items-center p-2 text-gray-600 hover:text-gray-900">
            <i class="fas fa-bell text-xl"></i>
            @if($unreadCount > 0)
                <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full">
                    {{ $unreadCount }}
                </span>
            @endif
        </button>
    </x-slot>

    <x-slot name="content">
        <div class="p-2">
            <h3 class="text-lg font-semibold px-4 py-2 border-b">
                Notificações
            </h3>

            <div class="max-h-96 overflow-y-auto">
                @forelse($notifications as $notification)
                    <a href="{{ $notification->link ?? '#' }}" 
                       class="block px-4 py-3 hover:bg-gray-50 {{ is_null($notification->read_at) ? 'bg-blue-50' : '' }}"
                       onclick="markAsRead({{ $notification->id }})">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="{{ $notification->getIconClass() }} text-xl"></i>
                            </div>
                            <div class="ml-3 w-0 flex-1">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $notification->title }}
                                </p>
                                <p class="mt-1 text-sm text-gray-500">
                                    {{ $notification->message }}
                                </p>
                                <p class="mt-1 text-xs text-gray-400">
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="px-4 py-3 text-sm text-gray-500">
                        Nenhuma notificação disponível.
                    </div>
                @endforelse
            </div>

            @if($notifications->isNotEmpty())
                <div class="border-t px-4 py-3">
                    <button onclick="markAllAsRead()" class="text-sm text-blue-600 hover:text-blue-800">
                        Marcar todas como lidas
                    </button>
                </div>
            @endif
        </div>
    </x-slot>
</x-dropdown>

@push('scripts')
<script>
function markAsRead(id) {
    fetch(`/customer/notifications/${id}/mark-as-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    });
}

function markAllAsRead() {
    fetch('/customer/notifications/mark-all-as-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    }).then(() => {
        window.location.reload();
    });
}
</script>
@endpush