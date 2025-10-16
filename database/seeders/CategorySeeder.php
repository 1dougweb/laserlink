<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Comunicação Visual',
                'description' => 'Letreiros, Placas impressas, Placas com letreiro sobreposto, placas de porta de identificação, placas de sinalização, placas QR CODE, broches/crachás, luminosos.',
                'sort_order' => 1,
            ],
            [
                'name' => 'Premiações',
                'description' => 'Troféus, Medalhas, Placas de homenagem, Quadros de metas alcançadas.',
                'sort_order' => 2,
            ],
            [
                'name' => 'Brindes Corporativos',
                'description' => 'Chaveiros, Crachás, copos e garrafas térmicas, Facas e Canivetes, Serialização e etiquetas patrimoniais.',
                'sort_order' => 3,
            ],
            [
                'name' => 'Displays',
                'description' => 'Displays de balcão modelos: Prisma, L, T, Folder... Displays de parede modelos: Frente, U, Quadros de gestão a plena vista, Quadros de alvarás.',
                'sort_order' => 4,
            ],
            [
                'name' => 'Caixas',
                'description' => 'Caixas sob medida, divisórias para caixas e gavetas, urnas, organizadores, cúpulas.',
                'sort_order' => 5,
            ],
            [
                'name' => 'Projetos Sob Medida',
                'description' => 'Projetos personalizados em Acrílico, MDF, PS e PET e impressão 3D.',
                'sort_order' => 6,
            ],
            [
                'name' => 'Gravação em Metal',
                'description' => 'Gravação em metal e diversos itens personalizados.',
                'sort_order' => 7,
            ],
            [
                'name' => 'Impressão 3D',
                'description' => 'Criação de logos e impressão 3D para diversos materiais.',
                'sort_order' => 8,
            ],
            [
                'name' => 'Acrílico',
                'description' => 'Produtos em acrílico de alta qualidade.',
                'sort_order' => 9,
            ],
            [
                'name' => 'MDF',
                'description' => 'Produtos em MDF para diversas aplicações.',
                'sort_order' => 10,
            ],
            [
                'name' => 'Banners',
                'description' => 'Banners publicitários e promocionais.',
                'sort_order' => 11,
            ],
            [
                'name' => 'Adesivos',
                'description' => 'Adesivos personalizados para diversos usos.',
                'sort_order' => 12,
            ],
        ];

        foreach ($categories as $index => $categoryData) {
            $slug = Str::slug($categoryData['name']);
            
            Category::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $categoryData['name'],
                    'description' => $categoryData['description'],
                    'sort_order' => $categoryData['sort_order'],
                    'home_order' => $index + 1,
                    'is_active' => true,
                    'show_in_home' => true,
                ]
            );
        }
    }
}
