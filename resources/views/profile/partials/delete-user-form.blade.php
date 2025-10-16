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
                <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
                    @csrf
                    @method('delete')

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
                        @error('password', 'userDeletion')
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
