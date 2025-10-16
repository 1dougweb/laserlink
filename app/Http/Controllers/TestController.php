<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ProductPricingCalculator;

class TestController extends Controller
{
    public function testPricing()
    {
        $calculator = new ProductPricingCalculator();
        
        // Exemplo: Letreiro de 50cm x 30cm com gravação
        $product = Product::where('name', 'Letreiro Acrílico Personalizado')->first();
        
        if (!$product) {
            return 'Produto não encontrado';
        }
        
        $config = [
            'largura' => 50,
            'altura' => 30,
            'gravação' => true,
            'quantity' => 1
        ];
        
        $result = $calculator->calculatePrice($product, $config);
        
        return response()->json([
            'produto' => $product->name,
            'configuração' => $config,
            'cálculo' => $result
        ]);
    }
}
