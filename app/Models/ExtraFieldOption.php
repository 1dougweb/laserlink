<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExtraFieldOption extends Model
{
    protected $fillable = [
        'extra_field_id',
        'value',
        'label',
        'image_url',
        'color_hex',
        'description',
        'price',
        'price_type',
        'settings',
        'conditional_logic',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'settings' => 'array',
        'conditional_logic' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'price' => 'decimal:2',
    ];

    public function field(): BelongsTo
    {
        return $this->belongsTo(ExtraField::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('label');
    }
}
