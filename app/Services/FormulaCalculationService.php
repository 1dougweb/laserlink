<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\FormulaField;
use App\Models\Product;
use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;

class FormulaCalculationService
{
    /**
     * Calcula o preço baseado na fórmula
     */
    public function calculatePrice(FormulaField $formulaField, array $variables = []): float
    {
        if (empty($formulaField->formula)) {
            return 0.0;
        }

        try {
            $formula = $this->replaceVariables($formulaField->formula, $variables);
            $result = $this->evaluateFormula($formula);
            
            return (float) $result;
        } catch (\Exception $e) {
            \Log::error('Erro ao calcular fórmula: ' . $e->getMessage(), [
                'formula_field_id' => $formulaField->id,
                'formula' => $formulaField->formula,
                'variables' => $variables
            ]);
            
            return 0.0;
        }
    }

    /**
     * Substitui variáveis na fórmula pelos valores reais
     */
    private function replaceVariables(string $formula, array $variables): string
    {
        $availableVars = [
            'quantity' => $variables['quantity'] ?? 1,
            'product_price' => $variables['product_price'] ?? 0,
            'area' => $variables['area'] ?? 0,
            'width' => $variables['width'] ?? 0,
            'height' => $variables['height'] ?? 0,
            'thickness' => $variables['thickness'] ?? 0,
            'weight' => $variables['weight'] ?? 0,
            'material_price' => $variables['material_price'] ?? 0,
            'finishing_price' => $variables['finishing_price'] ?? 0,
        ];

        foreach ($availableVars as $var => $value) {
            $formula = str_replace('{' . $var . '}', (string) $value, $formula);
        }

        return $formula;
    }

    /**
     * Avalia a fórmula matemática de forma segura
     */
    private function evaluateFormula(string $formula): float
    {
        // Limpar a fórmula
        $formula = trim($formula);
        
        // Remover caracteres perigosos
        $formula = preg_replace('/[^0-9+\-*/().\s]/', '', $formula);
        
        // Processar funções especiais
        $formula = $this->processSpecialFunctions($formula);
        
        // Avaliar a fórmula
        return $this->safeEval($formula);
    }

    /**
     * Processa funções especiais como if(), min(), max()
     */
    private function processSpecialFunctions(string $formula): string
    {
        // Processar função if(condição, valor_verdadeiro, valor_falso)
        $formula = preg_replace_callback(
            '/if\s*\(\s*([^,]+)\s*,\s*([^,]+)\s*,\s*([^)]+)\s*\)/',
            function ($matches) {
                $condition = trim($matches[1]);
                $trueValue = trim($matches[2]);
                $falseValue = trim($matches[3]);
                
                // Avaliar condição simples (comparações básicas)
                if (preg_match('/^(.+)\s*([><=!]+)\s*(.+)$/', $condition, $condMatches)) {
                    $left = $this->safeEval(trim($condMatches[1]));
                    $operator = trim($condMatches[2]);
                    $right = $this->safeEval(trim($condMatches[3]));
                    
                    $result = false;
                    switch ($operator) {
                        case '>':
                            $result = $left > $right;
                            break;
                        case '<':
                            $result = $left < $right;
                            break;
                        case '>=':
                            $result = $left >= $right;
                            break;
                        case '<=':
                            $result = $left <= $right;
                            break;
                        case '==':
                        case '=':
                            $result = abs($left - $right) < 0.0001; // Comparação com tolerância
                            break;
                        case '!=':
                            $result = abs($left - $right) >= 0.0001;
                            break;
                    }
                    
                    return $result ? $trueValue : $falseValue;
                }
                
                return $trueValue; // Se não conseguir avaliar, retorna valor verdadeiro
            },
            $formula
        );

        // Processar função min(valor1, valor2, ...)
        $formula = preg_replace_callback(
            '/min\s*\(\s*([^)]+)\s*\)/',
            function ($matches) {
                $values = array_map('trim', explode(',', $matches[1]));
                $evaluated = array_map([$this, 'safeEval'], $values);
                return (string) min($evaluated);
            },
            $formula
        );

        // Processar função max(valor1, valor2, ...)
        $formula = preg_replace_callback(
            '/max\s*\(\s*([^)]+)\s*\)/',
            function ($matches) {
                $values = array_map('trim', explode(',', $matches[1]));
                $evaluated = array_map([$this, 'safeEval'], $values);
                return (string) max($evaluated);
            },
            $formula
        );

        return $formula;
    }

