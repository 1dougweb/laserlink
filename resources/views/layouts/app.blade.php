<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Laravel'))</title>

        @yield('meta')

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Tailwind CSS CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
        
        <!-- Alpine.js -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        
        <!-- Axios -->
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script>
            window.axios = axios;
            window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        </script>
        
        <!-- Custom CSS -->
        <style>
            /* Animação de gradiente para imagens que falharam ao carregar */
            .gradient-background {
                background: linear-gradient(120deg, #f0f0f0 25%, #e0e0e0 37%, #f0f0f0 63%);
                background-size: 400% 100%;
                animation: skeleton-shimmer 4s ease-in-out infinite;
                min-height: 300px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 8px;
            }

            @keyframes skeleton-shimmer {
                0% {
                    background-position: -300% 0;
                }
                100% {
                    background-position: 600% 0;
                }
            }

            /* Skeleton específico para thumbnails do carrinho */
            .cart-skeleton {
                background: linear-gradient(120deg, #f0f0f0 25%, #e0e0e0 37%, #f0f0f0 63%);
                background-size: 400% 100%;
                animation: skeleton-shimmer 1.5s ease-in-out infinite;
                border-radius: 8px;
                width: 100%;
                height: 100%;
            }

            /* Header styles */
            .header {
                background-color: #ffffff;
                margin: 0;
            }

            .sticky-header {
                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);
                background-color: #ffffff;
                animation: slideDown 0.3s ease-out;
            }

            @supports not (backdrop-filter: blur(16px)) {
                .sticky-header {
                    background-color: #ffffff;
                }
            }

            @keyframes slideDown {
                from {
                    transform: translateY(-100%);
                    opacity: 0;
                }
                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }

            body {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
        </style>
        
        @stack('styles')
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                @yield('content')
                @isset($slot)
                    {{ $slot }}
                @endisset
            </main>
        </div>
        
        <!-- Notification Container -->
        <x-notification-container />
        
        @stack('scripts')
    </body>
</html>
