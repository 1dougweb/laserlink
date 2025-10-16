<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registro Admin - {{ \App\Models\Setting::get('site_name', 'Laser Link') }}</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '{{ \App\Models\Setting::get("primary_color", "#EE0000") }}',
                        secondary: '{{ \App\Models\Setting::get("secondary_color", "#f8f9fa") }}',
                        accent: '{{ \App\Models\Setting::get("accent_color", "#ffc107") }}',
                    }
                }
            }
        }
    </script>
    
    <!-- CSS personalizado para cores dinâmicas -->
    <style>
        :root {
            --primary-color: {{ \App\Models\Setting::get('primary_color', '#EE0000') }};
            --secondary-color: {{ \App\Models\Setting::get('secondary_color', '#f8f9fa') }};
            --accent-color: {{ \App\Models\Setting::get('accent_color', '#ffc107') }};
        }
        
        .bg-primary { background-color: var(--primary-color) !important; }
        .text-primary { color: var(--primary-color) !important; }
        .border-primary { border-color: var(--primary-color) !important; }
        .hover\:bg-primary:hover { background-color: var(--primary-color) !important; }
        .focus\:ring-primary:focus { --tw-ring-color: var(--primary-color) !important; }
        
        .bg-secondary { background-color: var(--secondary-color) !important; }
        .text-secondary { color: var(--secondary-color) !important; }
        
        .bg-accent { background-color: var(--accent-color) !important; }
        .text-accent { color: var(--accent-color) !important; }
    </style>
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Logo e Header -->
        <div class="text-center">
            <div class="flex justify-center mb-6">
                @php
                    // Preferir logo do site público; fallback para logo do sidebar
                    $siteLogoPath = \App\Models\Setting::get('site_logo_path');
                    $sidebarLogoPath = \App\Models\Setting::get('logo_path');
                    $selectedLogoPath = $siteLogoPath ?: $sidebarLogoPath;
                    $siteName = \App\Models\Setting::get('site_name', 'Laser Link');
                @endphp
                
                @if($selectedLogoPath)
                    <!-- Logo com imagem - título oculto -->
                    <img src="{{ asset('images/' . $selectedLogoPath) }}?v={{ time() }}" 
                         alt="{{ $siteName }}" 
                         class="h-16 w-auto object-contain"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="h-16 w-16 bg-primary rounded-lg flex items-center justify-center" style="display: none;">
                        <i class="bi bi-lightning text-white text-3xl"></i>
                    </div>
                @else
                    <!-- Logo sem imagem - com título -->
                    <div class="h-16 w-16 bg-primary rounded-lg flex items-center justify-center">
                        <i class="bi bi-lightning text-white text-3xl"></i>
                    </div>
                @endif
            </div>
            <h2 class="text-3xl font-bold text-gray-900">
                Criar Conta Admin
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                {{ \App\Models\Setting::get('site_name', 'Laser Link') }}
            </p>
        </div>

        <!-- Formulário de Registro -->
        <div class="bg-white py-8 px-6 shadow-lg rounded-lg">
            <form class="space-y-6" method="POST" action="{{ route('admin.register.post') }}">
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
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('password') border-red-500 @enderror"
                               placeholder="Mínimo 8 caracteres">
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
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('password_confirmation') border-red-500 @enderror"
                               placeholder="Confirme sua senha">
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
                        <a href="{{ route('admin.login') }}" 
                           class="font-medium text-primary hover:text-red-700">
                            Fazer login
                        </a>
                    </p>
                </div>
            </form>

        <div class="mt-6">
            <a href="{{ route('oauth.google.redirect', ['intent' => 'register']) }}"
               class="w-full flex items-center justify-center gap-2 bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50">
                <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google" class="h-5 w-5">
                Registrar com Google
            </a>
        </div>
        </div>

        <!-- Footer -->
        <div class="text-center">
            <p class="text-xs text-gray-500">
                © {{ date('Y') }} {{ \App\Models\Setting::get('site_name', 'Laser Link') }}. Todos os direitos reservados.
            </p>
        </div>
    </div>
</body>
</html>
