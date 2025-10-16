<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    <!-- Mobile Meta Tags -->
    <meta name="theme-color" content="#EE0000">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Laser Link">

    <!-- PWA Manifest -->
    <link rel="manifest" href="<?php echo e(url('/manifest.json')); ?>">
    
    <?php
        $faviconPath = \App\Models\Setting::get('favicon_path');
        // Verificar se o arquivo realmente existe antes de usar
        if ($faviconPath && file_exists(public_path('storage/' . $faviconPath))) {
            $faviconUrl = url('storage/' . $faviconPath);
        } elseif (file_exists(public_path('images/icon.svg'))) {
            $faviconUrl = url('/images/icon.svg');
        } else {
            $faviconUrl = url('/favicon.ico');
        }
    ?>
    
    <!-- Favicon Dinâmico -->
    <link rel="icon" href="<?php echo e($faviconUrl); ?>">
    <link rel="apple-touch-icon" href="<?php echo e($faviconUrl); ?>">
    
    <!-- PWA Icons Fallback -->
    <link rel="icon" type="image/png" sizes="144x144" href="<?php echo e(url('/icon-144x144.png')); ?>">

    <title><?php echo e($seoTitle ?? config('app.name', 'Laser Link')); ?></title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="<?php echo e($seoDescription ?? 'Laser Link - Especialistas em comunicação visual, acrílicos, troféus, medalhas e muito mais.'); ?>">
    <meta name="keywords" content="<?php echo e($seoKeywords ?? 'comunicação visual, acrílico, troféus, medalhas, placas, letreiros'); ?>">
    <meta name="author" content="Laser Link">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?php echo e(url()->current()); ?>">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="<?php echo e(isset($product) ? 'product' : 'website'); ?>">
    <meta property="og:url" content="<?php echo e(url()->current()); ?>">
    <meta property="og:title" content="<?php echo e($seoTitle ?? config('app.name')); ?>">
    <meta property="og:description" content="<?php echo e($seoDescription ?? 'Laser Link - Especialistas em comunicação visual'); ?>">
    <meta property="og:image" content="<?php echo e($ogImage ?? asset('images/logos/logo.png')); ?>">
    <meta property="og:site_name" content="Laser Link">
    <meta property="og:locale" content="pt_BR">
    <?php if(isset($product)): ?>
    <meta property="product:price:amount" content="<?php echo e($product->final_price); ?>">
    <meta property="product:price:currency" content="BRL">
    <meta property="product:availability" content="<?php echo e($product->stock_quantity > 0 ? 'in stock' : 'out of stock'); ?>">
    <?php endif; ?>

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="<?php echo e(url()->current()); ?>">
    <meta name="twitter:title" content="<?php echo e($seoTitle ?? config('app.name')); ?>">
    <meta name="twitter:description" content="<?php echo e($seoDescription ?? 'Laser Link - Especialistas em comunicação visual'); ?>">
    <meta name="twitter:image" content="<?php echo e($ogImage ?? asset('images/logos/logo.png')); ?>">

    <!-- Schema.org Structured Data - Organization -->
    <?php
        $organizationSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'Laser Link',
            'legalName' => 'Laser Link - Comunicação Visual',
            'url' => url('/'),
            'logo' => asset('images/logos/logo.png'),
            'foundingDate' => '2015',
            'description' => 'Especialistas em comunicação visual, corte e gravação a laser, acrílicos, troféus, medalhas, placas e brindes personalizados',
            'address' => [
                '@type' => 'PostalAddress',
                'addressCountry' => 'BR'
            ]
        ];
        
        // Adicionar telefone se existir
        $phone = \App\Models\Setting::get('phone');
        if ($phone) {
            $organizationSchema['contactPoint'] = [
                '@type' => 'ContactPoint',
                'telephone' => $phone,
                'contactType' => 'customer service',
                'availableLanguage' => ['Portuguese'],
                'areaServed' => 'BR'
            ];
        }
        
        // Adicionar redes sociais se existirem
        $socialLinks = [];
        if ($facebook = \App\Models\Setting::get('facebook')) {
            $socialLinks[] = $facebook;
        }
        if ($instagram = \App\Models\Setting::get('instagram')) {
            $socialLinks[] = $instagram;
        }
        if (!empty($socialLinks)) {
            $organizationSchema['sameAs'] = $socialLinks;
        }
        
        // Schema do website com busca
        $websiteSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => 'Laser Link',
            'url' => url('/'),
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => route('store.products') . '?search={search_term_string}'
                ],
                'query-input' => 'required name=search_term_string'
            ]
        ];
    ?>
    <script type="application/ld+json"><?php echo json_encode($organizationSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?></script>
    <script type="application/ld+json"><?php echo json_encode($websiteSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?></script>

    <!-- Configurações Globais -->
    <script src="<?php echo e(asset('js/global.js')); ?>"></script>
    
    <!-- DNS Prefetch & Preconnect para CDNs -->
    <link rel="dns-prefetch" href="https://cdn.tailwindcss.com">
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
    <link rel="dns-prefetch" href="https://fonts.bunny.net">
    <link rel="preconnect" href="https://cdn.tailwindcss.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>

    <!-- Fonts com display=swap para evitar FOIT -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
    /* Estilos para descrição de produtos */
    .product-description {
        line-height: 1.6;
    }
    
    .product-description p {
        margin: 1rem 0;
        font-size: 1rem;
    }
    
    .product-description h1, .product-description h2, .product-description h3, 
    .product-description h4, .product-description h5, .product-description h6 {
        font-weight: 600;
        margin: 1.5rem 0 0.75rem 0;
        color: #1f2937;
    }
    
    .product-description h2 {
        font-size: 1.5rem;
        border-bottom: 2px solidrgb(219, 212, 212);
        padding-bottom: 0.5rem;
    }
    
    .product-description h3 {
        font-size: 1.25rem;
    }
    
    .product-description ul, .product-description ol {
        margin: 1rem 0;
        padding-left: 1.5rem;
    }
    
    .product-description li {
        margin: 0.5rem 0;
    }
    
    .product-description strong {
        font-weight: 700;
        color: #111827;
    }
    
    .product-description a {
        color: #EE0000;
        text-decoration: underline;
    }
    
    .product-description a:hover {
        color: #cc0000;
    }
    </style>
    <script>
        // Suprimir avisos desnecessários do console
        (function() {
            const originalWarn = console.warn;
            const originalError = console.error;
            
            console.warn = function(...args) {
                const message = args.join(' ');
                if (message.includes('cdn.tailwindcss.com should not be used in production')) {
                    return; // Suprimir aviso do Tailwind CDN
                }
                originalWarn.apply(console, args);
            };
            
            console.error = function(...args) {
                const message = args.join(' ');
                if (message.includes('403 (Forbidden)') || message.includes('GET http://127.0.0.1:8000/storage/products/featured/')) {
                    return; // Suprimir erros 403 de imagens que são esperados
                }
                originalError.apply(console, args);
            };
        })();
        
        // Interceptar erros de rede para imagens
        window.addEventListener('error', function(e) {
            if (e.target && e.target.tagName === 'IMG' && e.target.src.includes('/storage/products/featured/')) {
                e.preventDefault();
                return false;
            }
        }, true);
        
        // Interceptar erros de fetch/XMLHttpRequest
        const originalFetch = window.fetch;
        window.fetch = function(...args) {
            return originalFetch.apply(this, args).catch(error => {
                if (error.message && error.message.includes('403')) {
                    return; // Ignorar erros 403
                }
                throw error;
            });
        };
    </script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#EE0000',
                    }
                }
            },
            corePlugins: {
                preflight: true,
            }
        }
    </script>
    
    <!-- Bootstrap Icons com preload -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"></noscript>
    
    <!-- Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        if (typeof axios !== 'undefined') {
            window.axios = axios;
            window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken.getAttribute('content');
            }
        }
    </script>
    
    <!-- Preload de scripts críticos -->
    <link rel="preload" href="<?php echo e(asset('js/favorites.js')); ?>" as="script">
    <link rel="preload" href="<?php echo e(asset('js/performance.js')); ?>" as="script">
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" as="script">
    
    <!-- Performance optimizations -->
    <script defer src="<?php echo e(asset('js/performance.js')); ?>"></script>
    
    <!-- Favorites Manager (carregar sincronamente antes do Alpine.js) -->
    <script>
        // Verificar se o arquivo existe antes de tentar carregar
        // Tentando carregar favorites.js
    </script>
    <script src="<?php echo e(asset('js/favorites.js')); ?>"></script>
    
    <!-- Alpine.js (carregar por último com defer) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Verificar Alpine após carregar -->
    <script>
        window.addEventListener('alpine:initialized', () => {
            // Alpine.js inicializado com sucesso
        });
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

        /* Reset absoluto - remover TODOS os espaçamentos */
        html {
            margin: 0 !important;
            padding: 0 !important;
            min-height: 100%;
        }
        
        body {
            margin: 0 !important;
            padding: 0 !important;
            min-height: 100vh;
            display: block !important;
            position: relative;
        }
        
        /* Remover padding/margin de TODOS os elementos dentro do body */
        body > * {
            margin: 0 !important;
        }
        
        body > *:first-child,
        body > div:first-child,
        body > header:first-child {
            margin-top: 0 !important;
            padding-top: 0 !important;
        }
        
        /* Header específico */
        header,
        header.header,
        .header,
        [class*="header"] {
            margin: 0 !important;
            margin-top: 0 !important;
            padding-top: 0 !important;
        }
        
        /* Main content */
        main {
            margin: 0 !important;
            padding: 0 !important;
        }
        
        /* Adicionar padding no bottom para mobile (tab bar) */
        @media (max-width: 768px) {
            body {
                padding-bottom: calc(64px + env(safe-area-inset-bottom, 0)); /* Altura do tab bar + safe area */
            }
        }
        
        /* Safe area para iPhone com notch */
        .safe-area-inset-bottom {
            padding-bottom: env(safe-area-inset-bottom, 0);
        }
        
        /* Efeito ripple no tab bar */
        @keyframes ripple {
            0% {
                transform: scale(0);
                opacity: 0.6;
            }
            100% {
                transform: scale(2);
                opacity: 0;
            }
        }
        
        /* Tab bar hover effect */
        .tab-item {
            position: relative;
            overflow: hidden;
        }
        
        .tab-item::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(238, 0, 0, 0.1);
            transform: translate(-50%, -50%);
            transition: width 0.3s, height 0.3s;
        }
        
        .tab-item:active::before {
            width: 100px;
            height: 100px;
        }
        
        /* Wrapper do header */
        div[x-data*="headerData"] {
            margin: 0 !important;
            padding: 0 !important;
        }
    </style>
    
    <?php
        $companyName = \App\Models\Setting::get('schema_company_name', 'Laser Link');
        $companyPhone = \App\Models\Setting::get('schema_phone', '+55-11-99999-9999');
        $companyStreet = \App\Models\Setting::get('schema_street', 'Rua Exemplo, 123');
        $companyCity = \App\Models\Setting::get('schema_city', 'São Paulo');
        $companyState = \App\Models\Setting::get('schema_state', 'SP');
        $companyPostalCode = \App\Models\Setting::get('schema_postal_code', '01234-567');
        $companyFacebook = \App\Models\Setting::get('schema_facebook');
        $companyInstagram = \App\Models\Setting::get('schema_instagram');
        $openingHoursStart = \App\Models\Setting::get('schema_opening_hours_start', '08:00');
        $openingHoursEnd = \App\Models\Setting::get('schema_opening_hours_end', '18:00');
        $priceRange = \App\Models\Setting::get('schema_price_range', '$$');
        
        $socialMedia = array_filter([$companyFacebook, $companyInstagram]);
    ?>

    <!-- Schema.org Organization -->
    <?php
        $orgSchema = [
            "@context" => "https://schema.org",
            "@type" => "Organization",
            "name" => $companyName,
            "url" => url('/'),
            "logo" => asset('images/logos/logo.png'),
            "description" => "Especialistas em comunicação visual, acrílicos, troféus, medalhas, placas e letreiros",
            "contactPoint" => [
                "@type" => "ContactPoint",
                "telephone" => $companyPhone,
                "contactType" => "customer service",
                "areaServed" => "BR",
                "availableLanguage" => ["Portuguese"]
            ]
        ];
        
        if (count($socialMedia) > 0) {
            $orgSchema["sameAs"] = $socialMedia;
        }
    ?>
    <script type="application/ld+json">
    <?php echo json_encode($orgSchema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>

    </script>

    <!-- Schema.org LocalBusiness -->
    <?php
        $localBusinessSchema = [
            "@context" => "https://schema.org",
            "@type" => "LocalBusiness",
            "name" => $companyName,
            "image" => asset('images/logos/logo.png'),
            "@id" => url('/'),
            "url" => url('/'),
            "telephone" => $companyPhone,
            "priceRange" => $priceRange,
            "address" => [
                "@type" => "PostalAddress",
                "streetAddress" => $companyStreet,
                "addressLocality" => $companyCity,
                "addressRegion" => $companyState,
                "postalCode" => $companyPostalCode,
                "addressCountry" => "BR"
            ],
            "openingHoursSpecification" => [[
                "@type" => "OpeningHoursSpecification",
                "dayOfWeek" => [
                    "Monday",
                    "Tuesday",
                    "Wednesday",
                    "Thursday",
                    "Friday"
                ],
                "opens" => $openingHoursStart,
                "closes" => $openingHoursEnd
            ]]
        ];
    ?>
    <script type="application/ld+json">
    <?php echo json_encode($localBusinessSchema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>

    </script>

    <?php echo $__env->yieldPushContent('schema'); ?>
</head>
<body style="margin: 0 !important; padding: 0 !important; background-color: #f9fafb;">
    <!-- Page Loader -->

    <!-- Header -->
    <?php if (isset($component)) { $__componentOriginale576d55ba60ae9ea0229c0eb386c22c8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale576d55ba60ae9ea0229c0eb386c22c8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.store-header','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('store-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale576d55ba60ae9ea0229c0eb386c22c8)): ?>
<?php $attributes = $__attributesOriginale576d55ba60ae9ea0229c0eb386c22c8; ?>
<?php unset($__attributesOriginale576d55ba60ae9ea0229c0eb386c22c8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale576d55ba60ae9ea0229c0eb386c22c8)): ?>
<?php $component = $__componentOriginale576d55ba60ae9ea0229c0eb386c22c8; ?>
<?php unset($__componentOriginale576d55ba60ae9ea0229c0eb386c22c8); ?>
<?php endif; ?>
    
    <!-- Menu Inferior da Loja -->
    <?php if (isset($component)) { $__componentOriginalca4da4380df588fe3890983384b57604 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalca4da4380df588fe3890983384b57604 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.store-bottom-menu','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('store-bottom-menu'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalca4da4380df588fe3890983384b57604)): ?>
<?php $attributes = $__attributesOriginalca4da4380df588fe3890983384b57604; ?>
<?php unset($__attributesOriginalca4da4380df588fe3890983384b57604); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalca4da4380df588fe3890983384b57604)): ?>
<?php $component = $__componentOriginalca4da4380df588fe3890983384b57604; ?>
<?php unset($__componentOriginalca4da4380df588fe3890983384b57604); ?>
<?php endif; ?>
    
    <!-- Main Content -->
    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>
    
    <!-- Footer -->
    <?php if (isset($component)) { $__componentOriginalcfcf49eb90c5e36e40bef660f74208a6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcfcf49eb90c5e36e40bef660f74208a6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.store-footer','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('store-footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcfcf49eb90c5e36e40bef660f74208a6)): ?>
<?php $attributes = $__attributesOriginalcfcf49eb90c5e36e40bef660f74208a6; ?>
<?php unset($__attributesOriginalcfcf49eb90c5e36e40bef660f74208a6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcfcf49eb90c5e36e40bef660f74208a6)): ?>
<?php $component = $__componentOriginalcfcf49eb90c5e36e40bef660f74208a6; ?>
<?php unset($__componentOriginalcfcf49eb90c5e36e40bef660f74208a6); ?>
<?php endif; ?>
    
    <!-- Notification Container -->
    <?php if (isset($component)) { $__componentOriginalf88f517bd8122b84c1db00478825e960 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf88f517bd8122b84c1db00478825e960 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.notification-container','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('notification-container'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf88f517bd8122b84c1db00478825e960)): ?>
<?php $attributes = $__attributesOriginalf88f517bd8122b84c1db00478825e960; ?>
<?php unset($__attributesOriginalf88f517bd8122b84c1db00478825e960); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf88f517bd8122b84c1db00478825e960)): ?>
<?php $component = $__componentOriginalf88f517bd8122b84c1db00478825e960; ?>
<?php unset($__componentOriginalf88f517bd8122b84c1db00478825e960); ?>
<?php endif; ?>
    
    <!-- PWA Install Banner -->
    <?php if (isset($component)) { $__componentOriginal5b6745fadc65cdc3052b92baf193c2e8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5b6745fadc65cdc3052b92baf193c2e8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.pwa-install-banner','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('pwa-install-banner'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5b6745fadc65cdc3052b92baf193c2e8)): ?>
