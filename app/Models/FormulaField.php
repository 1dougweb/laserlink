<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FormulaField extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'formula',
        'variables',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($formulaField) {
            if (empty($formulaField->slug)) {
                $formulaField->slug = \Str::slug($formulaField->name);
            }
        });

        static::updating(function ($formulaField) {
            if (empty($formulaField->slug)) {
                $formulaField->slug = \Str::slug($formulaField->name);
            }
        });
    }

    /**
     * Relacionamento com produtos
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_formula_fields')
                    ->withPivot(['is_required', 'sort_order', 'field_settings'])
                    ->withTimestamps()
                    ->orderBy('product_formula_fields.sort_order');
    }

    /**
     * Campos de fórmula ativos
     */
    public function activeProducts()
    {
        return $this->products()->where('is_active', true);
    }

    /**
     * Obtém as variáveis disponíveis para a fórmula
     */
    public function getAvailableVariables(): array
    {
        return [
            'quantity' => 'Quantidade',
            'product_price' => 'Preço do Produto',
            'area' => 'Área (m²)',
            'width' => 'Largura (cm)',
            'height' => 'Altura (cm)',
            'thickness' => 'Espessura (mm)',
            'weight' => 'Peso (kg)',
            'material_price' => 'Preço do Material',
            'finishing_price' => 'Preço do Acabamento',
        ];
    }

    /**
     * Valida se a fórmula é válida
     */
    public function isValidFormula(): bool
    {
        if (empty($this->formula)) {
            return false;
        }

        // Validação básica de sintaxe
        $formula = $this->formula;
        
        // Verificar parênteses balanceados
        $openParens = substr_count($formula, '(');
        $closeParens = substr_count($formula, ')');
        if ($openParens !== $closeParens) {
            return false;
        }

        // Verificar se contém pelo menos uma operação matemática válida
        $validOperators = ['+', '-', '*', '/', '^', '(', ')'];
        $hasOperator = false;
        foreach ($validOperators as $operator) {
            if (strpos($formula, $operator) !== false) {
                $hasOperator = true;
                break;
            }
        }

        return $hasOperator;
    }

    /**
     * Obtém as variáveis usadas na fórmula
     */
    public function getUsedVariables(): array
    {
        if (empty($this->formula)) {
            return [];
        }

        $variables = [];
        $availableVars = array_keys($this->getAvailableVariables());
        
        foreach ($availableVars as $var) {
            if (strpos($this->formula, '{' . $var . '}') !== false) {
                $variables[] = $var;
            }
        }

        return $variables;
    }
}
