<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Post;
use App\Models\Category;
use App\Models\Setting;
use Illuminate\Support\Collection;

class BlogSeoService
{
    /**
     * Gerar Schema.org Article para post individual
     */
    public function generateArticleSchema(Post $post): array
    {
        $siteName = Setting::get('site_name', 'Laser Link');
        $siteUrl = url('/');
        $logo = url('images/logo.png');

        return [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $post->title,
            'description' => $post->meta_description ?? $post->excerpt ?? strip_tags(substr($post->content, 0, 160)),
            'image' => $post->featured_image ? url('images/' . $post->featured_image) : $logo,
            'datePublished' => $post->published_at->toIso8601String(),
            'dateModified' => $post->updated_at->toIso8601String(),
            'author' => [
                '@type' => 'Person',
                'name' => $post->author->name,
                'url' => $siteUrl,
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => $siteName,
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => $logo,
                ],
            ],
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => route('blog.show', $post->slug),
            ],
            'articleSection' => $post->category?->name,
            'keywords' => $post->meta_keywords,
            'wordCount' => str_word_count(strip_tags($post->content)),
            'inLanguage' => 'pt-BR',
        ];
    }

    /**
     * Gerar Schema.org BreadcrumbList
     */
    public function generateBreadcrumbSchema(array $items): array
    {
        $breadcrumbs = [];
        $position = 1;

        foreach ($items as $item) {
            $breadcrumbs[] = [
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => $item['name'],
                'item' => $item['url'],
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $breadcrumbs,
        ];
    }

    /**
     * Gerar Schema.org Blog/CollectionPage
     */
    public function generateBlogSchema(): array
    {
        $siteName = Setting::get('site_name', 'Laser Link');
        
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Blog',
            'name' => "Blog {$siteName}",
            'description' => 'Fique por dentro das novidades em comunicação visual, dicas e tendências',
            'url' => route('blog.index'),
            'inLanguage' => 'pt-BR',
            'publisher' => [
                '@type' => 'Organization',
                'name' => $siteName,
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => url('images/logo.png'),
                ],
            ],
        ];
    }

    /**
     * Gerar meta tags completas para post
     */
    public function generatePostMetaTags(Post $post): array
    {
        $description = $post->meta_description ?? $post->excerpt ?? strip_tags(substr($post->content, 0, 160));
        $image = $post->featured_image ? url('images/' . $post->featured_image) : url('images/logo.png');
        $url = route('blog.show', $post->slug);

        return [
            'title' => $post->title . ' - Blog Laser Link',
            'description' => $description,
            'keywords' => $post->meta_keywords,
            'canonical' => $url,
            'og' => [
                'type' => 'article',
                'title' => $post->title,
                'description' => $description,
                'image' => $image,
                'url' => $url,
                'site_name' => 'Laser Link',
                'article:published_time' => $post->published_at->toIso8601String(),
                'article:modified_time' => $post->updated_at->toIso8601String(),
                'article:author' => $post->author->name,
                'article:section' => $post->category?->name,
                'article:tag' => $post->meta_keywords,
            ],
            'twitter' => [
                'card' => 'summary_large_image',
                'title' => $post->title,
                'description' => $description,
                'image' => $image,
            ],
        ];
    }

    /**
     * Gerar meta tags para listagem do blog
     */
    public function generateBlogIndexMetaTags(?string $search = null): array
    {
        $title = 'Blog - Laser Link';
        $description = 'Fique por dentro das novidades em comunicação visual, dicas, tendências e cases de sucesso. Artigos sobre letreiros, placas, troféus, displays e muito mais.';

        if ($search) {
            $title = "Busca: {$search} - Blog Laser Link";
            $description = "Resultados da busca por '{$search}' no blog Laser Link.";
        }

        return [
            'title' => $title,
            'description' => $description,
            'keywords' => 'blog, comunicação visual, letreiros, placas, troféus, displays, novidades, dicas, tendências',
            'canonical' => route('blog.index'),
            'og' => [
                'type' => 'website',
                'title' => $title,
                'description' => $description,
                'image' => url('images/logo.png'),
                'url' => route('blog.index'),
                'site_name' => 'Laser Link',
            ],
            'twitter' => [
                'card' => 'summary',
                'title' => $title,
                'description' => $description,
                'image' => url('images/logo.png'),
            ],
        ];
    }

    /**
     * Gerar meta tags para categoria
     */
    public function generateCategoryMetaTags(Category $category): array
    {
        $title = "{$category->name} - Blog Laser Link";
        $description = $category->description ?? "Confira todos os artigos sobre {$category->name} no blog Laser Link. Dicas, novidades e informações sobre comunicação visual.";

        return [
            'title' => $title,
            'description' => $description,
            'keywords' => "{$category->name}, blog, comunicação visual, laser link",
            'canonical' => route('blog.category', $category->slug),
            'og' => [
                'type' => 'website',
                'title' => $title,
                'description' => $description,
                'image' => url('images/logo.png'),
                'url' => route('blog.category', $category->slug),
                'site_name' => 'Laser Link',
            ],
            'twitter' => [
                'card' => 'summary',
                'title' => $title,
                'description' => $description,
                'image' => url('images/logo.png'),
            ],
        ];
    }

    /**
     * Gerar breadcrumbs para post
     */
    public function generatePostBreadcrumbs(Post $post): array
    {
        $breadcrumbs = [
            ['name' => 'Início', 'url' => url('/')],
            ['name' => 'Blog', 'url' => route('blog.index')],
        ];

        if ($post->category) {
            $breadcrumbs[] = [
                'name' => $post->category->name,
                'url' => route('blog.category', $post->category->slug),
            ];
        }

        $breadcrumbs[] = [
            'name' => $post->title,
            'url' => route('blog.show', $post->slug),
        ];

        return $breadcrumbs;
    }

    /**
     * Gerar breadcrumbs para categoria
     */
    public function generateCategoryBreadcrumbs(Category $category): array
    {
        return [
            ['name' => 'Início', 'url' => url('/')],
            ['name' => 'Blog', 'url' => route('blog.index')],
            ['name' => $category->name, 'url' => route('blog.category', $category->slug)],
        ];
    }

    /**
     * Gerar breadcrumbs para index do blog
     */
    public function generateBlogIndexBreadcrumbs(): array
    {
        return [
            ['name' => 'Início', 'url' => url('/')],
            ['name' => 'Blog', 'url' => route('blog.index')],
        ];
    }

    /**
     * Gerar BlogPosting ItemList para listagem de posts
     */
    public function generateBlogPostingListSchema(Collection $posts): array
    {
        $items = [];
        $position = 1;

        foreach ($posts as $post) {
            $items[] = [
                '@type' => 'ListItem',
                'position' => $position++,
                'url' => route('blog.show', $post->slug),
                'name' => $post->title,
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'itemListElement' => $items,
        ];
    }

    /**
     * Verificar e otimizar meta description
     */
    public function optimizeMetaDescription(?string $description, string $fallback, int $maxLength = 160): string
    {
        $text = $description ?? $fallback;
        
        if (mb_strlen($text) > $maxLength) {
            $text = mb_substr($text, 0, $maxLength - 3) . '...';
        }

        return strip_tags($text);
    }

    /**
     * Gerar tags de paginação (rel="prev" e rel="next")
     */
    public function generatePaginationTags(object $paginator): array
    {
        $tags = [];

        if ($paginator->currentPage() > 1) {
            $tags['prev'] = $paginator->previousPageUrl();
        }

        if ($paginator->hasMorePages()) {
            $tags['next'] = $paginator->nextPageUrl();
        }

        return $tags;
    }

    /**
     * Gerar Schema.org FAQPage
     * 
     * @param array $faqs Array de perguntas e respostas: [['question' => '...', 'answer' => '...'], ...]
     * @return array
     */
    public function generateFaqSchema(array $faqs): array
    {
        if (empty($faqs)) {
            return [];
        }

        $mainEntity = [];

        foreach ($faqs as $faq) {
            if (empty($faq['question']) || empty($faq['answer'])) {
                continue;
            }

            $mainEntity[] = [
                '@type' => 'Question',
                'name' => $faq['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $faq['answer'],
                ],
            ];
        }

        if (empty($mainEntity)) {
            return [];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $mainEntity,
        ];
    }

    /**
     * Gerar Schema.org HowTo
     * 
     * @param string $name Nome do tutorial
     * @param string $description Descrição
     * @param array $steps Array de passos: [['name' => '...', 'text' => '...', 'image' => '...'], ...]
     * @return array
     */
    public function generateHowToSchema(string $name, string $description, array $steps): array
    {
        if (empty($steps)) {
            return [];
        }

        $stepEntities = [];
        $position = 1;

        foreach ($steps as $step) {
            if (empty($step['name']) || empty($step['text'])) {
                continue;
            }

            $stepEntity = [
                '@type' => 'HowToStep',
                'position' => $position++,
                'name' => $step['name'],
                'text' => $step['text'],
            ];

            if (!empty($step['image'])) {
                $stepEntity['image'] = $step['image'];
            }

            $stepEntities[] = $stepEntity;
        }

        if (empty($stepEntities)) {
            return [];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'HowTo',
            'name' => $name,
            'description' => $description,
            'step' => $stepEntities,
        ];
    }

    /**
     * Gerar Schema.org VideoObject
     * 
     * @param string $name Nome do vídeo
     * @param string $description Descrição
     * @param string $thumbnailUrl URL da thumbnail
     * @param string $uploadDate Data de upload (ISO 8601)
     * @param string $contentUrl URL do vídeo
     * @param int|null $duration Duração em segundos
     * @return array
     */
    public function generateVideoSchema(
        string $name,
        string $description,
        string $thumbnailUrl,
        string $uploadDate,
        string $contentUrl,
        ?int $duration = null
    ): array {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'VideoObject',
            'name' => $name,
            'description' => $description,
            'thumbnailUrl' => $thumbnailUrl,
            'uploadDate' => $uploadDate,
            'contentUrl' => $contentUrl,
        ];

        if ($duration) {
            // Converter para formato ISO 8601 duration (PT1M30S = 1 min 30 seg)
            $minutes = floor($duration / 60);
            $seconds = $duration % 60;
            $schema['duration'] = "PT{$minutes}M{$seconds}S";
        }

        return $schema;
    }
}

