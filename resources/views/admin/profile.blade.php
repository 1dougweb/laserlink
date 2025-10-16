@extends('admin.layout')

@section('title', 'Meu Perfil - Admin Laser Link')
@section('page-title', 'Meu Perfil')

@section('content')
<div class="space-y-6">
    <!-- Profile Information -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bi bi-person mr-2 text-primary"></i>
                Informações do Perfil
            </h3>
            <p class="mt-1 text-sm text-gray-600">
                Atualize suas informações de perfil e endereço de e-mail.
            </p>
        </div>
        <div class="px-6 py-4">
            <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nome <span class="text-red-500">*</span>
                    </label>
                    <input id="name" 
                           name="name" 
                           type="text" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" 
                           value="{{ old('name', $user->name) }}" 
                           required 
                           autofocus>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        E-mail <span class="text-red-500">*</span>
                    </label>
                    <input id="email" 
                           name="email" 
                           type="email" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" 
                           value="{{ old('email', $user->email) }}" 
                           required>
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit" 
                            class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                        <i class="bi bi-check-lg mr-2"></i>
                        Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Password Update -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bi bi-shield-lock mr-2 text-primary"></i>
                Atualizar Senha
            </h3>
            <p class="mt-1 text-sm text-gray-600">
                Certifique-se de que sua conta está usando uma senha longa e aleatória para manter a segurança.
            </p>
        </div>
        <div class="px-6 py-4">
            <form method="POST" action="{{ route('admin.profile.password.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                        Senha Atual <span class="text-red-500">*</span>
                    </label>
                    <input id="current_password" 
                           name="current_password" 
                           type="password" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" 
                           autocomplete="current-password" 
                           required>
                    @error('current_password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Nova Senha <span class="text-red-500">*</span>
                    </label>
                    <input id="password" 
                           name="password" 
                           type="password" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" 
                           autocomplete="new-password" 
                           required>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Confirmar Nova Senha <span class="text-red-500">*</span>
                    </label>
                    <input id="password_confirmation" 
                           name="password_confirmation" 
                           type="password" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" 
                           autocomplete="new-password" 
                           required>
                    @error('password_confirmation')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit" 
                            class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                        <i class="bi bi-shield-check mr-2"></i>
                        Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Account -->
    <div class="bg-white shadow rounded-lg border-red-200">
        <div class="px-6 py-4 border-b border-red-200 bg-red-50">
            <h3 class="text-lg font-medium text-red-900 flex items-center">
                <i class="bi bi-exclamation-triangle mr-2 text-red-600"></i>
                Excluir Conta
            </h3>
            <p class="mt-1 text-sm text-red-600">
                Uma vez que sua conta for excluída, todos os seus recursos e dados serão permanentemente excluídos. 
                Antes de excluir sua conta, baixe todos os dados ou informações que deseja reter.
            </p>
        </div>
        <div class="px-6 py-4">
            <div class="space-y-6" x-data="{ showModal: false }">
                <button @click="showModal = true" 
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                    <i class="bi bi-trash mr-2"></i>
                    Excluir Conta
                </button>

                <!-- Modal -->
                <div x-show="showModal" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 z-50 overflow-y-auto"
                     style="display: none;">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showModal = false"></div>

                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            <form method="post" action="{{ route('admin.profile.delete') }}" class="p-6">
                                @csrf
                                @method('DELETE')

                                <div class="flex items-center mb-4">
                                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                                        <i class="bi bi-exclamation-triangle text-red-600 text-xl"></i>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">
                                        Tem certeza que deseja excluir sua conta?
                                    </h3>
                                    <p class="text-sm text-gray-600 mb-6">
                                        Uma vez que sua conta for excluída, todos os seus recursos e dados serão permanentemente excluídos. 
                                        Digite sua senha para confirmar que deseja excluir permanentemente sua conta.
                                    </p>
                                </div>

                                <div class="mb-6">
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                        Senha <span class="text-red-500">*</span>
                                    </label>
                                    <input id="password"
                                           name="password"
                                           type="password"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                           placeholder="Digite sua senha"
                                           required>
                                    @error('password')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex justify-end space-x-3">
                                    <button type="button" 
                                            @click="showModal = false"
                                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                        Cancelar
                                    </button>
                                    <button type="submit" 
                                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                        <i class="bi bi-trash mr-2"></i>
                                        Excluir Conta
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
