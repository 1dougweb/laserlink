<form method="post" action="{{ route('password.update') }}" class="space-y-6">
    @csrf
    @method('put')

    <div>
        <label for="update_password_current_password" class="block text-sm font-medium text-gray-700 mb-2">
            Senha Atual <span class="text-red-500">*</span>
        </label>
        <input id="update_password_current_password" 
               name="current_password" 
               type="password" 
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" 
               autocomplete="current-password" 
               required>
        @error('current_password', 'updatePassword')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="update_password_password" class="block text-sm font-medium text-gray-700 mb-2">
            Nova Senha <span class="text-red-500">*</span>
        </label>
        <input id="update_password_password" 
               name="password" 
               type="password" 
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" 
               autocomplete="new-password" 
               required>
        @error('password', 'updatePassword')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="update_password_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
            Confirmar Nova Senha <span class="text-red-500">*</span>
        </label>
        <input id="update_password_password_confirmation" 
               name="password_confirmation" 
               type="password" 
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" 
               autocomplete="new-password" 
               required>
        @error('password_confirmation', 'updatePassword')
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
