<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class EvolutionApiService
{
    private string $baseUrl;
    private string $apiKey;

    public function __construct(string $baseUrl, string $apiKey)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->apiKey = $apiKey;
    }

    /**
     * Criar nova instância na Evolution API
     */
    public function createInstance(string $instanceName, array $config = []): array
    {
        try {
            $response = Http::withHeaders([
                'apikey' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/instance/create", [
                'instanceName' => $instanceName,
                'qrcode' => true,
                'integration' => 'WHATSAPP-BAILEYS',
                'settings' => array_merge([
                    'rejectCall' => true,
                    'msgRetryCounterCache' => true,
                    'userAgent' => 'Laser Link WhatsApp Integration',
                    'alwaysOnline' => false,
                    'readMessages' => true,
                    'readStatus' => true,
                ], $config)
            ]);

            if ($response->successful()) {
                Log::info("Evolution API: Instância '{$instanceName}' criada com sucesso", [
                    'response' => $response->json()
                ]);
                return $response->json();
            }

            Log::error("Evolution API: Erro ao criar instância '{$instanceName}'", [
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            throw new Exception("Erro ao criar instância: " . $response->body());

        } catch (Exception $e) {
            Log::error("Evolution API: Exceção ao criar instância '{$instanceName}'", [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Obter QR Code da instância
     */
    public function getQrCode(string $instanceName): ?string
    {
        try {
            $response = Http::withHeaders([
                'apikey' => $this->apiKey,
            ])->get("{$this->baseUrl}/instance/connect/{$instanceName}");

            if ($response->successful()) {
                $data = $response->json();
                
                Log::info("Evolution API: Resposta do QR Code para '{$instanceName}'", [
                    'response' => $data
                ]);
                
                // Tentar diferentes estruturas de resposta
                if (isset($data['qrcode']['base64'])) {
                    return $data['qrcode']['base64'];
                }
                
                if (isset($data['base64'])) {
                    return $data['base64'];
                }
                
                if (isset($data['qrcode'])) {
                    return $data['qrcode'];
                }
                
                Log::warning("Evolution API: Estrutura de resposta inesperada para QR Code", [
                    'instance' => $instanceName,
                    'response_keys' => array_keys($data)
                ]);
                
                return null;
            }

            Log::warning("Evolution API: Erro ao obter QR Code para '{$instanceName}'", [
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            return null;

        } catch (Exception $e) {
            Log::error("Evolution API: Erro ao obter QR Code para '{$instanceName}'", [
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Verificar status da instância
     */
    public function getInstanceStatus(string $instanceName): array
    {
        try {
            $response = Http::withHeaders([
                'apikey' => $this->apiKey,
            ])->get("{$this->baseUrl}/instance/connectionState/{$instanceName}");

            if ($response->successful()) {
                return $response->json();
            }

            throw new Exception("Erro ao verificar status: " . $response->body());

        } catch (Exception $e) {
            Log::error("Evolution API: Erro ao verificar status da instância '{$instanceName}'", [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Verificar se instância está conectada
     */
    public function checkConnection(string $instanceName): bool
    {
        try {
            $status = $this->getInstanceStatus($instanceName);
            return isset($status['instance']['state']) && $status['instance']['state'] === 'open';
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Testar conexão com a Evolution API
     */
    public function testConnection(): array
    {
        try {
            $response = Http::withHeaders([
                'apikey' => $this->apiKey,
            ])->timeout(10)->get("{$this->baseUrl}/instance/fetchInstances");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Conexão com Evolution API bem-sucedida!',
                    'data' => $response->json()
                ];
            }

            return [
                'success' => false,
                'message' => 'Falha na conexão: ' . $response->body(),
                'status' => $response->status()
            ];

        } catch (Exception $e) {
            Log::error("Evolution API: Erro ao testar conexão", [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Erro de conexão: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Deletar instância
     */
    public function deleteInstance(string $instanceName): bool
    {
        try {
            $response = Http::withHeaders([
                'apikey' => $this->apiKey,
            ])->delete("{$this->baseUrl}/instance/delete/{$instanceName}");

            if ($response->successful()) {
                Log::info("Evolution API: Instância '{$instanceName}' deletada com sucesso");
                return true;
            }

            Log::error("Evolution API: Erro ao deletar instância '{$instanceName}'", [
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            return false;

        } catch (Exception $e) {
            Log::error("Evolution API: Exceção ao deletar instância '{$instanceName}'", [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Enviar mensagem de texto
     */
    public function sendTextMessage(string $instanceName, string $phone, string $message): array
    {
        try {
            \Log::debug('Evolution API: Preparando envio de mensagem', [
                'instance' => $instanceName,
                'phone' => $phone,
                'message_length' => strlen($message),
                'base_url' => $this->baseUrl
            ]);

            $requestUrl = "{$this->baseUrl}/message/sendText/{$instanceName}";
            
            $payload = [
                'number' => $phone,
                'text' => $message,
                'options' => [
                    'delay' => 1200,
                    'presence' => 'composing'
                ]
            ];

            \Log::debug('Evolution API: Enviando requisição', [
                'url' => $requestUrl,
                'payload' => $payload
            ]);

            $response = Http::withHeaders([
                'apikey' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($requestUrl, $payload);

            if ($response->successful()) {
                Log::info("Evolution API: Mensagem enviada para {$phone}", [
                    'instance' => $instanceName,
                    'response' => $response->json()
                ]);
                return $response->json();
            }

            Log::error("Evolution API: Erro ao enviar mensagem para {$phone}", [
                'instance' => $instanceName,
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            throw new Exception("Erro ao enviar mensagem: " . $response->body());

        } catch (Exception $e) {
            Log::error("Evolution API: Exceção ao enviar mensagem para {$phone}", [
                'instance' => $instanceName,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Enviar mensagem com mídia
     */
    public function sendMediaMessage(string $instanceName, string $phone, string $mediaUrl, string $caption = ''): array
    {
        try {
            $response = Http::withHeaders([
                'apikey' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/message/sendMedia/{$instanceName}", [
                'number' => $phone,
                'mediatype' => 'image',
                'media' => $mediaUrl,
                'caption' => $caption,
                'options' => [
                    'delay' => 1200,
                    'presence' => 'composing'
                ]
            ]);

            if ($response->successful()) {
                Log::info("Evolution API: Mídia enviada para {$phone}", [
                    'instance' => $instanceName,
                    'response' => $response->json()
                ]);
                return $response->json();
            }

            Log::error("Evolution API: Erro ao enviar mídia para {$phone}", [
                'instance' => $instanceName,
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            throw new Exception("Erro ao enviar mídia: " . $response->body());

        } catch (Exception $e) {
            Log::error("Evolution API: Exceção ao enviar mídia para {$phone}", [
                'instance' => $instanceName,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Obter informações da instância
     */
    public function getInstanceInfo(string $instanceName): array
    {
        try {
            $response = Http::withHeaders([
                'apikey' => $this->apiKey,
            ])->get("{$this->baseUrl}/instance/fetchInstances");

            if ($response->successful()) {
                $instances = $response->json();
                foreach ($instances as $instance) {
                    if ($instance['instanceName'] === $instanceName) {
                        return $instance;
                    }
                }
            }

            throw new Exception("Instância não encontrada");

        } catch (Exception $e) {
            Log::error("Evolution API: Erro ao obter informações da instância '{$instanceName}'", [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Reiniciar instância
     */
    public function restartInstance(string $instanceName): bool
    {
        try {
            $response = Http::withHeaders([
                'apikey' => $this->apiKey,
            ])->post("{$this->baseUrl}/instance/restart/{$instanceName}");

            if ($response->successful()) {
                Log::info("Evolution API: Instância '{$instanceName}' reiniciada com sucesso");
                return true;
            }

            return false;

        } catch (Exception $e) {
            Log::error("Evolution API: Exceção ao reiniciar instância '{$instanceName}'", [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Formatar número de telefone para o padrão internacional
     */
    public static function formatPhoneNumber(string $phone): string
    {
        // Remove caracteres não numéricos
        $phone = preg_replace('/\D/', '', $phone);
        
        // Se não começar com 55 (Brasil), adicionar
        if (!str_starts_with($phone, '55')) {
            // Se começar com 0, remover
            if (str_starts_with($phone, '0')) {
                $phone = substr($phone, 1);
            }
            $phone = '55' . $phone;
        }
        
        // Adicionar @s.whatsapp.net se não tiver
        if (!str_contains($phone, '@')) {
            $phone .= '@s.whatsapp.net';
        }
        
        return $phone;
    }

    /**
     * Validar se número de telefone é válido
     */
    public static function isValidPhoneNumber(string $phone): bool
    {
        $formatted = self::formatPhoneNumber($phone);
        return preg_match('/^55\d{10,11}@s\.whatsapp\.net$/', $formatted);
    }
}
