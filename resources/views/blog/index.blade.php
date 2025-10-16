@extends('layouts.blog')

@section('title', $seoData['metaTags']['title'])

@section('meta')
    <!-- Meta Tags Básicas -->
    <meta name="description" content="{{ $seoData['metaTags']['description'] }}">
    <meta name="keywords" content="{{ $seoData['metaTags']['keywords'] }}">
    <link rel="canonical" href="{{ $seoData['metaTags']['canonical'] }}">
    
    <!-- Paginação -->
    @if(isset($seoData['paginationTags']['prev']))
        <link rel="prev" href="{{ $seoData['paginationTags']['prev'] }}">
    @endif
    @if(isset($seoData['paginationTags']['next']))
        <link rel="next" href="{{ $seoData['paginationTags']['next'] }}">
    @endif
    
    <!-- Open Graph -->
    <meta property="og:type" content="{{ $seoData['metaTags']['og']['type'] }}">
    <meta property="og:title" content="{{ $seoData['metaTags']['og']['title'] }}">
    <meta property="og:description" content="{{ $seoData['metaTags']['og']['description'] }}">
    <meta property="og:image" content="{{ $seoData['metaTags']['og']['image'] }}">
    <meta property="og:url" content="{{ $seoData['metaTags']['og']['url'] }}">
    <meta property="og:site_name" content="{{ $seoData['metaTags']['og']['site_name'] }}">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="{{ $seoData['metaTags']['twitter']['card'] }}">
    <meta name="twitter:title" content="{{ $seoData['metaTags']['twitter']['title'] }}">
    <meta name="twitter:description" content="{{ $seoData['metaTags']['twitter']['description'] }}">
    <meta name="twitter:image" content="{{ $seoData['metaTags']['twitter']['image'] }}">
    
    <!-- Schema.org Blog -->
    <script type="application/ld+json">
        {!! json_encode($seoData['blogSchema'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
    
    <!-- Schema.org BreadcrumbList -->
    <script type="application/ld+json">
        {!! json_encode($seoData['breadcrumbSchema'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Hero Search -->
        <div class="mb-12 text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                Blog Laser Link
            </h1>
            <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                Dicas, tendências e novidades sobre comunicação visual
            </p>
            
            <!-- Search Bar -->
            <form action="{{ route('blog.index') }}" method="GET" class="max-w-2xl mx-auto">
                <div class="relative">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Buscar artigos..." 
                        class="w-full px-6 py-4 pr-32 rounded-full border-2 border-gray-200 focus:border-primary focus:outline-none text-lg"
                    >
                    <button 
                        type="submit"
                        class="absolute right-2 top-1/2 -translate-y-1/2 bg-primary hover:bg-primary-dark text-white px-8 py-3 rounded-full font-semibold transition-colors"
                    >
                        <i class="bi bi-search mr-2"></i>Buscar
                    </button>
                </div>
            </form>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Main Content -->
            <div class="flex-1">
                @if($posts->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @foreach($posts as $post)
                            <article class="post-card bg-white rounded-xl overflow-hidden">
                                <!-- Image -->
                                @if($post->featured_image)
                                    <a href="{{ route('blog.show', $post->slug) }}" class="block overflow-hidden">
                                        <img 
                                            src="{{ url('images/' . $post->featured_image) }}" 
                                            alt="{{ $post->title }}"
                                            class="w-full h-56 object-cover"
                                            loading="lazy"
                                        >
                                    </a>
                                @endif
                                
                                <!-- Content -->
                                <div class="p-6">
                                    <!-- Meta -->
                                    <div class="flex items-center justify-between mb-4">
                                        @if($post->category)
                                            <span class="category-badge">
                                                {{ $post->category->name }}
                                            </span>
                                        @endif
                                        
                                        <time class="text-sm text-gray-500">
                                            <i class="bi bi-calendar mr-1"></i>
                                            {{ $post->published_at->format('d/m/Y') }}
                                        </time>
                                    </div>
                                    
                                    <!-- Title -->
                                    <h2 class="text-2xl font-bold text-gray-900 mb-3 line-clamp-2">
                                        <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-primary transition-colors">
                                            {{ $post->title }}
                                        </a>
                                    </h2>
                                    
                                    <!-- Excerpt -->
                                    @if($post->excerpt)
                                        <p class="text-gray-600 mb-4 line-clamp-3">
                                            {{ $post->excerpt }}
                                        </p>
                                    @endif
                                    
                                    <!-- Footer -->
                                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                        <div class="flex items-center text-sm text-gray-500">
                                            <i class="bi bi-eye mr-2"></i>
                                            {{ $post->views }} visualizações
                                        </div>
                                        
                                        <a 
                                            href="{{ route('blog.show', $post->slug) }}"
                                            class="inline-flex items-center text-primary hover:text-primary-dark font-semibold transition-colors"
                                        >
                                            Ler mais
                                            <i class="bi bi-arrow-right ml-2"></i>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    @if($posts->hasPages())
                        <div class="mt-12">
                            {{ $posts->links() }}
                        </div>
                    @endif
                @else
                    <!-- Empty State -->
                    <div class="bg-white rounded-xl p-12 text-center">
                        <div class="text-gray-400 mb-4">
                            <i class="bi bi-search text-6xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">
                            Nenhum artigo encontrado
                        </h3>
                        <p class="text-gray-600 mb-6">
                            Tente buscar por outros termos ou explore nossas categorias.
                        </p>
                        <a href="{{ route('blog.index') }}" class="inline-block bg-primary hover:bg-primary-dark text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                            Ver todos os artigos
                        </a>
                    </div>
                @endif
            </div>
            
            <!-- Sidebar -->
            <aside class="lg:w-80 space-y-6">
                
                <!-- Categories -->
                <div class="sidebar-card">
                    <h3 class="sidebar-title">
                        <i class="bi bi-folder-open mr-2 text-primary"></i>
                        Categorias
                    </h3>
                    <nav class="space-y-1">
                        <a 
                            href="{{ route('blog.index') }}" 
                            class="category-item {{ !request('category') ? 'active' : '' }}"
                        >
                            <span>Todas</span>
                            <span class="category-count">{{ \App\Models\Post::published()->count() }}</span>
                        </a>
                        
                        @foreach($categories as $category)
                            <a 
                                href="{{ route('blog.category', $category->slug) }}" 
                                class="category-item {{ request('category') === $category->slug ? 'active' : '' }}"
                            >
                                <span>{{ $category->name }}</span>
                                <span class="category-count">{{ $category->posts_count }}</span>
                            </a>
                        @endforeach
                    </nav>
                </div>
                
                <!-- Recent Posts -->
                <div class="sidebar-card">
                    <h3 class="sidebar-title">
                        <i class="bi bi-clock mr-2 text-primary"></i>
                        Posts Recentes
                    </h3>
                    <div class="space-y-4">
                        @foreach($recentPosts as $recentPost)
                            <a href="{{ route('blog.show', $recentPost->slug) }}" class="flex gap-3 group">
                                @if($recentPost->featured_image)
                                    <img 
                                        src="{{ url('images/' . $recentPost->featured_image) }}" 
                                        alt="{{ $recentPost->title }}"
                                        class="w-20 h-20 object-cover rounded-lg flex-shrink-0"
                                        loading="lazy"
                                    >
                                @endif
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-gray-900 group-hover:text-primary transition-colors line-clamp-2 text-sm mb-1">
                                        {{ $recentPost->title }}
                                    </h4>
                                    <time class="text-xs text-gray-500">
                                        {{ $recentPost->published_at->format('d/m/Y') }}
                                    </time>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
                
                <!-- CTA -->
                <div class="sidebar-card bg-gradient-to-br from-primary to-primary-dark text-white">
                    <h3 class="text-xl font-bold mb-3">
                        Precisando de produtos?
                    </h3>
                    <p class="text-sm mb-4 opacity-90">
                        Confira nosso catálogo completo de comunicação visual.
                    </p>
                    <a 
                        href="{{ route('store.products') }}"
                        class="block w-full bg-white text-primary text-center py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors"
                    >
                        Ver Produtos
                    </a>
                </div>
                
            </aside>
            
        </div>
    </div>
</div>
@endsection
