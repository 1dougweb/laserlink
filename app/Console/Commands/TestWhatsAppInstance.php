<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Setting;
use App\Models\WhatsAppInstance;
use App\Services\EvolutionApiService;
use Illuminate\Console\Command;

class TestWhatsAppInstance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp:test-instance {instance_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test specific WhatsApp instance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $instanceId = $this->argument('instance_id');
        
        $this->info("Testando instância ID: {$instanceId}");

        $instance = WhatsAppInstance::find($instanceId);
        
        if (!$instance) {
            $this->error("Instância não encontrada!");
            return 1;
        }

        $this->info("Nome: {$instance->name}");
        $this->info("Instance Name: {$instance->instance_name}");
        $this->info("Status: {$instance->status}");
        $this->info("Base URL: {$instance->base_url}");

        try {
            $service = new EvolutionApiService($instance->base_url, $instance->api_key);
            
            // Testar status da instância
            $this->info("\n1. Testando status da instância...");
            $status = $service->getInstanceStatus($instance->instance_name);
            $this->info("Status da API: " . json_encode($status, JSON_PRETTY_PRINT));
            
            // Testar QR Code
            $this->info("\n2. Testando QR Code...");
            $qrCode = $service->getQrCode($instance->instance_name);
            if ($qrCode) {
                $this->info("✅ QR Code obtido com sucesso (tamanho: " . strlen($qrCode) . " caracteres)");
                $instance->update(['qr_code' => $qrCode]);
                $this->info("✅ QR Code salvo no banco de dados!");
            } else {
                $this->error("❌ Falha ao obter QR Code");
            }

        } catch (\Exception $e) {
            $this->error("❌ Erro: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
