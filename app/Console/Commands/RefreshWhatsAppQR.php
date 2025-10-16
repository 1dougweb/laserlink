<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Setting;
use App\Models\WhatsAppInstance;
use App\Services\EvolutionApiService;
use Illuminate\Console\Command;

class RefreshWhatsAppQR extends Command
{
    protected $signature = 'whatsapp:refresh-qr {instance_id}';
    protected $description = 'Refresh WhatsApp QR Code for specific instance';

    public function handle()
    {
        $instanceId = $this->argument('instance_id');
        
        $this->info("Atualizando QR Code para instância ID: {$instanceId}");

        $instance = WhatsAppInstance::find($instanceId);
        
        if (!$instance) {
            $this->error("Instância com ID {$instanceId} não encontrada.");
            return Command::FAILURE;
        }

        $this->info("Instância: {$instance->name}");
        $this->info("Status atual: {$instance->status}");

        try {
            $service = new EvolutionApiService($instance->base_url, $instance->api_key);
            
            // Primeiro, vamos desconectar a instância se estiver conectada
            if ($instance->status === 'connected') {
                $this->info("Desconectando instância...");
                $service->disconnectInstance($instance->instance_name);
                $instance->update(['status' => 'disconnected']);
            }
            
            // Agora vamos obter um novo QR Code
            $this->info("Obtendo novo QR Code...");
            $qrCode = $service->getQrCode($instance->instance_name);
            
            if ($qrCode) {
                $instance->update([
                    'qr_code' => $qrCode,
                    'status' => 'connecting'
                ]);
                
                $this->info("✅ Novo QR Code gerado com sucesso!");
                $this->info("Tamanho: " . strlen($qrCode) . " caracteres");
                $this->line("Agora você pode escanear o QR Code na interface web.");
                return Command::SUCCESS;
            } else {
                $this->error("❌ Falha ao obter novo QR Code.");
                return Command::FAILURE;
            }

        } catch (\Exception $e) {
            $this->error("❌ Erro ao atualizar QR Code: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}

