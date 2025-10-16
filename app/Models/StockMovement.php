<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'type',
        'quantity',
        'stock_before',
        'stock_after',
        'unit_cost',
        'reference',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'stock_before' => 'integer',
        'stock_after' => 'integer',
        'unit_cost' => 'decimal:2',
    ];

    /**
     * Produto relacionado
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Usuário que fez a movimentação
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Tipos de movimentação
     */
    public const TYPES = [
        'entrada' => 'Entrada',
        'saida' => 'Saída',
        'ajuste' => 'Ajuste',
        'venda' => 'Venda',
        'devolucao' => 'Devolução',
    ];

    /**
     * Obter label do tipo
     */
    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    /**
     * Cor do badge do tipo
     */
    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            'entrada' => 'bg-green-100 text-green-800',
            'saida' => 'bg-red-100 text-red-800',
            'ajuste' => 'bg-yellow-100 text-yellow-800',
            'venda' => 'bg-blue-100 text-blue-800',
            'devolucao' => 'bg-purple-100 text-purple-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }
}

