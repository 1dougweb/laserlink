<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use App\Models\ProductReview;

class Product extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'category_id',
        'supplier_id',
        'name',
        'slug',
        'description',
        'short_description',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'price',
        'sale_price',
        'quantity_discount_enabled',
        'quantity_discount_rules',
        'whatsapp_quote_enabled',
        'whatsapp_quote_text',
        'auto_calculate_price',
        'min_price',
        'max_price',
        'sku',
        'stock_quantity',
        'stock_min',
        'stock_max',
        'track_stock',
        'is_active',
        'is_featured',
        'images',
        'featured_image',
        'gallery_images',
        'attributes',
        'custom_attributes',
        'sort_order',
        'rating_average',
        'rating_count',
    ];

    protected $casts = [
        'images' => 'array',
        'gallery_images' => 'array',
        'attributes' => 'array',
        'custom_attributes' => 'array',
        'quantity_discount_rules' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'track_stock' => 'boolean',
        'auto_calculate_price' => 'boolean',
        'quantity_discount_enabled' => 'boolean',
        'whatsapp_quote_enabled' => 'boolean',
        'sort_order' => 'integer',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'min_price' => 'decimal:2',
        'max_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'stock_min' => 'integer',
        'stock_max' => 'integer',
        'rating_average' => 'decimal:2',
        'rating_count' => 'integer',
    ];

    /**
     * Atributos que devem ser incluídos na serialização JSON
     */
    protected $appends = [
        'final_price',
        'is_on_sale',
        'discount_percentage',
        'original_price',
        'first_image',
        'all_images'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Relacionamento com reviews/avaliações
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    /**
     * Reviews aprovadas
     */
    public function approvedReviews(): HasMany
    {
        return $this->hasMany(ProductReview::class)->where('is_approved', true);
    }

    /**
     * Relacionamento com campos extras
     */
    public function extraFields()
    {
        return $this->belongsToMany(ExtraField::class, 'product_extra_fields')
                    ->withPivot(['is_required', 'sort_order', 'field_settings'])
                    ->withTimestamps()
                    ->orderBy('product_extra_fields.sort_order');
    }

    /**
     * Campos extras ativos
     */
    public function activeExtraFields()
    {
        return $this->extraFields()->where('is_active', true);
    }

    /**
     * Relacionamento com campos de fórmula
     */
    public function formulaFields()
    {
        return $this->belongsToMany(FormulaField::class, 'product_formula_fields')
                    ->withPivot(['is_required', 'sort_order', 'field_settings'])
                    ->withTimestamps()
                    ->orderBy('product_formula_fields.sort_order');
    }

    /**
     * Campos de fórmula ativos
     */
    public function activeFormulaFields()
    {
        return $this->formulaFields()->where('is_active', true);
    }

    /**
     * Scope para produtos ativos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para produtos em destaque
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope para produtos por categoria
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Obtém o preço final (considerando desconto)
     */
    public function getFinalPriceAttribute(): float
    {
        $finalPrice = $this->sale_price && $this->sale_price > 0 ? $this->sale_price : $this->price;
        return (float) $finalPrice;
    }

    /**
     * Verifica se está em promoção
     */
    public function getIsOnSaleAttribute(): bool
    {
        return $this->sale_price && $this->sale_price > 0 && $this->sale_price < $this->price;
    }

    /**
     * Verifica se é um produto novo (últimos 30 dias)
     */
    public function getIsNewAttribute(): bool
    {
        return $this->created_at && $this->created_at->diffInDays(now()) <= 30;
    }

    /**
     * Calcula a porcentagem de desconto
     */
    public function getDiscountPercentageAttribute(): int
    {
        if (!$this->is_on_sale) {
            return 0;
        }
        
        $discount = (($this->price - $this->sale_price) / $this->price) * 100;
        return (int) round($discount);
    }

    /**
     * Obtém o preço original
     */
    public function getOriginalPriceAttribute(): ?float
    {
        return $this->is_on_sale ? (float) $this->price : null;
    }

    /**
     * Obtém a URL da imagem principal
     */
    public function getImageAttribute(): ?string
    {
        return $this->first_image;
    }

    /**
     * Obtém o preço mínimo (para exibição em produtos variáveis)
     */
    public function getMinPriceAttribute(): float
    {
        return $this->final_price;
    }

    /**
     * Obtém a URL da primeira imagem
     */
    public function getFirstImageAttribute(): ?string
    {
        if ($this->featured_image) {
            return url('images/' . $this->featured_image);
        }
        
        if ($this->gallery_images && count($this->gallery_images) > 0) {
            return url('images/' . $this->gallery_images[0]);
        }
        
        if ($this->images && count($this->images) > 0) {
            return url('images/' . $this->images[0]);
        }
        
        return null;
    }

    /**
     * Obtém todas as URLs das imagens
     */
    public function getAllImagesAttribute(): array
    {
        $images = [];
        
        if ($this->featured_image) {
            $images[] = url('images/' . $this->featured_image);
        }
        
        if ($this->gallery_images) {
            foreach ($this->gallery_images as $image) {
                $images[] = url('images/' . $image);
            }
        }
        
        if ($this->images) {
            foreach ($this->images as $image) {
                $images[] = url('images/' . $image);
            }
        }
        
        return array_unique($images);
    }

    /**
     * Fornecedor do produto
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Movimentações de estoque
     */
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Verificar se está sem estoque
     */
    public function isOutOfStock(): bool
    {
        if (!$this->track_stock) {
            return false;
        }
        
        return $this->stock_quantity <= 0;
    }

    /**
     * Adicionar estoque
     */
    public function addStock(int $quantity, string $type = 'entrada', ?float $unitCost = null, ?string $reference = null, ?string $notes = null): void
    {
        $stockBefore = $this->stock_quantity;
        $this->stock_quantity += $quantity;
        $this->save();

        StockMovement::create([
            'product_id' => $this->id,
            'user_id' => auth()->id(),
            'type' => $type,
            'quantity' => $quantity,
            'stock_before' => $stockBefore,
            'stock_after' => $this->stock_quantity,
            'unit_cost' => $unitCost,
            'reference' => $reference,
            'notes' => $notes,
        ]);
    }

    /**
     * Remover estoque
     */
    public function removeStock(int $quantity, string $type = 'saida', ?string $reference = null, ?string $notes = null): void
    {
        $stockBefore = $this->stock_quantity;
        $this->stock_quantity -= $quantity;
        $this->save();

        StockMovement::create([
            'product_id' => $this->id,
            'user_id' => auth()->id(),
            'type' => $type,
            'quantity' => -$quantity,
            'stock_before' => $stockBefore,
            'stock_after' => $this->stock_quantity,
            'reference' => $reference,
            'notes' => $notes,
        ]);
    }

    /**
     * Escopo para produtos com estoque baixo
     */
    public function scopeLowStock($query)
    {
        return $query->where('track_stock', true)
            ->whereNotNull('stock_min')
            ->where('stock_min', '>', 0)
            ->whereColumn('stock_quantity', '<=', 'stock_min')
            ->where('stock_quantity', '>', 0); // Exclui produtos zerados
    }

    /**
     * Escopo para produtos sem estoque
     */
    public function scopeOutOfStock($query)
    {
        return $query->where('track_stock', true)
            ->where('stock_quantity', '<=', 0);
    }
    
    /**
     * Verificar se está com estoque baixo
     */
    public function isLowStock(): bool
    {
        if (!$this->track_stock || !$this->stock_min || $this->stock_min <= 0) {
            return false;
        }
        
        return $this->stock_quantity > 0 && $this->stock_quantity <= $this->stock_min;
    }

    /**
     * Calcular preço com desconto por quantidade
     */
    public function calculateQuantityDiscountPrice(int $quantity): float
    {
        if (!$this->quantity_discount_enabled || !$this->quantity_discount_rules) {
            return $this->final_price;
        }

        $rules = $this->quantity_discount_rules;
        $basePrice = $this->sale_price && $this->sale_price > 0 ? $this->sale_price : $this->price;
        
        // Ordenar regras por quantidade mínima (decrescente)
        usort($rules, function($a, $b) {
            return $b['min_quantity'] <=> $a['min_quantity'];
        });

        // Encontrar a regra aplicável
        foreach ($rules as $rule) {
            if ($quantity >= $rule['min_quantity']) {
                $discountAmount = $basePrice * ($rule['discount_percentage'] / 100);
                return $basePrice - $discountAmount;
            }
        }

        return $basePrice;
    }

    /**
     * Obter regras de desconto por quantidade
     */
    public function getQuantityDiscountRules(): array
    {
        return $this->quantity_discount_rules ?? [];
    }
}