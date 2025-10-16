<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Setting;
use App\Models\WhatsAppInstance;
use App\Services\EvolutionApiService;
use Illuminate\Console\Command;

class DiagnoseWhatsApp extends Command
{
    protected $signature = 'whatsapp:diagnose {instance_id}';
    protected $description = 'Diagnose WhatsApp connection issues';

    public function handle()
    {
        $instanceId = $this->argument('instance_id');
        
        $this->info("=== DIAGNÓSTICO WHATSAPP ===");
        $this->line("");

        $instance = WhatsAppInstance::find($instanceId);
        
        if (!$instance) {
            $this->error("❌ Instância com ID {$instanceId} não encontrada.");
            return Command::FAILURE;
        }

        $this->info("📱 Instância: {$instance->name}");
        $this->info("🔗 Instance Name: {$instance->instance_name}");
        $this->info("🌐 Base URL: {$instance->base_url}");
        $this->info("🔑 API Key: " . (strlen($instance->api_key) > 10 ? substr($instance->api_key, 0, 10) . "..." : "Não configurada"));
        $this->line("");

        try {
            $service = new EvolutionApiService($instance->base_url, $instance->api_key);
            
            // 1. Testar conectividade com a Evolution API
            $this->info("1️⃣ Testando conectividade com Evolution API...");
            $connectionTest = $service->testConnection();
            if ($connectionTest['success']) {
                $this->info("✅ Conectividade com Evolution API: OK");
            } else {
                $this->error("❌ Conectividade com Evolution API: FALHA");
                $this->error("   Erro: " . $connectionTest['message']);
                return Command::FAILURE;
            }
            $this->line("");

            // 2. Verificar status da instância
            $this->info("2️⃣ Verificando status da instância...");
            $status = $service->getInstanceStatus($instance->instance_name);
            $this->info("   Status da API: " . json_encode($status, JSON_PRETTY_PRINT));
            
            if (isset($status['instance']['state'])) {
                $state = $status['instance']['state'];
                $this->info("   Estado da instância: {$state}");
                
                switch ($state) {
                    case 'open':
                        $this->info("✅ Instância está CONECTADA");
                        break;
                    case 'connecting':
                        $this->info("🔄 Instância está CONECTANDO - QR Code deve estar disponível");
                        break;
                    case 'close':
                        $this->info("❌ Instância está FECHADA - Precisa gerar novo QR Code");
                        break;
                    default:
                        $this->warn("⚠️ Estado desconhecido: {$state}");
                        break;
                }
            }
            $this->line("");

            // 3. Verificar QR Code
            $this->info("3️⃣ Verificando QR Code...");
            if ($instance->qr_code) {
                $this->info("✅ QR Code presente no banco de dados");
                $this->info("   Tamanho: " . strlen($instance->qr_code) . " caracteres");
                
                if (str_starts_with($instance->qr_code, 'data:image/png;base64,')) {
                    $this->info("✅ Formato do QR Code: Correto");
                } else {
                    $this->warn("⚠️ Formato do QR Code: Pode estar incorreto");
                }
            } else {
                $this->error("❌ QR Code não encontrado no banco de dados");
            }
            $this->line("");

            // 4. Tentar obter QR Code fresco
            $this->info("4️⃣ Tentando obter QR Code fresco...");
            $freshQrCode = $service->getQrCode($instance->instance_name);
            if ($freshQrCode) {
                $this->info("✅ QR Code fresco obtido com sucesso");
                $this->info("   Tamanho: " . strlen($freshQrCode) . " caracteres");
                
                // Atualizar no banco
                $instance->update(['qr_code' => $freshQrCode]);
                $this->info("✅ QR Code atualizado no banco de dados");
            } else {
                $this->error("❌ Não foi possível obter QR Code fresco");
            }
            $this->line("");

            // 5. Instruções de solução
            $this->info("5️⃣ INSTRUÇÕES PARA CONEXÃO:");
            $this->line("   📱 Abra o WhatsApp no seu celular");
            $this->line("   🔍 Vá em Configurações > Aparelhos conectados");
            $this->line("   ➕ Toque em 'Conectar um aparelho'");
            $this->line("   📷 Escaneie o QR Code que aparece na tela");
            $this->line("   ⏱️ O QR Code expira em alguns minutos");
            $this->line("   🔄 Se expirar, use o botão 'Atualizar QR Code'");
            $this->line("");

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("❌ Erro durante diagnóstico: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}

