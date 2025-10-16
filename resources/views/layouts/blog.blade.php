<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Mobile Meta Tags -->
    <meta name="theme-color" content="#1F2937">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Blog Laser Link">

    <!-- PWA Manifest -->
    <link rel="manifest" href="{{ url('/manifest.json') }}">
    
    @php
        $faviconPath = \App\Models\Setting::get('favicon_path');
        // Verificar se o arquivo realmente existe antes de usar
        if ($faviconPath && file_exists(public_path('storage/' . $faviconPath))) {
            $faviconUrl = url('storage/' . $faviconPath);
        } elseif (file_exists(public_path('images/icon.svg'))) {
            $faviconUrl = url('/images/icon.svg');
        } else {
            $faviconUrl = url('/favicon.ico');
        }
        
        // Meta tags SEO do controller ou defaults
        $seoData = $seoData ?? [];
        $metaTags = $seoData['metaTags'] ?? [];
        $title = $metaTags['title'] ?? config('app.name') . ' - Blog';
        $description = $metaTags['description'] ?? 'Blog da Laser Link - Artigos sobre comunicação visual, acrílicos, troféus e muito mais.';
        $keywords = $metaTags['keywords'] ?? 'blog, comunicação visual, acrílico, dicas, tutoriais';
        $canonical = $metaTags['canonical'] ?? url()->current();
        
        // Open Graph e Twitter
        $ogData = $metaTags['og'] ?? [];
        $twitterData = $metaTags['twitter'] ?? [];
        $ogImage = $ogData['image'] ?? asset('images/logos/logo.png');
        $ogType = $ogData['type'] ?? 'website';
        $ogTitle = $ogData['title'] ?? $title;
        $ogDescription = $ogData['description'] ?? $description;
        
        // Article specific
        $publishedTime = $ogData['article:published_time'] ?? null;
        $modifiedTime = $ogData['article:modified_time'] ?? null;
        $author = $ogData['article:author'] ?? null;
        $articleSection = $ogData['article:section'] ?? null;
        $articleTags = is_string($ogData['article:tag'] ?? null) ? explode(',', $ogData['article:tag']) : [];
    @endphp
    
    <!-- Favicon -->
    <link rel="icon" href="{{ $faviconUrl }}">
    <link rel="apple-touch-icon" href="{{ $faviconUrl }}">
    <link rel="icon" type="image/png" sizes="144x144" href="{{ url('/icon-144x144.png') }}">

    <title>{{ $title }}</title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="{{ $description }}">
    <meta name="keywords" content="{{ $keywords }}">
    <meta name="author" content="{{ $author ?? 'Laser Link' }}">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <link rel="canonical" href="{{ $canonical }}">

    <!-- Open Graph -->
    <meta property="og:type" content="{{ $ogType }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:title" content="{{ $ogTitle }}">
    <meta property="og:description" content="{{ $ogDescription }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:locale" content="pt_BR">
    
    @if($ogType === 'article')
    @if($publishedTime)<meta property="article:published_time" content="{{ $publishedTime }}">@endif
    @if($modifiedTime)<meta property="article:modified_time" content="{{ $modifiedTime }}">@endif
    @if($author)<meta property="article:author" content="{{ $author }}">@endif
    @if($articleSection)<meta property="article:section" content="{{ $articleSection }}">@endif
    @if(count($articleTags) > 0)@foreach($articleTags as $tag)<meta property="article:tag" content="{{ trim($tag) }}">@endforeach @endif
    @endif

    <!-- Twitter Card -->
    <meta name="twitter:card" content="{{ $twitterData['card'] ?? 'summary_large_image' }}">
    <meta name="twitter:url" content="{{ $canonical }}">
    <meta name="twitter:title" content="{{ $twitterData['title'] ?? $ogTitle }}">
    <meta name="twitter:description" content="{{ $twitterData['description'] ?? $ogDescription }}">
    <meta name="twitter:image" content="{{ $twitterData['image'] ?? $ogImage }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
    /* Estilos para conteúdo do blog */
    .blog-content {
        line-height: 1.7;
        color: #374151;
    }
    
    .blog-content h1 {
        font-size: 2.25rem;
        font-weight: 700;
        color: #111827;
        margin: 2rem 0 1rem 0;
        line-height: 1.2;
    }
    
    .blog-content h2 {
        font-size: 1.875rem;
        font-weight: 600;
        color: #111827;
        margin: 1.75rem 0 0.75rem 0;
        line-height: 1.3;
        border-bottom: 2px solid #e5e7eb;
        padding-bottom: 0.5rem;
    }
    
    .blog-content h3 {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1f2937;
        margin: 1.5rem 0 0.5rem 0;
        line-height: 1.4;
    }
    
    .blog-content h4 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #374151;
        margin: 1.25rem 0 0.5rem 0;
    }
    
    .blog-content p {
        margin: 1rem 0;
        font-size: 1.125rem;
    }
    
    .blog-content ul, .blog-content ol {
        margin: 1rem 0;
        padding-left: 2rem;
    }
    
    .blog-content li {
        margin: 0.5rem 0;
        font-size: 1.125rem;
    }
    
    .blog-content strong {
        font-weight: 700;
        color: #111827;
    }
    
    .blog-content em {
        font-style: italic;
        color: #4b5563;
    }
    
    .blog-content blockquote {
        border-left: 4px solid #e5e7eb;
        padding-left: 1rem;
        margin: 1.5rem 0;
        font-style: italic;
        color: #6b7280;
        background-color: #f9fafb;
        padding: 1rem;
        border-radius: 0.375rem;
    }
    
    .blog-content a {
        color: #EE0000;
        text-decoration: underline;
        font-weight: 500;
    }
    
    .blog-content a:hover {
        color: #cc0000;
        text-decoration: none;
    }
    
    .blog-content img {
        max-width: 100%;
        height: auto;
        border-radius: 0.5rem;
        margin: 1.5rem 0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    .blog-content code {
        background-color: #f3f4f6;
        padding: 0.125rem 0.25rem;
        border-radius: 0.25rem;
        font-family: 'Courier New', monospace;
        font-size: 0.875rem;
    }
    
    .blog-content pre {
        background-color: #1f2937;
        color: #f9fafb;
        padding: 1rem;
        border-radius: 0.5rem;
        overflow-x: auto;
        margin: 1.5rem 0;
    }
    
    .blog-content pre code {
        background-color: transparent;
        padding: 0;
        color: inherit;
    }
    
    .blog-content table {
        width: 100%;
        border-collapse: collapse;
        margin: 1.5rem 0;
    }
    
    .blog-content th, .blog-content td {
        border: 1px solid #d1d5db;
        padding: 0.75rem;
        text-align: left;
    }
    
    .blog-content th {
        background-color: #f9fafb;
        font-weight: 600;
    }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#EE0000',
                        'primary-dark': '#CC0000',
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html {
            scroll-behavior: smooth;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background-color: #F9FAFB;
        }
        
        /* Reading Progress Bar */
        .reading-progress {
            position: fixed;
            top: 0;
            left: 0;
            width: 0%;
            height: 3px;
            background: linear-gradient(90deg, #EE0000, #FF4444);
            z-index: 9999;
            transition: width 0.1s ease;
        }
        
        /* Blog Header */
        .blog-header {
            background: linear-gradient(135deg, #1F2937 0%, #111827 100%);
            border-bottom: 3px solid #EE0000;
        }
        
        /* Navigation */
        .blog-nav a {
            position: relative;
            transition: color 0.3s;
        }
        
        .blog-nav a::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: #EE0000;
            transition: width 0.3s;
        }
        
        .blog-nav a:hover::after,
        .blog-nav a.active::after {
            width: 100%;
        }
        
        /* Post Card */
        .post-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid #E5E7EB;
        }
        
        .post-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border-color: #EE0000;
        }
        
        .post-card img {
            transition: transform 0.5s;
        }
        
        .post-card:hover img {
            transform: scale(1.05);
        }
        
        /* Category Badge */
        .category-badge {
            background: linear-gradient(135deg, #EE0000, #CC0000);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* Sidebar */
        .sidebar-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #E5E7EB;
        }
        
        .sidebar-title {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 2px solid #E5E7EB;
        }
        
        /* Category List */
        .category-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            border-radius: 8px;
            transition: all 0.2s;
            color: #4B5563;
            text-decoration: none;
        }
        
        .category-item:hover {
            background: #F3F4F6;
            color: #EE0000;
            padding-left: 20px;
        }
        
        .category-item.active {
            background: #FEF2F2;
            color: #EE0000;
            font-weight: 600;
            border-left: 4px solid #EE0000;
        }
        
        .category-count {
            background: #E5E7EB;
            color: #6B7280;
            padding: 2px 10px;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 600;
        }
        
        .category-item.active .category-count {
            background: #EE0000;
            color: white;
        }
        
        /* Typography */
        .blog-content {
            font-size: 1.125rem;
            line-height: 1.8;
            color: #374151;
        }
        
        .blog-content h1, .blog-content h2, .blog-content h3 {
            color: #111827;
            font-weight: 700;
            margin-top: 2rem;
            margin-bottom: 1rem;
        }
        
        .blog-content h1 { font-size: 2.5rem; }
        .blog-content h2 { font-size: 2rem; }
        .blog-content h3 { font-size: 1.5rem; }
        
        .blog-content a {
            color: #EE0000;
            text-decoration: underline;
        }
        
        .blog-content img {
            border-radius: 12px;
            margin: 2rem 0;
        }
        
        /* Footer */
        .blog-footer {
            background: #1F2937;
            color: #D1D5DB;
            border-top: 3px solid #EE0000;
        }
        
        /* Mobile */
        @media (max-width: 768px) {
            .blog-content { font-size: 1rem; }
            .blog-content h1 { font-size: 2rem; }
            .blog-content h2 { font-size: 1.75rem; }
        }
    </style>
    
    <!-- Schema.org Organization -->
    @php
        $companyName = \App\Models\Setting::get('schema_company_name', 'Laser Link');
        $companyLogo = asset('images/logos/logo.png');
        
        $orgSchema = [
            "@context" => "https://schema.org",
            "@type" => "Organization",
            "name" => $companyName,
            "url" => url('/'),
            "logo" => $companyLogo,
            "description" => "Especialistas em comunicação visual, acrílicos, troféus, medalhas, placas e letreiros"
        ];
        
        $websiteSchema = [
            "@context" => "https://schema.org",
            "@type" => "WebSite",
            "name" => config('app.name') . " - Blog",
            "url" => route('blog.index'),
            "potentialAction" => [
                "@type" => "SearchAction",
                "target" => route('blog.index') . "?search={search_term_string}",
                "query-input" => "required name=search_term_string"
            ]
        ];
    @endphp
    
    <script type="application/ld+json">{!! json_encode($orgSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    <script type="application/ld+json">{!! json_encode($websiteSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    
    @if(isset($seoData) && isset($seoData['breadcrumbSchema']))
    <script type="application/ld+json">{!! json_encode($seoData['breadcrumbSchema'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endif
    
    @if(isset($seoData) && isset($seoData['articleSchema']))
    <script type="application/ld+json">{!! json_encode($seoData['articleSchema'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endif
    
    @if(isset($seoData) && isset($seoData['blogSchema']))
    <script type="application/ld+json">{!! json_encode($seoData['blogSchema'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endif

    @stack('schema')
</head>
<body>
    
    <!-- Reading Progress Bar -->
    <div class="reading-progress" id="readingProgress"></div>
    
    <!-- Blog Header -->
    <header class="blog-header sticky top-0 z-50 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                @php
                    $blogLogoPath = \App\Models\Setting::get('blog_logo_path');
                    $blogLogoUrl = $blogLogoPath ? url('storage/' . $blogLogoPath) : null;
                @endphp
                <a href="{{ route('blog.index') }}" class="flex items-center space-x-3">
                    @if($blogLogoUrl)
                        <!-- Logo configurado - mostrar apenas o logo -->
                        <img src="{{ $blogLogoUrl }}" alt="Laser Link Blog" class="h-12 max-w-[200px] object-contain">
                    @else
                        <!-- Sem logo - mostrar logo padrão + texto -->
                        <img src="{{ asset('images/logos/logo.png') }}" alt="Laser Link" class="h-10">
                        <div class="flex flex-col">
                            <span class="text-white font-bold text-xl">Laser Link</span>
                            <span class="text-gray-400 text-xs uppercase tracking-wider">Blog</span>
                        </div>
                    @endif
                </a>
                
                <!-- Navigation -->
                <nav class="hidden md:flex items-center space-x-8 blog-nav">
                    <a href="{{ route('store.index') }}" class="text-gray-300 hover:text-white font-medium">
                        <i class="bi bi-house mr-2"></i>Início
                    </a>
                    <a href="{{ route('blog.index') }}" class="text-gray-300 hover:text-white font-medium {{ request()->routeIs('blog.index') ? 'active text-white' : '' }}">
                        <i class="bi bi-newspaper mr-2"></i>Artigos
                    </a>
                    <a href="{{ route('store.products') }}" class="text-gray-300 hover:text-white font-medium">
                        <i class="bi bi-bag mr-2"></i>Produtos
                    </a>
                    <a href="{{ route('contact.index') }}" class="text-gray-300 hover:text-white font-medium">
                        <i class="bi bi-envelope mr-2"></i>Contato
                    </a>
                </nav>
                
                <!-- Mobile Menu Button -->
                <button class="md:hidden text-white" onclick="document.getElementById('mobileMenu').classList.toggle('hidden')">
                    <i class="bi bi-list text-2xl"></i>
                </button>
            </div>
            
            <!-- Mobile Menu -->
            <div id="mobileMenu" class="hidden md:hidden pb-4">
                <nav class="flex flex-col space-y-3">
                    <a href="{{ route('store.index') }}" class="text-gray-300 hover:text-white font-medium py-2">
                        <i class="bi bi-house mr-2"></i>Início
                    </a>
                    <a href="{{ route('blog.index') }}" class="text-gray-300 hover:text-white font-medium py-2 {{ request()->routeIs('blog.index') ? 'text-white' : '' }}">
                        <i class="bi bi-newspaper mr-2"></i>Artigos
                    </a>
                    <a href="{{ route('store.products') }}" class="text-gray-300 hover:text-white font-medium py-2">
                        <i class="bi bi-bag mr-2"></i>Produtos
                    </a>
                    <a href="{{ route('contact.index') }}" class="text-gray-300 hover:text-white font-medium py-2">
                        <i class="bi bi-envelope mr-2"></i>Contato
                    </a>
                </nav>
            </div>
        </div>
    </header>
    
    <!-- Breadcrumbs -->
    @if(isset($seoData['breadcrumbs']) && count($seoData['breadcrumbs']) > 1)
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <nav class="flex items-center space-x-2 text-sm" aria-label="Breadcrumb">
                @foreach($seoData['breadcrumbs'] as $breadcrumb)
                    @if($loop->last)
                        <span class="text-gray-600 font-medium">{{ $breadcrumb['name'] }}</span>
                    @else
                        <a href="{{ $breadcrumb['url'] }}" class="text-gray-500 hover:text-primary transition-colors">
                            {{ $breadcrumb['name'] }}
                        </a>
                        <span class="text-gray-400">/</span>
                    @endif
                @endforeach
            </nav>
        </div>
    </div>
    @endif
    
    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="blog-footer mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- About -->
                <div>
                    <h3 class="text-white font-bold text-lg mb-4">Sobre o Blog</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Conteúdo exclusivo sobre comunicação visual, dicas, tendências e cases de sucesso da Laser Link.
                    </p>
                </div>
                
                <!-- Links -->
                <div>
                    <h3 class="text-white font-bold text-lg mb-4">Links Rápidos</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('store.index') }}" class="text-gray-400 hover:text-white transition-colors">Início</a></li>
                        <li><a href="{{ route('store.products') }}" class="text-gray-400 hover:text-white transition-colors">Produtos</a></li>
                        <li><a href="{{ route('contact.index') }}" class="text-gray-400 hover:text-white transition-colors">Contato</a></li>
                    </ul>
                </div>
                
                <!-- Social -->
                <div>
                    <h3 class="text-white font-bold text-lg mb-4">Redes Sociais</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="bi bi-facebook text-2xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="bi bi-instagram text-2xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="bi bi-linkedin text-2xl"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-sm text-gray-400">
                <p>&copy; {{ date('Y') }} Laser Link. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>
    
    <!-- Scripts -->
    <script>
        // Reading Progress
        window.addEventListener('scroll', function() {
            const progress = document.getElementById('readingProgress');
            if (progress) {
                const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
                const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
                const scrolled = (winScroll / height) * 100;
                progress.style.width = scrolled + '%';
            }
        });
        
        // Share Functions
        function shareOnFacebook(url, title) {
            window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`, 'facebook', 'width=626,height=436');
        }
        
        function shareOnTwitter(url, title) {
            window.open(`https://twitter.com/intent/tweet?url=${encodeURIComponent(url)}&text=${encodeURIComponent(title)}`, 'twitter', 'width=626,height=436');
        }
        
        function shareOnWhatsApp(url, title) {
            window.open(`https://wa.me/?text=${encodeURIComponent(title + ' - ' + url)}`, 'whatsapp', 'width=626,height=436');
        }
        
        function copyToClipboard(url) {
            navigator.clipboard.writeText(url).then(() => alert('Link copiado!'));
        }
    </script>
    
    @stack('scripts')
    
</body>
</html>
