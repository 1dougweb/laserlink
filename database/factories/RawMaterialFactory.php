<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\RawMaterial;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RawMaterial>
 */
class RawMaterialFactory extends Factory
{
    protected $model = RawMaterial::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $category = $this->faker->randomElement(array_keys(RawMaterial::CATEGORIES));
        $unit = $this->faker->randomElement(array_keys(RawMaterial::UNITS));
        
        // Gerar código único para o material
        $code = strtoupper($this->faker->unique()->bothify('MAT-???-####'));
        
        // Quantidades baseadas na unidade
        $stockQuantity = match($unit) {
            'm2' => $this->faker->randomFloat(2, 5, 200),
            'kg' => $this->faker->randomFloat(3, 1, 100),
            'l' => $this->faker->randomFloat(2, 1, 50),
            'ml' => $this->faker->randomFloat(2, 100, 5000),
            'g' => $this->faker->randomFloat(2, 100, 10000),
            'un' => $this->faker->numberBetween(10, 500),
            default => $this->faker->randomFloat(2, 10, 100),
        };

        $stockMin = $stockQuantity * 0.2; // 20% do estoque atual
        $stockMax = $stockQuantity * 2.5; // 250% do estoque atual

        return [
            'supplier_id' => Supplier::factory(),
            'name' => $this->generateMaterialName($category),
            'code' => $code,
            'category' => $category,
            'unit' => $unit,
            'stock_quantity' => $stockQuantity,
            'stock_min' => $stockMin,
            'stock_max' => $stockMax,
            'unit_cost' => $this->faker->randomFloat(2, 5, 500),
            'description' => $this->faker->optional(0.7)->sentence(),
            'specifications' => $this->faker->optional(0.5)->paragraph(),
            'is_active' => $this->faker->boolean(95),
        ];
    }

    /**
     * Gera nome de material baseado na categoria
     */
    private function generateMaterialName(string $category): string
    {
        $materials = [
            'acrilico' => [
                'Acrílico Cristal 2mm',
                'Acrílico Cristal 3mm',
                'Acrílico Cristal 5mm',
                'Acrílico Leitoso 3mm',
                'Acrílico Colorido 2mm',
                'Acrílico Espelhado 3mm',
            ],
            'mdf' => [
                'MDF Cru 3mm',
                'MDF Cru 6mm',
                'MDF Cru 9mm',
                'MDF Branco 6mm',
                'MDF Resinado 3mm',
            ],
            'ps' => [
                'PS Cristal 1mm',
                'PS Cristal 2mm',
                'PS Leitoso 2mm',
                'PS Preto 2mm',
            ],
            'pet' => [
                'PET Cristal 0.5mm',
                'PET Cristal 1mm',
                'PET Reciclado 0.8mm',
            ],
            'metal' => [
                'Chapa de Alumínio 1mm',
                'Chapa de Aço Inox 1mm',
                'Perfil Alumínio U',
                'Parafuso M3',
                'Rebite de Alumínio',
            ],
            'tinta' => [
                'Tinta UV Branca',
                'Tinta UV Preta',
                'Tinta UV CMYK',
                'Verniz UV Brilhante',
                'Primer para Acrílico',
            ],
            'adesivo' => [
                'Vinil Adesivo Branco',
                'Vinil Adesivo Transparente',
                'Adesivo Dupla Face 3M',
                'Contact Transparente',
                'Película Protetora',
            ],
            'eletrico' => [
                'LED Strip 5050',
                'LED Strip 3528',
                'Fonte 12V 5A',
                'Cabo PP 2x1.5mm',
                'Tomada Macho 2P+T',
            ],
            'outros' => [
                'Cola Instantânea',
                'Cola para Acrílico',
                'Lixa 120',
                'Lixa 220',
                'Pano de Limpeza',
            ],
        ];

        $names = $materials[$category] ?? ['Material Genérico'];
        return $this->faker->randomElement($names);
    }

    /**
     * Estado para materiais de Acrílico
     */
    public function acrilico(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'acrilico',
            'unit' => 'm2',
            'name' => $this->generateMaterialName('acrilico'),
        ]);
    }

    /**
     * Estado para materiais de MDF
     */
    public function mdf(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'mdf',
            'unit' => 'm2',
            'name' => $this->generateMaterialName('mdf'),
        ]);
    }

    /**
     * Estado para materiais de PS
     */
    public function ps(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'ps',
            'unit' => 'm2',
            'name' => $this->generateMaterialName('ps'),
        ]);
    }

    /**
     * Estado para materiais de PET
     */
    public function pet(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'pet',
            'unit' => 'm2',
            'name' => $this->generateMaterialName('pet'),
        ]);
    }

    /**
     * Estado para tintas
     */
    public function tinta(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'tinta',
            'unit' => 'l',
            'name' => $this->generateMaterialName('tinta'),
        ]);
    }

    /**
     * Estado para materiais com estoque baixo
     */
    public function lowStock(): static
    {
        return $this->state(function (array $attributes) {
            $stockMin = $attributes['stock_min'];
            return [
                'stock_quantity' => $stockMin * 0.5, // 50% do mínimo
            ];
        });
    }

    /**
     * Estado para materiais sem estoque
     */
    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock_quantity' => 0,
        ]);
    }

    /**
     * Estado para materiais inativos
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}

