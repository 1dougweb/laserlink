<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RawMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'name',
        'code',
        'category',
        'unit',
        'stock_quantity',
        'stock_min',
        'stock_max',
        'unit_cost',
        'description',
        'specifications',
        'is_active',
    ];

    protected $casts = [
        'stock_quantity' => 'decimal:3',
        'stock_min' => 'decimal:3',
        'stock_max' => 'decimal:3',
        'unit_cost' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Unidades de medida disponíveis
     */
    public const UNITS = [
        'm2' => 'M²',
        'kg' => 'Kg',
        'l' => 'Litros',
        'ml' => 'Ml',
        'g' => 'Gramas',
        'un' => 'Unidades',
    ];

    /**
     * Categorias de materiais
     */
    public const CATEGORIES = [
        'acrilico' => 'Acrílico',
        'mdf' => 'MDF',
        'ps' => 'PS (Poliestireno)',
        'pet' => 'PET',
        'metal' => 'Metal',
        'tinta' => 'Tintas',
        'adesivo' => 'Adesivos',
        'eletrico' => 'Elétrico',
        'outros' => 'Outros',
    ];

    /**
     * Fornecedor do material
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
        return $this->hasMany(RawMaterialStockMovement::class);
    }

    /**
     * Label da unidade
     */
    public function getUnitLabelAttribute(): string
    {
        return self::UNITS[$this->unit] ?? $this->unit;
    }

    /**
     * Label da categoria
     */
    public function getCategoryLabelAttribute(): string
    {
        return self::CATEGORIES[$this->category] ?? $this->category;
    }

    /**
     * Verificar se está com estoque baixo
     */
    public function isLowStock(): bool
    {
        return $this->stock_quantity <= $this->stock_min;
    }

    /**
     * Verificar se está sem estoque
     */
    public function isOutOfStock(): bool
    {
        return $this->stock_quantity <= 0;
    }

    /**
     * Adicionar estoque
     */
    public function addStock(float $quantity, string $type = 'entrada', ?float $unitCost = null, ?string $reference = null, ?string $notes = null): void
    {
        $stockBefore = $this->stock_quantity;
        $this->stock_quantity += $quantity;
        
        if ($unitCost !== null) {
            $this->unit_cost = $unitCost;
        }
        
        $this->save();

        RawMaterialStockMovement::create([
            'raw_material_id' => $this->id,
            'user_id' => auth()->id(),
            'type' => $type,
            'quantity' => $quantity,
            'stock_before' => $stockBefore,
            'stock_after' => $this->stock_quantity,
            'unit_cost' => $unitCost ?? $this->unit_cost,
            'total_cost' => $quantity * ($unitCost ?? $this->unit_cost),
            'reference' => $reference,
            'notes' => $notes,
        ]);
    }

    /**
     * Remover estoque
     */
    public function removeStock(float $quantity, string $type = 'saida', ?string $reference = null, ?string $notes = null): void
    {
        $stockBefore = $this->stock_quantity;
        $this->stock_quantity -= $quantity;
        $this->save();

        RawMaterialStockMovement::create([
            'raw_material_id' => $this->id,
            'user_id' => auth()->id(),
            'type' => $type,
            'quantity' => -$quantity,
            'stock_before' => $stockBefore,
            'stock_after' => $this->stock_quantity,
            'unit_cost' => $this->unit_cost,
            'total_cost' => -($quantity * $this->unit_cost),
            'reference' => $reference,
            'notes' => $notes,
        ]);
    }

    /**
     * Escopo para materiais com estoque baixo
     */
    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock_quantity', '<=', 'stock_min');
    }

    /**
     * Escopo para materiais sem estoque
     */
    public function scopeOutOfStock($query)
    {
        return $query->where('stock_quantity', '<=', 0);
    }

    /**
     * Escopo para materiais ativos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
