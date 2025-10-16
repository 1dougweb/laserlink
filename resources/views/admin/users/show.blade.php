@extends('admin.layout')

@section('title', 'Detalhes do Usuário')
@section('page-title', 'Detalhes do Usuário')

@section('content')
<div class="space-y-6">
    
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
        <div class="flex gap-3">
            <a href="{{ route('admin.users.index') }}" 
               class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                <i class="bi bi-arrow-left mr-2"></i>Voltar
            </a>
            <a href="{{ route('admin.users.edit', $user) }}" 
               class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors">
                <i class="bi bi-pencil mr-2"></i>Editar
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Informações do Usuário -->
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informações Pessoais</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Nome</p>
                        <p class="text-sm text-gray-900 mt-1">{{ $user->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Email</p>
                        <p class="text-sm text-gray-900 mt-1">{{ $user->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Telefone</p>
                        <p class="text-sm text-gray-900 mt-1">{{ $user->phone ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">CPF</p>
                        <p class="text-sm text-gray-900 mt-1">{{ $user->cpf ?? '-' }}</p>
                    </div>
                </div>
            </div>

            @if($user->address)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Endereço</h3>
                <div class="text-sm text-gray-700 space-y-1">
                    <p>{{ $user->address }}, {{ $user->address_number }}</p>
                    @if($user->address_complement)
                        <p>{{ $user->address_complement }}</p>
                    @endif
                    <p>{{ $user->neighborhood }}</p>
                    <p>{{ $user->city }} - {{ $user->state }}</p>
                    <p>CEP: {{ $user->zip_code }}</p>
                </div>
            </div>
            @endif

            <!-- Permissões -->
            @if($user->permissions->count() > 0)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Permissões Diretas</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($user->permissions as $permission)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                            {{ $permission->name }}
                        </span>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            
            <!-- Status -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Status</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Função</p>
                        @foreach($user->roles as $role)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium mt-1
                                @if($role->name === 'admin') bg-red-100 text-red-800
                                @elseif($role->name === 'vendedor') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($role->name) }}
                            </span>
                        @endforeach
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Email Verificado</p>
                        <p class="text-sm mt-1">
                            @if($user->email_verified_at)
                                <span class="text-green-600"><i class="bi bi-check-circle mr-1"></i>Sim</span>
                            @else
                                <span class="text-red-600"><i class="bi bi-x-circle mr-1"></i>Não</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Cadastrado em</p>
                        <p class="text-sm text-gray-900 mt-1">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Último Login</p>
                        <p class="text-sm text-gray-900 mt-1">
                            {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Nunca' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Pedidos -->
            @php
                $ordersCount = \App\Models\Order::where('user_id', $user->id)->count();
                $ordersTotal = \App\Models\Order::where('user_id', $user->id)->sum('total');
            @endphp
            @if($ordersCount > 0)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Estatísticas</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total de Pedidos</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $ordersCount }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Valor Total</p>
                        <p class="text-2xl font-bold text-primary mt-1">R$ {{ number_format($ordersTotal, 2, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            @endif

        </div>

    </div>

</div>
@endsection

