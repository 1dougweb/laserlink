<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreMenuItem extends Model
{
    protected $fillable = [
        'name',
        'url',
        'icon',
        'is_active',
        'is_external',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_external' => 'boolean',
        'sort_order' => 'integer'
    ];

    /**
     * Scope para itens ativos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para ordenar por sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
