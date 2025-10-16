@extends('admin.layout')

@section('title', 'Nova Função')
@section('page-title', 'Nova Função')

@section('content')
<div class="space-y-6">
    
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-900">Criar Nova Função</h2>
        <a href="{{ route('admin.roles.index') }}" 
           class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
            <i class="bi bi-arrow-left mr-2"></i>Voltar
        </a>
    </div>

    <form action="{{ route('admin.roles.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Informações da Função -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Informações da Função</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nome da Função <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}"
                               placeholder="ex: gerente"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                               required>
                        <p class="text-xs text-gray-500 mt-1">Apenas letras minúsculas e sem espaços</p>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="display_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nome de Exibição <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="display_name" 
                               name="display_name" 
                               value="{{ old('display_name') }}"
                               placeholder="ex: Gerente"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                               required>
                        @error('display_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Permissões -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Permissões</h3>
                
                <div class="space-y-6">
                    @foreach($permissions as $group => $groupPermissions)
                        <div class="border rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-gray-900 mb-3 uppercase">{{ $group }}</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($groupPermissions as $permission)
                                    <label class="flex items-center p-2 border rounded hover:bg-gray-50 cursor-pointer">
                                        <input type="checkbox" 
                                               name="permissions[]" 
                                               value="{{ $permission->name }}"
                                               {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}
                                               class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                        <span class="ml-2 text-sm text-gray-700">{{ str_replace($group . '.', '', $permission->name) }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
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
                <i class="bi bi-check-lg mr-2"></i>Criar Função
            </button>
        </div>
    </form>

</div>
@endsection

