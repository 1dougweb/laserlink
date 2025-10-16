@extends('admin.layout')

@section('title', 'Gerenciamento de Usuários')
@section('page-title', 'Usuários')

@section('content')
<div class="space-y-6">
    
    <!-- Header com Ações -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Gerenciamento de Usuários</h2>
            <p class="text-gray-600 mt-1">Gerencie usuários, funções e permissões do sistema</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.roles.index') }}" 
               class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                <i class="bi bi-shield-lock mr-2"></i>Funções
            </a>
            <a href="{{ route('admin.users.create') }}" 
               class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors">
                <i class="bi bi-plus-lg mr-2"></i>Novo Usuário
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Total</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $users->total() }}</p>
                </div>
                <i class="bi bi-people text-3xl text-blue-500"></i>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Administradores</p>
                    <p class="text-2xl font-bold text-gray-900">{{ \App\Models\User::role('admin')->count() }}</p>
                </div>
                <i class="bi bi-shield-check text-3xl text-green-500"></i>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Vendedores</p>
                    <p class="text-2xl font-bold text-gray-900">{{ \App\Models\User::role('vendedor')->count() }}</p>
                </div>
                <i class="bi bi-person-badge text-3xl text-yellow-500"></i>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Clientes</p>
                    <p class="text-2xl font-bold text-gray-900">{{ \App\Models\User::role('cliente')->count() }}</p>
                </div>
                <i class="bi bi-person text-3xl text-purple-500"></i>
            </div>
        </div>
    </div>

    <!-- Tabela de Usuários -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuário</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Função</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Criado em</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Último Login</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                        <i class="bi bi-person text-gray-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    @if($user->id === auth()->id())
                                        <span class="text-xs text-primary">(Você)</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $user->email }}</div>
                            @if($user->phone)
                                <div class="text-xs text-gray-500">{{ $user->phone }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @foreach($user->roles as $role)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($role->name === 'admin') bg-red-100 text-red-800
                                    @elseif($role->name === 'vendedor') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($role->name) }}
                                </span>
                            @endforeach
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Nunca' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.users.show', $user) }}" 
                                   class="text-blue-600 hover:text-blue-900"
                                   title="Ver detalhes">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}" 
                                   class="text-yellow-600 hover:text-yellow-900"
                                   title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Tem certeza que deseja deletar este usuário?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900"
                                            title="Deletar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            Nenhum usuário encontrado
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Paginação -->
    @if($users->hasPages())
        <div class="mt-6">
            {{ $users->links() }}
        </div>
    @endif

</div>
@endsection

