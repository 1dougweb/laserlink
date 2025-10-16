<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class ExtraField extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'type',
        'settings',
        'validation_rules',
        'pricing_rules',
        'conditional_logic',
        'is_required',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'settings' => 'array',
        'validation_rules' => 'array',
        'pricing_rules' => 'array',
        'conditional_logic' => 'array',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($field) {
            if (empty($field->slug)) {
                $field->slug = Str::slug($field->name);
            }
        });
    }

    public function options(): HasMany
    {
        return $this->hasMany(ExtraFieldOption::class)->orderBy('sort_order');
    }

    public function activeOptions(): HasMany
    {
        return $this->hasMany(ExtraFieldOption::class)->where('is_active', true)->orderBy('sort_order');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_extra_fields')
                    ->withPivot(['is_required', 'sort_order', 'field_settings'])
                    ->withTimestamps()
                    ->orderBy('pivot_sort_order');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Obtém opções disponíveis baseadas em dependências
     */
    public function getAvailableOptions(array $selectedValues = []): array
    {
        $options = $this->activeOptions;
        
        // Se há dependências, filtrar opções
        if ($this->conditional_logic && !empty($this->conditional_logic)) {
            foreach ($this->conditional_logic as $dependency) {
                $fieldSlug = $dependency['field'];
                $requiredValue = $dependency['value'];
                
                if (!isset($selectedValues[$fieldSlug]) || $selectedValues[$fieldSlug] !== $requiredValue) {
                    // Filtrar opções que dependem desta condição
                    $options = $options->filter(function ($option) use ($dependency) {
                        if (!isset($option->conditional_logic[$fieldSlug])) {
                            return true;
                        }
                        return $option->conditional_logic[$fieldSlug] === $dependency['value'];
                    });
                }
            }
        }
        
        return $options->toArray();
    }

    /**
     * Calcula preço baseado na seleção
     */
    public function calculatePrice(string $selectedValue, array $context = []): float
    {
        $option = $this->activeOptions()->where('value', $selectedValue)->first();
        
        if (!$option) {
            return 0.0;
        }

        $basePrice = $context['base_price'] ?? 0;
        $quantity = $context['quantity'] ?? 1;
        $area = $context['area'] ?? 1;

        switch ($option->price_type) {
            case 'fixed':
                return $option->price;
                
            case 'percentage':
                return $basePrice * ($option->price / 100);
                
            case 'per_unit':
                return $option->price * $quantity;
                
            case 'per_area':
                return $option->price * $area;
                
            default:
                return $option->price;
        }
    }

    /**
     * Valida valor selecionado
     */
    public function validateValue(string $value): array
    {
        $errors = [];
        
        // Verificar se é obrigatório
        if ($this->is_required && empty($value)) {
            $errors[] = "O campo {$this->name} é obrigatório.";
            return $errors;
        }
        
        // Verificar se a opção existe
        if (!empty($value) && !$this->activeOptions()->where('value', $value)->exists()) {
            $errors[] = "A opção selecionada para {$this->name} é inválida.";
        }
        
        // Aplicar regras de validação customizadas
        if ($this->validation_rules) {
            foreach ($this->validation_rules as $rule => $params) {
                switch ($rule) {
                    case 'min_length':
                        if (strlen($value) < $params) {
                            $errors[] = "O campo {$this->name} deve ter pelo menos {$params} caracteres.";
                        }
                        break;
                    case 'max_length':
                        if (strlen($value) > $params) {
                            $errors[] = "O campo {$this->name} deve ter no máximo {$params} caracteres.";
                        }
                        break;
                }
            }
        }
        
        return $errors;
    }
}
