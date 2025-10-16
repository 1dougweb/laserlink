<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductPricingConfiguration;
use Illuminate\Support\Collection;

class PricingService
{
    /**
     * Calcula o preço de um produto baseado na configuração ativa
     */
    public function calculateProductPrice(Product $product, ?ProductPricingConfiguration $configuration = null): float
    {
        $configuration = $configuration ?? $this->getDefaultConfiguration();
        
        if (!$configuration) {
            return $product->price;
        }

        return $configuration->calculatePrice($product);
    }

    /**
     * Calcula preços para múltiplos produtos
     */
    public function calculateBulkPrices(Collection $products, ?ProductPricingConfiguration $configuration = null): Collection
    {
        $configuration = $configuration ?? $this->getDefaultConfiguration();
        
        return $products->map(function ($product) use ($configuration) {
            return [
                'product' => $product,
                'current_price' => $product->price,
                'calculated_price' => $this->calculateProductPrice($product, $configuration),
                'difference' => $this->calculateProductPrice($product, $configuration) - $product->price,
                'variation_percentage' => $this->calculateVariationPercentage($product->price, $this->calculateProductPrice($product, $configuration)),
            ];
        });
    }

    /**
     * Aplica configuração de preços a todos os produtos ativos
     */
    public function applyConfigurationToProducts(ProductPricingConfiguration $configuration): int
    {
        $products = Product::active()->get();
        $updated = 0;

        foreach ($products as $product) {
            $newPrice = $this->calculateProductPrice($product, $configuration);
            
            if ($newPrice != $product->price) {
                $product->update(['price' => $newPrice]);
                $updated++;
            }
        }

        return $updated;
    }

    /**
     * Simula preços baseados em uma configuração
     */
    public function simulatePricing(ProductPricingConfiguration $configuration): Collection
    {
        $products = Product::active()->with(['category', 'productType'])->get();
        
        return $this->calculateBulkPrices($products, $configuration);
    }

    /**
     * Calcula desconto por volume baseado nas regras
     */
    public function calculateVolumeDiscount(int $quantity, array $volumeRules): float
    {
        foreach ($volumeRules as $range => $discount) {
            if ($this->isQuantityInRange($quantity, $range)) {
                return $discount;
            }
        }

        return 0;
    }

    /**
     * Calcula ajuste sazonal
     */
    public function calculateSeasonalAdjustment(array $seasonalRules, string $season = 'normal'): float
    {
        return $seasonalRules[$season] ?? 0;
    }

    /**
     * Obtém configuração padrão ativa
     */
    public function getDefaultConfiguration(): ?ProductPricingConfiguration
    {
        return ProductPricingConfiguration::default()->active()->first();
    }

    /**
     * Obtém todas as configurações ativas
     */
    public function getActiveConfigurations(): Collection
    {
        return ProductPricingConfiguration::active()->ordered()->get();
    }

    /**
     * Calcula percentual de variação
     */
    private function calculateVariationPercentage(float $originalPrice, float $newPrice): float
    {
        if ($originalPrice == 0) {
            return 0;
        }

        return (($newPrice - $originalPrice) / $originalPrice) * 100;
    }

    /**
     * Verifica se quantidade está no range especificado
     */
    private function isQuantityInRange(int $quantity, string $range): bool
    {
        if (str_contains($range, '+')) {
            $min = (int) str_replace('+', '', $range);
            return $quantity >= $min;
        }

        if (str_contains($range, '-')) {
            [$min, $max] = explode('-', $range);
            return $quantity >= (int) $min && $quantity <= (int) $max;
        }

        return $quantity == (int) $range;
    }

    /**
     * Gera relatório de preços
     */
    public function generatePricingReport(ProductPricingConfiguration $configuration): array
    {
        $simulation = $this->simulatePricing($configuration);
        
        $totalCurrent = $simulation->sum('current_price');
        $totalCalculated = $simulation->sum('calculated_price');
        $totalDifference = $totalCalculated - $totalCurrent;
        $averageVariation = $totalCurrent > 0 ? (($totalDifference / $totalCurrent) * 100) : 0;

        return [
            'configuration' => $configuration,
            'total_products' => $simulation->count(),
            'total_current_value' => $totalCurrent,
            'total_calculated_value' => $totalCalculated,
            'total_difference' => $totalDifference,
            'average_variation' => $averageVariation,
            'products_with_increase' => $simulation->where('difference', '>', 0)->count(),
            'products_with_decrease' => $simulation->where('difference', '<', 0)->count(),
            'products_unchanged' => $simulation->where('difference', '=', 0)->count(),
            'simulation_data' => $simulation,
        ];
    }
}












