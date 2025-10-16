<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CustomizableProduct;
use App\Models\Material;

class CustomizableProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Busca materiais existentes
        $materials = Material::active()->get();
        $materialIds = $materials->pluck('id')->toArray();

        $products = [
            [
                'name' => 'Placa Personalizada',
                'slug' => 'placa-personalizada',
                'description' => 'Placa personalizada com material, dimensões e acabamento sob medida. Ideal para identificação, sinalização ou decoração.',
                'short_description' => 'Placa personalizada com total flexibilidade de configuração',
                'meta_title' => 'Placa Personalizada - Comunicação Visual',
                'meta_description' => 'Crie placas personalizadas com materiais de qualidade, dimensões sob medida e acabamentos profissionais.',
                'meta_keywords' => json_encode(['placa', 'personalizada', 'comunicação visual', 'sinalização']),
                'base_price' => 25.00,
                'sku' => 'PLACA-PERS-001',
                'is_active' => true,
                'is_featured' => true,
                'calculation_type' => 'area',
                'margin_percentage' => 35.00,
                'min_price' => 15.00,
                'max_price' => 500.00,
                'available_materials' => json_encode($materialIds),
                'available_finishes' => json_encode(['fosco', 'brilho', 'polido', 'texturizado', 'laminado']),
                'available_extras' => json_encode(['ilhós', 'corte_laser', 'impressao_uv', 'laminação', 'iluminação', 'base_metalica']),
                'text_customization' => json_encode([
                    'enabled' => true,
                    'max_length' => 500,
                    'allowed_characters' => 'all'
                ]),
                'font_options' => json_encode(['Arial', 'Times New Roman', 'Calibri', 'custom']),
                'color_options' => json_encode(['preto', 'branco', 'azul', 'vermelho', 'verde', 'personalizado']),
                'adhesive_printing' => json_encode([
                    'enabled' => true,
                    'types' => ['vinil', 'papel', 'transparente', 'reflexivo'],
                    'price_per_m2' => 25.00
                ]),
                'base_support_options' => json_encode(['base_acrilica', 'base_metalica', 'base_madeira', 'suporte_parede', 'suporte_balcao'])
            ],
            [
                'name' => 'Letreiro Personalizado',
                'slug' => 'letreiro-personalizado',
                'description' => 'Letreiro personalizado com design único, materiais de alta qualidade e acabamentos profissionais. Perfeito para estabelecimentos comerciais.',
                'short_description' => 'Letreiro personalizado para seu negócio',
                'meta_title' => 'Letreiro Personalizado - Comunicação Visual',
                'meta_description' => 'Letreiros personalizados com materiais premium, iluminação LED e acabamentos de alta qualidade.',
                'meta_keywords' => json_encode(['letreiro', 'personalizado', 'comercial', 'iluminação']),
                'base_price' => 80.00,
                'sku' => 'LETREIRO-PERS-001',
                'is_active' => true,
                'is_featured' => true,
                'calculation_type' => 'area',
                'margin_percentage' => 40.00,
                'min_price' => 50.00,
                'max_price' => 2000.00,
                'available_materials' => json_encode($materialIds),
                'available_finishes' => json_encode(['fosco', 'brilho', 'polido', 'texturizado']),
                'available_extras' => json_encode(['corte_laser', 'impressao_uv', 'iluminação', 'base_metalica', 'instalacao']),
                'text_customization' => json_encode([
                    'enabled' => true,
                    'max_length' => 1000,
                    'allowed_characters' => 'all'
                ]),
                'font_options' => json_encode(['Arial', 'Times New Roman', 'Calibri', 'custom']),
                'color_options' => json_encode(['preto', 'branco', 'azul', 'vermelho', 'verde', 'dourado', 'prateado', 'personalizado']),
                'adhesive_printing' => json_encode([
                    'enabled' => true,
                    'types' => ['vinil', 'transparente', 'reflexivo'],
                    'price_per_m2' => 30.00
                ]),
                'base_support_options' => json_encode(['base_metalica', 'suporte_parede', 'suporte_balcao'])
            ],
            [
                'name' => 'Troféu Personalizado',
                'slug' => 'troféu-personalizado',
                'description' => 'Troféu personalizado com design exclusivo, materiais nobres e acabamentos de alta qualidade. Ideal para premiações e eventos.',
                'short_description' => 'Troféu personalizado para premiações',
                'meta_title' => 'Troféu Personalizado - Premiações',
                'meta_description' => 'Troféus personalizados com materiais nobres, gravação a laser e acabamentos de alta qualidade.',
                'meta_keywords' => json_encode(['troféu', 'personalizado', 'premiação', 'evento']),
                'base_price' => 45.00,
                'sku' => 'TROFEU-PERS-001',
                'is_active' => true,
                'is_featured' => false,
                'calculation_type' => 'unit',
                'margin_percentage' => 50.00,
                'min_price' => 30.00,
                'max_price' => 300.00,
                'available_materials' => json_encode($materialIds),
                'available_finishes' => json_encode(['fosco', 'brilho', 'polido']),
                'available_extras' => json_encode(['corte_laser', 'gravação', 'base_metalica', 'placa_identificação']),
                'text_customization' => json_encode([
                    'enabled' => true,
                    'max_length' => 200,
                    'allowed_characters' => 'all'
                ]),
                'font_options' => json_encode(['Arial', 'Times New Roman', 'Calibri', 'custom']),
                'color_options' => json_encode(['dourado', 'prateado', 'bronze', 'preto', 'personalizado']),
                'adhesive_printing' => json_encode([
                    'enabled' => false
                ]),
                'base_support_options' => json_encode(['base_metalica', 'base_madeira'])
            ]
        ];

        foreach ($products as $productData) {
            CustomizableProduct::create($productData);
        }
    }
}