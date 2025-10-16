<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Material;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductExamplesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Busca materiais e categorias necessários
        $acrilicoCristal = Material::where('name', 'Acrílico Cristal')->first();
        $mdf = Material::where('name', 'MDF')->first();
        $metal = Material::where('name', 'Metal - Aço Inox')->first();
        
        $categoriaLetreiros = Category::where('name', 'Letreiros')->first();
        $categoriaTrofeus = Category::where('name', 'Troféus')->first();
        $categoriaPlacas = Category::where('name', 'Placas')->first();

        // Se não existirem as categorias, cria algumas básicas
        if (!$categoriaLetreiros) {
            $categoriaLetreiros = Category::create([
                'name' => 'Letreiros',
                'description' => 'Letreiros em diversos materiais',
                'is_active' => true,
                'sort_order' => 1
            ]);
        }

        if (!$categoriaTrofeus) {
            $categoriaTrofeus = Category::create([
                'name' => 'Troféus',
                'description' => 'Troféus personalizados',
                'is_active' => true,
                'sort_order' => 2
            ]);
        }

        if (!$categoriaPlacas) {
            $categoriaPlacas = Category::create([
                'name' => 'Placas',
                'description' => 'Placas de identificação e sinalização',
                'is_active' => true,
                'sort_order' => 3
            ]);
        }

        // Exemplos de produtos dinâmicos
        $produtos = [
            [
                'product_data' => [
                    'category_id' => $categoriaLetreiros->id,
                    'material_id' => $acrilicoCristal?->id,
                    'name' => 'Letreiro em Acrílico Cristal',
                    'description' => 'Letreiro personalizado em acrílico cristal com corte a laser de alta precisão',
                    'short_description' => 'Letreiro acrílico cristal personalizado',
                    'price' => 150.00, // Preço base
                    'calculation_type' => 'area_based',
                    'auto_calculate_price' => true,
                    'labor_cost' => 25.00,
                    'margin_percentage' => 40.0,
                    'min_price' => 50.00,
                    'is_active' => true,
                    'is_featured' => true,
                    'manufacturing_processes' => [],
                    'custom_attributes' => [
                        'permite_personalizacao' => true,
                        'tipos_acabamento' => ['polido', 'fosco'],
                        'cores_disponiveis' => ['cristal', 'fumê'],
                    ],
                    'sort_order' => 1,
                ],
                'thickness_prices' => [
                    3 => ['price_per_m2' => 85.00, 'minimum_area' => 0.01, 'setup_cost' => 15.00],
                    5 => ['price_per_m2' => 110.00, 'minimum_area' => 0.01, 'setup_cost' => 15.00],
                    8 => ['price_per_m2' => 145.00, 'minimum_area' => 0.01, 'setup_cost' => 20.00],
                    10 => ['price_per_m2' => 180.00, 'minimum_area' => 0.01, 'setup_cost' => 25.00],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $categoriaTrofeus->id,
                    'material_id' => $acrilicoCristal?->id,
                    'name' => 'Troféu em Acrílico',
                    'description' => 'Troféu personalizado em acrílico cristal com gravação a laser',
                    'short_description' => 'Troféu acrílico personalizado',
                    'price' => 85.00,
                    'calculation_type' => 'unit_based',
                    'auto_calculate_price' => true,
                    'labor_cost' => 15.00,
                    'margin_percentage' => 50.0,
                    'min_price' => 40.00,
                    'is_active' => true,
                    'is_featured' => true,
                    'width' => 15.0, // cm
                    'height' => 20.0, // cm
                    'custom_attributes' => [
                        'permite_gravacao' => true,
                        'formatos_disponiveis' => ['retangular', 'oval', 'personalizado'],
                        'base_inclusa' => true,
                    ],
                    'sort_order' => 2,
                ],
                'thickness_prices' => [
                    5 => ['price_per_m2' => 120.00, 'minimum_area' => 0.03, 'setup_cost' => 20.00],
                    8 => ['price_per_m2' => 155.00, 'minimum_area' => 0.03, 'setup_cost' => 25.00],
                    10 => ['price_per_m2' => 190.00, 'minimum_area' => 0.03, 'setup_cost' => 30.00],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $categoriaPlacas->id,
                    'material_id' => $metal?->id,
                    'name' => 'Placa em Aço Inox',
                    'description' => 'Placa de identificação em aço inoxidável com gravação personalizada',
                    'short_description' => 'Placa aço inox gravada',
                    'price' => 120.00,
                    'calculation_type' => 'area_based',
                    'auto_calculate_price' => true,
                    'labor_cost' => 20.00,
                    'margin_percentage' => 35.0,
                    'min_price' => 60.00,
                    'is_active' => true,
                    'is_featured' => false,
                    'custom_attributes' => [
                        'acabamento_borda' => ['escovado', 'polido'],
                        'fixacao' => ['parafusos', 'adesivo', 'imã'],
                        'resistente_intemperies' => true,
                    ],
                    'sort_order' => 3,
                ],
                'thickness_prices' => [
                    0.5 => ['price_per_m2' => 280.00, 'minimum_area' => 0.005, 'setup_cost' => 25.00],
                    0.8 => ['price_per_m2' => 320.00, 'minimum_area' => 0.005, 'setup_cost' => 30.00],
                    1.0 => ['price_per_m2' => 380.00, 'minimum_area' => 0.005, 'setup_cost' => 35.00],
                    1.5 => ['price_per_m2' => 450.00, 'minimum_area' => 0.005, 'setup_cost' => 40.00],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $categoriaLetreiros->id,
                    'material_id' => $mdf?->id,
                    'name' => 'Base em MDF para Letreiro',
                    'description' => 'Base estrutural em MDF para montagem de letreiros e displays',
                    'short_description' => 'Base MDF para letreiro',
                    'price' => 45.00,
                    'calculation_type' => 'area_based',
                    'auto_calculate_price' => true,
                    'labor_cost' => 12.00,
                    'margin_percentage' => 45.0,
                    'min_price' => 25.00,
                    'is_active' => true,
                    'is_featured' => false,
                    'custom_attributes' => [
                        'acabamento' => ['cru', 'selado', 'pintado'],
                        'formato_corte' => ['reto', 'curvo', 'personalizado'],
                        'furacoes_inclusas' => true,
                    ],
                    'sort_order' => 4,
                ],
                'thickness_prices' => [
                    6 => ['price_per_m2' => 45.00, 'minimum_area' => 0.02, 'setup_cost' => 10.00],
                    9 => ['price_per_m2' => 55.00, 'minimum_area' => 0.02, 'setup_cost' => 12.00],
                    12 => ['price_per_m2' => 68.00, 'minimum_area' => 0.02, 'setup_cost' => 15.00],
                    15 => ['price_per_m2' => 80.00, 'minimum_area' => 0.02, 'setup_cost' => 18.00],
                    18 => ['price_per_m2' => 95.00, 'minimum_area' => 0.02, 'setup_cost' => 20.00],
                ],
            ],
        ];

        foreach ($produtos as $produtoData) {
            // Cria o produto
            $produto = Product::create($produtoData['product_data']);
            
            // Configura os preços por espessura
            $produto->syncThicknessPrices($produtoData['thickness_prices']);
            
            echo "Produto criado: {$produto->name} com " . count($produtoData['thickness_prices']) . " espessuras configuradas\n";
        }
    }
}