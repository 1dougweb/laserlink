<?php

namespace Database\Seeders;

use App\Models\ProductPricingConfiguration;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductPricingConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Configuração baseada na planilha Laser Link 2025
        ProductPricingConfiguration::create([
            'name' => 'Laser Link 2025 - Configuração Padrão',
            'slug' => 'laser-link-2025-padrao',
            'description' => 'Configuração de preços baseada na planilha Laser Link 2025 1.1',
            'base_margin' => 15.0, // Margem base de 15%
            'min_price' => 10.00, // Preço mínimo R$ 10,00
            'max_price' => 5000.00, // Preço máximo R$ 5.000,00
            'is_active' => true,
            'is_default' => true,
            'pricing_rules' => [
                'base_margin' => 15.0,
                'volume_discounts' => [
                    '1-10' => 0, // 0% desconto para 1-10 unidades
                    '11-50' => 5, // 5% desconto para 11-50 unidades
                    '51-100' => 10, // 10% desconto para 51-100 unidades
                    '100+' => 15, // 15% desconto para 100+ unidades
                ],
                'seasonal_adjustments' => [
                    'high_season' => 10, // +10% na alta temporada
                    'low_season' => -5, // -5% na baixa temporada
                ]
            ],
            'categories_config' => [
                // Será preenchido dinamicamente baseado nas categorias existentes
            ],
            'product_types_config' => [
                // Será preenchido dinamicamente baseado nos tipos de produtos existentes
            ],
            'sort_order' => 1,
        ]);

        // Configuração para produtos premium
        ProductPricingConfiguration::create([
            'name' => 'Laser Link 2025 - Premium',
            'slug' => 'laser-link-2025-premium',
            'description' => 'Configuração premium com margens maiores para produtos de alta qualidade',
            'base_margin' => 25.0, // Margem base de 25%
            'min_price' => 50.00, // Preço mínimo R$ 50,00
            'max_price' => 10000.00, // Preço máximo R$ 10.000,00
            'is_active' => true,
            'is_default' => false,
            'pricing_rules' => [
                'base_margin' => 25.0,
                'premium_bonus' => 10, // Bônus de 10% para produtos premium
                'volume_discounts' => [
                    '1-5' => 0,
                    '6-20' => 3,
                    '21-50' => 8,
                    '50+' => 12,
                ]
            ],
            'categories_config' => [],
            'product_types_config' => [],
            'sort_order' => 2,
        ]);

        // Configuração para produtos em promoção
        ProductPricingConfiguration::create([
            'name' => 'Laser Link 2025 - Promocional',
            'slug' => 'laser-link-2025-promocional',
            'description' => 'Configuração promocional com margens reduzidas para liquidação',
            'base_margin' => 5.0, // Margem base de apenas 5%
            'min_price' => 5.00, // Preço mínimo R$ 5,00
            'max_price' => 2000.00, // Preço máximo R$ 2.000,00
            'is_active' => false, // Inativa por padrão
            'is_default' => false,
            'pricing_rules' => [
                'base_margin' => 5.0,
                'promotional_discount' => 20, // Desconto promocional de 20%
                'clearance_mode' => true,
            ],
            'categories_config' => [],
            'product_types_config' => [],
            'sort_order' => 3,
        ]);
    }
}
