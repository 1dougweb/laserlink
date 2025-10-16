<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Changelog extends Model
{
    protected $fillable = [
        'version',
        'title',
        'description',
        'features',
        'improvements',
        'fixes',
        'release_date',
        'is_published',
        'user_id',
    ];

    protected $casts = [
        'features' => 'array',
        'improvements' => 'array',
        'fixes' => 'array',
        'release_date' => 'date',
        'is_published' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('release_date', 'desc')->orderBy('created_at', 'desc');
    }
}
