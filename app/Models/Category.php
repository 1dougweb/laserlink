<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'icon',
        'is_active',
        'sort_order',
        'show_in_home',
        'home_order',
        'fields',
        'parent_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'show_in_home' => 'boolean',
        'home_order' => 'integer',
        'fields' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
        
        static::updating(function ($category) {
            if ($category->isDirty('name') && empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Relacionamento com categoria pai
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Relacionamento com subcategorias (filhas)
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Relacionamento com subcategorias ativas
     */
    public function activeChildren(): HasMany
    {
        return $this->children()->where('is_active', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function scopeForHome($query)
    {
        return $query->where('is_active', true)
                    ->where('show_in_home', true)
                    ->orderBy('home_order')
                    ->orderBy('name');
    }

    /**
     * Scope para obter apenas categorias principais (sem parent_id)
     */
    public function scopeParent($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Relacionamento com campos extras
     */
    public function extraFields(): BelongsToMany
    {
        return $this->belongsToMany(ExtraField::class, 'category_extra_fields')
                    ->withPivot(['is_required', 'sort_order', 'field_settings'])
                    ->withTimestamps()
                    ->orderBy('sort_order');
    }

    /**
     * Get the fields array or fetch from extra fields if not set
     */
    public function getFieldsAttribute($value)
    {
        if (!empty($value)) {
            return $value;
        }

        // If no fields are set, get them from extra fields
        $extraFields = $this->extraFields()->with('options')->get();
        
        return $extraFields->map(function($field) {
            return [
                'name' => $field->name,
                'type' => $field->type,
                'label' => $field->name,
                'calculation' => !empty($field->pricing_rules),
                'options' => $field->options->map(function($option) {
                    return [
                        'value' => $option->value,
                        'label' => $option->label,
                        'price' => $option->price,
                        'price_type' => $option->price_type
                    ];
                })->toArray()
            ];
        })->toArray();
    }
}