<?php $attributes = $__attributesOriginal5b6745fadc65cdc3052b92baf193c2e8; ?>
<?php unset($__attributesOriginal5b6745fadc65cdc3052b92baf193c2e8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5b6745fadc65cdc3052b92baf193c2e8)): ?>
<?php $component = $__componentOriginal5b6745fadc65cdc3052b92baf193c2e8; ?>
<?php unset($__componentOriginal5b6745fadc65cdc3052b92baf193c2e8); ?>
<?php endif; ?>
    
    <!-- Cookie Consent Banner -->
    <?php if (isset($component)) { $__componentOriginal929715dcacade4e957f0bc5aff0c8a6d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal929715dcacade4e957f0bc5aff0c8a6d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cookie-consent','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cookie-consent'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal929715dcacade4e957f0bc5aff0c8a6d)): ?>
<?php $attributes = $__attributesOriginal929715dcacade4e957f0bc5aff0c8a6d; ?>
<?php unset($__attributesOriginal929715dcacade4e957f0bc5aff0c8a6d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal929715dcacade4e957f0bc5aff0c8a6d)): ?>
<?php $component = $__componentOriginal929715dcacade4e957f0bc5aff0c8a6d; ?>
<?php unset($__componentOriginal929715dcacade4e957f0bc5aff0c8a6d); ?>
<?php endif; ?>
    
    <!-- Cookie Settings Button (após aceitar) -->
    <?php if (isset($component)) { $__componentOriginal13817dec0127122e113736af36ae49e6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal13817dec0127122e113736af36ae49e6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cookie-settings-button','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cookie-settings-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal13817dec0127122e113736af36ae49e6)): ?>
