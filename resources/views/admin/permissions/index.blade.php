@extends('admin.layout')

@section('title', 'Permissões do Sistema')
@section('page-title', 'Permissões')

@section('content')
<div class="space-y-6">
    
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Permissões do Sistema</h2>
            <p class="text-gray-600 mt-1">Visualize todas as permissões disponíveis</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.roles.index') }}" 
               class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                <i class="bi bi-arrow-left mr-2"></i>Funções
            </a>
            <form action="{{ route('admin.permissions.seed') }}" method="POST" class="inline">
                @csrf
                <button type="submit" 
                        class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors"
                        onclick="return confirm('Isso criará as permissões padrão do sistema. Continuar?')">
                    <i class="bi bi-arrow-clockwise mr-2"></i>Gerar Permissões Padrão
                </button>
            </form>
        </div>
    </div>

    <!-- Grid de Permissões por Grupo -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($permissions as $group => $groupPermissions)
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center mb-4">
                    <div class="selo rounded-full bg-blue-100 text-blue-600">
                        <i class="bi bi-key text-xl"></i>
                    </div>
                    <h3 class="ml-3 text-lg font-semibold text-gray-900 uppercase">{{ $group }}</h3>
                </div>
                
                <div class="space-y-2">
                    @foreach($groupPermissions as $permission)
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <i class="bi bi-check-circle text-green-600 mr-2"></i>
                            <span class="text-sm text-gray-700">{{ str_replace($group . '.', '', $permission->name) }}</span>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-4 pt-4 border-t">
                    <p class="text-xs text-gray-500">{{ $groupPermissions->count() }} permissões</p>
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection

