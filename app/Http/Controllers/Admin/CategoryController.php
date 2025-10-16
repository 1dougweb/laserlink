<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $categories = Category::withCount('products')
            ->with('parent')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $parentCategories = Category::whereNull('parent_id')
            ->orderBy('name')
            ->get();
            
        return view('admin.categories.create', compact('parentCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'nullable|boolean',
            'show_in_home' => 'nullable|boolean',
            'home_order' => 'nullable|integer|min:0',
            'sort_order' => 'nullable|integer|min:0',
            'parent_id' => 'nullable|exists:categories,id',
        ]);
        
        // Definir valores padrão para requisições AJAX
        if ($request->wantsJson() || $request->ajax()) {
            $validated['is_active'] = $validated['is_active'] ?? true;
            $validated['show_in_home'] = $validated['show_in_home'] ?? false;
        }

        // Upload da imagem
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        // Se não foi informado sort_order, usar o próximo disponível
        if (!isset($validated['sort_order'])) {
            $validated['sort_order'] = Category::max('sort_order') + 1;
        }

        // Se não foi informado home_order, usar o próximo disponível
        if (!isset($validated['home_order'])) {
            $validated['home_order'] = Category::max('home_order') + 1;
        }

        $category = Category::create($validated);

        // Se for requisição AJAX, retornar JSON
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Categoria criada com sucesso!',
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'parent_id' => $category->parent_id,
                ],
            ]);
        }

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoria criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category): View
    {
        $category->loadCount('products');
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category): View
    {
        $parentCategories = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->orderBy('name')
            ->get();
            
        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
            'show_in_home' => 'boolean',
            'home_order' => 'nullable|integer|min:0',
            'sort_order' => 'nullable|integer|min:0',
            'remove_image' => 'nullable|boolean',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        // Remover imagem se solicitado
        if ($request->boolean('remove_image') && $category->image) {
            Storage::disk('public')->delete($category->image);
            $validated['image'] = null;
        }

        // Upload da nova imagem
        if ($request->hasFile('image')) {
            // Remover imagem anterior se existir
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoria atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): RedirectResponse
    {
        // Verificar se a categoria tem produtos
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Não é possível excluir uma categoria que possui produtos.');
        }
        
        // Verificar se a categoria tem subcategorias
        if ($category->children()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Não é possível excluir uma categoria que possui subcategorias.');
        }

        // Remover imagem se existir
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoria excluída com sucesso!');
    }
}