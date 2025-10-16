<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'status',
        'author_id',
        'category_id',
        'published_at',
        'views',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'views' => 'integer',
    ];

    protected $attributes = [
        'status' => 'draft',
        'views' => 0,
    ];

    /**
     * Boot the model
     */
    protected static function boot(): void
    {
        parent::boot();

        // Gerar slug automaticamente
        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });

        static::updating(function ($post) {
            if ($post->isDirty('title') && empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });
    }

    /**
     * Relacionamento com o autor
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Relacionamento com a categoria (legacy - compatibilidade)
     * @deprecated Use categories() para múltiplas categorias
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relacionamento com categorias (many-to-many)
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_post')
                    ->withTimestamps();
    }

    /**
     * Relacionamento com comentários
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Comentários aprovados
     */
    public function approvedComments(): HasMany
    {
        return $this->hasMany(Comment::class)->approved()->latest();
    }

    /**
     * Scope para posts publicados
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    /**
     * Scope para posts em rascunho
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope para ordenar por data de publicação
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('published_at', 'desc');
    }

    /**
     * Scope para buscar posts
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('content', 'like', "%{$search}%")
              ->orWhere('excerpt', 'like', "%{$search}%");
        });
    }

    /**
     * Incrementar visualizações
     */
    public function incrementViews(): void
    {
        $this->increment('views');
    }

    /**
     * Verificar se o post está publicado
     */
    public function isPublished(): bool
    {
        return $this->status === 'published' 
               && $this->published_at !== null 
               && $this->published_at->isPast();
    }

    /**
     * Obter URL do post
     */
    public function getUrlAttribute(): string
    {
        return route('blog.show', $this->slug);
    }

    /**
     * Obter tempo de leitura estimado
     */
    public function getReadingTimeAttribute(): int
    {
        $words = str_word_count(strip_tags($this->content));
        return (int) ceil($words / 200); // Média de 200 palavras por minuto
    }
}
