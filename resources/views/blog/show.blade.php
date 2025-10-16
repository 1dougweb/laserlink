@extends('layouts.blog')

@section('content')
<div class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Conteúdo Principal -->
            <article class="flex-1" itemscope itemtype="https://schema.org/Article">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <!-- Imagem Destacada -->
                    @if($post->featured_image)
                        <img src="{{ url('images/' . $post->featured_image) }}" 
                             alt="{{ $post->title }}"
                             class="w-full h-96 object-cover"
                             loading="eager"
                             width="1200"
                             height="630"
                             itemprop="image"
                             onerror="this.src='{{ url('images/general/callback-image.svg') }}'; this.classList.add('object-contain', 'bg-gray-100');">
                    @endif
                    
                    <!-- Conteúdo -->
                    <div class="p-8 md:p-12">
                        <!-- Meta escondida para Schema.org -->
                        <meta itemprop="headline" content="{{ $post->title }}">
                        <meta itemprop="datePublished" content="{{ $post->published_at->toIso8601String() }}">
                        <meta itemprop="dateModified" content="{{ $post->updated_at->toIso8601String() }}">
                        <meta itemprop="author" content="{{ $post->author->name }}">
                        @if($post->meta_description)
                            <meta itemprop="description" content="{{ $post->meta_description }}">
                        @endif
                        
                        <!-- Meta Info -->
                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-6">
                            @if($post->category)
                                <a href="{{ route('blog.category', $post->category->slug) }}" 
                                   class="bg-primary text-white px-3 py-1 rounded-full hover:bg-primary-dark transition-colors"
                                   itemprop="articleSection">
                                    {{ $post->category->name }}
                                </a>
                            @endif
                            <time datetime="{{ $post->published_at->toIso8601String() }}" class="flex items-center gap-1">
                                <i class="bi bi-calendar"></i>
                                {{ $post->published_at->format('d/m/Y') }}
                            </time>
                            <span class="flex items-center gap-1">
                                <i class="bi bi-clock"></i>
                                <span itemprop="timeRequired">{{ $post->reading_time }} min de leitura</span>
                            </span>
                            <span class="flex items-center gap-1">
                                <i class="bi bi-eye"></i>
                                {{ number_format($post->views) }} visualizações
                            </span>
                        </div>
                        
                        <!-- Título -->
                        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6" itemprop="headline">
                            {{ $post->title }}
                        </h1>
                        
                        <!-- Autor -->
                        <div class="flex items-center gap-3 pb-6 mb-8 border-b border-gray-200" itemprop="author" itemscope itemtype="https://schema.org/Person">
                            <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center text-white font-bold text-xl">
                                {{ substr($post->author->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900" itemprop="name">{{ $post->author->name }}</div>
                                <div class="text-sm text-gray-500">✍️ Conteúdo escrito por humano</div>
                            </div>
                        </div>
                        
                        <!-- Excerpt -->
                        @if($post->excerpt)
                            <div class="text-xl text-gray-600 italic mb-8 pb-8 border-b border-gray-200" itemprop="description">
                                {{ $post->excerpt }}
                            </div>
                        @endif
                        
                        <!-- Conteúdo do Post -->
                        <div class="blog-content max-w-none" itemprop="articleBody">
                            {!! $post->content !!}
                        </div>
                        
                        <!-- Tags/Keywords -->
                        @if($post->meta_keywords)
                            <div class="mt-8 pt-8 border-t border-gray-200">
                                <h4 class="text-sm font-semibold text-gray-700 mb-3">Tags:</h4>
                                <div class="flex flex-wrap gap-2">
                                    @foreach(explode(',', $post->meta_keywords) as $keyword)
                                        <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm" itemprop="keywords">
                                            {{ trim($keyword) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        
                        <!-- Compartilhar -->
                        <div class="mt-8 pt-8 border-t border-gray-200">
                            <h4 class="text-sm font-semibold text-gray-700 mb-3">Compartilhar:</h4>
                            <div class="flex gap-3">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('blog.show', $post->slug)) }}" 
                                   target="_blank"
                                   class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="bi bi-facebook"></i>Facebook
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog.show', $post->slug)) }}&text={{ urlencode($post->title) }}" 
                                   target="_blank"
                                   class="flex items-center gap-2 bg-sky-500 text-white px-4 py-2 rounded-lg hover:bg-sky-600 transition-colors">
                                    <i class="bi bi-twitter"></i>Twitter
                                </a>
                                <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(route('blog.show', $post->slug)) }}&title={{ urlencode($post->title) }}" 
                                   target="_blank"
                                   class="flex items-center gap-2 bg-blue-700 text-white px-4 py-2 rounded-lg hover:bg-blue-800 transition-colors">
                                    <i class="bi bi-linkedin"></i>LinkedIn
                                </a>
                                <a href="https://api.whatsapp.com/send?text={{ urlencode($post->title . ' - ' . route('blog.show', $post->slug)) }}" 
                                   target="_blank"
                                   class="flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                                    <i class="bi bi-whatsapp"></i>WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Comentários -->
                <div class="mt-12 bg-white rounded-lg shadow-lg p-8">
                    <h2 class="text-3xl font-bold mb-6">
                        <i class="bi bi-chat-left-text mr-2"></i>
                        Comentários ({{ $post->approvedComments->count() }})
                    </h2>

                    <!-- Lista de Comentários Aprovados -->
                    @if($post->approvedComments->count() > 0)
                        <div class="space-y-6 mb-8">
                            @foreach($post->approvedComments as $comment)
                                <div class="flex gap-4 p-4 bg-gray-50 rounded-lg">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-primary text-white rounded-full flex items-center justify-center font-bold text-lg">
                                            {{ substr($comment->author_name, 0, 1) }}
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-2">
                                            <div>
                                                <span class="font-semibold text-gray-900">{{ $comment->author_name }}</span>
                                                <span class="text-sm text-gray-500 ml-2">
                                                    <i class="bi bi-clock"></i>
                                                    {{ $comment->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                        </div>
                                        <p class="text-gray-700">{{ $comment->content }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-6">
                            <i class="bi bi-chat text-4xl mb-2 block"></i>
                            Nenhum comentário ainda. Seja o primeiro a comentar!
                        </p>
                    @endif

                    <!-- Formulário de Comentário -->
                    <div class="border-t pt-8">
                        <h3 class="text-xl font-bold mb-4">Deixe seu comentário</h3>
                        
                        @if(session('success'))
                            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-4">
                                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('comments.store', $post) }}" method="POST" class="space-y-4">
                            @csrf
                            
                            @guest
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="author_name" class="block text-sm font-medium text-gray-700 mb-2">
                                            Nome *
                                        </label>
                                        <input 
                                            type="text" 
                                            id="author_name" 
                                            name="author_name" 
                                            value="{{ old('author_name') }}"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                            required
                                        >
                                        @error('author_name')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="author_email" class="block text-sm font-medium text-gray-700 mb-2">
                                            E-mail *
                                        </label>
                                        <input 
                                            type="email" 
                                            id="author_email" 
                                            name="author_email" 
                                            value="{{ old('author_email') }}"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                            required
                                        >
                                        @error('author_email')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            @endguest

                            <div>
                                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                                    Comentário *
                                </label>
                                <textarea 
                                    id="content" 
                                    name="content" 
                                    rows="4"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                    placeholder="Escreva seu comentário aqui..."
                                    required
                                >{{ old('content') }}</textarea>
                                @error('content')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center justify-between">
                                <p class="text-sm text-gray-500">
                                    <i class="bi bi-info-circle"></i>
                                    Seu comentário será publicado após aprovação.
                                </p>
                                <button 
                                    type="submit" 
                                    class="bg-primary hover:bg-primary-dark text-white px-6 py-3 rounded-lg font-semibold transition-colors"
                                >
                                    <i class="bi bi-send"></i>
                                    Enviar Comentário
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Posts Relacionados -->
                @if($relatedPosts->isNotEmpty())
                    <div class="mt-12">
                        <h2 class="text-3xl font-bold mb-6">Posts Relacionados</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @foreach($relatedPosts as $relatedPost)
                                <article class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                                    @if($relatedPost->featured_image)
                                        <a href="{{ route('blog.show', $relatedPost->slug) }}">
                                            <img src="{{ url('images/' . $relatedPost->featured_image) }}" 
                                                 alt="{{ $relatedPost->title }}"
                                                 class="w-full h-40 object-cover hover:opacity-90 transition-opacity"
                                                 loading="lazy"
                                                 width="400"
                                                 height="160"
                                                 onerror="this.src='{{ url('images/general/callback-image.svg') }}'; this.classList.add('object-contain', 'bg-gray-100');">
                                        </a>
                                    @else
                                        <div class="w-full h-40 bg-gradient-to-br from-primary to-primary-dark"></div>
                                    @endif
                                    
                                    <div class="p-4">
                                        <h3 class="text-lg font-bold mb-2 hover:text-primary transition-colors">
                                            <a href="{{ route('blog.show', $relatedPost->slug) }}">
                                                {{ Str::limit($relatedPost->title, 60) }}
                                            </a>
                                        </h3>
                                        <p class="text-sm text-gray-600 mb-3">
                                            {{ Str::limit($relatedPost->excerpt ?? strip_tags($relatedPost->content), 100) }}
                                        </p>
                                        <a href="{{ route('blog.show', $relatedPost->slug) }}" 
                                           class="text-primary hover:text-primary-dark font-semibold text-sm">
                                            Ler mais <i class="bi bi-arrow-right ml-1"></i>
                                        </a>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                @endif
            </article>

            <!-- Sidebar -->
            <aside class="lg:w-80">
                <!-- Posts Recentes -->
                <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                    <h3 class="text-xl font-bold mb-4 flex items-center">
                        <i class="bi bi-clock mr-2 text-primary"></i>Posts Recentes
                    </h3>
                    <ul class="space-y-4">
                        @foreach($recentPosts as $recentPost)
                            <li class="border-b border-gray-100 last:border-0 pb-4 last:pb-0">
                                <a href="{{ route('blog.show', $recentPost->slug) }}" 
                                   class="flex gap-3 group">
                                    @if($recentPost->featured_image)
                                        <img src="{{ url('images/' . $recentPost->featured_image) }}" 
                                             alt="{{ $recentPost->title }}"
                                             class="w-16 h-16 object-cover rounded"
                                             loading="lazy"
                                             width="64"
                                             height="64"
                                             onerror="this.src='{{ url('images/general/callback-image.svg') }}'; this.classList.add('object-contain', 'bg-gray-100');">
                                    @else
                                        <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center flex-shrink-0">
                                            <i class="bi bi-image text-gray-400"></i>
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-semibold text-sm group-hover:text-primary transition-colors line-clamp-2">
                                            {{ $recentPost->title }}
                                        </h4>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $recentPost->published_at->format('d/m/Y') }}
                                        </p>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- CTA -->
                <div class="bg-gradient-to-br from-primary to-primary-dark text-white rounded-lg shadow-lg p-6">
                    <h3 class="text-xl font-bold mb-3">
                        <i class="bi bi-envelope mr-2"></i>Fale Conosco
                    </h3>
                    <p class="mb-4 opacity-90">
                        Tem alguma dúvida ou precisa de um orçamento? Entre em contato!
                    </p>
                    <a href="{{ route('contact.index') }}" 
                       class="block w-full bg-white text-primary text-center px-4 py-3 rounded-lg hover:bg-gray-100 transition-colors font-semibold">
                        Solicitar Orçamento
                    </a>
                </div>
            </aside>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .prose {
        color: #374151;
    }
    .prose h2 {
        font-size: 1.875rem;
        font-weight: 700;
        margin-top: 2rem;
        margin-bottom: 1rem;
    }
    .prose h3 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
    }
    .prose p {
        margin-bottom: 1.25rem;
        line-height: 1.75;
    }
    .prose ul, .prose ol {
        margin-bottom: 1.25rem;
        padding-left: 1.5rem;
    }
    .prose li {
        margin-bottom: 0.5rem;
    }
    .prose img {
        border-radius: 0.5rem;
        margin: 2rem 0;
    }
    .prose a {
        color: #0066cc;
        text-decoration: underline;
    }
    .prose a:hover {
        color: #0052a3;
    }
    .prose blockquote {
        border-left: 4px solid #0066cc;
        padding-left: 1rem;
        font-style: italic;
        color: #6b7280;
        margin: 1.5rem 0;
    }
    .prose code {
        background-color: #f3f4f6;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-family: monospace;
        font-size: 0.875rem;
    }
    .prose pre {
        background-color: #1f2937;
        color: #f9fafb;
        padding: 1rem;
        border-radius: 0.5rem;
        overflow-x: auto;
        margin: 1.5rem 0;
    }
    .prose pre code {
        background-color: transparent;
        color: inherit;
        padding: 0;
    }
</style>
@endpush

