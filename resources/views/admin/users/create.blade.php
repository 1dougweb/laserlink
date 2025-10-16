@extends('admin.layout')

@section('title', 'Novo Usuário')
@section('page-title', 'Novo Usuário')

@section('content')
<div class="space-y-6">
    
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-900">Criar Novo Usuário</h2>
        <a href="{{ route('admin.users.index') }}" 
           class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
            <i class="bi bi-arrow-left mr-2"></i>Voltar
        </a>
    </div>

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- Informações Básicas -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Informações Básicas</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nome Completo <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                               required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                               required>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Telefone
                        </label>
                        <input type="text" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone') }}"
                               placeholder="(11) 99999-9999"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="cpf" class="block text-sm font-medium text-gray-700 mb-2">
                            CPF
                        </label>
                        <input type="text" 
                               id="cpf" 
                               name="cpf" 
                               value="{{ old('cpf') }}"
                               placeholder="000.000.000-00"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        @error('cpf')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Segurança e Função -->
            <div class="space-y-6">
                
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Segurança</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Senha <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                   required>
                            <p class="text-xs text-gray-500 mt-1">Mínimo de 8 caracteres</p>
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                Confirmar Senha <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                   required>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Função no Sistema</h3>
                    
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-3">
                            Selecione a Função <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-3">
                            @foreach($roles as $role)
                                <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-primary transition-colors">
                                    <input type="radio" 
                                           name="role" 
                                           value="{{ $role->name }}"
                                           {{ old('role') === $role->name ? 'checked' : '' }}
                                           class="h-4 w-4 text-primary focus:ring-primary"
                                           required>
                                    <div class="ml-3">
                                        <span class="text-sm font-medium text-gray-900">{{ ucfirst($role->name) }}</span>
                                        <p class="text-xs text-gray-500">
                                            @if($role->name === 'admin')
                                                Acesso total ao sistema
                                            @elseif($role->name === 'vendedor')
                                                Gerenciar produtos e pedidos
                                            @else
                                                Acesso à loja virtual
                                            @endif
                                        </p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('role')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

            </div>
        </div>

        <!-- Botões -->
        <div class="flex justify-end gap-4 mt-6">
            <a href="{{ route('admin.users.index') }}" 
               class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                Cancelar
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors">
                <i class="bi bi-check-lg mr-2"></i>Criar Usuário
            </button>
        </div>
    </form>

</div>
@endsection

