<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ExtraField;
use App\Models\ExtraFieldOption;

class ExtraFieldService
{
    /**
     * Obter configuração dos campos extras
     */
    public function getConfig(): array
    {
        $fields = ExtraField::active()
            ->ordered()
            ->with(['activeOptions'])
            ->get();

        return [
            'fields' => $fields->map(function ($field) {
                return [
                    'id' => $field->id,
                    'name' => $field->name,
                    'slug' => $field->slug,
                    'type' => $field->type,
                    'description' => $field->description,
                    'is_required' => $field->is_required,
                    'settings' => $field->settings,
                    'validation_rules' => $field->validation_rules,
                    'options' => $field->activeOptions->map(function ($option) {
                        return [
                            'value' => $option->value,
                            'label' => $option->label,
                            'price' => $option->price,
                            'price_type' => $option->price_type,
                            'description' => $option->description,
                        ];
                    })
                ];
            })
        ];
    }

    /**
     * Obter opções de um campo específico
     */
    public function getFieldOptions(ExtraField $field): array
    {
        return $field->activeOptions->map(function ($option) {
            return [
                'value' => $option->value,
                'label' => $option->label,
                'price' => $option->price,
                'price_type' => $option->price_type,
                'description' => $option->description,
            ];
        })->toArray();
    }

    /**
     * Calcular preço baseado nas seleções
     */
    public function calculatePrice(array $selections, array $context = []): float
    {
        $totalPrice = 0.0;
        
        foreach ($selections as $fieldSlug => $selectedValue) {
            $field = ExtraField::where('slug', $fieldSlug)->first();
            
            if (!$field || empty($selectedValue)) {
                continue;
            }
            
            $fieldPrice = $field->calculatePrice($selectedValue, $context);
            $totalPrice += $fieldPrice;
        }
        
        return $totalPrice;
    }

    /**
     * Obter breakdown do preço
     */
    public function getPriceBreakdown(array $selections, array $context = []): array
    {
        $breakdown = [];
        
        foreach ($selections as $fieldSlug => $selectedValue) {
            $field = ExtraField::where('slug', $fieldSlug)->first();
            
            if (!$field || empty($selectedValue)) {
                continue;
            }
            
            $fieldPrice = $field->calculatePrice($selectedValue, $context);
            
            if ($fieldPrice > 0) {
                $breakdown[] = [
                    'field' => $field->name,
                    'value' => $selectedValue,
                    'price' => $fieldPrice
                ];
            }
        }
        
        return $breakdown;
    }

    /**
     * Validar seleções
     */
    public function validateSelections(array $selections): array
    {
        $errors = [];
        
        foreach ($selections as $fieldSlug => $selectedValue) {
            $field = ExtraField::where('slug', $fieldSlug)->first();
            
            if (!$field) {
                continue;
            }
            
            $fieldErrors = $field->validateValue($selectedValue);
            $errors = array_merge($errors, $fieldErrors);
        }
        
        return $errors;
    }
}
