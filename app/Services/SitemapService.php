<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use App\Models\Post;
use App\Models\Page;
use App\Models\Setting;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;

class SitemapService
{
    /**
     * Gera o sitemap completo
     */
    public function generate(): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        // Página inicial
        $xml .= $this->generateUrlTag(url('/'), '1.0', 'daily', now());

        // Páginas estáticas
        $xml .= $this->generateUrlTag(route('store.products'), '0.9', 'daily', now());
        $xml .= $this->generateUrlTag(route('blog.index'), '0.9', 'daily', now());
        
        // Contato
        if (route('contact.index')) {
            $xml .= $this->generateUrlTag(route('contact.index'), '0.7', 'monthly', now());
        }

        // Páginas customizadas (Políticas, Sobre, etc)
        $pages = Page::where('is_active', true)->get();
        foreach ($pages as $page) {
            $xml .= $this->generateUrlTag(
                route('page.show', $page->slug),
                '0.8',
                'monthly',
                $page->updated_at
            );
        }

        // Produtos
        $products = Product::where('is_active', true)->get();
        foreach ($products as $product) {
            $xml .= $this->generateUrlTag(
                route('store.product', $product->slug),
                '0.8',
                'weekly',
                $product->updated_at
            );
        }

        // Categorias
        $categories = Category::where('is_active', true)->get();
        foreach ($categories as $category) {
            $xml .= $this->generateUrlTag(
                route('store.category', $category->slug),
                '0.7',
                'weekly',
                $category->updated_at
            );
        }

        // Categorias do Blog
        $blogCategories = Category::whereHas('posts', function ($query) {
            $query->where('status', 'published');
        })->get();
        
        foreach ($blogCategories as $blogCategory) {
            $xml .= $this->generateUrlTag(
                route('blog.category', $blogCategory->slug),
                '0.7',
                'weekly',
                $blogCategory->updated_at
            );
        }

        // Posts do Blog (priorizando posts recentes)
        $posts = Post::published()
            ->orderBy('published_at', 'desc')
            ->get();
            
        foreach ($posts as $index => $post) {
            // Posts mais recentes têm prioridade maior
            $priority = $index < 10 ? '0.8' : '0.6';
            $changefreq = $index < 10 ? 'weekly' : 'monthly';
            
            $xml .= $this->generateUrlTag(
                route('blog.show', $post->slug),
                $priority,
                $changefreq,
                $post->updated_at
            );
        }

        $xml .= '</urlset>';

        // Salvar arquivo
        $this->saveSitemap($xml);

