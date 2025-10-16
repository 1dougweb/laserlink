@extends('admin.layout')

@section('title', 'Gerenciar Comentários')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">
            <i class="bi bi-chat-left-text mr-2"></i>
            Comentários do Blog
        </h1>
    </div>

    <!-- Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="selo rounded-full bg-orange-100 text-orange-600">
                    <i class="bi bi-hourglass-split text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pendentes</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Comment::pending()->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="selo rounded-full bg-green-100 text-green-600">
                    <i class="bi bi-check-circle text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Aprovados</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Comment::approved()->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="selo rounded-full bg-red-100 text-red-600">
                    <i class="bi bi-x-circle text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Rejeitados</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Comment::rejected()->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="selo rounded-full bg-blue-100 text-blue-600">
                    <i class="bi bi-chat-dots text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Comment::count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow mb-6" x-data="{ showFilters: {{ request()->hasAny(['search', 'status']) ? 'true' : 'false' }} }">
        <div class="p-4 border-b border-gray-200">
            <button @click="showFilters = !showFilters" 
                    class="flex items-center justify-between w-full text-left">
                <div class="flex items-center space-x-2">
                    <i class="bi bi-funnel text-gray-600"></i>
                    <span class="font-medium text-gray-900">Filtros de Pesquisa</span>
                    @if(request()->hasAny(['search', 'status']))
                        <span class="bg-primary bg-opacity-10 text-primary text-xs font-medium px-2.5 py-0.5 rounded-full">
                            Ativos
                        </span>
                    @endif
                </div>
                <i class="bi transition-transform duration-200" 
                   :class="showFilters ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
            </button>
        </div>

        <form method="GET" action="{{ route('admin.comments.index') }}" x-show="showFilters" x-cloak>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Busca -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="bi bi-search mr-1"></i>
                        Buscar
                    </label>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Buscar por conteúdo ou autor..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
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
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>
                            Pendentes ({{ \App\Models\Comment::pending()->count() }})
                        </option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>
                            Aprovados ({{ \App\Models\Comment::approved()->count() }})
                        </option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>
                            Rejeitados ({{ \App\Models\Comment::rejected()->count() }})
                        </option>
                    </select>
                </div>
            </div>

            <div class="px-6 pb-6 flex justify-between">
                <a href="{{ route('admin.comments.index') }}" 
                   class="text-gray-600 hover:text-gray-900 px-4 py-2 text-sm font-medium">
                    <i class="bi bi-x-circle mr-1"></i>Limpar Filtros
                </a>
                <button type="submit" 
                        class="bg-primary hover:opacity-90 text-white px-6 py-2 rounded-lg transition-all shadow-sm hover:shadow-md font-medium">
                    <i class="bi bi-search mr-1"></i>Aplicar Filtros
                </button>
            </div>
        </form>
    </div>

    <!-- Lista de Comentários -->
    <div class="bg-white rounded-lg shadow">
        @if($comments->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Post
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Autor
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Comentário
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Data
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($comments as $comment)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <a href="{{ route('blog.show', $comment->post->slug) }}" 
                                       target="_blank" 
                                       class="text-primary hover:text-red-700 font-medium flex items-center">
                                        {{ Str::limit($comment->post->title, 40) }}
                                        <i class="bi bi-box-arrow-up-right ml-2 text-xs"></i>
                                    </a>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center text-white font-bold">
                                            {{ substr($comment->author_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $comment->author_name }}</div>
                                            @if($comment->author_email)
                                                <div class="text-sm text-gray-500">{{ $comment->author_email }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 max-w-md">
                                    <p class="text-gray-700 text-sm">{{ Str::limit($comment->content, 100) }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">
                                        {{ $comment->created_at->format('d/m/Y') }}
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        {{ $comment->created_at->format('H:i') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($comment->status === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                            <i class="bi bi-hourglass-split mr-1"></i>Pendente
                                        </span>
                                    @elseif($comment->status === 'approved')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="bi bi-check-circle mr-1"></i>Aprovado
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="bi bi-x-circle mr-1"></i>Rejeitado
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($comment->status !== 'approved')
                                            <form action="{{ route('admin.comments.approve', $comment) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="text-green-600 hover:text-green-900 transition-colors" 
                                                        title="Aprovar">
                                                    <i class="bi bi-check-lg text-xl"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if($comment->status !== 'rejected')
                                            <form action="{{ route('admin.comments.reject', $comment) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="text-orange-600 hover:text-orange-900 transition-colors" 
                                                        title="Rejeitar">
                                                    <i class="bi bi-x-lg text-xl"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <form action="{{ route('admin.comments.destroy', $comment) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('Tem certeza que deseja excluir este comentário?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900 transition-colors" 
                                                    title="Excluir">
                                                <i class="bi bi-trash text-xl"></i>
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
            @if($comments->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $comments->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                    <i class="bi bi-chat-left-text text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum comentário encontrado</h3>
                <p class="text-gray-500 mb-4">
                    @if(request()->hasAny(['search', 'status']))
                        Nenhum comentário corresponde aos filtros aplicados.
                    @else
                        Os comentários dos posts aparecerão aqui.
                    @endif
                </p>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('admin.comments.index') }}" 
                       class="inline-flex items-center text-primary hover:text-red-700 font-medium">
                        <i class="bi bi-arrow-counterclockwise mr-2"></i>
                        Limpar Filtros
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
