@extends('admin.layout')

@section('title', 'Funções e Permissões')
@section('page-title', 'Funções')

@section('content')
<div class="space-y-6">
    
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Funções e Permissões</h2>
            <p class="text-gray-600 mt-1">Gerencie as funções de usuários e suas permissões</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.permissions.index') }}" 
               class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                <i class="bi bi-key mr-2"></i>Permissões
            </a>
            <a href="{{ route('admin.roles.create') }}" 
               class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors">
                <i class="bi bi-plus-lg mr-2"></i>Nova Função
            </a>
        </div>
    </div>

    <!-- Grid de Funções -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($roles as $role)
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center">
                        <div class="selo rounded-full 
                            @if($role->name === 'admin') bg-red-100 text-red-600
                            @elseif($role->name === 'vendedor') bg-blue-100 text-blue-600
                            @else bg-gray-100 text-gray-600
                            @endif">
                            <i class="bi bi-shield-lock text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-semibold text-gray-900">{{ ucfirst($role->name) }}</h3>
                            <p class="text-xs text-gray-500">{{ $role->users_count }} usuários</p>
                        </div>
                    </div>
                    
                    @if(!in_array($role->name, ['admin', 'vendedor', 'cliente']))
                    <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="inline"
                          onsubmit="return confirm('Tem certeza que deseja deletar esta função?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                    @endif
                </div>

                <div class="mb-4">
                    <p class="text-sm font-medium text-gray-700 mb-2">Permissões:</p>
                    <div class="flex flex-wrap gap-1">
                        @forelse($role->permissions as $permission)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-blue-50 text-blue-700">
                                {{ $permission->name }}
                            </span>
                        @empty
                            <span class="text-xs text-gray-500">Nenhuma permissão atribuída</span>
                        @endforelse
                    </div>
                </div>

                <div class="flex gap-2 pt-4 border-t">
                    <a href="{{ route('admin.roles.edit', $role) }}" 
                       class="flex-1 px-3 py-2 text-center text-sm bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors">
                        <i class="bi bi-pencil mr-1"></i>Editar
                    </a>
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection

