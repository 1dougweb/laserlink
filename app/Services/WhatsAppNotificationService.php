<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Models\WhatsAppInstance;
use App\Models\WhatsAppNotification;
use App\Models\WhatsAppMessageTemplate;
use App\Services\EvolutionApiService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Exception;
use Illuminate\Database\Eloquent\Model;

class WhatsAppNotificationService
{
    private ?EvolutionApiService $evolutionApi = null;

    /**
     * Construtor
     */
    public function __construct()
    {
        // Será inicializado sob demanda quando necessário
    }

    /**
     * Verifica se há uma instância ativa do WhatsApp
     */
    public function hasActiveInstance(): bool
    {
        $instance = $this->getInstanceByPurpose('orders');
        return $instance && $instance->isConnected();
    }

    private function initEvolutionApi(WhatsAppInstance $instance): void
    {
        if (!$this->evolutionApi) {
            $this->evolutionApi = new EvolutionApiService($instance->base_url, $instance->api_key);
        }
    }

    /**
     * Enviar notificação de mudança de status do pedido
     */
    public function sendOrderNotification(Order $order, string $templateType): void
    {
        try {
            // Verificar se WhatsApp está habilitado
            $whatsappEnabled = $this->isWhatsAppEnabled();
            Log::info('Verificando status do WhatsApp', [
                'enabled' => $whatsappEnabled,
                'order_id' => $order->id,
                'template_type' => $templateType
            ]);

            if (!$whatsappEnabled) {
                Log::info('WhatsApp desabilitado, notificação não enviada', [
                    'order_id' => $order->id,
                    'template_type' => $templateType
                ]);
                return;
            }

            // Obter instância para pedidos
            $instance = $this->getInstanceByPurpose('orders');
            Log::info('Buscando instância para pedidos', [
                'order_id' => $order->id,
                'instance_found' => (bool)$instance,
                'instance_status' => $instance?->status,
                'instance_name' => $instance?->name
            ]);

            if (!$instance || !$instance->isConnected()) {
                Log::warning('Instância WhatsApp para pedidos não disponível', [
                    'order_id' => $order->id,
                    'instance_status' => $instance?->status
                ]);
                return;
            }

            // Idempotência: evitar envios duplicados próximos no tempo
            if ($this->wasRecentlySent(
                $order->customer_phone,
                'order_status',
                Order::class,
                $order->id,
                now()->subSeconds(90)
            )) {
                Log::warning('Envio WhatsApp evitado por idempotência (order_status recente)', [
                    'order_id' => $order->id,
                    'recipient' => $order->customer_phone,
                    'template_type' => $templateType
                ]);
                return;
            }

            // Obter template (opcional)
            $template = WhatsAppMessageTemplate::getActiveByType($templateType);
            Log::info('Buscando template de mensagem', [
                'order_id' => $order->id,
                'template_type' => $templateType,
                'template_found' => (bool)$template,
                'template_name' => $template?->name
            ]);

            // Preparar dados comuns
            $data = $this->prepareOrderData($order);

            // Montar mensagem final: usa template se válido; caso contrário, usa fallback
            if ($template && $template->hasRequiredVariables($data)) {
                $message = $template->replaceVariables($data);
            } else {
                if (!$template) {
                    Log::warning('Template WhatsApp não encontrado, usando fallback simples', [
                        'order_id' => $order->id,
                        'template_type' => $templateType
                    ]);
                } else {
                    Log::warning('Template inválido (variáveis faltando), usando fallback simples', [
                        'order_id' => $order->id,
                        'template_type' => $templateType,
                        'missing_variables' => array_values(array_diff($template->getAvailableVariables(), array_keys($data)))
                    ]);
                }

                $message = $this->buildFallbackOrderMessage($order);
            }

            // Enviar mensagem
            $this->sendMessage($instance, $order->customer_phone, $order->customer_name, $message, 'order_status', $order);

            Log::info('Notificação de pedido enviada via WhatsApp', [
                'order_id' => $order->id,
                'template_type' => $templateType,
                'recipient' => $order->customer_phone
            ]);

        } catch (Exception $e) {
            Log::error('Erro ao enviar notificação de pedido via WhatsApp', [
                'order_id' => $order->id,
                'template_type' => $templateType,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Verifica se já enviamos notificação semelhante recentemente
     */
    private function wasRecentlySent(
        string $phone,
        string $type,
        string $relatedType,
        int $relatedId,
        \DateTimeInterface $since
    ): bool {
        try {
            return \App\Models\WhatsAppNotification::where('recipient_phone', $this->formatPhoneNumber($phone))
                ->where('notification_type', $type)
                ->where('related_type', $relatedType)
                ->where('related_id', $relatedId)
                ->whereIn('status', ['pending', 'sent'])
                ->where('created_at', '>=', $since)
                ->exists();
        } catch (\Throwable $e) {
            Log::error('Erro na verificação de idempotência', [
                'error' => $e->getMessage()
            ]);
            return false; // Em caso de erro, não bloquear envio
        }
    }

    /**
     * Mensagem fallback quando não há template válido
     */
    private function buildFallbackOrderMessage(Order $order): string
    {
        $lines = [];
        $lines[] = 'Olá ' . trim((string) $order->customer_name) . '! 👋';
        $lines[] = 'Recebemos seu pedido com sucesso.';
        $lines[] = '';
        $lines[] = 'Número do pedido: ' . ($order->order_number ?? '-') ;
        $lines[] = 'Status: ' . ($order->status ?? 'Pendente');
        $lines[] = 'Total: R$ ' . number_format((float) $order->total, 2, ',', '.');
        $address = $this->formatShippingAddress($order);
        if (!empty($address)) {
            $lines[] = 'Entrega: ' . $address;
        }
        $lines[] = '';
        $lines[] = 'Qualquer dúvida, estamos à disposição.';
        $lines[] = config('app.name', 'Laser Link');

        return implode("\n", $lines);
    }

    /**
     * Enviar mensagem promocional
     */
    public function sendPromotionalMessage(array $customerIds, string $message, ?string $mediaUrl = null): void
    {
        try {
            // Verificar se WhatsApp está habilitado
            if (!$this->isWhatsAppEnabled()) {
                Log::info('WhatsApp desabilitado, promoção não enviada');
                return;
            }

            // Obter instância para promoções
            $instance = $this->getInstanceByPurpose('promotions');
            if (!$instance || !$instance->isConnected()) {
                Log::warning('Instância WhatsApp para promoções não disponível');
                return;
            }

            // Obter clientes
            $customers = User::whereIn('id', $customerIds)
                           ->whereNotNull('phone')
                           ->where('phone', '!=', '')
                           ->get();

            if ($customers->isEmpty()) {
                Log::warning('Nenhum cliente válido encontrado para envio de promoção');
                return;
            }

            // Enviar para cada cliente
            foreach ($customers as $customer) {
                $this->sendMessage(
                    $instance,
                    $customer->phone,
                    $customer->name,
                    $message,
                    'promotion',
                    null,
                    $mediaUrl
                );
            }

            Log::info('Mensagens promocionais enviadas via WhatsApp', [
                'customers_count' => $customers->count(),
                'instance' => $instance->name
            ]);

        } catch (Exception $e) {
            Log::error('Erro ao enviar mensagens promocionais via WhatsApp', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Enviar mensagem personalizada
     */
    public function sendCustomMessage(string $phone, string $message, ?string $mediaUrl = null): void
    {
        try {
            // Verificar se WhatsApp está habilitado
            if (!$this->isWhatsAppEnabled()) {
                Log::info('WhatsApp desabilitado, mensagem personalizada não enviada');
                return;
            }

            // Obter instância para suporte (ou primeira disponível)
            $instance = $this->getInstanceByPurpose('support') ?? 
                       $this->getFirstConnectedInstance();
            
            if (!$instance || !$instance->isConnected()) {
                Log::warning('Nenhuma instância WhatsApp disponível para mensagem personalizada');
                return;
            }

            $this->sendMessage($instance, $phone, '', $message, 'custom', null, $mediaUrl);

            Log::info('Mensagem personalizada enviada via WhatsApp', [
                'recipient' => $phone,
                'instance' => $instance->name
            ]);

        } catch (Exception $e) {
            Log::error('Erro ao enviar mensagem personalizada via WhatsApp', [
                'recipient' => $phone,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Obter instância por finalidade
     */
    public function getInstanceByPurpose(string $purpose): ?WhatsAppInstance
    {
        return WhatsAppInstance::active()
                              ->byPurpose($purpose)
                              ->connected()
                              ->first();
    }

    /**
     * Obter primeira instância conectada
     */
    public function getFirstConnectedInstance(): ?WhatsAppInstance
    {
        return WhatsAppInstance::active()
                              ->connected()
                              ->first();
    }

    /**
     * Registrar notificação no histórico
     */
    public function logNotification(WhatsAppInstance $instance, array $data): WhatsAppNotification
    {
        return WhatsAppNotification::create([
            'whatsapp_instance_id' => $instance->id,
            'recipient_phone' => $data['phone'],
            'recipient_name' => $data['name'] ?? '',
            'notification_type' => $data['type'],
            'related_type' => $data['related_type'] ?? null,
            'related_id' => $data['related_id'] ?? null,
            'message' => $data['message'],
            'status' => 'pending',
        ]);
    }

    /**
     * Processar template com dados
     */
    public function processTemplate(WhatsAppMessageTemplate $template, array $data): string
    {
        return $template->replaceVariables($data);
    }

    /**
     * Preparar dados do pedido para template
     */
    private function prepareOrderData(Order $order): array
    {
        return [
            'customer_name' => $order->customer_name,
            'customer_phone' => $order->customer_phone,
            'customer_email' => $order->customer_email,
            'order_number' => $order->order_number,
            'order_total' => 'R$ ' . number_format((float)$order->total, 2, ',', '.'),
            'status' => $order->status ?? 'Pendente',
            'tracking_code' => $order->tracking_code ?? 'Em breve',
            'company_name' => config('app.name', 'Laser Link'),
            'shipping_address' => $this->formatShippingAddress($order),
            'date' => now()->format('d/m/Y'),
            'time' => now()->format('H:i'),
        ];
    }

    /**
     * Formatar endereço de entrega
     */
    private function formatShippingAddress(Order $order): string
    {
        $parts = [];
        
        // Endereço principal
        if (!empty($order->shipping_address)) {
            $parts[] = $order->shipping_address;
        }

        // Bairro
        if (!empty($order->shipping_neighborhood)) {
            $parts[] = $order->shipping_neighborhood;
        }

        // Cidade/Estado
        $cityState = [];
        if (!empty($order->shipping_city)) {
            $cityState[] = $order->shipping_city;
        }
        if (!empty($order->shipping_state)) {
            $cityState[] = $order->shipping_state;
        }
        if (!empty($cityState)) {
            $parts[] = implode('/', $cityState);
        }

        // CEP
        if (!empty($order->shipping_zip)) {
            $parts[] = 'CEP: ' . $order->shipping_zip;
        }
        
        return implode(' - ', array_filter($parts));
    }

    /**
     * Enviar mensagem usando Evolution API
     */
    private function sendMessage(
        WhatsAppInstance $instance,
        string $phone,
        string $name,
        string $message,
        string $type,
        ?Model $related = null,
        ?string $mediaUrl = null
    ): void {
        try {
            \Log::debug('Iniciando envio de mensagem', [
                'instance_id' => $instance->id,
                'instance_name' => $instance->name,
                'phone' => $phone,
                'type' => $type,
                'media_url' => $mediaUrl ?? 'none'
            ]);

            // Inicializar Evolution API Service
            $this->initEvolutionApi($instance);
            
            // Formatar número de telefone
            $formattedPhone = $this->formatPhoneNumber($phone);
            
            \Log::debug('Número formatado', [
                'original' => $phone,
                'formatted' => $formattedPhone
            ]);
            
            // Registrar notificação no histórico
            $notification = $this->logNotification($instance, [
                'phone' => $formattedPhone,
                'name' => $name,
                'type' => $type,
                'message' => $message,
                'related_type' => $related ? get_class($related) : null,
                'related_id' => $related?->id,
            ]);

            // Enviar mensagem
            \Log::debug('Enviando mensagem via Evolution API', [
                'instance_name' => $instance->instance_name,
                'phone' => $formattedPhone,
                'message_length' => strlen($message)
            ]);

            if ($mediaUrl) {
                \Log::debug('Enviando mensagem com mídia', [
                    'media_url' => $mediaUrl
                ]);
                $response = $this->evolutionApi->sendMediaMessage(
                    $instance->instance_name,
                    $formattedPhone,
                    $mediaUrl,
                    $message
                );
            } else {
                $response = $this->evolutionApi->sendTextMessage(
                    $instance->instance_name,
                    $formattedPhone,
                    $message
                );
            }
            
            \Log::debug('Resposta da Evolution API', [
                'response' => $response
            ]);

            // Atualizar status da notificação
            $notification->update([
                'status' => 'sent',
                'sent_at' => now(),
            ]);

        } catch (Exception $e) {
            // Atualizar notificação com erro
            if (isset($notification)) {
                $notification->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);
            }

            Log::error('Erro ao enviar mensagem WhatsApp', [
                'instance' => $instance->name,
                'phone' => $phone,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Verificar se WhatsApp está habilitado
     */
    private function isWhatsAppEnabled(): bool
    {
        return true; // Sempre habilitado para garantir o envio das mensagens
    }

    /**
     * Verificar se notificações estão habilitadas
     */
    private function areNotificationsEnabled(): bool
    {
        // Verificar configuração de notificações
        if (class_exists(\App\Models\Setting::class)) {
            return (bool) \App\Models\Setting::get('whatsapp_notifications_enabled', true);
        }
        
        return true; // Padrão habilitado
    }

    /**
     * Formata número de telefone para o formato esperado pela Evolution API
     */
    private function formatPhoneNumber(string $phone): string
    {
        // Remove todos os caracteres não numéricos
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Se não começar com 55 (Brasil), adiciona
        if (!str_starts_with($phone, '55')) {
            $phone = '55' . $phone;
        }
        
        return $phone;
    }

    /**
     * Enviar notificação de carrinho abandonado
     */
    public function sendCartAbandonmentNotification(User $user, array $cartItems): void
    {
        try {
            if (!$this->areNotificationsEnabled()) {
                return;
            }

            $instance = $this->getInstanceByPurpose('promotions');
            if (!$instance || !$instance->isConnected()) {
                return;
            }

            // Obter template para carrinho abandonado
            $template = WhatsAppMessageTemplate::getActiveByType('cart_abandonment');
            if (!$template) {
                return;
            }

            // Preparar dados
            $data = [
                'customer_name' => $user->name,
                'company_name' => config('app.name', 'Laser Link'),
                'cart_items_count' => count($cartItems),
                'date' => now()->format('d/m/Y'),
            ];

            $message = $template->replaceVariables($data);

            $this->sendMessage($instance, $user->phone, $user->name, $message, 'cart_abandonment');

        } catch (Exception $e) {
            Log::error('Erro ao enviar notificação de carrinho abandonado', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}

