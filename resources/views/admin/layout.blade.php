<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    @php
        $faviconPath = \App\Models\Setting::get('favicon_path');
        $faviconUrl = $faviconPath ? url('storage/' . $faviconPath) : url('/favicon.ico');
    @endphp
    
    <!-- Favicon Dinâmico -->
    <link rel="icon" href="{{ $faviconUrl }}">
    
    <title>@yield('title', 'Admin - Laser Link')</title>
    
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
    
    <!-- CSS personalizado para cores dinâmicas -->
    <style>
        @php
            $primaryColor = \App\Models\Setting::get('primary_color', '#EE0000');
            $secondaryColor = \App\Models\Setting::get('secondary_color', '#f8f9fa');
            $accentColor = \App\Models\Setting::get('accent_color', '#ffc107');
            
            // Converter hex para RGB
            $hex = ltrim($primaryColor, '#');
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
            $primaryRgb = "$r, $g, $b";
        @endphp
        
        :root {
            --primary-color: {{ $primaryColor }};
            --secondary-color: {{ $secondaryColor }};
            --accent-color: {{ $accentColor }};
            --primary-color-rgb: {{ $primaryRgb }};
        }
        
        /* Classes de cores */
        .bg-primary { background-color: var(--primary-color) !important; }
        .text-primary { color: var(--primary-color) !important; }
        .border-primary { border-color: var(--primary-color) !important; }
        .hover\:bg-primary:hover { background-color: var(--primary-color) !important; }
        
        .bg-secondary { background-color: var(--secondary-color) !important; }
        .text-secondary { color: var(--secondary-color) !important; }
        
        .bg-accent { background-color: var(--accent-color) !important; }
        .text-accent { color: var(--accent-color) !important; }
        .rotate-180 { transform: rotate(180deg) !important; }
        
        /* Estilos globais de formulários com cores dinâmicas */
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus,
        input[type="number"]:focus,
        input[type="tel"]:focus,
        input[type="url"]:focus,
        input[type="search"]:focus,
        textarea:focus,
        select:focus {
            border-color: var(--primary-color) !important;
            outline: none !important;
            box-shadow: 0 0 0 3px rgba(var(--primary-color-rgb), 0.2) !important;
        }
        
        /* Checkboxes e Radio buttons */
        input[type="checkbox"]:checked,
        input[type="radio"]:checked {
            background-color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
        }
        
        input[type="checkbox"]:focus,
        input[type="radio"]:focus {
            outline: none !important;
            box-shadow: 0 0 0 3px rgba(var(--primary-color-rgb), 0.2) !important;
        }
        
        /* Botões primários */
        .btn-primary {
            background-color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
        }
        
        .btn-primary:hover {
            filter: brightness(0.9);
        }
        
        /* Custom scrollbar - invisible */
        .sidebar-scroll::-webkit-scrollbar { 
            display: none;  /* Safari and Chrome */
        }
        
        .sidebar-scroll {
            scrollbar-width: none;  /* Firefox */
            -ms-overflow-style: none;  /* Internet Explorer 10+ */
        }
    </style>
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- SortableJS para Drag and Drop -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    
    @yield('styles')
    <style>
        .selo { display:flex; align-items:center; justify-content:center; position:relative; width: 50px; height: 50px; padding: 16px; }
        
        /* Estilos para drag and drop */
        .sortable-ghost {
            opacity: 0.4;
            background-color: #e5e7eb;
        }
        
        .sortable-drag {
            opacity: 1;
            cursor: grabbing !important;
        }
        
        .drag-handle {
            cursor: grab;
        }
        
        .drag-handle:active {
            cursor: grabbing;
        }
        
        /* Animação de spin */
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .animate-spin {
            animation: spin 1s linear infinite;
        }
    </style>
    
    <!-- Meta tags adicionais de páginas específicas -->
    @stack('head')
