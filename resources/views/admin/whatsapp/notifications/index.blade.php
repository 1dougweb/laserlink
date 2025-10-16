@extends('admin.layout')

@section('title', 'Notificações WhatsApp')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Histórico de Notificações</h1>
            <p class="text-gray-600 mt-1">Acompanhe todas as mensagens WhatsApp enviadas</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.whatsapp.notifications.send-promotion') }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-all shadow-sm hover:shadow-md">
                <i class="bi bi-megaphone mr-2"></i> Enviar Promoção
            </a>
            <a href="{{ route('admin.whatsapp.notifications.export', request()->query()) }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-all shadow-sm hover:shadow-md">
                <i class="bi bi-download mr-2"></i> Exportar
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
            <div class="flex justify-between items-center">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.parentElement.style.display='none'" class="text-green-700 hover:text-green-900">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
            <div class="flex justify-between items-center">
                <span>{{ session('error') }}</span>
                <button onclick="this.parentElement.parentElement.style.display='none'" class="text-red-700 hover:text-red-900">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>
    @endif

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6" 
         x-data="{ showFilters: {{ request()->hasAny(['type', 'status', 'instance_id', 'search', 'start_date', 'end_date']) ? 'true' : 'false' }} }">
        <div class="p-4 border-b border-gray-200">
            <button @click="showFilters = !showFilters" 
                    class="flex items-center justify-between w-full text-left">
                <div class="flex items-center space-x-2">
                    <i class="bi bi-funnel text-gray-600"></i>
                    <span class="font-medium text-gray-900">Filtros de Pesquisa</span>
                    @if(request()->hasAny(['type', 'status', 'instance_id', 'search', 'start_date', 'end_date']))
                        <span class="bg-primary bg-opacity-10 text-primary text-xs font-medium px-2.5 py-0.5 rounded-full">
                            Ativos
                        </span>
                    @endif
                </div>
                <i class="bi transition-transform duration-200" 
                   :class="showFilters ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
            </button>
        </div>

        <form method="GET" action="{{ route('admin.whatsapp.notifications.index') }}" x-show="showFilters" x-cloak>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Tipo</label>
                    <select name="type" id="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">Todos os tipos</option>
                        <option value="order_status" {{ request('type') == 'order_status' ? 'selected' : '' }}>Status do Pedido</option>
                        <option value="promotion" {{ request('type') == 'promotion' ? 'selected' : '' }}>Promoção</option>
                        <option value="cart_abandonment" {{ request('type') == 'cart_abandonment' ? 'selected' : '' }}>Carrinho Abandonado</option>
                        <option value="custom" {{ request('type') == 'custom' ? 'selected' : '' }}>Personalizada</option>
                    </select>
                </div>
                
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">Todos os status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendente</option>
                        <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Enviada</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Entregue</option>
                        <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Lida</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Falhou</option>
                    </select>
                </div>
                
                <div>
                    <label for="instance_id" class="block text-sm font-medium text-gray-700 mb-2">Instância</label>
                    <select name="instance_id" id="instance_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">Todas as instâncias</option>
                        @foreach($instances as $instance)
                            <option value="{{ $instance->id }}" {{ request('instance_id') == $instance->id ? 'selected' : '' }}>
                                {{ $instance->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                    <input type="text" name="search" id="search" 
                           placeholder="Telefone, nome ou mensagem..." 
                           value="{{ request('search') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
                
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Data Início</label>
                    <input type="date" name="start_date" id="start_date" 
                           value="{{ request('start_date') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
                
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Data Fim</label>
                    <input type="date" name="end_date" id="end_date" 
                           value="{{ request('end_date') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
                
                <div class="lg:col-span-2 flex items-end space-x-3">
                    <button type="submit" class="bg-primary hover:opacity-90 text-white px-4 py-2 rounded-lg transition-all">
                        <i class="bi bi-search mr-2"></i> Filtrar
                    </button>
                    <a href="{{ route('admin.whatsapp.notifications.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-all">
                        <i class="bi bi-x-circle mr-2"></i> Limpar
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="bi bi-chat-dots text-2xl text-blue-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="bi bi-check-circle text-2xl text-green-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Enviadas</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['sent'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="bi bi-check2-all text-2xl text-blue-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Entregues</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['delivered'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="bi bi-x-circle text-2xl text-red-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Falharam</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['failed'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela de Notificações -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Notificações</h3>
            
            @if($notifications->isEmpty())
                <div class="text-center py-12">
                    <div class="mb-4">
                        <i class="bi bi-inbox text-6xl text-gray-400"></i>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-900 mb-2">Nenhuma notificação encontrada</h4>
                    <p class="text-gray-600">Ajuste os filtros ou envie sua primeira mensagem.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destinatário</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Instância</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mensagem</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($notifications as $notification)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $notification->created_at->format('d/m/Y') }}</div>
                                        <div class="text-sm text-gray-500">{{ $notification->created_at->format('H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $notification->recipient_name ?: 'N/A' }}</div>
                                        <div class="text-sm text-gray-500">{{ $notification->recipient_phone }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $notification->type_label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $notification->status_color }}">
                                            {{ $notification->status_label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $notification->instance->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 max-w-xs truncate">
                                            {{ Str::limit($notification->message, 50) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.whatsapp.notifications.show', $notification) }}" 
                                               class="text-blue-600 hover:text-blue-900">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            
                                            @if($notification->status === 'failed')
                                                <form method="POST" action="{{ route('admin.whatsapp.notifications.resend', $notification) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-green-600 hover:text-green-900" title="Reenviar">
                                                        <i class="bi bi-arrow-clockwise"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginação -->
                <div class="flex justify-center mt-6">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection