<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class ProductFactorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar apenas 2 fornecedores
        $suppliers = Supplier::factory()->count(2)->create();
        
        // Criar categorias se não existirem
        $this->createCategories();
        
        // Criar apenas 8 produtos
        $this->createLimitedProducts();
        
        $this->command->info('Produtos criados com sucesso!');
        $this->command->info('Total de produtos: ' . Product::count());
        $this->command->info('Total de fornecedores: ' . Supplier::count());
    }

    /**
     * Criar categorias principais
     */
    private function createCategories(): void
    {
        $categories = [
            [
                'name' => 'Letreiros',
                'slug' => 'letreiros',
                'description' => 'Letreiros personalizados em diversos materiais para identificação corporativa.',
                'sort_order' => 1
            ],
            [
                'name' => 'Placas Impressas',
                'slug' => 'placas-impressas',
                'description' => 'Placas com impressão digital de alta qualidade para sinalização e identificação.',
                'sort_order' => 2
            ],
            [
                'name' => 'Placas com Letreiro Sobreposto',
                'slug' => 'placas-com-letreiro',
                'description' => 'Placas com letreiro em relevo ou sobreposto para máxima elegância.',
                'sort_order' => 3
            ],
            [
                'name' => 'Placas de Porta',
                'slug' => 'placas-de-porta',
                'description' => 'Placas de identificação para portas de escritórios e ambientes corporativos.',
                'sort_order' => 4
            ],
            [
                'name' => 'Placas de Sinalização',
                'slug' => 'placas-de-sinalizacao',
                'description' => 'Placas de orientação e sinalização para diversos ambientes.',
                'sort_order' => 5
            ],
            [
                'name' => 'Placas QR Code',
                'slug' => 'placas-qr-code',
                'description' => 'Placas com QR Code integrado para acesso digital e informações.',
                'sort_order' => 6
            ],
            [
                'name' => 'Broches e Crachás',
                'slug' => 'broches-crachas',
                'description' => 'Crachás de identificação e broches promocionais personalizados.',
                'sort_order' => 7
            ],
            [
                'name' => 'Luminosos',
                'slug' => 'luminosos',
                'description' => 'Letreiros e placas luminosas com tecnologia LED de alta eficiência.',
                'sort_order' => 8
            ],
            [
                'name' => 'Troféus',
                'slug' => 'trofeus',
                'description' => 'Troféus personalizados para premiações e eventos especiais.',
                'sort_order' => 9
            ],
            [
                'name' => 'Medalhas',
                'slug' => 'medalhas',
                'description' => 'Medalhas comemorativas e de participação com design exclusivo.',
                'sort_order' => 10
            ],
            [
                'name' => 'Placas de Homenagem',
                'slug' => 'placas-de-homenagem',
                'description' => 'Placas especiais para homenagens e reconhecimentos.',
                'sort_order' => 11
            ],
            [
                'name' => 'Quadros de Metas',
                'slug' => 'quadros-de-metas',
                'description' => 'Quadros para acompanhamento de metas e produtividade.',
                'sort_order' => 12
            ],
            [
                'name' => 'Chaveiros',
                'slug' => 'chaveiros',
                'description' => 'Chaveiros promocionais e corporativos personalizados.',
                'sort_order' => 13
            ],
            [
                'name' => 'Copos e Garrafas Térmicas',
                'slug' => 'copos-garrafas-termicas',
                'description' => 'Canecas e garrafas térmicas com personalização corporativa.',
                'sort_order' => 14
            ],
            [
                'name' => 'Facas e Canivetes',
                'slug' => 'facas-canivetes',
                'description' => 'Facas e canivetes promocionais com gravação personalizada.',
                'sort_order' => 15
            ],
            [
                'name' => 'Serialização e Etiquetas',
                'slug' => 'serializacao-etiquetas',
                'description' => 'Etiquetas patrimoniais e de controle com numeração sequencial.',
                'sort_order' => 16
            ],
            [
                'name' => 'Displays de Balcão',
                'slug' => 'displays-de-balcao',
                'description' => 'Displays para balcão com diversos modelos e acabamentos.',
                'sort_order' => 17
            ],
            [
                'name' => 'Displays de Parede',
                'slug' => 'displays-de-parede',
                'description' => 'Displays para parede e quadros de gestão.',
                'sort_order' => 18
            ],
            [
                'name' => 'Caixas Sob Medida',
                'slug' => 'caixas-sob-medida',
                'description' => 'Caixas personalizadas fabricadas sob medida em diversos materiais.',
                'sort_order' => 19
            ],
            [
                'name' => 'Divisórias para Caixas',
                'slug' => 'divisorias-caixas',
                'description' => 'Divisórias e organizadores para caixas e gavetas.',
                'sort_order' => 20
            ],
            [
                'name' => 'Urnas',
                'slug' => 'urnas',
                'description' => 'Urnas eleitorais e de votação transparentes e seguras.',
                'sort_order' => 21
            ],
            [
                'name' => 'Organizadores',
                'slug' => 'organizadores',
                'description' => 'Organizadores personalizados para escritórios e ambientes corporativos.',
                'sort_order' => 22
            ],
            [
                'name' => 'Cúpulas',
                'slug' => 'cupulas',
                'description' => 'Cúpulas de proteção e exposição em acrílico transparente.',
                'sort_order' => 23
            ],
            [
                'name' => 'Projetos Sob Medida',
                'slug' => 'projetos-sob-medida',
                'description' => 'Projetos especiais desenvolvidos sob medida para necessidades únicas.',
                'sort_order' => 24
            ]
        ];

        foreach ($categories as $categoryData) {
            Category::firstOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );
        }
    }

    /**
     * Criar produtos limitados (apenas 8 produtos)
     */
    private function createLimitedProducts(): void
    {
        // Selecionar as 8 categorias principais
        $mainCategories = [
            'letreiros',
            'placas-impressas',
            'trofeus',
            'chaveiros',
            'displays-de-balcao',
            'caixas-sob-medida',
            'broches-crachas',
            'luminosos'
        ];
        
        foreach ($mainCategories as $index => $slug) {
            $category = Category::where('slug', $slug)->first();
            
            if ($category) {
                // Criar 1 produto por categoria
                // Variar os tipos: alguns em destaque, alguns em promoção
                $productData = ['category_id' => $category->id];
                
                if ($index === 0) {
                    // Primeiro produto: em destaque
                    $product = Product::factory()->featured()->create($productData);
                } elseif ($index === 1) {
                    // Segundo produto: em promoção
                    $product = Product::factory()->onSale()->create($productData);
                } else {
                    // Demais produtos: normais
                    $product = Product::factory()->create($productData);
                }
                
                $this->command->info("Produto criado: {$product->name} ({$category->name})");
            }
        }
        
        $this->command->info('---');
        $this->command->info('Total: 8 produtos criados');
        $this->command->info('- 1 produto em destaque');
        $this->command->info('- 1 produto em promoção');
        $this->command->info('- 6 produtos normais');
    }
}