</head>
<body class="bg-gray-100">
    <div x-data="adminLayout()" class="flex h-screen">
        <!-- Sidebar -->
        <div class="bg-gray-900 text-white w-64 h-screen transform transition-transform duration-300 ease-in-out fixed z-30 sidebar-scroll overflow-y-auto" 
             :class="getSidebarClasses()"
             x-show="sidebarOpen || !isMobile">
            <div class="py-6 px-4">
                <!-- Logo -->
                <div class="flex items-center mb-8">
                    @php
                        $logoPath = \App\Models\Setting::get('logo_path');
                            $siteName = \App\Models\Setting::get('site_name', 'Laser Link');
                    @endphp
                    
                    @if($logoPath)
                        <!-- Logo com imagem - título oculto -->
                        <img src="{{ asset('images/' . $logoPath) }}?v={{ time() }}" 
                             alt="{{ $siteName }}" 
                             class="h-10 w-auto object-contain"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="h-10 w-10 bg-primary rounded flex items-center justify-center" style="display: none;">
                            <i class="bi bi-shop text-white text-lg"></i>
                        </div>
                    @else
                        <!-- Logo sem imagem - com título -->
                        <div class="h-8 w-8 mr-3 bg-primary rounded flex items-center justify-center">
                            <i class="bi bi-shop text-white text-sm"></i>
                        </div>
                        <h1 class="text-xl font-bold">{{ $siteName }}</h1>
                    @endif
                </div>
                
                <!-- Search Bar -->
                <div class="mb-6">
                    <div class="relative">
                        <input type="text" 
                               x-model="searchQuery" 
                               @input="filterMenuItems()"
                               placeholder="Pesquisar menu..." 
                               class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary">
                        <i class="bi bi-search absolute right-3 top-3 text-gray-400"></i>
                    </div>
                </div>
                
                <!-- Navigation Menu -->
                <nav class="space-y-2">
                    <template x-for="item in filteredMenuItems" :key="item.name">
                        <div>
                            <template x-if="!item.children">
                                <a :href="item.url" 
                                   class="flex items-center px-3 py-2 rounded-lg transition-colors"
                                   :class="item.active ? 'bg-primary text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white'">
                                    <i :class="item.icon" class="mr-3"></i>
                                    <span x-text="item.name"></span>
                                </a>
                            </template>
                            
                            <template x-if="item.children">
                                <div @click="toggleDropdown(item)"
                                     class="flex items-center px-3 py-2 rounded-lg transition-colors cursor-pointer"
                                     :class="item.active ? 'bg-primary text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white'">
                                    <i :class="item.icon" class="mr-3"></i>
                                    <span x-text="item.name"></span>
                                    <i class="bi bi-chevron-down ml-auto transition-transform duration-200" :class="{ 'rotate-180': item.expanded }"></i>
                                </div>
                            </template>
                            
                            <!-- Submenu -->
                            <div x-show="item.children && item.expanded" class="ml-2 mt-2 space-y-1">
                                <template x-for="child in item.children" :key="child.name">
                                    <a :href="child.url" 
                                       :target="child.target || '_self'"
                                       class="flex items-center px-3 py-2 text-sm text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition-colors">
                                        <i :class="child.icon" class="mr-3"></i>
                                        <span x-text="child.name"></span>
                                        <span x-show="child.badge && child.badge > 0" 
                                              :class="child.badgeClass || 'bg-primary'"
                                              class="ml-auto text-xs text-white px-2 py-0.5 rounded-full font-semibold"
                                              x-text="child.badge"></span>
                                        <i x-show="child.target === '_blank'" class="bi bi-box-arrow-up-right ml-auto text-xs"></i>
                                    </a>
                                </template>
                            </div>
                        </div>
                    </template>
                </nav>
            </div>
        </div>
        
        <!-- Mobile Overlay -->
        <div x-show="sidebarOpen && isMobile" 
             @click="toggleSidebar()"
             class="fixed inset-0 bg-black bg-opacity-50 z-20 lg:hidden"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden transition-all duration-300" 
             :class="getMainContentClasses()">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm border border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <button @click="toggleSidebar()" class="text-gray-600 hover:text-gray-900 mr-4">
                            <i class="bi bi-list text-xl"></i>
                        </button>
                        <h2 class="text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        
                        <!-- Ver Loja -->
                        <a href="{{ route('store.index') }}" 
                           target="_blank"
                           class="flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors font-medium shadow-sm hover:shadow-md">
                            <i class="bi bi-shop mr-2"></i>
                            <span>Ver Loja</span>
                            <i class="bi bi-box-arrow-up-right ml-2 text-sm"></i>
                        </a>
                        
                        <!-- Notifications -->
                        <div class="relative" x-data="notifications()">
                            <button @click="toggleNotifications()" class="relative text-gray-600 hover:text-gray-900">
                                <i class="bi bi-bell text-xl"></i>
                                <span x-show="unreadCount > 0" 
                                      x-text="unreadCount" 
                                      class="absolute -top-1 -right-1 bg-primary text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                </span>
                            </button>
                            
                            <!-- Dropdown de Notificações -->
                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform scale-95"
                                 x-transition:enter-end="opacity-100 transform scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 transform scale-100"
                                 x-transition:leave-end="opacity-0 transform scale-95"
                                 class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg pt-1 z-50 max-h-96 overflow-y-auto"
                                 style="display: none;">
                                 
                                <div class="px-4 py-3 border-b border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-sm font-semibold text-gray-900">Notificações</h3>
                                        <span x-show="unreadCount > 0" class="text-xs text-gray-500">
                                            <span x-text="unreadCount"></span> novos pedidos
                                        </span>
                                    </div>
                                </div>
                                
                                <div x-show="loading" class="px-4 py-8 text-center">
                                    <i class="bi bi-arrow-repeat animate-spin text-2xl text-gray-400"></i>
                                </div>
                                
                                <div x-show="!loading && notificationsList.length === 0" class="px-4 py-8 text-center text-gray-500 text-sm">
                                    Nenhuma notificação
                                </div>
                                
                                <div x-show="!loading && notificationsList.length > 0">
                                    <!-- Botão Marcar Todas como Lidas -->
                                    <div class="px-4 py-2 border-b border-gray-200 bg-gray-50">
                                        <button @click="markAllAsRead()" 
                                                class="text-xs text-primary hover:text-red-700 font-medium">
                                            <i class="bi bi-check-all mr-1"></i>Marcar todas como lidas
                                        </button>
                                    </div>
                                    
                                    <template x-for="notification in notificationsList" :key="notification.id">
                                        <a :href="notification.url" 
                                           @click="markAsRead(notification.id)"
                                           class="block px-4 py-3 hover:bg-gray-50 transition-colors border-b border-gray-100 last:border-b-0"
                                           :class="notification.is_read ? 'opacity-60' : ''">
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0">
                                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center"
                                                         :class="notification.is_new ? 'ring-2 ring-primary' : ''">
                                                        <i class="bi bi-cart-check text-blue-600"></i>
                                                    </div>
                                                </div>
                                                <div class="ml-3 flex-1">
                                                    <div class="flex items-center justify-between">
                                                        <p class="text-sm font-medium text-gray-900" x-text="'#' + notification.order_number"></p>
                                                        <span x-show="notification.is_new" class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-primary text-white">
                                                            Novo
                                                        </span>
                                                    </div>
                                                    <p class="text-sm text-gray-600 mt-1" x-text="notification.customer_name"></p>
                                                    <div class="flex items-center justify-between mt-1">
                                                        <span class="text-xs text-gray-500" x-text="notification.created_at"></span>
                                                        <span class="text-sm font-semibold text-green-600" x-text="'R$ ' + parseFloat(notification.total_amount).toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </template>
                                </div>
                                
                                <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
                                    <a href="{{ route('admin.orders') }}" class="text-sm font-medium text-primary hover:text-red-700 flex items-center justify-center">
                                        Ver todos os pedidos
                                        <i class="bi bi-arrow-right ml-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Comments Notifications -->
                        <div class="relative" x-data="commentsNotifications()">
                            <button @click="toggleComments()" class="relative text-gray-600 hover:text-gray-900">
                                <i class="bi bi-chat-left-text text-xl"></i>
                                <span x-show="pendingCount > 0" 
                                      x-text="pendingCount" 
                                      class="absolute -top-1 -right-1 bg-orange-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                </span>
                            </button>
                            
                            <!-- Dropdown de Comentários -->
                            <div x-show="commentsOpen" 
                                 @click.away="commentsOpen = false"
                                 x-transition
                                 class="absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-lg pt-1 z-50 max-h-96 overflow-y-auto"
                                 style="display: none;">
                                 
                                <div class="px-4 py-3 border-b border-gray-200 bg-gradient-to-r from-orange-50 to-orange-100">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-sm font-semibold text-gray-900 flex items-center">
                                            <i class="bi bi-chat-left-text mr-2"></i>
                                            Comentários Pendentes
                                        </h3>
                                        <span x-show="pendingCount > 0" class="text-xs bg-orange-500 text-white px-2 py-1 rounded-full font-semibold">
                                            <span x-text="pendingCount"></span>
                                        </span>
                                    </div>
                                </div>
                                
                                <div x-show="loading" class="px-4 py-8 text-center">
                                    <i class="bi bi-arrow-repeat animate-spin text-2xl text-gray-400"></i>
                                </div>
                                
                                <div x-show="!loading && commentsList.length === 0" class="px-4 py-8 text-center text-gray-500 text-sm">
                                    <i class="bi bi-chat-left text-3xl mb-2 block"></i>
                                    Nenhum comentário pendente
                                </div>
                                
                                <div x-show="!loading && commentsList.length > 0">
                                    <template x-for="comment in commentsList" :key="comment.id">
                                        <a :href="comment.url" 
                                           class="block px-4 py-3 hover:bg-gray-50 transition-colors border-b border-gray-100 last:border-b-0">
                                            <div class="flex items-start gap-3">
                                                <div class="flex-shrink-0">
                                                    <div class="h-10 w-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 font-bold">
                                                        <span x-text="comment.author.charAt(0)"></span>
                                                    </div>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center justify-between">
                                                        <p class="text-sm font-medium text-gray-900" x-text="comment.author"></p>
                                                        <span class="text-xs text-gray-500" x-text="comment.created_at"></span>
                                                    </div>
                                                    <p class="text-xs text-gray-600 mt-1 mb-1" x-text="comment.post_title"></p>
                                                    <p class="text-sm text-gray-700 line-clamp-2" x-text="comment.content"></p>
                                                </div>
                                            </div>
                                        </a>
                                    </template>
                                </div>
                                
                                <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
                                    <a href="{{ route('admin.comments.index', ['status' => 'pending']) }}" 
                                       class="text-sm font-medium text-primary hover:text-red-700 flex items-center justify-center">
                                        Ver todos os comentários
                                        <i class="bi bi-arrow-right ml-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- User Menu -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900">
                                <img src="{{ auth()->user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}" 
                                     alt="{{ auth()->user()->name }}" 
                                     class="h-8 w-8 rounded-full">
                                <span>{{ auth()->user()->name }}</span>
                                <i class="bi bi-chevron-down"></i>
                            </button>
                            
                            <div x-show="open" 
                                 @click.away="open = false"
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <a href="{{ route('admin.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="bi bi-person mr-2"></i>Perfil
                                </a>
                                <a href="{{ route('admin.settings') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="bi bi-gear mr-2"></i>Configurações
                                </a>
                                <hr class="my-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="bi bi-box-arrow-right mr-2"></i>Sair
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                <!-- Flash Messages -->
                @if(session('success'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            if (typeof window.showNotification === 'function') {
                                window.showNotification(@json(session('success')), 'success');
                            }
                        });
                    </script>
                @endif
                @if(session('error'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            if (typeof window.showNotification === 'function') {
                                window.showNotification(@json(session('error')), 'error');
                            }
                        });
                    </script>
                @endif
                @if(session('warning'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            if (typeof window.showNotification === 'function') {
                                window.showNotification(@json(session('warning')), 'warning');
                            }
                        });
                    </script>
                @endif
                @if(session('info'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            if (typeof window.showNotification === 'function') {
                                window.showNotification(@json(session('info')), 'info');
                            }
                        });
                    </script>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Alpine is bootstrapped via resources/js/app.js -->
    
    <!-- Custom JS -->
    <script>
        function adminLayout() {
            return {
                sidebarOpen: true,
                isMobile: window.innerWidth < 1024,
                searchQuery: '',
                filteredMenuItems: [],
                menuItems: [
                    {
                        name: 'Dashboard',
                        url: '{{ route("admin.dashboard") }}',
                        icon: 'bi bi-speedometer2',
                        active: '{{ request()->routeIs("admin.dashboard") }}',
                        children: null
                    },
                    @can('categories.view')
                    {
                        name: 'Categorias',
                        url: '{{ route("admin.categories.index") }}',
                        icon: 'bi bi-folder',
                        active: '{{ request()->routeIs("admin.categories*") }}',
                        expanded: false,
                        children: [
                            {
                                name: 'Listar Categorias',
                                url: '{{ route("admin.categories.index") }}',
                                icon: 'bi bi-list'
                            },
                            {
                                name: 'Nova Categoria',
                                url: '{{ route("admin.categories.create") }}',
                                icon: 'bi bi-plus'
                            }
                        ]
                    },
                    @endcan
                    @can('products.view')
                    {
                        name: 'Produtos',
                        url: '{{ route("admin.products") }}',
                        icon: 'bi bi-box',
                        active: '{{ request()->routeIs("admin.products*", "admin.stock*") }}',
                        expanded: false,
                        children: [
                            {
                                name: 'Listar Produtos',
                                url: '{{ route("admin.products") }}',
                                icon: 'bi bi-list'
                            },
                            {
                                name: 'Novo Produto',
                                url: '{{ route("admin.products.create") }}',
                                icon: 'bi bi-plus'
                            },
                            {
                                name: 'Estoque de Produtos',
                                url: '{{ route("admin.stock.index") }}',
                                icon: 'bi bi-box-seam'
                            }
                        ]
                    },
                    @endcan
                    @can('raw-materials.view')
                    {
                        name: 'Matéria-Prima',
                        url: '{{ route("admin.raw-materials.index") }}',
                        icon: 'bi bi-layers',
                        active: '{{ request()->routeIs("admin.raw-materials*", "admin.suppliers*") }}',
                        expanded: false,
                        children: [
                            {
                                name: 'Listar Materiais',
                                url: '{{ route("admin.raw-materials.index") }}',
                                icon: 'bi bi-list'
                            },
                            {
                                name: 'Novo Material',
                                url: '{{ route("admin.raw-materials.create") }}',
                                icon: 'bi bi-plus'
                            },
                            {
                                name: 'Movimentações',
                                url: '{{ route("admin.raw-materials.movements") }}',
                                icon: 'bi bi-arrow-left-right'
                            },
                            {
                                name: 'Fornecedores',
                                url: '{{ route("admin.suppliers.index") }}',
                                icon: 'bi bi-truck'
                            }
                        ]
                    },
                    @endcan
                    @can('extra-fields.view')
                    {
                        name: 'Campos Extras',
                        url: '{{ route("admin.extra-fields.index") }}',
                        icon: 'bi bi-gear-fill',
                        active: '{{ request()->routeIs("admin.extra-fields*") }}',
                        expanded: false,
                        children: [
                            {
                                name: 'Listar Campos',
                                url: '{{ route("admin.extra-fields.index") }}',
                                icon: 'bi bi-list'
                            },
                            {
                                name: 'Novo Campo',
                                url: '{{ route("admin.extra-fields.create") }}',
                                icon: 'bi bi-plus'
                            }
                        ]
                    },
                    @endcan
                    @can('budgets.view')
                    {
                        name: 'Orçamentos',
                        url: '{{ route("admin.budgets.index") }}',
                        icon: 'bi bi-file-text',
                        active: '{{ request()->routeIs("admin.budgets*") }}',
                        expanded: false,
                        children: [
                            {
                                name: 'Listar Orçamentos',
                                url: '{{ route("admin.budgets.index") }}',
                                icon: 'bi bi-list'
                            },
                            {
                                name: 'Novo Orçamento',
                                url: '{{ route("admin.budgets.create") }}',
                                icon: 'bi bi-plus'
                            }
                        ]
                    },
                    @endcan
                    @can('orders.view')
                    {
                        name: 'Pedidos',
                        url: '{{ route("admin.orders") }}',
                        icon: 'bi bi-cart-check',
                        active: '{{ request()->routeIs("admin.orders*") }}',
                        children: null
                    },
                    @endcan
                    @can('reports.view')
                    {
                        name: 'Relatórios',
                        url: '{{ route("admin.reports.index") }}',
                        icon: 'bi bi-graph-up',
                        active: '{{ request()->routeIs("admin.reports*") }}',
                        expanded: false,
                        children: [
                            {
                                name: 'Dashboard',
                                url: '{{ route("admin.reports.index") }}',
                                icon: 'bi bi-speedometer2'
                            },
                            {
                                name: 'Vendas',
                                url: '{{ route("admin.reports.sales") }}',
                                icon: 'bi bi-currency-dollar'
                            },
                            {
                                name: 'Produtos',
                                url: '{{ route("admin.reports.products") }}',
                                icon: 'bi bi-box'
                            }
                        ]
                    },
                    @endcan
                    @can('posts.view') 
                    {
                        name: 'Blog',
                        url: '{{ route("admin.posts.index") }}',
                        icon: 'bi bi-newspaper',
                        active: '{{ request()->routeIs("admin.posts*") || request()->routeIs("admin.comments*") }}',
                        expanded: false,
                        children: [
                            {
                                name: 'Listar Posts',
                                url: '{{ route("admin.posts.index") }}',
                                icon: 'bi bi-list'
                            },
                            {
                                name: 'Novo Post',
                                url: '{{ route("admin.posts.create") }}',
                                icon: 'bi bi-plus'
                            },
                            {
                                name: 'Comentários',
                                url: '{{ route("admin.comments.index") }}',
                                icon: 'bi bi-chat-left-text',
                                badge: '{{ \App\Models\Comment::pending()->count() }}',
                                badgeClass: 'bg-orange-500'
                            },
                            {
                                name: 'Ver Blog Público',
                                url: '{{ route("blog.index") }}',
                                icon: 'bi bi-eye',
                                target: '_blank'
                            }
                        ]
                    },
                    @endcan
                    {
                        name: 'Páginas',
                        url: '{{ route("admin.pages.index") }}',
                        icon: 'bi bi-file-earmark-text',
                        active: '{{ request()->routeIs("admin.pages*") }}',
                        expanded: false,
                        children: [
                            {
                                name: 'Listar Páginas',
                                url: '{{ route("admin.pages.index") }}',
                                icon: 'bi bi-list'
                            },
                            {
                                name: 'Nova Página',
                                url: '{{ route("admin.pages.create") }}',
                                icon: 'bi bi-plus'
                            }
                        ]
                    },
                    @can('settings.view')
                    {
                        name: 'Configurações',
                        url: '#',
                        icon: 'bi bi-gear',
                        active: '{{ request()->routeIs("admin.settings*") }}',
                        expanded: false,
                        children: [
                                {
                                    name: 'Geral',
                                    url: '{{ route("admin.settings.general") }}',
                                    icon: 'bi bi-gear'
                                },
                                {
                                    name: 'WhatsApp',
                                    url: '{{ route("admin.settings.whatsapp") }}',
                                    icon: 'bi bi-whatsapp'
                                },
                                {
                                    name: 'Aparência',
                                    url: '{{ route("admin.settings.appearance") }}',
                                    icon: 'bi bi-image'
                                },
                                {
                                    name: 'SEO',
                                    url: '{{ route("admin.settings.seo") }}',
                                    icon: 'bi bi-search'
                                },
                                {
                                    name: 'Gemini AI',
                                    url: '{{ route("admin.settings.gemini") }}',
                                    icon: 'bi bi-robot'
                                },
                                {
                                    name: 'Email/SMTP',
                                    url: '{{ route("admin.settings.email") }}',
                                    icon: 'bi bi-envelope-at'
                                },
                                {
                                    name: 'Sitemap',
                                    url: '{{ route("admin.settings.sitemap") }}',
                                    icon: 'bi bi-diagram-3'
                                },
                                {
                                    name: 'Cache',
                                    url: '{{ route("admin.settings.cache") }}',
                                    icon: 'bi bi-speedometer2'
                                },
                                {
                                    name: 'Loja Virtual',
                                    url: '{{ route("admin.store-settings") }}',
                                    icon: 'bi bi-shop'
                                },
                                {
                                    name: 'Usuários',
                                    url: '{{ route("admin.users.index") }}',
                                    icon: 'bi bi-people'
                                },
                                {
                                    name: 'Funções',
                                    url: '{{ route("admin.roles.index") }}',
                                    icon: 'bi bi-shield-lock'
                                },
                                {
                                    name: 'Permissões',
                                    url: '{{ route("admin.permissions.index") }}',
                                    icon: 'bi bi-shield-check'
                                }
                            ]
                        },
                        @endcan
                        {
                            name: 'WhatsApp',
                            url: '#',
                            icon: 'bi bi-whatsapp',
                            active: '{{ request()->routeIs("admin.whatsapp*") }}',
                            expanded: false,
                            children: [
                                {
                                    name: 'Configurações',
                                    url: '{{ route("admin.whatsapp.settings") }}',
                                    icon: 'bi bi-gear'
                                },
                                {
                                    name: 'Instâncias',
                                    url: '{{ route("admin.whatsapp.instances.index") }}',
                                    icon: 'bi bi-phone'
                                },
                                {
                                    name: 'Templates',
                                    url: '{{ route("admin.whatsapp.templates.index") }}',
                                    icon: 'bi bi-chat-text'
                                },
                                {
                                    name: 'Notificações',
                                    url: '{{ route("admin.whatsapp.notifications.index") }}',
                                    icon: 'bi bi-bell'
                                }
                            ]
                        },
                        {
                            name: 'Arquivos',
                            url: '{{ route("admin.admin.file-manager") }}',
                            icon: 'bi bi-folder',
                            active: '{{ request()->routeIs("admin.admin.file-manager*", "file-manager*") }}'
                        },
                        @role('webmaster')
                        {
                            name: 'Atualizações',
                            url: '{{ route("admin.changelogs.index") }}',
                            icon: 'bi bi-rocket-takeoff-fill',
                            active: '{{ request()->routeIs("admin.changelogs*") }}',
                            children: null
                        }
                        @endrole
                ],
                filteredMenuItems: [],
                
                init() {
                    this.filteredMenuItems = this.menuItems;
                    // Inicializar estado baseado no tamanho da tela
                    this.updateLayout();
                    window.addEventListener('resize', () => this.updateLayout());
                },
                
                updateLayout() {
                    this.isMobile = window.innerWidth < 1024;
                    // No mobile, fechar sidebar por padrão
                    if (this.isMobile) {
                        this.sidebarOpen = false;
                    }
                },
                
                toggleSidebar() {
                    this.sidebarOpen = !this.sidebarOpen;
                },

                toggleDropdown(item) {
                    // Fechar todos os outros dropdowns
                    this.menuItems.forEach(menuItem => {
                        if (menuItem !== item) {
                            menuItem.expanded = false;
                        }
                    });
                    // Alternar o dropdown atual
                    item.expanded = !item.expanded;
                },
                
                // Método para calcular classes do sidebar
                getSidebarClasses() {
                    if (this.isMobile) {
                        return this.sidebarOpen ? 'translate-x-0' : '-translate-x-full';
                    } else {
                        // No desktop, permitir que o sidebar seja controlado pelo toggle
                        return this.sidebarOpen ? 'translate-x-0' : '-translate-x-full';
                    }
                },
                
                // Método para calcular classes do conteúdo principal
                getMainContentClasses() {
                    if (this.isMobile) {
                        return 'ml-0'; // No mobile, sempre sem margem (sidebar é overlay)
                    } else {
                        // No desktop com sidebar fixed, sempre dar margem quando sidebar estiver visível
                        return this.sidebarOpen ? 'ml-64' : 'ml-0';
                    }
                },
                
                filterMenuItems() {
                    if (!this.searchQuery) {
                        this.filteredMenuItems = this.menuItems;
                        return;
                    }
                    
                    this.filteredMenuItems = this.menuItems.filter(item => 
                        item.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                        (item.children && item.children.some(child => 
                            child.name.toLowerCase().includes(this.searchQuery.toLowerCase())
                        ))
                    );
                }
            }
        }
    </script>
    
    @yield('scripts')
    @stack('scripts')
    
    <!-- Notifications System -->
    <script>
        function notifications() {
            return {
                open: false,
                loading: false,
                notificationsList: [],
                unreadCount: 0,
                
                init() {
                    // Carregar notificações ao iniciar
                    this.loadNotifications();
                    
                    // Atualizar a cada 60 segundos
                    setInterval(() => this.loadNotifications(), 60000);
                },
                
                async toggleNotifications() {
                    this.open = !this.open;
                    
                    if (this.open && this.notificationsList.length === 0) {
                        await this.loadNotifications();
                    }
                },
                
                async loadNotifications() {
                    this.loading = true;
                    
                    try {
                        const response = await fetch('{{ route("admin.api.notifications.orders") }}');
                        if (response.ok) {
                            const data = await response.json();
                            this.notificationsList = data.notifications || [];
                            this.unreadCount = data.unread_count || 0;
                        }
                    } catch (error) {
                        console.error('Erro ao carregar notificações:', error);
                    } finally {
                        this.loading = false;
                    }
                },
                
                async markAsRead(orderId) {
                    try {
                        const response = await fetch('{{ route("admin.api.notifications.mark-as-read") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ order_id: orderId })
                        });
                        
                        if (response.ok) {
                            // Atualizar localmente
                            const notification = this.notificationsList.find(n => n.id === orderId);
                            if (notification) {
                                notification.is_new = false;
                                notification.is_read = true;
                            }
                            this.unreadCount = Math.max(0, this.unreadCount - 1);
                        }
                    } catch (error) {
                        console.error('Erro ao marcar notificação como lida:', error);
                    }
                },
                
                async markAllAsRead() {
                    try {
                        const response = await fetch('{{ route("admin.api.notifications.mark-all-as-read") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });
                        
                        if (response.ok) {
                            // Marcar todas como lidas localmente
                            this.notificationsList.forEach(notification => {
                                notification.is_new = false;
                                notification.is_read = true;
                            });
                            this.unreadCount = 0;
                        }
                    } catch (error) {
                        console.error('Erro ao marcar todas como lidas:', error);
                    }
                }
            }
        }

        // Notificações de Comentários
        function commentsNotifications() {
            return {
                commentsOpen: false,
                loading: false,
                commentsList: [],
                pendingCount: 0,
                
                init() {
                    // Carregar comentários ao iniciar
                    this.loadComments();
                    
                    // Atualizar a cada 60 segundos
                    setInterval(() => this.loadComments(), 60000);
                },
                
                async toggleComments() {
                    this.commentsOpen = !this.commentsOpen;
                    
                    if (this.commentsOpen && this.commentsList.length === 0) {
                        await this.loadComments();
                    }
                },
                
                async loadComments() {
                    this.loading = true;
                    
                    try {
                        const response = await fetch('{{ route("admin.api.comments.pending") }}');
                        if (response.ok) {
                            const data = await response.json();
                            this.commentsList = data.comments || [];
                            this.pendingCount = data.count || 0;
                        }
                    } catch (error) {
                        console.error('Erro ao carregar comentários:', error);
                    } finally {
                        this.loading = false;
                    }
                }
            }
        }
    </script>
    
    <!-- Online Users Tracker -->
    <script>
        function onlineUsers() {
            return {
                onlineCount: 0,
                
                init() {
                    this.updateCount();
                    // Atualizar a cada 30 segundos
                    setInterval(() => this.updateCount(), 30000);
                },
                
                async updateCount() {
                    try {
                        const response = await fetch('/api/online-users');
                        if (response.ok) {
                            const data = await response.json();
                            this.onlineCount = data.data?.total || 0;
                        }
                    } catch (error) {
                        this.onlineCount = 0;
                    }
                }
            }
        }
    </script>
    
    <!-- Notification Container -->
    <x-notification-container />
    
    <!-- Global Notification Function -->
    <script>
        // Função global para mostrar notificações em todo o admin
        window.showNotification = function(message, type) {
            // Remover notificações anteriores
            document.querySelectorAll('.admin-notification').forEach(n => n.remove());
            
            const notification = document.createElement('div');
            let bgClass, borderClass, textClass, icon;
            
            if (type === 'success') {
                bgClass = 'bg-green-100';
                borderClass = 'border-green-400';
                textClass = 'text-green-700';
                icon = 'bi-check-circle';
            } else if (type === 'info') {
                bgClass = 'bg-blue-100';
                borderClass = 'border-blue-400';
                textClass = 'text-blue-700';
                icon = 'bi-info-circle';
            } else if (type === 'warning') {
                bgClass = 'bg-yellow-100';
                borderClass = 'border-yellow-400';
                textClass = 'text-yellow-700';
                icon = 'bi-exclamation-triangle';
            } else {
                bgClass = 'bg-red-100';
                borderClass = 'border-red-400';
                textClass = 'text-red-700';
                icon = 'bi-exclamation-triangle';
            }
            
            notification.className = `admin-notification fixed top-20 right-4 ${bgClass} border ${borderClass} ${textClass} px-6 py-4 rounded-lg shadow-xl z-[99999] min-w-[400px] max-w-[600px] transform transition-all duration-300`;
            notification.innerHTML = `
                <div class="flex items-start">
                    <i class="bi ${icon} text-xl mt-0.5 flex-shrink-0"></i>
                    <span class="font-medium leading-relaxed break-words">${message}</span>
                </div>
            `;
            
            // Adicionar com animação
            notification.style.transform = 'translateX(600px)';
            notification.style.opacity = '0';
            document.body.appendChild(notification);
            
            // Animar entrada
            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
                notification.style.opacity = '1';
            }, 10);
            
            // Auto-hide após 6 segundos (mais tempo para ler mensagens longas)
            setTimeout(() => {
                notification.style.transform = 'translateX(600px)';
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 300);
            }, 6000);
        };
    </script>
    
    
    <!-- Quill.js Editor Global -->
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar Quill em todos os elementos com classe 'quill-editor'
        const quillEditors = document.querySelectorAll('.quill-editor');
        
        quillEditors.forEach(function(textarea, index) {
            // Criar um ID único se não existir
            if (!textarea.id) {
                textarea.id = 'quill-editor-' + index;
            }
            
            // Criar container para o Quill
            const quillContainer = document.createElement('div');
            quillContainer.id = 'quill-container-' + textarea.id;
            quillContainer.style.height = '300px';
            
            // Inserir o container após o textarea
            textarea.parentNode.insertBefore(quillContainer, textarea.nextSibling);
            
            // Esconder o textarea original
            textarea.style.display = 'none';
            
            // Inicializar Quill
            const quill = new Quill('#' + quillContainer.id, {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'color': [] }, { 'background': [] }],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'indent': '-1'}, { 'indent': '+1' }],
                        [{ 'align': [] }],
                        ['link', 'image'],
                        ['clean']
                    ]
                },
                placeholder: 'Digite seu conteúdo aqui...'
            });
            
            // Carregar conteúdo existente
            if (textarea.value) {
                quill.root.innerHTML = textarea.value;
            }
            
            // Sincronizar conteúdo com o textarea (para envio do formulário)
            quill.on('text-change', function() {
                textarea.value = quill.root.innerHTML;
            });
            
            // Também sincronizar no evento 'input'
            quill.on('input', function() {
                textarea.value = quill.root.innerHTML;
            });
            
        });
    });
    </script>
    
    @stack('scripts')
</body>
</html>
