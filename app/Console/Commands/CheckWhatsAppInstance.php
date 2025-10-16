<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\WhatsAppInstance;
use Illuminate\Console\Command;

class CheckWhatsAppInstance extends Command
{
    protected $signature = 'whatsapp:check-instance {instance_id}';
    protected $description = 'Check WhatsApp instance details';

    public function handle()
    {
        $instanceId = $this->argument('instance_id');
        
        $instance = WhatsAppInstance::find($instanceId);
        
        if (!$instance) {
            $this->error("Instância com ID {$instanceId} não encontrada.");
            return Command::FAILURE;
        }

        $this->info("=== Detalhes da Instância ===");
        $this->line("ID: {$instance->id}");
        $this->line("Nome: {$instance->name}");
        $this->line("Instance Name: {$instance->instance_name}");
        $this->line("Status: {$instance->status}");
        $this->line("QR Code: " . ($instance->qr_code ? 'Presente (' . strlen($instance->qr_code) . ' caracteres)' : 'Ausente'));
        $this->line("Ativo: " . ($instance->is_active ? 'Sim' : 'Não'));
        $this->line("Criado em: {$instance->created_at}");
        $this->line("Atualizado em: {$instance->updated_at}");

        return Command::SUCCESS;
    }
}

