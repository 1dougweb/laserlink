<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'company_name',
        'cnpj',
        'email',
        'phone',
        'whatsapp',
        'website',
        'address',
        'city',
        'state',
        'zip_code',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Produtos deste fornecedor
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Matérias-primas deste fornecedor
     */
    public function rawMaterials(): HasMany
    {
        return $this->hasMany(RawMaterial::class);
    }

    /**
     * Escopo para fornecedores ativos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

