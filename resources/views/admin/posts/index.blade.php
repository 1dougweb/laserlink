@extends('admin.layout')

@section('title', 'Posts do Blog')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Posts do Blog</h1>
        <a href="{{ route('admin.posts.create') }}" 
           class="bg-primary hover:opacity-90 text-white px-4 py-2 rounded-lg transition-all shadow-sm hover:shadow-md">
            + Novo Post
        </a>
    </div>

    <!-- Filtros de Pesquisa -->
    <div class="bg-white rounded-lg shadow mb-6" x-data="{ showFilters: {{ request()->hasAny(['search', 'category_id', 'status']) ? 'true' : 'false' }} }">
        <div class="p-4 border-b border-gray-200">
            <button @click="showFilters = !showFilters" 
                    class="flex items-center justify-between w-full text-left">
                <div class="flex items-center space-x-2">
                    <i class="bi bi-funnel text-gray-600"></i>
                    <span class="font-medium text-gray-900">Filtros de Pesquisa</span>
                    @if(request()->hasAny(['search', 'category_id', 'status']))
                        <span class="bg-primary bg-opacity-10 text-primary text-xs font-medium px-2.5 py-0.5 rounded-full">
                            Ativos
                        </span>
                    @endif
                </div>
                <i class="bi transition-transform duration-200" 
                   :class="showFilters ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
            </button>
        </div>

        <form method="GET" action="{{ route('admin.posts.index') }}" x-show="showFilters" x-cloak>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Pesquisa -->
                <div class="lg:col-span-2">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="bi bi-search mr-1"></i>
                        Buscar por Título
                    </label>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Digite o título do post..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>

                <!-- Categoria -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="bi bi-folder mr-1"></i>
                        Categoria
                    </label>
                    <select id="category_id" 
                            name="category_id" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">Todas</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="bi bi-toggle-on mr-1"></i>
                        Status
                    </label>
                    <select id="status" 
                            name="status" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">Todos</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Rascunho</option>
                        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Publicado</option>
                    </select>
                </div>

                <!-- Ordenação -->
                <div>
                    <label for="sort_by" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="bi bi-sort-down mr-1"></i>
                        Ordenar por
                    </label>
                    <select id="sort_by" 
                            name="sort_by" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="created_at" {{ request('sort_by', 'created_at') === 'created_at' ? 'selected' : '' }}>Data de Criação</option>
                        <option value="title" {{ request('sort_by') === 'title' ? 'selected' : '' }}>Título</option>
                        <option value="views" {{ request('sort_by') === 'views' ? 'selected' : '' }}>Visualizações</option>
                        <option value="published_at" {{ request('sort_by') === 'published_at' ? 'selected' : '' }}>Data de Publicação</option>
                    </select>
                </div>
            </div>
            
            <div class="px-6 pb-4 flex justify-end space-x-3">
                <a href="{{ route('admin.posts.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="bi bi-arrow-counterclockwise mr-1"></i>
                    Limpar
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-primary text-white rounded-lg hover:opacity-90 transition-colors">
                    <i class="bi bi-search mr-1"></i>
                    Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Tabela de Posts -->
    @if ($posts->isEmpty())
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <i class="bi bi-newspaper text-gray-300 text-6xl mb-4"></i>
            <p class="text-lg text-gray-500">Nenhum post encontrado.</p>
            <p class="text-sm text-gray-400 mt-2">Crie seu primeiro post para começar!</p>
            <a href="{{ route('admin.posts.create') }}" 
               class="mt-4 inline-block bg-primary text-white px-6 py-2 rounded-lg hover:opacity-90 transition-colors">
                Criar Primeiro Post
            </a>
        </div>
    @else
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Post
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Categoria
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Autor
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Visualizações
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Data
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ações
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($posts as $post)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if ($post->featured_image)
                                        <img src="{{ asset('storage/' . $post->featured_image) }}" 
                                             alt="{{ $post->title }}"
                                             class="w-16 h-16 object-cover rounded mr-3">
                                    @else
                                        <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center mr-3">
                                            <i class="bi bi-newspaper text-gray-400"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ Str::limit($post->title, 50) }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ Str::limit($post->excerpt ?? strip_tags($post->content), 80) }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $post->category->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $post->author->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form action="{{ route('admin.posts.toggle-status', $post) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium rounded-full {{ $post->status === 'published' ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200' }} transition-colors">
                                        <i class="bi {{ $post->status === 'published' ? 'bi-check-circle' : 'bi-clock' }} mr-1"></i>
                                        {{ $post->status === 'published' ? 'Publicado' : 'Rascunho' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <i class="bi bi-eye mr-1"></i>{{ number_format($post->views) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if ($post->published_at)
                                    {{ $post->published_at->format('d/m/Y H:i') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    @if ($post->isPublished())
                                        <a href="{{ $post->url }}" 
                                           target="_blank"
                                           class="text-blue-600 hover:text-blue-900"
                                           title="Ver Post">
                                            <i class="bi bi-box-arrow-up-right"></i>
                                        </a>
                                    @endif
                                    <a href="{{ route('admin.posts.edit', $post) }}" 
                                       class="text-primary hover:text-red-700"
                                       title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.posts.destroy', $post) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Tem certeza que deseja deletar este post?')"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900"
                                                title="Deletar">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginação -->
        <div class="mt-4">
            {{ $posts->links() }}
        </div>
    @endif
</div>

@endsection
