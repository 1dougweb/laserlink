    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', 'Dashboard - Laser Link')</title>
        
        <!-- Tailwind CSS CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
        
        <!-- Alpine.js -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        <!-- Axios -->
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script>
            window.axios = axios;
            window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        </script>

        @stack('scripts')
        
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
            
            .bg-secondary { background-color: var(--secondary-color) !important; }
            .text-secondary { color: var(--secondary-color) !important; }
            
            .bg-accent { background-color: var(--accent-color) !important; }
            .text-accent { color: var(--accent-color) !important; }
            
            /* Custom scrollbar - invisible */
            .sidebar-scroll::-webkit-scrollbar { 
                display: none;  /* Safari and Chrome */
            }
            
            .sidebar-scroll {
                -ms-overflow-style: none;  /* IE and Edge */
                scrollbar-width: none;  /* Firefox */
            }
        </style>
        
        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        
        <!-- Alpine is bootstrapped via resources/js/app.js -->
    </head>
    <body class="bg-gray-50" x-data="dashboardLayout()">
        <div class="flex h-screen">
            <!-- Sidebar -->
            <div class="hidden md:flex md:w-64 md:flex-col" :class="{ 'md:flex': !sidebarOpen, 'md:hidden': sidebarOpen }">
                <div class="flex flex-col flex-grow pt-5 bg-white overflow-y-auto sidebar-scroll border-r border-gray-200">
                    <!-- Logo -->
                    <div class="flex items-center flex-shrink-0 px-4 mb-8">
                        <a href="{{ route('store.index') }}" class="flex items-center">
                            @php
                                $sidebarLogoPath = \App\Models\Setting::get('sidebar_logo_path');
                                $logoPath = \App\Models\Setting::get('logo_path');
                                $selectedLogoPath = $sidebarLogoPath ?: $logoPath;
                                $siteName = \App\Models\Setting::get('site_name', 'Laser Link');
                            @endphp
                            
                            @if($selectedLogoPath)
                                <!-- Logo com imagem - título oculto -->
                                <img src="{{ asset('images/' . $selectedLogoPath) }}?v={{ time() }}" 
                                    alt="{{ $siteName }}" 
                                    class="h-8 w-auto object-contain"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="h-8 w-8 bg-primary rounded-lg flex items-center justify-center" style="display: none;">
                                    <i class="bi bi-lightning text-white text-lg"></i>
                                </div>
                            @else
                                <!-- Logo sem imagem - com título -->
                                <div class="h-8 w-8 bg-primary rounded-lg flex items-center justify-center">
                                    <i class="bi bi-lightning text-white text-lg"></i>
                                </div>
                                <span class="ml-2 text-xl font-bold text-gray-900">{{ $siteName }}</span>
                            @endif
                        </a>
                    </div>
                    
                    <!-- Navigation -->
                    <nav class="flex-1 px-2 space-y-1">
                        <a href="{{ route('dashboard') }}" 
                        class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i class="bi bi-speedometer2 mr-3 text-lg"></i>
                            Dashboard
                        </a>
                        
                        <a href="{{ route('profile.edit') }}" 
                        class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('profile.*') ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i class="bi bi-person mr-3 text-lg"></i>
                            Meu Perfil
                        </a>
                        
                        <a href="{{ route('store.user-orders') }}" 
                        class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('store.user-orders') ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i class="bi bi-bag mr-3 text-lg"></i>
                            Meus Pedidos
                        </a>
                        
                        <a href="{{ route('store.cart') }}" 
                        class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('store.cart') ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i class="bi bi-cart mr-3 text-lg"></i>
                            Carrinho
                        </a>
                        
                        <a href="{{ route('store.index') }}" 
                        class="group flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50 hover:text-gray-900">
                            <i class="bi bi-shop mr-3 text-lg"></i>
                            Loja
                        </a>
                    </nav>
                    
                    
                </div>
            </div>

            <!-- Mobile sidebar overlay -->
            <div x-show="sidebarOpen" 
                x-transition:enter="transition-opacity ease-linear duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-linear duration-300"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 flex z-40 md:hidden">
                <div class="fixed inset-0 bg-gray-600 bg-opacity-75" @click="sidebarOpen = false"></div>
                <div class="relative flex-1 flex flex-col max-w-xs w-full bg-white">
                    <div class="absolute top-0 right-0 -mr-12 pt-2">
                        <button @click="sidebarOpen = false" 
                                class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                            <i class="bi bi-x text-white text-xl"></i>
                        </button>
                    </div>
                    <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                        <div class="flex-shrink-0 flex items-center px-4">
                            <a href="{{ route('store.index') }}" class="flex items-center">
                                @php
                                    $sidebarLogoPath = \App\Models\Setting::get('sidebar_logo_path');
                                    $logoPath = \App\Models\Setting::get('logo_path');
                                    $selectedLogoPath = $sidebarLogoPath ?: $logoPath;
                                    $siteName = \App\Models\Setting::get('site_name', 'Laser Link');
                                @endphp
                                
                                @if($selectedLogoPath)
                                    <!-- Logo com imagem - título oculto -->
                                    <img src="{{ asset('images/' . $selectedLogoPath) }}?v={{ time() }}" 
                                        alt="{{ $siteName }}" 
                                        class="h-8 w-auto object-contain"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="h-8 w-8 bg-primary rounded-lg flex items-center justify-center" style="display: none;">
                                        <i class="bi bi-lightning text-white text-lg"></i>
                                    </div>
                                @else
                                    <!-- Logo sem imagem - com título -->
                                    <div class="h-8 w-8 bg-primary rounded-lg flex items-center justify-center">
                                        <i class="bi bi-lightning text-white text-lg"></i>
                                    </div>
                                    <span class="ml-2 text-xl font-bold text-gray-900">{{ $siteName }}</span>
                                @endif
                            </a>
                        </div>
                        <nav class="mt-5 px-2 space-y-1">
                            <a href="{{ route('dashboard') }}" 
                            class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                <i class="bi bi-speedometer2 mr-4 text-lg"></i>
                                Dashboard
                            </a>
                            
                            <a href="{{ route('profile.edit') }}" 
                            class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('profile.*') ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                <i class="bi bi-person mr-4 text-lg"></i>
                                Meu Perfil
                            </a>
                            
                            <a href="{{ route('store.user-orders') }}" 
                            class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('store.user-orders') ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                <i class="bi bi-bag mr-4 text-lg"></i>
                                Meus Pedidos
                            </a>
                            
                            <a href="{{ route('store.cart') }}" 
                            class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('store.cart') ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                <i class="bi bi-cart mr-4 text-lg"></i>
                                Carrinho
                            </a>
                            
                            <a href="{{ route('store.index') }}" 
                            class="group flex items-center px-2 py-2 text-base font-medium rounded-md text-gray-600 hover:bg-gray-50 hover:text-gray-900">
                                <i class="bi bi-shop mr-4 text-lg"></i>
                                Loja
                            </a>
                        </nav>
                        
                        <!-- Seções de Login e Registro Mobile -->
                        <div class="px-4 py-4 border-t border-gray-200">
                            <div class="space-y-2">
                                <a href="{{ route('login') }}" 
                                class="group flex items-center px-3 py-2 text-base font-medium rounded-md text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors">
                                    <i class="bi bi-box-arrow-in-right mr-4 text-lg"></i>
                                    Entrar
                                </a>
                                
                                <a href="{{ route('register') }}" 
                                class="group flex items-center px-3 py-2 text-base font-medium rounded-md bg-primary text-white hover:bg-red-700 transition-colors">
                                    <i class="bi bi-person-plus mr-4 text-lg"></i>
                                    Cadastrar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <div class="flex flex-col w-0 flex-1 overflow-hidden">
                <!-- Top navigation -->
                <div class="relative z-10 flex-shrink-0 flex h-16 bg-white shadow">
                    <button @click="sidebarOpen = true" 
                            class="px-4 border-r border-gray-200 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary md:hidden">
                        <i class="bi bi-list text-xl"></i>
                    </button>
                    
                    <div class="flex-1 px-4 flex justify-between">
                        <div class="flex-1 flex items-center">
                            <h1 class="text-2xl font-semibold text-gray-900">@yield('page-title', 'Dashboard')</h1>
                        </div>
                        
                        <div class="ml-4 flex items-center md:ml-6">
                            <!-- Notifications -->
                            <x-customer-notifications :user-id="auth()->id()" />

                            <!-- Profile dropdown -->
                            <div class="ml-3 relative" x-data="{ open: false }">
                                <div>
                                    <button @click="open = !open" 
                                            class="max-w-xs bg-white flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                                        <span class="sr-only">Abrir menu do usuário</span>
                                        <div class="h-8 w-8 rounded-full bg-primary flex items-center justify-center">
                                            <i class="bi bi-person text-white"></i>
                                        </div>
                                        <span class="ml-2 text-gray-700 font-medium">{{ Auth::user()->name }}</span>
                                        <i class="bi bi-chevron-down ml-1 text-gray-400"></i>
                                    </button>
                                </div>
                                
                                <div x-show="open" 
                                    @click.away="open = false"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
                                    <a href="{{ route('profile.edit') }}" 
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="bi bi-person mr-2"></i>Meu Perfil
                                    </a>
                                    <a href="{{ route('store.user-orders') }}" 
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="bi bi-bag mr-2"></i>Meus Pedidos
                                    </a>
                                    <a href="{{ route('store.cart') }}" 
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="bi bi-cart mr-2"></i>Carrinho
                                    </a>
                                    <div class="border-t border-gray-100"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" 
                                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="bi bi-box-arrow-right mr-2"></i>Sair
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Page content -->
                <main class="flex-1 relative overflow-y-auto focus:outline-none">
                    <div class="py-6">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                            @yield('content')
                        </div>
                    </div>
                </main>
            </div>
        </div>

        <script>
            function dashboardLayout() {
                return {
                    sidebarOpen: false,
                    
                    init() {
                        // Close mobile sidebar on route change
                        this.$watch('$store.router', () => {
                            this.sidebarOpen = false;
                        });
                    }
                }
            }
        </script>
    </body>
    </html>
