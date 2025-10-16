<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="post" action="{{ route('profile.update') }}" class="space-y-6">
    @csrf
    @method('patch')

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
               autofocus 
               autocomplete="name">
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
               required 
               autocomplete="username">
        @error('email')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="mt-2">
                <p class="text-sm text-gray-800">
                    Seu endereço de e-mail não foi verificado.

                    <button form="send-verification" 
                            class="underline text-sm text-primary hover:text-red-700 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                        Clique aqui para reenviar o e-mail de verificação.
                    </button>
                </p>

                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 font-medium text-sm text-green-600">
                        Um novo link de verificação foi enviado para seu endereço de e-mail.
                    </p>
                @endif
            </div>
        @endif
    </div>

    <div class="flex items-center justify-end">
        <button type="submit" 
                class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
            <i class="bi bi-check-lg mr-2"></i>
            Salvar
        </button>
    </div>
</form>
