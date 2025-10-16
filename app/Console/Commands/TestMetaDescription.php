<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestMetaDescription extends Command
{
    protected $signature = 'meta:test {product_name}';
    protected $description = 'Test meta description length';

    public function handle()
    {
        $productName = $this->argument('product_name');
        
        $this->info("Testando meta description para: '{$productName}'");
        
        // Simular geração de meta description
        $mainKeyword = $this->extractMainKeyword($productName);
        
        $metaDescription = "Descubra o melhor {$mainKeyword} com qualidade superior. " .
                          "Materiais premium, acabamento profissional e durabilidade garantida. " .
                          "Entrega rápida!";
        
        $this->info("Meta Description: '{$metaDescription}'");
        $this->info("Tamanho: " . strlen($metaDescription) . " caracteres");
        
        if (strlen($metaDescription) <= 160) {
            $this->info("✅ Dentro do limite de 160 caracteres");
        } else {
            $this->error("❌ Excede o limite de 160 caracteres");
        }
    }
    
    private function extractMainKeyword(string $productName): string
    {
        $commonWords = ['de', 'da', 'do', 'das', 'dos', 'para', 'com', 'em', 'na', 'no', 'nas', 'nos', 'personalizada', 'personalizado', 'customizada', 'customizado'];
        
        $words = explode(' ', strtolower($productName));
        
        $keywords = array_filter($words, function($word) use ($commonWords) {
            return strlen($word) > 3 && !in_array($word, $commonWords);
        });
        
        if (empty($keywords)) {
            $words = explode(' ', $productName);
            return $words[0] ?? $productName;
        }
        
        return ucfirst(reset($keywords));
    }
}
