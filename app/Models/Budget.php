<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Budget extends Model
{
    protected $fillable = [
        'budget_number',
        'client_name',
        'client_email',
        'client_phone',
        'client_company',
        'client_address',
        'description',
        'items',
        'subtotal',
        'discount_percentage',
        'discount_amount',
        'tax_percentage',
        'tax_amount',
        'total',
        'status',
        'valid_until',
        'notes',
        'terms',
        'user_id',
    ];

    protected $casts = [
        'items' => 'array',
        'subtotal' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_percentage' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'valid_until' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($budget) {
            if (empty($budget->budget_number)) {
                $budget->budget_number = $budget->generateBudgetNumber();
            }
            if (empty($budget->status)) {
                $budget->status = 'draft';
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'expired');
    }

    public function scopeValid($query)
    {
        return $query->where('valid_until', '>=', now()->toDateString());
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'draft' => 'Rascunho',
            'sent' => 'Enviado',
            'approved' => 'Aprovado',
            'rejected' => 'Rejeitado',
            'expired' => 'Expirado',
            default => 'Desconhecido'
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'draft' => 'gray',
            'sent' => 'blue',
            'approved' => 'green',
            'rejected' => 'red',
            'expired' => 'yellow',
            default => 'gray'
        };
    }

    public function isExpired()
    {
        return $this->valid_until && $this->valid_until < now()->toDateString();
    }

    public function calculateTotals()
    {
        $subtotal = 0;
        
        foreach ($this->items as $item) {
            $subtotal += $item['quantity'] * $item['unit_price'];
        }
        
        $this->subtotal = $subtotal;
        
        // Calcular desconto
        if ($this->discount_percentage > 0) {
            $this->discount_amount = $subtotal * ($this->discount_percentage / 100);
        }
        
        $afterDiscount = $subtotal - ($this->discount_amount ?? 0);
        
        // Calcular impostos
        if ($this->tax_percentage > 0) {
            $this->tax_amount = $afterDiscount * ($this->tax_percentage / 100);
        }
        
        $this->total = $afterDiscount + ($this->tax_amount ?? 0);
        
        return $this;
    }

    public function addItem($productId, $productName, $quantity, $unitPrice, $description = null)
    {
        $items = $this->items ?? [];
        
        $items[] = [
            'product_id' => $productId,
            'product_name' => $productName,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'description' => $description,
            'total' => $quantity * $unitPrice,
        ];
        
        $this->items = $items;
        $this->calculateTotals();
        
        return $this;
    }

    public function removeItem($index)
    {
        $items = $this->items ?? [];
        unset($items[$index]);
        $this->items = array_values($items);
        $this->calculateTotals();
        
        return $this;
    }

    public function updateItem($index, $data)
    {
        $items = $this->items ?? [];
        
        if (isset($items[$index])) {
            $items[$index] = array_merge($items[$index], $data);
            $items[$index]['total'] = $items[$index]['quantity'] * $items[$index]['unit_price'];
            $this->items = $items;
            $this->calculateTotals();
        }
        
        return $this;
    }

    private function generateBudgetNumber()
    {
        $year = now()->year;
        $prefix = "ORC-{$year}-";
        
        $lastBudget = static::where('budget_number', 'like', $prefix . '%')
            ->orderBy('budget_number', 'desc')
            ->first();
        
        if ($lastBudget) {
            $lastNumber = (int) str_replace($prefix, '', $lastBudget->budget_number);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    public function markAsSent()
    {
        $this->update(['status' => 'sent']);
    }

    public function markAsApproved()
    {
        $this->update(['status' => 'approved']);
    }

    public function markAsRejected()
    {
        $this->update(['status' => 'rejected']);
    }

    public function markAsExpired()
    {
        $this->update(['status' => 'expired']);
    }

    /**
     * Ensure all items have required fields
     */
    public function ensureItemsIntegrity()
    {
        if (!$this->items || !is_array($this->items)) {
            $this->update(['items' => []]);
            return;
        }

        $items = $this->items;
        $updated = false;
        
        foreach ($items as $index => $item) {
            // Ensure item is an array
            if (!is_array($item)) {
                $items[$index] = [
                    'product_id' => null,
                    'product_name' => 'Produto não especificado',
                    'quantity' => 0,
                    'unit_price' => 0,
                    'total' => 0,
                    'description' => null
                ];
                $updated = true;
                continue;
            }
            
            if (!isset($item['product_name']) || empty($item['product_name'])) {
                $items[$index]['product_name'] = 'Produto não especificado';
                $updated = true;
            }
            
            if (!isset($item['quantity']) || !is_numeric($item['quantity'])) {
                $items[$index]['quantity'] = 0;
                $updated = true;
            }
            
            if (!isset($item['unit_price']) || !is_numeric($item['unit_price'])) {
                $items[$index]['unit_price'] = 0;
                $updated = true;
            }
            
            // Recalculate total
            $quantity = $items[$index]['quantity'] ?? 0;
            $unitPrice = $items[$index]['unit_price'] ?? 0;
            $calculatedTotal = $quantity * $unitPrice;
            
            if (!isset($item['total']) || $item['total'] != $calculatedTotal) {
                $items[$index]['total'] = $calculatedTotal;
                $updated = true;
            }
            
            // Ensure product_id exists
            if (!isset($item['product_id'])) {
                $items[$index]['product_id'] = null;
                $updated = true;
            }
            
            // Ensure description exists
            if (!isset($item['description'])) {
                $items[$index]['description'] = null;
                $updated = true;
            }
        }

        if ($updated) {
            $this->update(['items' => $items]);
        }
    }
}
