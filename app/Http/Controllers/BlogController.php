<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Services\BlogSeoService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function __construct(
        private readonly BlogSeoService $blogSeoService
    ) {
    }

    /**
     * Display a listing of blog posts.
     */
    public function index(Request $request): View
    {
        $query = Post::with(['author', 'category'])
            ->published()
            ->latest('published_at');

        // Busca
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filtro por categoria
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $posts = $query->paginate(12);
        $categories = Category::has('posts')->withCount('posts')->orderBy('name')->get();
        $recentPosts = Post::published()->latest('published_at')->limit(5)->get();

        // SEO Data
        $seoData = [
            'metaTags' => $this->blogSeoService->generateBlogIndexMetaTags($request->search),
            'breadcrumbSchema' => $this->blogSeoService->generateBreadcrumbSchema(
                $this->blogSeoService->generateBlogIndexBreadcrumbs()
            ),
            'blogSchema' => $this->blogSeoService->generateBlogSchema(),
            'breadcrumbs' => $this->blogSeoService->generateBlogIndexBreadcrumbs(),
            'paginationTags' => $this->blogSeoService->generatePaginationTags($posts),
        ];

        return view('blog.index', compact('posts', 'categories', 'recentPosts', 'seoData'));
    }

    /**
     * Display the specified blog post.
     */
    public function show(string $slug): View
    {
        $post = Post::with(['author', 'category'])
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();

        // Incrementar visualizações
        $post->incrementViews();

        // Posts relacionados (mesma categoria)
        $relatedPosts = Post::published()
            ->where('id', '!=', $post->id)
            ->where('category_id', $post->category_id)
            ->latest('published_at')
            ->limit(3)
            ->get();

        $recentPosts = Post::published()->latest('published_at')->limit(5)->get();

        // SEO Data
        $seoData = [
            'metaTags' => $this->blogSeoService->generatePostMetaTags($post),
            'articleSchema' => $this->blogSeoService->generateArticleSchema($post),
            'breadcrumbSchema' => $this->blogSeoService->generateBreadcrumbSchema(
                $this->blogSeoService->generatePostBreadcrumbs($post)
            ),
            'breadcrumbs' => $this->blogSeoService->generatePostBreadcrumbs($post),
        ];

        return view('blog.show', compact('post', 'relatedPosts', 'recentPosts', 'seoData'));
    }

    /**
     * Display posts by category.
     */
    public function category(string $slug): View
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        
        $posts = Post::with(['author', 'category'])
            ->published()
            ->where('category_id', $category->id)
            ->latest('published_at')
            ->paginate(12);

        $categories = Category::has('posts')->withCount('posts')->orderBy('name')->get();
        $recentPosts = Post::published()->latest('published_at')->limit(5)->get();

        // SEO Data
        $seoData = [
            'metaTags' => $this->blogSeoService->generateCategoryMetaTags($category),
            'breadcrumbSchema' => $this->blogSeoService->generateBreadcrumbSchema(
                $this->blogSeoService->generateCategoryBreadcrumbs($category)
            ),
            'breadcrumbs' => $this->blogSeoService->generateCategoryBreadcrumbs($category),
            'paginationTags' => $this->blogSeoService->generatePaginationTags($posts),
        ];

        return view('blog.category', compact('category', 'posts', 'categories', 'recentPosts', 'seoData'));
    }
}

