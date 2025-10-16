<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Setting;
use App\Services\EvolutionApiService;
use Illuminate\Console\Command;

class TestWhatsAppConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp:test-connection';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test WhatsApp Evolution API connection';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testando conexão com Evolution API...');

        $baseUrl = Setting::get('evolution_api_base_url');
        $apiKey = Setting::get('evolution_api_key');

        if (!$baseUrl || !$apiKey) {
            $this->error('Configurações da Evolution API não encontradas!');
            $this->info('Configure em: Admin > WhatsApp > Configurações');
            return 1;
        }

        $this->info("Base URL: {$baseUrl}");
        $this->info("API Key: " . substr($apiKey, 0, 10) . "...");

        try {
            $service = new EvolutionApiService($baseUrl, $apiKey);
            $result = $service->testConnection();

            if ($result['success']) {
                $this->info('✅ ' . $result['message']);
                
                if (isset($result['data']['instance'])) {
                    $this->info('Instâncias encontradas: ' . count($result['data']['instance']));
                    foreach ($result['data']['instance'] as $instance) {
                        $this->info("- {$instance['instance']['instanceName']} ({$instance['instance']['state']})");
                    }
                }
            } else {
                $this->error('❌ ' . $result['message']);
            }

        } catch (\Exception $e) {
            $this->error('❌ Erro: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}