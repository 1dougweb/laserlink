<x-guest-layout>
    @section('page-title', 'Criar nova conta')

    <form class="space-y-6" method="POST" action="{{ route('register') }}">
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

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                Nome Completo
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="bi bi-person text-gray-400"></i>
                </div>
                <input id="name" 
                       name="name" 
                       type="text" 
                       autocomplete="name" 
                       required 
                       class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('name') border-red-500 @enderror"
                       placeholder="Seu nome completo"
                       value="{{ old('name') }}">
            </div>
        </div>

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
                       autocomplete="new-password" 
                       required 
                       class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('password') border-red-500 @enderror"
                       placeholder="Digite sua senha"
                       oninput="validatePassword()">
                <button type="button" 
                        class="absolute inset-y-0 right-0 pr-3 flex items-center"
                        onclick="togglePassword('password')">
                    <i class="bi bi-eye text-gray-400 hover:text-gray-600" id="password-eye"></i>
                </button>
            </div>
            
            <!-- Password Validation Rules -->
            <div class="mt-3 p-4 bg-gray-50 rounded-lg" id="password-validation-box">
                <h4 class="text-sm font-medium text-gray-700 mb-3">A SENHA DEVE CONTER:</h4>
                <div class="space-y-2">
                    <div class="flex items-center text-sm" id="lowercase-rule">
                        <i class="bi bi-x-circle text-red-500 mr-2" id="lowercase-icon"></i>
                        <span class="text-gray-600">Pelo menos uma letra minúscula</span>
                    </div>
                    <div class="flex items-center text-sm" id="uppercase-rule">
                        <i class="bi bi-x-circle text-red-500 mr-2" id="uppercase-icon"></i>
                        <span class="text-gray-600">Pelo menos uma letra maiúscula</span>
                    </div>
                    <div class="flex items-center text-sm" id="number-rule">
                        <i class="bi bi-x-circle text-red-500 mr-2" id="number-icon"></i>
                        <span class="text-gray-600">Pelo menos um número</span>
                    </div>
                    <div class="flex items-center text-sm" id="length-rule">
                        <i class="bi bi-x-circle text-red-500 mr-2" id="length-icon"></i>
                        <span class="text-gray-600">Mínimo 8 caracteres</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Password Confirmation -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                Confirmar Senha
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="bi bi-lock-fill text-gray-400"></i>
                </div>
                <input id="password_confirmation" 
                       name="password_confirmation" 
                       type="password" 
                       autocomplete="new-password" 
                       required 
                       class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('password_confirmation') border-red-500 @enderror"
                       placeholder="Confirme sua senha">
                <button type="button" 
                        class="absolute inset-y-0 right-0 pr-3 flex items-center"
                        onclick="togglePassword('password_confirmation')">
                    <i class="bi bi-eye text-gray-400 hover:text-gray-600" id="password_confirmation-eye"></i>
                </button>
            </div>
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" 
                    class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-primary hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                    <i class="bi bi-person-plus text-white"></i>
                </span>
                Criar Conta
            </button>
        </div>

        <!-- Login Link -->
        <div class="text-center">
            <p class="text-sm text-gray-600">
                Já tem uma conta?
                <a href="{{ route('login') }}" 
                   class="font-medium text-primary hover:text-red-700">
                    Fazer login
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
            <span class="px-2 bg-white text-gray-500">Ou cadastre-se com</span>
        </div>
    </div>

    <!-- Google Sign Up -->
    <div class="mt-6">
        <a href="{{ route('oauth.google.redirect', ['intent' => 'register']) }}"
           class="w-full flex items-center justify-center gap-2 bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
            <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google" class="h-5 w-5">
            Cadastrar com Google
        </a>
    </div>

    <script>
        function validatePassword() {
            const password = document.getElementById('password').value;
            
            // Validações
            const hasLowercase = /[a-z]/.test(password);
            const hasUppercase = /[A-Z]/.test(password);
            const hasNumber = /\d/.test(password);
            const hasMinLength = password.length >= 8;
            
            // Atualizar ícones e cores
            updateRule('lowercase', hasLowercase);
            updateRule('uppercase', hasUppercase);
            updateRule('number', hasNumber);
            updateRule('length', hasMinLength);
            
            // Verificar se todas as regras foram atendidas
            const allRulesMet = hasLowercase && hasUppercase && hasNumber && hasMinLength;
            updatePasswordStrength(allRulesMet);
        }
        
        function updateRule(ruleName, isValid) {
            const icon = document.getElementById(ruleName + '-icon');
            const rule = document.getElementById(ruleName + '-rule');
            
            if (isValid) {
                icon.className = 'bi bi-check-circle text-green-500 mr-2';
                rule.classList.remove('text-gray-600');
                rule.classList.add('text-green-600');
            } else {
                icon.className = 'bi bi-x-circle text-red-500 mr-2';
                rule.classList.remove('text-green-600');
                rule.classList.add('text-gray-600');
            }
        }
        
        function updatePasswordStrength(allRulesMet) {
            const passwordField = document.getElementById('password');
            const validationBox = document.getElementById('password-validation-box');
            
            if (allRulesMet) {
                passwordField.classList.remove('border-gray-300', 'border-red-500');
                passwordField.classList.add('border-green-500');
                validationBox.classList.remove('bg-gray-50');
                validationBox.classList.add('bg-green-50', 'border', 'border-green-200');
            } else {
                passwordField.classList.remove('border-green-500');
                passwordField.classList.add('border-gray-300');
                validationBox.classList.remove('bg-green-50', 'border', 'border-green-200');
                validationBox.classList.add('bg-gray-50');
            }
        }
        
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
        
        // Validar senha quando a página carrega
        document.addEventListener('DOMContentLoaded', function() {
            validatePassword();
        });
    </script>
</x-guest-layout>
