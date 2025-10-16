<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Checkout') - {{ config('app.name', 'Laser Link') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#EE0000',
                        secondary: '#f8f9fa',
                        accent: '#ffc107',
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js e plugins -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/mask@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
    </style>

    @stack('styles')
</head>
<body class="antialiased">
    <!-- Logo Header -->
    <div class="text-center">
            <div class="flex justify-center bg-white py-4">
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
            
        </div>
    </div>

    <!-- Progress Steps (optional) -->
    @yield('progress')

    <!-- Main Content -->
    <main class="py-8">
        @yield('content')
    </main>

    <!-- Footer minimal -->
    <div class="bg-white border-t mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="text-center text-sm text-gray-600">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'Laser Link') }}. Todos os direitos reservados.</p>
                <p class="mt-1">
                    <i class="bi bi-shield-check text-green-600"></i> Compra 100% segura
                </p>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>

