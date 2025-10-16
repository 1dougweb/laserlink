<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Product;
use Database\Seeders\ProductFactorySeeder;
use Illuminate\Console\Command;

class GenerateProductsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:generate 
                            {--count=500 : Número de produtos para gerar}
                            {--category= : Categoria específica para gerar produtos}
                            {--featured : Gerar apenas produtos em destaque}
                            {--on-sale : Gerar apenas produtos em promoção}
                            {--out-of-stock : Gerar apenas produtos sem estoque}
                            {--clear : Limpar produtos existentes antes de gerar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gera produtos de exemplo usando a factory para popular a loja';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $count = (int) $this->option('count');
        $category = $this->option('category');
        $featured = $this->option('featured');
        $onSale = $this->option('on-sale');
        $outOfStock = $this->option('out-of-stock');
        $clear = $this->option('clear');

        if ($clear) {
            $this->info('Limpando produtos existentes...');
            Product::truncate();
        }

        $this->info("Gerando {$count} produtos...");

        if ($category) {
            $this->generateProductsForCategory($category, $count, $featured, $onSale, $outOfStock);
        } elseif ($featured) {
            $this->generateFeaturedProducts($count);
        } elseif ($onSale) {
            $this->generateOnSaleProducts($count);
        } elseif ($outOfStock) {
            $this->generateOutOfStockProducts($count);
        } else {
            $this->generateAllProducts($count);
        }

        $this->info('Produtos gerados com sucesso!');
        $this->info('Total de produtos: ' . Product::count());

        return Command::SUCCESS;
    }

    /**
     * Gerar produtos para uma categoria específica
     */
    private function generateProductsForCategory(string $categorySlug, int $count, bool $featured, bool $onSale, bool $outOfStock): void
    {
        $category = \App\Models\Category::where('slug', $categorySlug)->first();
        
        if (!$category) {
            $this->error("Categoria '{$categorySlug}' não encontrada.");
            return;
        }

        $factory = Product::factory()->count($count)->for($category);

        if ($featured) {
            $factory = $factory->featured();
        } elseif ($onSale) {
            $factory = $factory->onSale();
        } elseif ($outOfStock) {
            $factory = $factory->outOfStock();
        }

        $factory->create();

        $this->info("Criados {$count} produtos para categoria: {$category->name}");
    }

    /**
     * Gerar apenas produtos em destaque
     */
    private function generateFeaturedProducts(int $count): void
    {
        Product::factory()
            ->count($count)
            ->featured()
            ->create();
            
        $this->info("Criados {$count} produtos em destaque");
    }

    /**
     * Gerar apenas produtos em promoção
     */
    private function generateOnSaleProducts(int $count): void
    {
        Product::factory()
            ->count($count)
            ->onSale()
            ->create();
            
        $this->info("Criados {$count} produtos em promoção");
    }

    /**
     * Gerar apenas produtos sem estoque
     */
    private function generateOutOfStockProducts(int $count): void
    {
        Product::factory()
            ->count($count)
            ->outOfStock()
            ->create();
            
        $this->info("Criados {$count} produtos sem estoque");
    }

    /**
     * Gerar produtos variados
     */
    private function generateAllProducts(int $count): void
    {
        // Executar o seeder completo
        $seeder = new ProductFactorySeeder();
        $seeder->setCommand($this);
        $seeder->run();
    }
}
