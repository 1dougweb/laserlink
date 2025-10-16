<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar ou criar categoria para o blog
        $category = Category::firstOrCreate(
            ['slug' => 'novidades'],
            [
                'name' => 'Novidades',
                'description' => 'Últimas novidades e atualizações',
                'is_active' => true,
            ]
        );

        // Buscar o primeiro usuário admin
        $admin = User::first();

        if (!$admin) {
            $this->command->warn('Nenhum usuário encontrado. Execute o AdminUserSeeder primeiro.');
            return;
        }

        $posts = [
            [
                'title' => 'Bem-vindo ao Blog Laser Link',
                'slug' => 'bem-vindo-ao-blog-laser-link',
                'excerpt' => 'Conheça nosso novo blog com dicas, novidades e tendências em comunicação visual.',
                'content' => '<p>Seja bem-vindo ao blog da <strong>Laser Link</strong>! Aqui você encontrará conteúdos exclusivos sobre comunicação visual, dicas de personalização de produtos, tendências do mercado e muito mais.</p>

<p>Nossa missão é fornecer não apenas produtos de alta qualidade, mas também conhecimento para que você possa fazer as melhores escolhas para seu negócio ou evento.</p>

<h2>O que você encontrará aqui?</h2>

<ul>
<li>Tendências em comunicação visual</li>
<li>Dicas de personalização de produtos</li>
<li>Cases de sucesso</li>
<li>Novidades da indústria</li>
<li>Tutoriais e guias práticos</li>
</ul>

<p>Fique ligado em nossos conteúdos e não deixe de compartilhar suas dúvidas e sugestões nos comentários!</p>',
                'meta_description' => 'Conheça o blog Laser Link com dicas e novidades sobre comunicação visual',
                'meta_keywords' => 'blog, laser link, comunicação visual, novidades',
                'status' => 'published',
                'published_at' => now()->subDays(7),
            ],
            [
                'title' => 'Como Escolher o Material Ideal para Seu Letreiro',
                'slug' => 'como-escolher-material-ideal-letreiro',
                'excerpt' => 'Descubra qual material é mais adequado para seu projeto de sinalização.',
                'content' => '<p>Escolher o material certo para seu letreiro é fundamental para garantir durabilidade e impacto visual. Neste artigo, vamos explorar as principais opções disponíveis.</p>

<h2>Principais Materiais</h2>

<h3>1. Acrílico</h3>
<p>O acrílico é uma das opções mais populares devido à sua versatilidade e acabamento elegante. É ideal para ambientes internos e externos, resistente às intempéries e oferece excelente transparência.</p>

<h3>2. MDF</h3>
<p>O MDF é uma opção econômica e versátil, perfeita para projetos internos. Pode ser cortado em formas complexas e aceita diversos tipos de acabamento.</p>

<h3>3. PVC</h3>
<p>Leve, resistente e econômico, o PVC é ideal para diversos tipos de aplicações, tanto internas quanto externas.</p>

<h2>Como Decidir?</h2>
<p>Considere fatores como:</p>
<ul>
<li>Localização (interno ou externo)</li>
<li>Orçamento disponível</li>
<li>Durabilidade necessária</li>
<li>Estilo desejado</li>
</ul>

<p>Nossa equipe está sempre disponível para ajudar você a fazer a melhor escolha!</p>',
                'meta_description' => 'Guia completo para escolher o material ideal para seu letreiro',
                'meta_keywords' => 'letreiro, acrílico, MDF, PVC, sinalização',
                'status' => 'published',
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => '5 Tendências em Troféus e Premiações para 2025',
                'slug' => '5-tendencias-trofeus-premiacoes-2025',
                'excerpt' => 'Conheça as principais tendências em troféus e premiações para este ano.',
                'content' => '<p>O mercado de premiações está em constante evolução. Veja as principais tendências para 2025:</p>

<h2>1. Sustentabilidade</h2>
<p>Troféus feitos com materiais reciclados ou sustentáveis estão em alta, refletindo a preocupação ambiental das empresas.</p>

<h2>2. Personalização em Massa</h2>
<p>Tecnologias de corte a laser permitem personalização única em cada peça, mesmo em grandes quantidades.</p>

<h2>3. Design Minimalista</h2>
<p>Linhas limpas e designs simples dominam as preferências, transmitindo elegância e modernidade.</p>

<h2>4. Integração de Tecnologia</h2>
<p>QR codes e NFC tags integrados aos troféus conectam o físico ao digital.</p>

<h2>5. Materiais Alternativos</h2>
<p>Madeira, acrílico colorido e combinações de materiais criam peças únicas e memoráveis.</p>

<p>Quer estar à frente das tendências? Entre em contato conosco!</p>',
                'meta_description' => 'Descubra as 5 principais tendências em troféus e premiações para 2025',
                'meta_keywords' => 'troféus, premiações, tendências 2025, personalização',
                'status' => 'published',
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => 'A Importância da Comunicação Visual para Empresas',
                'slug' => 'importancia-comunicacao-visual-empresas',
                'excerpt' => 'Entenda como a comunicação visual impacta diretamente no sucesso do seu negócio.',
                'content' => '<p>A comunicação visual é muito mais do que apenas estética. Ela é uma ferramenta estratégica essencial para qualquer negócio.</p>

<h2>Por que investir em comunicação visual?</h2>

<h3>1. Identidade Visual Forte</h3>
<p>Uma identidade visual consistente ajuda a criar reconhecimento de marca e confiança.</p>

<h3>2. Diferenciação no Mercado</h3>
<p>Em um mercado competitivo, destacar-se visualmente pode ser o diferencial decisivo.</p>

<h3>3. Comunicação Eficiente</h3>
<p>Mensagens visuais são processadas 60.000 vezes mais rápido que textos.</p>

<h3>4. Profissionalismo</h3>
<p>Materiais de comunicação visual de qualidade transmitem profissionalismo e credibilidade.</p>

<h2>Elementos Essenciais</h2>
<ul>
<li>Letreiros e fachadas</li>
<li>Sinalização interna</li>
<li>Material promocional</li>
<li>Identidade visual consistente</li>
</ul>

<p>Invista em comunicação visual de qualidade e veja seu negócio crescer!</p>',
                'meta_description' => 'Descubra a importância da comunicação visual para o sucesso do seu negócio',
                'meta_keywords' => 'comunicação visual, empresas, marketing, identidade visual',
                'status' => 'published',
                'published_at' => now()->subDays(1),
            ],
        ];

        foreach ($posts as $postData) {
            Post::create([
                ...$postData,
                'author_id' => $admin->id,
                'category_id' => $category->id,
                'views' => rand(10, 500),
            ]);
        }

        $this->command->info('Posts de exemplo criados com sucesso!');
    }
}