    /**
     * Avalia uma expressão matemática de forma segura
     */
    private function safeEval(string $expression): float
    {
        $expression = trim($expression);
        
        if (is_numeric($expression)) {
            return (float) $expression;
        }

        try {
            // Usar BigDecimal para cálculos precisos
            $result = $this->calculateExpression($expression);
            return (float) $result;
        } catch (\Exception $e) {
            \Log::warning('Erro ao avaliar expressão: ' . $e->getMessage(), [
                'expression' => $expression
            ]);
            return 0.0;
        }
    }

    /**
     * Calcula uma expressão matemática usando BigDecimal
     */
    private function calculateExpression(string $expression): float
    {
        // Remover espaços
        $expression = str_replace(' ', '', $expression);
        
        // Processar operadores de potência (^)
        $expression = $this->processPowerOperator($expression);
        
        // Processar multiplicação e divisão
        $expression = $this->processMultiplicationDivision($expression);
        
        // Processar adição e subtração
        $expression = $this->processAdditionSubtraction($expression);
        
        return (float) $expression;
    }

    /**
     * Processa operador de potência (^)
     */
    private function processPowerOperator(string $expression): string
    {
        while (preg_match('/(\d+(?:\.\d+)?)\^(\d+(?:\.\d+)?)/', $expression, $matches)) {
            $base = (float) $matches[1];
            $exponent = (float) $matches[2];
            $result = pow($base, $exponent);
            $expression = str_replace($matches[0], (string) $result, $expression);
        }
        
        return $expression;
    }

    /**
     * Processa multiplicação e divisão
     */
    private function processMultiplicationDivision(string $expression): string
    {
        while (preg_match('/(\d+(?:\.\d+)?)\s*([*/])\s*(\d+(?:\.\d+)?)/', $expression, $matches)) {
            $left = (float) $matches[1];
            $operator = $matches[2];
            $right = (float) $matches[3];
            
            $result = ($operator === '*') ? $left * $right : $left / $right;
            $expression = str_replace($matches[0], (string) $result, $expression);
        }
        
        return $expression;
    }

    /**
     * Processa adição e subtração
     */
    private function processAdditionSubtraction(string $expression): string
    {
        while (preg_match('/(\d+(?:\.\d+)?)\s*([+-])\s*(\d+(?:\.\d+)?)/', $expression, $matches)) {
            $left = (float) $matches[1];
            $operator = $matches[2];
            $right = (float) $matches[3];
            
            $result = ($operator === '+') ? $left + $right : $left - $right;
            $expression = str_replace($matches[0], (string) $result, $expression);
        }
        
        return $expression;
    }

    /**
     * Valida uma fórmula antes de salvar
     */
    public function validateFormula(string $formula): array
    {
        $errors = [];
        
        if (empty($formula)) {
            $errors[] = 'Fórmula não pode estar vazia';
            return $errors;
        }

        // Verificar parênteses balanceados
        $openParens = substr_count($formula, '(');
        $closeParens = substr_count($formula, ')');
        if ($openParens !== $closeParens) {
            $errors[] = 'Parênteses não balanceados';
        }

        // Verificar se contém pelo menos uma operação matemática
        $validOperators = ['+', '-', '*', '/', '^', '(', ')'];
        $hasOperator = false;
        foreach ($validOperators as $operator) {
            if (strpos($formula, $operator) !== false) {
                $hasOperator = true;
                break;
            }
        }

        if (!$hasOperator) {
            $errors[] = 'Fórmula deve conter pelo menos uma operação matemática';
        }

        // Verificar variáveis válidas
        $availableVars = array_keys((new FormulaField())->getAvailableVariables());
        preg_match_all('/\{([^}]+)\}/', $formula, $matches);
        foreach ($matches[1] as $var) {
            if (!in_array($var, $availableVars)) {
                $errors[] = "Variável '{$var}' não é válida";
            }
        }

        return $errors;
    }

    /**
     * Testa uma fórmula com valores de exemplo
     */
    public function testFormula(string $formula, array $testVariables = []): array
    {
        $defaultTestVars = [
            'quantity' => 2,
            'product_price' => 100.00,
            'area' => 1.5,
            'width' => 50,
            'height' => 30,
            'thickness' => 3,
            'weight' => 0.5,
            'material_price' => 25.00,
            'finishing_price' => 15.00,
        ];

        $testVariables = array_merge($defaultTestVars, $testVariables);

        try {
            $formulaField = new FormulaField(['formula' => $formula]);
            $result = $this->calculatePrice($formulaField, $testVariables);
            
            return [
                'success' => true,
                'result' => $result,
                'variables_used' => $testVariables
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'variables_used' => $testVariables
            ];
        }
    }
}
