<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;

class TestAPIGeneration extends Command
{
    protected $signature = 'api:test {product_name}';
    protected $description = 'Test API generation for product description';

    public function handle()
    {
        $productName = $this->argument('product_name');
        
        $this->info("Testando geração de API para: '{$productName}'");
        
        // Simular request
        $request = new Request([
            'product_name' => $productName
        ]);
        
        $controller = new AdminController();
        
        try {
            $response = $controller->generateDescription($request);
            $data = json_decode($response->getContent(), true);
            
            $this->info("Resposta da API:");
            $this->line("Success: " . ($data['success'] ? 'true' : 'false'));
            
            if ($data['success']) {
                $this->line("Short Description: " . ($data['short_description'] ?? 'N/A'));
                $this->line("Description: " . substr($data['description'] ?? 'N/A', 0, 100) . '...');
                $this->line("SKU: " . ($data['sku'] ?? 'N/A'));
                $this->line("Meta Title: " . ($data['meta_title'] ?? 'N/A'));
                $this->line("Meta Description: " . ($data['meta_description'] ?? 'N/A'));
                $this->line("Meta Keywords: " . ($data['meta_keywords'] ?? 'N/A'));
                $this->line("SEO Tags: " . ($data['seo_tags'] ?? 'N/A'));
            } else {
                $this->error("Erro: " . ($data['message'] ?? 'Erro desconhecido'));
            }
            
        } catch (\Exception $e) {
            $this->error("Erro na chamada: " . $e->getMessage());
        }
    }
}