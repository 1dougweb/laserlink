<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_description',
        'product_price',
        'unit_price',
        'quantity',
        'total_price',
        'product_image',
        'customization',
        'extra_cost',
        'base_price',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'product_price' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'extra_cost' => 'decimal:2',
        'base_price' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
