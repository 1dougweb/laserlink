@extends('admin.layout')

@section('title', 'Editar Função')
@section('page-title', 'Editar Função')

@section('content')
<div class="space-y-6">
    
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-900">Editar Função: {{ ucfirst($role->name) }}</h2>
        <a href="{{ route('admin.roles.index') }}" 
           class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
            <i class="bi bi-arrow-left mr-2"></i>Voltar
        </a>
    </div>

    <form action="{{ route('admin.roles.update', $role) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Selecione as Permissões</h3>
            
            <div class="space-y-6">
                @foreach($permissions as $group => $groupPermissions)
                    <div class="border rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3 uppercase">{{ $group }}</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($groupPermissions as $permission)
                                <label class="flex items-center p-2 border rounded hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox" 
                                           name="permissions[]" 
                                           value="{{ $permission->name }}"
                                           {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}
                                           class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">{{ str_replace($group . '.', '', $permission->name) }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Botões -->
        <div class="flex justify-end gap-4 mt-6">
            <a href="{{ route('admin.roles.index') }}" 
               class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                Cancelar
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors">
                <i class="bi bi-check-lg mr-2"></i>Salvar Permissões
            </button>
        </div>
    </form>

</div>
@endsection

