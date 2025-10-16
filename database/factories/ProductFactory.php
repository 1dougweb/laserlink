<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Categorias de comunicação visual
        $categories = [
            'letreiros',
            'placas-impressas',
            'placas-com-letreiro',
            'placas-de-porta',
            'placas-de-sinalizacao',
            'placas-qr-code',
            'broches-crachas',
            'luminosos',
            'troféus',
            'medalhas',
            'placas-de-homenagem',
            'quadros-de-metas',
            'chaveiros',
            'copos-garrafas-termicas',
            'facas-canivetes',
            'serializacao-etiquetas',
            'displays-de-balcao',
            'displays-de-parede',
            'caixas-sob-medida',
            'divisorias-caixas',
            'urnas',
            'organizadores',
            'cupulas',
            'projetos-sob-medida'
        ];

        $categorySlug = $this->faker->randomElement($categories);
        
        // Buscar categoria ou criar uma padrão
        $category = Category::where('slug', $categorySlug)->first();
        if (!$category) {
            $category = Category::create([
                'name' => ucfirst(str_replace('-', ' ', $categorySlug)),
                'slug' => $categorySlug,
                'description' => 'Categoria de ' . str_replace('-', ' ', $categorySlug),
                'is_active' => true,
                'sort_order' => rand(1, 100)
            ]);
        }

        // Gerar nome baseado na categoria
        $name = $this->generateProductName($categorySlug);
        
        // Preços baseados no tipo de produto
        $price = $this->generatePrice($categorySlug);
        
        // Descrições baseadas no tipo
        $description = $this->generateDescription($categorySlug, $name);
        $shortDescription = $this->generateShortDescription($categorySlug, $name);

        return [
            'category_id' => $category->id,
            'supplier_id' => Supplier::factory(),
            'name' => $name,
            'slug' => Str::slug($name) . '-' . $this->faker->unique()->numerify('####'),
            'description' => $description,
            'short_description' => $shortDescription,
            'meta_title' => $name . ' - Laser Link',
            'meta_description' => $shortDescription,
            'meta_keywords' => $this->generateKeywords($categorySlug),
            'price' => $price,
            'sale_price' => $this->faker->optional(0.2)->randomFloat(2, $price * 0.7, $price * 0.9),
            'sku' => 'LL-' . strtoupper($categorySlug) . '-' . $this->faker->unique()->numerify('####'),
            'stock_quantity' => $this->faker->numberBetween(0, 100),
            'stock_min' => 5,
            'stock_max' => 100,
            'track_stock' => $this->faker->boolean(80),
            'is_active' => true, // Todos os produtos são ativos
            'is_featured' => $this->faker->boolean(50), // 50% dos produtos em destaque
            'images' => [], // Não gerar imagens fictícias
            'featured_image' => null, // Deixar vazio para ser definido depois
            'gallery_images' => [], // Não gerar imagens fictícias
            'attributes' => $this->generateAttributes($categorySlug),
            'custom_attributes' => $this->generateCustomAttributes($categorySlug),
            'sort_order' => $this->faker->numberBetween(1, 1000),
        ];
    }

    /**
     * Gerar nome do produto baseado na categoria
     */
    private function generateProductName(string $categorySlug): string
    {
        $names = [
            'letreiros' => [
                'Letreiro Corporativo em Acrílico',
                'Letreiro LED para Empresas',
                'Letreiro 3D Personalizado',
                'Letreiro em MDF Laminado',
                'Letreiro Iluminado'
            ],
            'placas-impressas' => [
                'Placa Impressa em Acrílico',
                'Placa de Sinalização Impressa',
                'Placa de Identificação',
                'Placa Promocional',
                'Placa Informativa'
            ],
            'placas-com-letreiro' => [
                'Placa com Letreiro Sobreposto',
                'Placa de Identificação Corporativa',
                'Placa com Texto em Relevo',
                'Placa de Porta Personalizada'
            ],
            'placas-de-porta' => [
                'Placa de Porta Simples',
                'Placa de Porta com Moldura',
                'Placa de Porta Iluminada',
                'Placa de Porta Executiva'
            ],
            'placas-de-sinalizacao' => [
                'Placa de Sinalização',
                'Placa de Emergência',
                'Placa de Orientação',
                'Placa de Segurança'
            ],
            'placas-qr-code' => [
                'Placa com QR Code',
                'QR Code em Acrílico',
                'Placa Digital QR Code',
                'QR Code Personalizado'
            ],
            'broches-crachas' => [
                'Crachá de Identificação',
                'Broche Promocional',
                'Crachá com Cordão',
                'Broche Corporativo'
            ],
            'luminosos' => [
                'Letreiro Luminoso LED',
                'Placa Iluminada',
                'Sinalização Luminosa',
                'Display LED'
            ],
            'troféus' => [
                'Troféu em Acrílico',
                'Troféu em MDF',
                'Troféu Metálico',
                'Troféu Personalizado'
            ],
            'medalhas' => [
                'Medalha de Participação',
                'Medalha Comemorativa',
                'Medalha Personalizada',
                'Medalha Corporativa'
            ],
            'placas-de-homenagem' => [
                'Placa de Homenagem',
                'Placa de Reconhecimento',
                'Placa Comemorativa',
                'Placa de Agradecimento'
            ],
            'quadros-de-metas' => [
                'Quadro de Metas',
                'Quadro de Acompanhamento',
                'Quadro Gerencial',
                'Quadro de Produtividade'
            ],
            'chaveiros' => [
                'Chaveiro Promocional',
                'Chaveiro Corporativo',
                'Chaveiro Personalizado',
                'Chaveiro de Brinde'
            ],
            'copos-garrafas-termicas' => [
                'Caneca Personalizada',
                'Garrafa Térmica',
                'Copo Promocional',
                'Caneca Corporativa'
            ],
            'facas-canivetes' => [
                'Canivete Promocional',
                'Faca Personalizada',
                'Canivete Corporativo',
                'Faca de Brinde'
            ],
            'serializacao-etiquetas' => [
                'Etiqueta Patrimonial',
                'Etiqueta de Identificação',
                'Etiqueta Serializada',
                'Etiqueta de Controle'
            ],
            'displays-de-balcao' => [
                'Display de Balcão Prisma',
                'Display de Balcão L',
                'Display de Balcão T',
                'Display de Balcão Folder'
            ],
            'displays-de-parede' => [
                'Display de Parede Frente',
                'Display de Parede U',
                'Quadro de Gestão',
                'Quadro de Alvarás'
            ],
            'caixas-sob-medida' => [
                'Caixa Sob Medida em Acrílico',
                'Caixa Personalizada em MDF',
                'Caixa em PS Transparente',
                'Caixa em PET'
            ],
            'divisorias-caixas' => [
                'Divisória para Caixa',
                'Divisória para Gaveta',
                'Organizador de Caixa',
                'Separador Personalizado'
            ],
            'urnas' => [
                'Urna Eleitoral',
                'Urna de Votação',
                'Urna Transparente',
                'Urna Personalizada'
            ],
            'organizadores' => [
                'Organizador de Mesa',
                'Organizador de Gaveta',
                'Organizador de Arquivo',
                'Organizador Personalizado'
            ],
            'cupulas' => [
                'Cúpula de Proteção',
                'Cúpula de Exposição',
                'Cúpula Transparente',
                'Cúpula Personalizada'
            ],
            'projetos-sob-medida' => [
                'Projeto Sob Medida',
                'Projeto Personalizado',
                'Projeto Especial',
                'Projeto Customizado'
            ]
        ];

        $categoryNames = $names[$categorySlug] ?? ['Produto Personalizado'];
        return $this->faker->randomElement($categoryNames);
    }

    /**
     * Gerar preço baseado no tipo de produto
     */
    private function generatePrice(string $categorySlug): float
    {
        $priceRanges = [
            'letreiros' => [150.00, 800.00],
            'placas-impressas' => [25.00, 200.00],
            'placas-com-letreiro' => [45.00, 300.00],
            'placas-de-porta' => [15.00, 80.00],
            'placas-de-sinalizacao' => [20.00, 120.00],
            'placas-qr-code' => [30.00, 150.00],
            'broches-crachas' => [3.00, 25.00],
            'luminosos' => [200.00, 1200.00],
            'troféus' => [40.00, 300.00],
            'medalhas' => [8.00, 50.00],
            'placas-de-homenagem' => [35.00, 250.00],
            'quadros-de-metas' => [60.00, 400.00],
            'chaveiros' => [2.00, 15.00],
            'copos-garrafas-termicas' => [8.00, 45.00],
            'facas-canivetes' => [12.00, 80.00],
            'serializacao-etiquetas' => [0.50, 8.00],
            'displays-de-balcao' => [80.00, 500.00],
            'displays-de-parede' => [60.00, 350.00],
            'caixas-sob-medida' => [120.00, 800.00],
            'divisorias-caixas' => [25.00, 180.00],
            'urnas' => [100.00, 600.00],
            'organizadores' => [35.00, 250.00],
            'cupulas' => [50.00, 300.00],
            'projetos-sob-medida' => [200.00, 2000.00]
        ];

        $range = $priceRanges[$categorySlug] ?? [50.00, 500.00];
        return $this->faker->randomFloat(2, $range[0], $range[1]);
    }

    /**
     * Gerar descrição detalhada
     */
    private function generateDescription(string $categorySlug, string $name): string
    {
        $descriptions = [
            'letreiros' => [
                'Letreiro de alta qualidade fabricado em acrílico de primeira linha. Ideal para identificação corporativa, com acabamento profissional e durabilidade garantida. Disponível em diversas cores e tamanhos.',
                'Letreiro personalizado com tecnologia LED de última geração. Economia de energia e alta luminosidade para máxima visibilidade. Perfeito para fachadas comerciais e identificação empresarial.',
                'Letreiro 3D com efeito de profundidade e sombras. Fabricado com precisão em acrílico ou MDF, proporcionando elegância e sofisticação para qualquer ambiente corporativo.'
            ],
            'placas-impressas' => [
                'Placa impressa com alta definição e cores vibrantes. Utilizamos materiais premium como acrílico, MDF ou PS para garantir durabilidade e acabamento profissional.',
                'Placa de sinalização com impressão digital de alta qualidade. Resistente à intempéries e com excelente legibilidade. Ideal para orientação e identificação em diversos ambientes.',
                'Placa personalizada com design exclusivo. Impressão em alta resolução com acabamento laminado para proteção contra desgaste e ação do tempo.'
            ],
            'troféus' => [
                'Troféu elegante fabricado em acrílico cristalino com gravação a laser. Base sólida e acabamento premium para eventos corporativos, esportivos ou comemorativos.',
                'Troféu em MDF com acabamento laminado e gravação personalizada. Design moderno e sofisticado, perfeito para premiações e reconhecimentos.',
                'Troféu metálico com acabamento dourado ou prateado. Fabricação artesanal com detalhes refinados para eventos especiais e comemorações importantes.'
            ],
            'medalhas' => [
                'Medalha comemorativa em metal com acabamento dourado, prateado ou bronzeado. Gravação personalizada com alta definição. Ideal para eventos esportivos, corporativos ou educacionais.',
                'Medalha de participação com design exclusivo. Fabricada em metal de qualidade com cordão personalizado. Perfeita para maratonas, competições e eventos especiais.'
            ],
            'chaveiros' => [
                'Chaveiro promocional de alta qualidade com impressão ou gravação personalizada. Disponível em diversos materiais: acrílico, metal, silicone. Ideal para brindes corporativos e eventos.',
                'Chaveiro corporativo com logo ou texto personalizado. Fabricado com materiais duráveis e acabamento profissional. Excelente opção para marketing promocional.'
            ]
        ];

        $categoryDescriptions = $descriptions[$categorySlug] ?? [
            'Produto de alta qualidade fabricado com materiais premium. Design personalizado e acabamento profissional para atender suas necessidades específicas.',
            'Solução personalizada desenvolvida especialmente para seu projeto. Utilizamos as melhores tecnologias e materiais disponíveis no mercado.',
            'Produto sob medida com design exclusivo. Fabricação artesanal com atenção aos detalhes para garantir a máxima qualidade e satisfação.'
        ];

        return $this->faker->randomElement($categoryDescriptions);
    }

    /**
     * Gerar descrição curta
     */
    private function generateShortDescription(string $categorySlug, string $name): string
    {
        $shortDescriptions = [
            'letreiros' => 'Letreiro personalizado em acrílico com acabamento profissional.',
            'placas-impressas' => 'Placa impressa de alta qualidade para identificação e sinalização.',
            'troféus' => 'Troféu elegante com gravação personalizada.',
            'medalhas' => 'Medalha comemorativa com design exclusivo.',
            'chaveiros' => 'Chaveiro promocional personalizado.',
            'displays-de-balcao' => 'Display de balcão para exposição de produtos.',
            'caixas-sob-medida' => 'Caixa personalizada sob medida para suas necessidades.',
            'projetos-sob-medida' => 'Projeto personalizado desenvolvido especialmente para você.'
        ];

        return $shortDescriptions[$categorySlug] ?? 'Produto personalizado de alta qualidade.';
    }

    /**
     * Gerar palavras-chave
     */
    private function generateKeywords(string $categorySlug): string
    {
        $keywords = [
            'letreiros' => 'letreiro, acrílico, led, luminoso, corporativo, fachada',
            'placas-impressas' => 'placa, impressão, sinalização, identificação, acrílico, mdf',
            'troféus' => 'troféu, premiação, acrílico, gravação, evento, comemoração',
            'medalhas' => 'medalha, premiação, metal, gravação, evento, esporte',
            'chaveiros' => 'chaveiro, brinde, promocional, personalizado, acrílico',
            'displays-de-balcao' => 'display, balcão, exposição, prisma, organizador',
            'caixas-sob-medida' => 'caixa, sob medida, personalizada, acrílico, mdf',
            'projetos-sob-medida' => 'projeto, sob medida, personalizado, customizado, especial'
        ];

        return $keywords[$categorySlug] ?? 'personalizado, qualidade, laser, acrílico';
    }

    /**
     * Gerar imagens
     */
    private function generateImages(): array
    {
        $imagePaths = [
            'products/letreiro-acrilico-1.jpg',
            'products/placa-impressa-1.jpg',
            'products/trofeu-premium-1.jpg',
            'products/medalha-ouro-1.jpg',
            'products/chaveiro-promocional-1.jpg',
            'products/display-balcao-1.jpg',
            'products/caixa-personalizada-1.jpg',
            'products/projeto-especial-1.jpg'
        ];

        return $this->faker->randomElements($imagePaths, $this->faker->numberBetween(1, 3));
    }

    /**
     * Gerar imagem destacada
     */
    private function generateFeaturedImage(): ?string
    {
        $featuredImages = [
            'products/featured/DWXJTloH1R63rb2ygh9a93HufaYOiYixOKcONO8b.jpg',
            'products/featured/S7NTGTumD8jF3XG80pWGsHOLaJP1LWKVxRhRbW3V.jpg',
            'products/featured/Xx48iwCzXaODwJcYYopWWYd9ozHHsEuinkVCbJnu.webp',
            'products/featured/deYxAXTlICgb3VphH56LAJb3Bik0ns6S2Y1zkbzJ.jpg',
            'products/featured/p1kYia098DSbyc00gfgNiVzNmlR3jyZ1jU72DXFV.jpg'
        ];

        return $this->faker->optional(0.9)->randomElement($featuredImages);
    }

    /**
     * Gerar galeria de imagens
     */
    private function generateGalleryImages(): array
    {
        $galleryImages = [
            'products/gallery/letreiro-1.jpg',
            'products/gallery/letreiro-2.jpg',
            'products/gallery/placa-1.jpg',
            'products/gallery/placa-2.jpg',
            'products/gallery/trofeu-1.jpg',
            'products/gallery/trofeu-2.jpg',
            'products/gallery/medalha-1.jpg',
            'products/gallery/chaveiro-1.jpg',
            'products/gallery/display-1.jpg',
            'products/gallery/caixa-1.jpg'
        ];

        return $this->faker->randomElements($galleryImages, $this->faker->numberBetween(2, 5));
    }

    /**
     * Gerar atributos baseados na categoria
     */
    private function generateAttributes(string $categorySlug): array
    {
        $baseAttributes = [
            'material' => $this->faker->randomElement(['Acrílico', 'MDF', 'PS', 'PET', 'Metal']),
            'cor' => $this->faker->randomElement(['Branco', 'Preto', 'Vermelho', 'Azul', 'Verde', 'Transparente']),
            'tamanho' => $this->faker->randomElement(['Pequeno', 'Médio', 'Grande', 'Personalizado']),
            'acabamento' => $this->faker->randomElement(['Fosco', 'Brilhante', 'Laminado', 'Natural'])
        ];

        $categorySpecific = [];
        
        switch ($categorySlug) {
            case 'letreiros':
            case 'luminosos':
                $categorySpecific = [
                    'iluminacao' => $this->faker->randomElement(['LED', 'Neon', 'Sem iluminação']),
                    'voltagem' => $this->faker->randomElement(['110V', '220V', '12V'])
                ];
                break;
                
            case 'placas-impressas':
            case 'placas-com-letreiro':
                $categorySpecific = [
                    'tipo_impressao' => $this->faker->randomElement(['Digital', 'Sublimação', 'UV']),
                    'resolucao' => $this->faker->randomElement(['300 DPI', '600 DPI', '1200 DPI'])
                ];
                break;
                
            case 'troféus':
            case 'medalhas':
                $categorySpecific = [
                    'tipo_gravacao' => $this->faker->randomElement(['Laser', 'Serigrafia', 'Relevo']),
                    'base' => $this->faker->randomElement(['Sim', 'Não'])
                ];
                break;
                
            case 'caixas-sob-medida':
                $categorySpecific = [
                    'dimensoes' => $this->faker->randomElement(['Sob medida', 'Padrão']),
                    'divisorias' => $this->faker->randomElement(['Sim', 'Não'])
                ];
                break;
        }

        return array_merge($baseAttributes, $categorySpecific);
    }

    /**
     * Gerar atributos customizados
     */
    private function generateCustomAttributes(string $categorySlug): array
    {
        $customAttributes = [
            'personalizacao' => $this->faker->boolean(70),
            'prazo_producao' => $this->faker->numberBetween(1, 15) . ' dias úteis',
            'frete_gratis' => $this->faker->boolean(30),
            'garantia' => $this->faker->randomElement(['6 meses', '1 ano', '2 anos']),
            'instalacao' => $this->faker->boolean(20)
        ];

        // Atributos específicos por categoria
        if (in_array($categorySlug, ['letreiros', 'luminosos'])) {
            $customAttributes['instalacao_eletrica'] = $this->faker->boolean(40);
        }

        if (in_array($categorySlug, ['placas-impressas', 'placas-com-letreiro'])) {
            $customAttributes['resistencia_intemperies'] = $this->faker->boolean(80);
        }

        return $customAttributes;
    }

    /**
     * Estado para produtos em destaque
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
            'price' => $attributes['price'] * 1.2, // Produtos em destaque podem ser mais caros
        ]);
    }

    /**
     * Estado para produtos em promoção
     */
    public function onSale(): static
    {
        return $this->state(function (array $attributes) {
            $salePrice = $attributes['price'] * $this->faker->randomFloat(2, 0.6, 0.85);
            return [
                'sale_price' => $salePrice,
                'is_featured' => $this->faker->boolean(30),
            ];
        });
    }

    /**
     * Estado para produtos sem estoque
     */
    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock_quantity' => 0,
            'track_stock' => true,
        ]);
    }

    /**
     * Estado para produtos com estoque baixo
     */
    public function lowStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock_quantity' => $this->faker->numberBetween(1, 4),
            'track_stock' => true,
            'stock_min' => 5,
        ]);
    }
}
