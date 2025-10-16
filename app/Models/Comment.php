<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'user_id',
        'author_name',
        'author_email',
        'content',
        'status',
        'ip_address',
        'user_agent',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'pending',
    ];

    /**
     * Relacionamento com o post
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Relacionamento com o usuário (autor do comentário)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relacionamento com quem aprovou
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scope para comentários aprovados
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope para comentários pendentes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope para comentários rejeitados
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Obter nome do autor (user ou guest)
     */
    public function getAuthorNameAttribute(): string
    {
        return $this->user ? $this->user->name : ($this->attributes['author_name'] ?? 'Anônimo');
    }

    /**
     * Verificar se está aprovado
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Verificar se está pendente
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}
