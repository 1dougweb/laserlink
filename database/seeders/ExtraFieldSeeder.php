<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExtraField;
use App\Models\ExtraFieldOption;

class ExtraFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Campo de Material
        $materialField = ExtraField::create([
            'name' => 'Material',
            'slug' => 'material',
            'description' => 'Selecione o material do produto',
            'type' => 'select',
            'is_required' => true,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        // Opções do Material
        $materialOptions = [
            ['value' => 'acrilico', 'label' => 'Acrílico', 'price' => 0.00, 'price_type' => 'fixed'],
            ['value' => 'mdf', 'label' => 'MDF', 'price' => 0.00, 'price_type' => 'fixed'],
            ['value' => 'metal', 'label' => 'Metal', 'price' => 0.00, 'price_type' => 'fixed'],
            ['value' => 'madeira', 'label' => 'Madeira', 'price' => 0.00, 'price_type' => 'fixed'],
        ];

        foreach ($materialOptions as $index => $option) {
            ExtraFieldOption::create([
                'extra_field_id' => $materialField->id,
                'value' => $option['value'],
                'label' => $option['label'],
                'price' => $option['price'],
                'price_type' => $option['price_type'],
                'sort_order' => $index + 1,
                'is_active' => true,
            ]);
        }

        // Campo de Espessura
        $thicknessField = ExtraField::create([
            'name' => 'Espessura',
            'slug' => 'espessura',
            'description' => 'Selecione a espessura do material',
            'type' => 'select',
            'is_required' => true,
            'is_active' => true,
            'sort_order' => 2,
        ]);

        // Opções de Espessura
        $thicknessOptions = [
            ['value' => '2mm', 'label' => '2mm', 'price' => 0.00, 'price_type' => 'fixed'],
            ['value' => '3mm', 'label' => '3mm', 'price' => 5.00, 'price_type' => 'fixed'],
            ['value' => '5mm', 'label' => '5mm', 'price' => 10.00, 'price_type' => 'fixed'],
            ['value' => '8mm', 'label' => '8mm', 'price' => 15.00, 'price_type' => 'fixed'],
            ['value' => '10mm', 'label' => '10mm', 'price' => 20.00, 'price_type' => 'fixed'],
        ];

        foreach ($thicknessOptions as $index => $option) {
            ExtraFieldOption::create([
                'extra_field_id' => $thicknessField->id,
                'value' => $option['value'],
                'label' => $option['label'],
                'price' => $option['price'],
                'price_type' => $option['price_type'],
                'sort_order' => $index + 1,
                'is_active' => true,
            ]);
        }

        // Campo de Acabamento
        $finishField = ExtraField::create([
            'name' => 'Acabamento',
            'slug' => 'acabamento',
            'description' => 'Selecione o acabamento desejado',
            'type' => 'radio',
            'is_required' => false,
            'is_active' => true,
            'sort_order' => 3,
        ]);

        // Opções de Acabamento
        $finishOptions = [
            ['value' => 'fosco', 'label' => 'Fosco', 'price' => 0.00, 'price_type' => 'fixed'],
            ['value' => 'brilho', 'label' => 'Brilho', 'price' => 5.00, 'price_type' => 'fixed'],
            ['value' => 'polido', 'label' => 'Polido', 'price' => 10.00, 'price_type' => 'fixed'],
            ['value' => 'texturizado', 'label' => 'Texturizado', 'price' => 8.00, 'price_type' => 'fixed'],
        ];

        foreach ($finishOptions as $index => $option) {
            ExtraFieldOption::create([
                'extra_field_id' => $finishField->id,
                'value' => $option['value'],
                'label' => $option['label'],
                'price' => $option['price'],
                'price_type' => $option['price_type'],
                'sort_order' => $index + 1,
                'is_active' => true,
            ]);
        }

        // Campo de Dimensões
        $dimensionsField = ExtraField::create([
            'name' => 'Dimensões',
            'slug' => 'dimensoes',
            'description' => 'Informe as dimensões em centímetros',
            'type' => 'text',
            'is_required' => true,
            'is_active' => true,
            'sort_order' => 4,
            'settings' => [
                'placeholder' => 'Ex: 30x40cm',
                'unit' => 'cm'
            ],
            'validation_rules' => [
                'min_length' => 3,
                'max_length' => 20
            ]
        ]);

        // Campo de Personalização
        $personalizationField = ExtraField::create([
            'name' => 'Personalização',
            'slug' => 'personalizacao',
            'description' => 'Texto ou frase personalizada',
            'type' => 'textarea',
            'is_required' => false,
            'is_active' => true,
            'sort_order' => 5,
            'settings' => [
                'placeholder' => 'Digite seu texto personalizado aqui...',
                'rows' => 3
            ],
            'validation_rules' => [
                'max_length' => 200
            ]
        ]);

        // Campo de Quantidade
        $quantityField = ExtraField::create([
            'name' => 'Quantidade',
            'slug' => 'quantidade',
            'description' => 'Quantidade de peças',
            'type' => 'number',
            'is_required' => true,
            'is_active' => true,
            'sort_order' => 6,
            'settings' => [
                'min' => 1,
                'max' => 1000,
                'step' => 1
            ]
        ]);
    }
}