        return $xml;
    }

    /**
     * Gera uma tag URL do sitemap
     */
    private function generateUrlTag(string $url, string $priority, string $changefreq, $lastmod): string
    {
        $xml = '  <url>' . PHP_EOL;
        $xml .= '    <loc>' . htmlspecialchars($url) . '</loc>' . PHP_EOL;
        $xml .= '    <lastmod>' . $lastmod->toW3cString() . '</lastmod>' . PHP_EOL;
        $xml .= '    <changefreq>' . $changefreq . '</changefreq>' . PHP_EOL;
        $xml .= '    <priority>' . $priority . '</priority>' . PHP_EOL;
        $xml .= '  </url>' . PHP_EOL;
        
        return $xml;
    }

    /**
     * Salva o sitemap no diretório público
     */
    private function saveSitemap(string $xml): void
    {
        $path = public_path('sitemap.xml');
        File::put($path, $xml);
    }

    /**
     * Verifica se o sitemap existe
     */
    public function exists(): bool
    {
        return File::exists(public_path('sitemap.xml'));
    }

    /**
     * Retorna a data da última geração
     */
    public function getLastGenerated(): ?string
    {
        if (!$this->exists()) {
            return null;
        }

        $timestamp = File::lastModified(public_path('sitemap.xml'));
        return date('d/m/Y H:i:s', $timestamp);
    }

    /**
     * Retorna o tamanho do arquivo
     */
    public function getFileSize(): ?string
    {
        if (!$this->exists()) {
            return null;
        }

        $bytes = File::size(public_path('sitemap.xml'));
        return $this->formatBytes($bytes);
    }

    /**
     * Formata bytes para formato legível
     */
    private function formatBytes(int $bytes): string
    {
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    /**
     * Conta URLs no sitemap
     */
    public function countUrls(): int
    {
        $products = Product::where('is_active', true)->count();
        $categories = Category::where('is_active', true)->count();
        $posts = Post::where('status', 'published')->count();
        $pages = Page::where('is_active', true)->count();
        $blogCategories = Category::whereHas('posts', function ($query) {
            $query->where('status', 'published');
        })->count();
        
        // 4 páginas estáticas + páginas customizadas + produtos + categorias + posts + categorias do blog
        return 4 + $pages + $products + $categories + $posts + $blogCategories;
    }
    
    /**
     * Gera apenas o sitemap do blog
     */
    public function generateBlogSitemap(): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" ';
        $xml .= 'xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">' . PHP_EOL;

        // Página principal do blog
        $xml .= $this->generateUrlTag(route('blog.index'), '1.0', 'daily', now());

        // Categorias do Blog
        $blogCategories = Category::whereHas('posts', function ($query) {
            $query->where('status', 'published');
        })->get();
        
        foreach ($blogCategories as $blogCategory) {
            $xml .= $this->generateUrlTag(
                route('blog.category', $blogCategory->slug),
                '0.9',
                'weekly',
                $blogCategory->updated_at
            );
        }

        // Posts do Blog
        $posts = Post::published()
            ->orderBy('published_at', 'desc')
            ->get();
            
        foreach ($posts as $index => $post) {
            $priority = $index < 10 ? '0.9' : '0.7';
            $changefreq = $index < 10 ? 'daily' : 'weekly';
            
            $xml .= $this->generateBlogPostTag($post, $priority, $changefreq);
        }

        $xml .= '</urlset>';

        // Salvar arquivo separado
        $this->saveBlogSitemap($xml);

        return $xml;
    }
    
    /**
     * Gera tag de URL com suporte a Google News (para posts recentes)
     */
    private function generateBlogPostTag(Post $post, string $priority, string $changefreq): string
    {
        $xml = '  <url>' . PHP_EOL;
        $xml .= '    <loc>' . htmlspecialchars(route('blog.show', $post->slug)) . '</loc>' . PHP_EOL;
        $xml .= '    <lastmod>' . $post->updated_at->toW3cString() . '</lastmod>' . PHP_EOL;
        $xml .= '    <changefreq>' . $changefreq . '</changefreq>' . PHP_EOL;
        $xml .= '    <priority>' . $priority . '</priority>' . PHP_EOL;
        
        // Adicionar Google News tags para posts recentes (últimos 2 dias)
        if ($post->published_at->isAfter(now()->subDays(2))) {
            $xml .= '    <news:news>' . PHP_EOL;
            $xml .= '      <news:publication>' . PHP_EOL;
            $xml .= '        <news:name>Laser Link Blog</news:name>' . PHP_EOL;
            $xml .= '        <news:language>pt</news:language>' . PHP_EOL;
            $xml .= '      </news:publication>' . PHP_EOL;
            $xml .= '      <news:publication_date>' . $post->published_at->toW3cString() . '</news:publication_date>' . PHP_EOL;
            $xml .= '      <news:title>' . htmlspecialchars($post->title) . '</news:title>' . PHP_EOL;
            $xml .= '    </news:news>' . PHP_EOL;
        }
        
        $xml .= '  </url>' . PHP_EOL;
        
        return $xml;
    }
    
    /**
     * Salva o sitemap do blog
     */
    private function saveBlogSitemap(string $xml): void
    {
        $path = public_path('blog-sitemap.xml');
        File::put($path, $xml);
    }
}

