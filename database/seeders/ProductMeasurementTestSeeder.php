<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductMeasurement;
use App\Models\Category;
use App\Models\Material;

class ProductMeasurementTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar ou criar categoria
        $category = Category::firstOrCreate([
            'name' => 'Comunicação Visual'
        ], [
            'slug' => 'comunicacao-visual',
            'description' => 'Produtos de comunicação visual',
            'is_active' => true
        ]);

        // Buscar ou criar material
        $material = Material::firstOrCreate([
            'name' => 'Acrílico Cristal'
        ], [
            'slug' => 'acrilico-cristal',
            'density_g_cm3' => 1.19,
            'is_active' => true
        ]);

        // Criar produto de teste
        $product = Product::create([
            'name' => 'Letreiro Acrílico Teste',
            'slug' => 'letreiro-acrilico-teste',
            'category_id' => $category->id,
            'product_type' => 'letreiro',
            'description' => 'Letreiro de teste com medidas pré-definidas',
            'price' => 100.00,
            'stock_quantity' => 10,
            'material_id' => $material->id,
            'is_active' => true
        ]);

        // Criar medidas para o produto
        $product->measurements()->create([
            'name' => 'Pequeno',
            'description' => 'Medida pequena para teste',
            'width' => 30,
            'height' => 20,
            'thickness' => 3,
            'area' => 0.06,
            'volume' => 0.00018,
            'weight' => 0.214,
            'is_default' => true,
            'is_active' => true,
            'sort_order' => 0
        ]);

        $product->measurements()->create([
            'name' => 'Médio',
            'description' => 'Medida média para teste',
            'width' => 50,
            'height' => 30,
            'thickness' => 5,
            'area' => 0.15,
            'volume' => 0.00075,
            'weight' => 0.893,
            'is_default' => false,
            'is_active' => true,
            'sort_order' => 1
        ]);

        $product->measurements()->create([
            'name' => 'Grande',
            'description' => 'Medida grande para teste',
            'width' => 80,
            'height' => 50,
            'thickness' => 8,
            'area' => 0.40,
            'volume' => 0.0032,
            'weight' => 3.808,
            'is_default' => false,
            'is_active' => true,
            'sort_order' => 2
        ]);

        $this->command->info("Produto de teste criado com ID: {$product->id}");
        $this->command->info("Medidas criadas: " . $product->measurements()->count());
    }
}