<?php $attributes = $__attributesOriginal13817dec0127122e113736af36ae49e6; ?>
<?php unset($__attributesOriginal13817dec0127122e113736af36ae49e6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal13817dec0127122e113736af36ae49e6)): ?>
<?php $component = $__componentOriginal13817dec0127122e113736af36ae49e6; ?>
<?php unset($__componentOriginal13817dec0127122e113736af36ae49e6); ?>
<?php endif; ?>
    
    <!-- Cart Manager -->
    <script src="<?php echo e(url('js/cart-manager.js')); ?>"></script>
    
    <!-- Cart Counter Update -->
    <script>
        function updateCartCount() {
            try {
                const cart = JSON.parse(localStorage.getItem('cart') || '[]');
                const count = cart.reduce((total, item) => total + item.quantity, 0);
                
                // Atualizar todos os elementos com contador de carrinho
                const cartCountElements = document.querySelectorAll('.cart-count, [data-cart-count]');
                cartCountElements.forEach(element => {
                    element.textContent = count;
                    element.style.display = count > 0 ? 'inline' : 'none';
                });
            } catch (error) {
                // Silenciosamente ignorar erro ao atualizar contador
            }
        }
        
        // Atualizar contador quando o carrinho for modificado
        window.addEventListener('cartUpdated', updateCartCount);
        
        // Atualizar contador quando a página carregar
        document.addEventListener('DOMContentLoaded', updateCartCount);
    </script>
    
    <!-- Page-specific Scripts -->
    <?php echo $__env->yieldPushContent('scripts'); ?>
    
    <!-- Garantir que não há espaçamento -->
    <script>
        // Executar imediatamente (antes do DOMContentLoaded)
        (function() {
            document.documentElement.style.margin = '0';
            document.documentElement.style.padding = '0';
            document.body.style.margin = '0';
            document.body.style.padding = '0';
        })();
        
        document.addEventListener('DOMContentLoaded', function() {
            // Remover qualquer padding/margin adicionado por outros scripts
            document.documentElement.style.margin = '0';
            document.documentElement.style.padding = '0';
            document.body.style.margin = '0';
            document.body.style.padding = '0';
            
            // Forçar todos os filhos diretos do body
            Array.from(document.body.children).forEach((child, index) => {
                if (index === 0) {
                    child.style.marginTop = '0';
                    child.style.paddingTop = '0';
                }
            });
            
            // Forçar o header especificamente
            const headers = document.querySelectorAll('header, .header');
            headers.forEach(header => {
                header.style.marginTop = '0';
                header.style.paddingTop = '0';
            });
            
            // Debug: Verificar margens
            const bodyStyle = window.getComputedStyle(document.body);
            const htmlStyle = window.getComputedStyle(document.documentElement);
            
            // Validar margens e preenchimentos
        });
        
        // Executar novamente após Alpine carregar
        window.addEventListener('alpine:initialized', function() {
            document.body.style.margin = '0';
            document.body.style.padding = '0';
        });
    </script>
    
    <!-- PWA Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js')
                    .then(function(registration) {
                        // Service Worker registrado com sucesso
                        
                        // Verificar atualizações
                        registration.addEventListener('updatefound', function() {
                            // Nova versão do Service Worker encontrada
                        });
                    })
                    .catch(function(error) {
                        // Silenciosamente ignorar erro ao registrar Service Worker
                    });
            });
            
            // Detectar quando o app está pronto para instalar
            let deferredPrompt;
            window.addEventListener('beforeinstallprompt', function(e) {
                e.preventDefault();
                deferredPrompt = e;
                // App está pronto para ser instalado
                
                // Você pode mostrar um botão de instalação aqui
                // Exemplo: document.getElementById('installButton').style.display = 'block';
            });
            
            // Detectar quando o app foi instalado
            window.addEventListener('appinstalled', function() {
                // App instalado com sucesso
                deferredPrompt = null;
            });
        }
    </script>
    
</body>
</html><?php /**PATH C:\xampp\htdocs\resources\views/layouts/store.blade.php ENDPATH**/ ?>