<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Post::with(['author', 'category']);

        // Busca
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filtro por status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtro por categoria
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $posts = $query->latest('created_at')->paginate(20);
        $categories = Category::orderBy('name')->get();

        return view('admin.posts.index', compact('posts', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        $fileManagerItems = $this->loadFilesForManager();
        return view('admin.posts.create', compact('categories', 'fileManagerItems'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:posts,slug',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|string|max:500',
            'meta_description' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published',
            'category_id' => 'nullable|exists:categories,id',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'published_at' => 'nullable|date',
        ]);

        // Gerar slug se não fornecido
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        // Verificar slug único
        $originalSlug = $validated['slug'];
        $counter = 1;
        while (Post::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Upload da imagem destacada (via file manager ou upload direto)
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')
                ->store('posts', 'public');
        } elseif ($request->filled('featured_image')) {
            // Imagem vem do file manager (já é um path)
            // Não fazer nada, usar o path fornecido
        }

        // Definir autor
        $validated['author_id'] = Auth::id();

        // Se status for publicado e não tiver data, usar agora
        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        // Extrair categorias antes de criar
        $categories = $request->input('categories', []);

        $post = Post::create($validated);

        // Sincronizar categorias (many-to-many)
        if (!empty($categories)) {
            $post->categories()->sync($categories);
        }

        // Manter compatibilidade com category_id único
        if ($request->filled('category_id') && empty($categories)) {
            $post->categories()->sync([$request->category_id]);
        }

        return redirect()
            ->route('admin.posts.edit', $post)
            ->with('success', 'Post criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post): View
    {
        $post->load(['author', 'category']);
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post): View
    {
        $categories = Category::orderBy('name')->get();
        $fileManagerItems = $this->loadFilesForManager();
        return view('admin.posts.edit', compact('post', 'categories', 'fileManagerItems'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:posts,slug,' . $post->id,
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|string|max:500',
            'meta_description' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published',
            'category_id' => 'nullable|exists:categories,id',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'published_at' => 'nullable|date',
            'remove_image' => 'nullable|boolean',
        ]);

        // Gerar slug se não fornecido
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        // Verificar slug único (exceto para o post atual)
        $originalSlug = $validated['slug'];
        $counter = 1;
        while (Post::where('slug', $validated['slug'])->where('id', '!=', $post->id)->exists()) {
            $validated['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Remover imagem se solicitado
        if ($request->boolean('remove_image') && $post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
            $validated['featured_image'] = null;
        }

        // Upload da nova imagem destacada (via file manager ou upload direto)
        if ($request->hasFile('featured_image')) {
            // Deletar imagem antiga
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')
                ->store('posts', 'public');
        } elseif ($request->filled('featured_image')) {
            // Imagem vem do file manager (já é um path)
            // Não fazer nada, usar o path fornecido
        }

        // Se status mudou para publicado e não tinha data, usar agora
        if ($validated['status'] === 'published' && empty($validated['published_at']) && $post->status !== 'published') {
            $validated['published_at'] = now();
        }

        // Extrair categorias antes de atualizar
        $categories = $request->input('categories', []);

        $post->update($validated);

        // Sincronizar categorias (many-to-many)
        if (!empty($categories)) {
            $post->categories()->sync($categories);
        } elseif ($request->filled('category_id')) {
            // Manter compatibilidade com category_id único
            $post->categories()->sync([$request->category_id]);
        } else {
            // Se nenhuma categoria foi selecionada, limpar todas
            $post->categories()->sync([]);
        }

        return redirect()
            ->route('admin.posts.edit', $post)
            ->with('success', 'Post atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post): RedirectResponse
    {
        // Deletar imagem se existir
        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }

        $post->delete();

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Post deletado com sucesso!');
    }

    /**
     * Toggle post status
     */
    public function toggleStatus(Post $post): RedirectResponse
    {
        $newStatus = $post->status === 'published' ? 'draft' : 'published';
        
        $post->update([
            'status' => $newStatus,
            'published_at' => $newStatus === 'published' && !$post->published_at ? now() : $post->published_at,
        ]);

        return back()->with('success', 'Status do post atualizado!');
    }

    /**
     * Carregar arquivos para o File Manager
     */
    private function loadFilesForManager(): array
    {
        try {
            $allFiles = collect();
            $this->loadAllFilesRecursively('', $allFiles);
            
            return $allFiles->sortBy('name')->values()->toArray();
            
        } catch (\Exception $e) {
            Log::error('Erro ao carregar arquivos: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Carregar arquivos recursivamente
     */
    private function loadAllFilesRecursively(string $directory, &$allFiles): void
    {
        try {
            $files = Storage::disk('public')->allFiles($directory);
            
            foreach ($files as $file) {
                $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                
                // Filtrar apenas imagens
                if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp', 'bmp', 'ico'])) {
                    $folder = 'Raiz';
                    if (strpos($file, '/') !== false) {
                        $parts = explode('/', $file);
                        $folder = $parts[0];
                    }
                    
                    $allFiles->push([
                        'name' => basename($file),
                        'path' => $file,
                        'url' => Storage::disk('public')->url($file),
                        'extension' => $extension,
                        'folder' => $folder,
                        'type' => 'file'
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Erro ao carregar arquivos recursivamente: ' . $e->getMessage());
        }
    }
}
