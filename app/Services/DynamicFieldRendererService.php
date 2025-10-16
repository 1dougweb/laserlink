<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ExtraField;
use App\Models\FormulaField;
use App\Models\Product;
use Illuminate\Support\Collection;

class DynamicFieldRendererService
{
    /**
     * Renderizar campos extras dinamicamente
     */
    public function renderExtraFields(Product $product, array $context = []): array
    {
        $fields = $product->activeExtraFields()
            ->with(['activeOptions'])
            ->orderBy('pivot_sort_order')
            ->get();

        $renderedFields = [];

        foreach ($fields as $field) {
            $renderedFields[] = [
                'field' => $field,
                'type' => $field->type,
                'name' => $field->name,
                'slug' => $field->slug,
                'description' => $field->description,
                'is_required' => $field->pivot->is_required ?? false,
                'settings' => $field->settings ?? [],
                'options' => $field->activeOptions->map(function ($option) {
                    return [
                        'value' => $option->value,
                        'label' => $option->label,
                        'price' => $option->price,
                        'price_type' => $option->price_type,
                        'description' => $option->description,
                    ];
                })->toArray(),
                'conditional_logic' => $field->conditional_logic ?? [],
                'validation_rules' => $field->validation_rules ?? [],
            ];
        }

        return $renderedFields;
    }

    /**
     * Renderizar campos de fórmula dinamicamente
     */
    public function renderFormulaFields(Product $product, array $context = []): array
    {
        $fields = $product->activeFormulaFields()
            ->with(['activeOptions'])
            ->orderBy('pivot_sort_order')
            ->get();

        $renderedFields = [];

        foreach ($fields as $field) {
            $renderedFields[] = [
                'field' => $field,
                'type' => $field->type ?? 'text',
                'name' => $field->name,
                'slug' => $field->slug,
                'description' => $field->description,
                'formula' => $field->formula,
                'variables' => $field->variables ?? [],
                'is_required' => $field->pivot->is_required ?? false,
                'settings' => $field->settings ?? [],
                'options' => $field->activeOptions->map(function ($option) {
                    return [
                        'value' => $option->value,
                        'label' => $option->label,
                        'price' => $option->price,
                        'price_type' => $option->price_type,
                        'description' => $option->description,
                    ];
                })->toArray(),
            ];
        }

        return $renderedFields;
    }

    /**
     * Renderizar todos os campos dinamicamente
     */
    public function renderAllFields(Product $product, array $context = []): array
    {
        return [
            'extra_fields' => $this->renderExtraFields($product, $context),
            'formula_fields' => $this->renderFormulaFields($product, $context),
        ];
    }

    /**
     * Obter configuração de campos para JavaScript
     */
    public function getFieldsConfig(Product $product): array
    {
        $extraFields = $this->renderExtraFields($product);
        $formulaFields = $this->renderFormulaFields($product);

        return [
            'extra_fields' => $extraFields,
            'formula_fields' => $formulaFields,
            'total_fields' => count($extraFields) + count($formulaFields),
        ];
    }

    /**
     * Validar campos baseado em regras condicionais
     */
    public function validateFields(array $fieldValues, array $fieldConfigs): array
    {
        $errors = [];

        foreach ($fieldConfigs as $fieldConfig) {
            $fieldSlug = $fieldConfig['slug'];
            $value = $fieldValues[$fieldSlug] ?? null;

            // Validar campo obrigatório
            if ($fieldConfig['is_required'] && empty($value)) {
                $errors[] = "O campo {$fieldConfig['name']} é obrigatório.";
                continue;
            }

            // Validar regras condicionais
            if (!empty($fieldConfig['conditional_logic'])) {
                $conditionalErrors = $this->validateConditionalLogic($value, $fieldConfig['conditional_logic'], $fieldValues);
                $errors = array_merge($errors, $conditionalErrors);
            }

            // Validar regras de validação
            if (!empty($fieldConfig['validation_rules'])) {
                $validationErrors = $this->validateFieldRules($value, $fieldConfig['validation_rules'], $fieldConfig['name']);
                $errors = array_merge($errors, $validationErrors);
            }
        }

        return $errors;
    }

    /**
     * Validar lógica condicional
     */
    private function validateConditionalLogic($value, array $conditionalLogic, array $allValues): array
    {
        $errors = [];

        foreach ($conditionalLogic as $condition) {
            $fieldSlug = $condition['field'] ?? null;
            $requiredValue = $condition['value'] ?? null;
            $operator = $condition['operator'] ?? '==';

            if (!$fieldSlug || !isset($allValues[$fieldSlug])) {
                continue;
            }

            $fieldValue = $allValues[$fieldSlug];
            $isValid = false;

            switch ($operator) {
                case '==':
                    $isValid = $fieldValue == $requiredValue;
                    break;
                case '!=':
                    $isValid = $fieldValue != $requiredValue;
                    break;
                case '>':
                    $isValid = $fieldValue > $requiredValue;
                    break;
                case '<':
                    $isValid = $fieldValue < $requiredValue;
                    break;
                case '>=':
                    $isValid = $fieldValue >= $requiredValue;
                    break;
                case '<=':
                    $isValid = $fieldValue <= $requiredValue;
                    break;
            }

            if (!$isValid) {
                $errors[] = "Condição não atendida para o campo {$fieldSlug}.";
            }
        }

        return $errors;
    }

    /**
     * Validar regras de campo
     */
    private function validateFieldRules($value, array $rules, string $fieldName): array
    {
        $errors = [];

        foreach ($rules as $rule => $params) {
            switch ($rule) {
                case 'min_length':
                    if (strlen($value) < $params) {
                        $errors[] = "O campo {$fieldName} deve ter pelo menos {$params} caracteres.";
                    }
                    break;
                case 'max_length':
                    if (strlen($value) > $params) {
                        $errors[] = "O campo {$fieldName} deve ter no máximo {$params} caracteres.";
                    }
                    break;
                case 'min':
                    if (is_numeric($value) && $value < $params) {
                        $errors[] = "O campo {$fieldName} deve ser maior ou igual a {$params}.";
                    }
                    break;
                case 'max':
                    if (is_numeric($value) && $value > $params) {
                        $errors[] = "O campo {$fieldName} deve ser menor ou igual a {$params}.";
                    }
                    break;
                case 'pattern':
                    if (!preg_match($params, $value)) {
                        $errors[] = "O campo {$fieldName} não está no formato correto.";
                    }
                    break;
            }
        }

        return $errors;
    }
}
