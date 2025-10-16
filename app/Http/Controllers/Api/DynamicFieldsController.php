<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\DynamicFieldRendererService;
use App\Services\FormulaCalculationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class DynamicFieldsController extends Controller
{
    public function __construct(
        private DynamicFieldRendererService $fieldRenderer,
        private FormulaCalculationService $formulaCalculator
    ) {}

    /**
     * Obter configuração de campos para um produto
     */
    public function getFieldsConfig(Product $product): JsonResponse
    {
        try {
            $config = $this->fieldRenderer->getFieldsConfig($product);
            
            return response()->json([
                'success' => true,
                'data' => $config
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validar campos dinamicamente
     */
    public function validateFields(Request $request, Product $product): JsonResponse
    {
        try {
            $fieldValues = $request->input('field_values', []);
            $config = $this->fieldRenderer->getFieldsConfig($product);
            
            $allFields = array_merge(
                $config['extra_fields'] ?? [],
                $config['formula_fields'] ?? []
            );
            
            $errors = $this->fieldRenderer->validateFields($fieldValues, $allFields);
            
            return response()->json([
                'success' => true,
                'valid' => empty($errors),
                'errors' => $errors
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calcular preço com campos dinâmicos
     */
    public function calculatePrice(Request $request, Product $product): JsonResponse
    {
        try {
            $fieldValues = $request->input('field_values', []);
            $context = $request->input('context', []);
            
            $config = $this->fieldRenderer->getFieldsConfig($product);
            $totalPrice = 0.0;
            $breakdown = [];
            
            // Calcular preços dos campos extras
            foreach ($config['extra_fields'] as $fieldConfig) {
                $fieldSlug = $fieldConfig['slug'];
                $value = $fieldValues[$fieldSlug] ?? null;
                
                if (empty($value)) continue;
                
                $fieldPrice = $this->calculateFieldPrice($fieldConfig, $value, $context);
                $totalPrice += $fieldPrice;
                
                if ($fieldPrice > 0) {
                    $breakdown[] = [
                        'field' => $fieldConfig['name'],
                        'value' => $value,
                        'price' => $fieldPrice
                    ];
                }
            }
            
            // Calcular preços dos campos de fórmula
            foreach ($config['formula_fields'] as $fieldConfig) {
                $fieldSlug = $fieldConfig['slug'];
                $value = $fieldValues[$fieldSlug] ?? null;
                
                if (empty($value)) continue;
                
                $formulaPrice = $this->calculateFormulaPrice($fieldConfig, $value, $context);
                $totalPrice += $formulaPrice;
                
                if ($formulaPrice > 0) {
                    $breakdown[] = [
                        'field' => $fieldConfig['name'],
                        'value' => $value,
                        'price' => $formulaPrice
                    ];
                }
            }
            
            return response()->json([
                'success' => true,
                'total_price' => $totalPrice,
                'breakdown' => $breakdown
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calcular preço de um campo específico
     */
    private function calculateFieldPrice(array $fieldConfig, $value, array $context): float
    {
        $field = $fieldConfig['field'];
        $options = $fieldConfig['options'] ?? [];
        
        // Encontrar a opção selecionada
        $selectedOption = collect($options)->firstWhere('value', $value);
        
        if (!$selectedOption) {
            return 0.0;
        }
        
        $basePrice = $context['base_price'] ?? 0;
        $quantity = $context['quantity'] ?? 1;
        $area = $context['area'] ?? 1;
        
        switch ($selectedOption['price_type']) {
            case 'fixed':
                return (float) $selectedOption['price'];
                
            case 'percentage':
                return $basePrice * ($selectedOption['price'] / 100);
                
            case 'per_unit':
                return $selectedOption['price'] * $quantity;
                
            case 'per_area':
                return $selectedOption['price'] * $area;
                
            default:
                return (float) $selectedOption['price'];
        }
    }

    /**
     * Calcular preço de um campo de fórmula
     */
    private function calculateFormulaPrice(array $fieldConfig, $value, array $context): float
    {
        $field = $fieldConfig['field'];
        $formula = $fieldConfig['formula'] ?? '';
        
        if (empty($formula)) {
            return 0.0;
        }
        
        // Preparar variáveis para a fórmula
        $variables = array_merge($context, [
            'field_value' => $value,
            'quantity' => $context['quantity'] ?? 1,
            'area' => $context['area'] ?? 1,
        ]);
        
        try {
            return $this->formulaCalculator->calculatePrice($field, $variables);
        } catch (\Exception $e) {
            \Log::error('Erro ao calcular fórmula: ' . $e->getMessage());
            return 0.0;
        }
    }
}
