<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    @section('page-title', 'Entrar na sua conta')

    <form class="space-y-6" method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Error Messages -->
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                <div class="flex items-center">
                    <i class="bi bi-exclamation-circle mr-2"></i>
                    <div>
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Success Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                <div class="flex items-center">
                    <i class="bi bi-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                E-mail
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="bi bi-envelope text-gray-400"></i>
                </div>
                <input id="email" 
                       name="email" 
                       type="email" 
                       autocomplete="email" 
                       required 
                       class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('email') border-red-500 @enderror"
                       placeholder="seu@email.com"
                       value="{{ old('email') }}">
            </div>
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                Senha
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="bi bi-lock text-gray-400"></i>
                </div>
                <input id="password" 
                       name="password" 
                       type="password" 
                       autocomplete="current-password" 
                       required 
                       class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('password') border-red-500 @enderror"
                       placeholder="Sua senha">
                <button type="button" 
                        class="absolute inset-y-0 right-0 pr-3 flex items-center"
                        onclick="togglePassword('password')">
                    <i class="bi bi-eye text-gray-400 hover:text-gray-600" id="password-eye"></i>
                </button>
            </div>
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input id="remember" 
                       name="remember" 
                       type="checkbox" 
                       class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                <label for="remember" class="ml-2 block text-sm text-gray-700">
                    Lembrar de mim
                </label>
            </div>

            @if (Route::has('password.request'))
                <div class="text-sm">
                    <a href="{{ route('password.request') }}" 
                       class="font-medium text-primary hover:text-red-700">
                        Esqueceu a senha?
                    </a>
                </div>
            @endif
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" 
                    class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-primary hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                    <i class="bi bi-box-arrow-in-right text-white"></i>
                </span>
                Entrar
            </button>
        </div>

        <!-- Register Link -->
        <div class="text-center">
            <p class="text-sm text-gray-600">
                Não tem uma conta?
                <a href="{{ route('register') }}" 
                   class="font-medium text-primary hover:text-red-700">
                    Cadastre-se aqui
                </a>
            </p>
        </div>
    </form>

    <!-- Divider -->
    <div class="relative mt-6">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-300"></div>
        </div>
        <div class="relative flex justify-center text-sm">
            <span class="px-2 bg-white text-gray-500">Ou entre com</span>
        </div>
    </div>

    <!-- Google Sign In -->
    <div class="mt-6">
        <a href="{{ route('oauth.google.redirect', ['intent' => 'login']) }}"
           class="w-full flex items-center justify-center gap-2 bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
            <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google" class="h-5 w-5">
            Entrar com Google
        </a>
    </div>

    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const eyeIcon = document.getElementById(fieldId + '-eye');
            
            if (field.type === 'password') {
                field.type = 'text';
                eyeIcon.className = 'bi bi-eye-slash text-gray-400 hover:text-gray-600';
            } else {
                field.type = 'password';
                eyeIcon.className = 'bi bi-eye text-gray-400 hover:text-gray-600';
            }
        }
    </script>
</x-guest-layout>
