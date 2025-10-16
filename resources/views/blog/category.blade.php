@extends('layouts.blog')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Category Hero -->
        <div class="mb-12">
            <div class="flex items-center gap-3 mb-4">
                <span class="category-badge text-lg px-4 py-2">
                    {{ $category->name }}
                </span>
                <span class="text-gray-500">{{ $posts->total() }} {{ $posts->total() === 1 ? 'artigo' : 'artigos' }}</span>
            </div>
            
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                {{ $category->name }}
            </h1>
            
            @if($category->description)
                <p class="text-xl text-gray-600 max-w-3xl">
                    {{ $category->description }}
                </p>
            @endif
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
                                        <span class="category-badge">
                                            {{ $post->category->name }}
                                        </span>
                                        
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
                            <i class="bi bi-inbox text-6xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">
                            Nenhum artigo nesta categoria
                        </h3>
                        <p class="text-gray-600 mb-6">
                            Em breve teremos conteúdo novo por aqui!
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
                            class="category-item"
                        >
                            <span>Todas</span>
                            <span class="category-count">{{ \App\Models\Post::published()->count() }}</span>
                        </a>
                        
                        @foreach($categories as $cat)
                            <a 
                                href="{{ route('blog.category', $cat->slug) }}" 
                                class="category-item {{ $cat->id === $category->id ? 'active' : '' }}"
                            >
                                <span>{{ $cat->name }}</span>
                                <span class="category-count">{{ $cat->posts_count }}</span>
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
