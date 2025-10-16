<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\WhatsAppInstance;
use Illuminate\Console\Command;

class CheckQRCode extends Command
{
    protected $signature = 'whatsapp:check-qr {instance_id}';
    protected $description = 'Check QR Code content';

    public function handle()
    {
        $instanceId = $this->argument('instance_id');
        
        $instance = WhatsAppInstance::find($instanceId);
        
        if (!$instance) {
            $this->error("Instância com ID {$instanceId} não encontrada.");
            return Command::FAILURE;
        }

        if (!$instance->qr_code) {
            $this->error("QR Code não encontrado para esta instância.");
            return Command::FAILURE;
        }

        $this->info("=== Análise do QR Code ===");
        $this->line("Tamanho: " . strlen($instance->qr_code) . " caracteres");
        $this->line("Primeiros 50 caracteres: " . substr($instance->qr_code, 0, 50));
        $this->line("Últimos 50 caracteres: " . substr($instance->qr_code, -50));
        
        if (str_starts_with($instance->qr_code, 'data:image/png;base64,')) {
            $this->info("✅ QR Code já tem o prefixo data:image/png;base64,");
        } else {
            $this->warn("⚠️ QR Code não tem o prefixo data:image/png;base64,");
            $this->line("Precisa adicionar o prefixo na view ou no service");
        }

        return Command::SUCCESS;
    }
}

