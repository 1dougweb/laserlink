<?php

namespace Database\Seeders;

use App\Models\ManufacturingProcess;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ManufacturingProcessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Processos de fabricação baseados na planilha Laser Link 2025
        $processes = [
            [
                'name' => 'Corte a Laser',
                'description' => 'Corte de precisão a laser para acrílico, MDF e outros materiais',
                'unit' => 'area', // cobrança por área
                'cost_per_unit' => 25.00, // R$ por m²
                'setup_cost' => 15.00,
                'min_time_minutes' => 10,
                'time_per_unit' => 2.5, // minutos por m²
                'material_compatibility' => [1, 2, 3, 4], // Acrílico Cristal, Colorido, MDF, PET
                'complexity_multipliers' => [
                    'simple' => 1.0,   // formas simples
                    'normal' => 1.3,   // formas normais
                    'complex' => 1.8,  // formas complexas
                    'very_complex' => 2.5, // formas muito complexas
                ],
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Gravação a Laser',
                'description' => 'Gravação superficial ou profunda a laser',
                'unit' => 'area',
                'cost_per_unit' => 35.00,
                'setup_cost' => 20.00,
                'min_time_minutes' => 5,
                'time_per_unit' => 3.0,
                'material_compatibility' => [1, 2, 3, 4, 5], // Todos exceto vidro
                'complexity_multipliers' => [
                    'simple' => 1.0,
                    'normal' => 1.4,
                    'complex' => 2.0,
                    'very_complex' => 3.0,
                ],
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Polimento de Bordas',
                'description' => 'Polimento das bordas cortadas para acabamento premium',
                'unit' => 'linear', // cobrança por metro linear
                'cost_per_unit' => 8.50, // R$ por metro linear
                'setup_cost' => 5.00,
                'min_time_minutes' => 15,
                'time_per_unit' => 5.0, // minutos por metro
                'material_compatibility' => [1, 2, 4, 6], // Acrílicos, PET e Vidro
                'complexity_multipliers' => [
                    'simple' => 1.0,   // bordas retas
                    'normal' => 1.3,   // curvas simples
                    'complex' => 1.8,  // curvas complexas
                ],
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Dobra/Curvatura',
                'description' => 'Dobra ou curvatura de materiais termoplásticos',
                'unit' => 'linear',
                'cost_per_unit' => 12.00,
                'setup_cost' => 25.00,
                'min_time_minutes' => 20,
                'time_per_unit' => 8.0,
                'material_compatibility' => [1, 2, 4], // Acrílicos e PET
                'complexity_multipliers' => [
                    'simple' => 1.0,   // dobras simples
                    'normal' => 1.5,   // dobras múltiplas
                    'complex' => 2.2,  // formas complexas
                ],
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Furação',
                'description' => 'Furação de precisão para fixação',
                'unit' => 'quantity', // cobrança por furo
                'cost_per_unit' => 2.50, // R$ por furo
                'setup_cost' => 8.00,
                'min_time_minutes' => 5,
                'time_per_unit' => 1.0, // minutos por furo
                'material_compatibility' => [1, 2, 3, 4, 5, 6], // Todos os materiais
                'complexity_multipliers' => [
                    'simple' => 1.0,   // furos simples
                    'normal' => 1.2,   // furos precisos
                    'complex' => 1.5,  // furos em ângulo
                ],
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Colagem',
                'description' => 'Colagem de peças com adesivos especiais',
                'unit' => 'time', // cobrança por tempo
                'cost_per_unit' => 45.00, // R$ por hora
                'setup_cost' => 10.00,
                'min_time_minutes' => 30,
                'time_per_unit' => 15.0, // minutos por junta
                'material_compatibility' => [1, 2, 3, 4], // Materiais coláveis
                'complexity_multipliers' => [
                    'simple' => 1.0,
                    'normal' => 1.3,
                    'complex' => 1.8,
                ],
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'Impressão UV',
                'description' => 'Impressão digital UV para personalização',
                'unit' => 'area',
                'cost_per_unit' => 55.00,
                'setup_cost' => 30.00,
                'min_time_minutes' => 15,
                'time_per_unit' => 4.0,
                'material_compatibility' => [1, 2, 3, 4, 5], // Todos exceto vidro temperado
                'complexity_multipliers' => [
                    'simple' => 1.0,   // cores chapadas
                    'normal' => 1.4,   // gradientes
                    'complex' => 1.9,  // múltiplas cores
                    'very_complex' => 2.5, // imagens complexas
                ],
                'is_active' => true,
                'sort_order' => 7,
            ],
            [
                'name' => 'Acabamento Superficial',
                'description' => 'Lixamento, escovação ou texturização',
                'unit' => 'area',
                'cost_per_unit' => 18.00,
                'setup_cost' => 12.00,
                'min_time_minutes' => 10,
                'time_per_unit' => 6.0,
                'material_compatibility' => [3, 5], // MDF e Metal
                'complexity_multipliers' => [
                    'simple' => 1.0,
                    'normal' => 1.3,
                    'complex' => 1.6,
                ],
                'is_active' => true,
                'sort_order' => 8,
            ],
            [
                'name' => 'Montagem/Assemblagem',
                'description' => 'Montagem final de peças e componentes',
                'unit' => 'time',
                'cost_per_unit' => 38.00, // R$ por hora
                'setup_cost' => 5.00,
                'min_time_minutes' => 15,
                'time_per_unit' => 20.0, // minutos por peça
                'material_compatibility' => [1, 2, 3, 4, 5, 6], // Todos os materiais
                'complexity_multipliers' => [
                    'simple' => 1.0,
                    'normal' => 1.4,
                    'complex' => 2.0,
                    'very_complex' => 2.8,
                ],
                'is_active' => true,
                'sort_order' => 9,
            ],
            [
                'name' => 'Instalação de LEDs',
                'description' => 'Instalação de sistema de iluminação LED',
                'unit' => 'quantity', // cobrança por metro de LED
                'cost_per_unit' => 25.00,
                'setup_cost' => 35.00,
                'min_time_minutes' => 45,
                'time_per_unit' => 12.0,
                'material_compatibility' => [1, 2, 3, 5], // Materiais que suportam LED
                'complexity_multipliers' => [
                    'simple' => 1.0,   // LED linear simples
                    'normal' => 1.5,   // múltiplas cores
                    'complex' => 2.2,  // sistemas complexos
                    'very_complex' => 3.0, // automação
                ],
                'is_active' => true,
                'sort_order' => 10,
            ],
        ];

        foreach ($processes as $processData) {
            ManufacturingProcess::create($processData);
        }
    }
}