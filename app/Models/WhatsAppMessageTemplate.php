<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WhatsAppMessageTemplate extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_message_templates';

    protected $fillable = [
        'name',
        'template_type',
        'message_template',
        'variables',
        'is_active',
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Substituir variáveis no template com dados fornecidos
     */
    public function replaceVariables(array $data): string
    {
        $message = $this->message_template;
        
        foreach ($data as $key => $value) {
            $placeholder = '{' . $key . '}';
            $message = str_replace($placeholder, (string) $value, $message);
        }
        
        return $message;
    }

    /**
     * Obter lista de variáveis disponíveis no template
     */
    public function getAvailableVariables(): array
    {
        preg_match_all('/\{([^}]+)\}/', $this->message_template, $matches);
        return $matches[1] ?? [];
    }

    /**
     * Verificar se template tem todas as variáveis necessárias
     */
    public function hasRequiredVariables(array $data): bool
    {
        $requiredVariables = $this->getAvailableVariables();
        
        foreach ($requiredVariables as $variable) {
            if (!isset($data[$variable])) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Obter label do tipo de template
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->template_type) {
            'order_pending' => 'Pedido Pendente',
            'order_confirmed' => 'Pedido Confirmado',
            'order_processing' => 'Pedido Processando',
            'order_shipped' => 'Pedido Enviado',
            'order_delivered' => 'Pedido Entregue',
            'order_cancelled' => 'Pedido Cancelado',
            'promotion_general' => 'Promoção Geral',
            'cart_abandonment' => 'Carrinho Abandonado',
            'welcome_new_customer' => 'Boas-vindas Cliente Novo',
            'custom' => 'Personalizado',
            default => 'Desconhecido'
        };
    }

    /**
     * Obter preview do template com dados de exemplo
     */
    public function getPreview(array $sampleData = []): string
    {
        $defaultData = [
            'customer_name' => 'João Silva',
            'order_number' => '#12345',
            'status' => 'Confirmado',
            'total' => 'R$ 150,00',
            'tracking_code' => 'BR123456789',
            'company_name' => 'Laser Link',
            'product_name' => 'Placa Personalizada',
            'quantity' => '2',
            'date' => now()->format('d/m/Y'),
        ];

        $data = array_merge($defaultData, $sampleData);
        return $this->replaceVariables($data);
    }

    /**
     * Scope para templates ativos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope por tipo
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('template_type', $type);
    }

    /**
     * Buscar template ativo por tipo
     */
    public static function getActiveByType(string $type): ?self
    {
        return self::where('template_type', $type)
                   ->where('is_active', true)
                   ->first();
    }

    /**
     * Obter variáveis padrão disponíveis para todos os templates
     */
    public static function getDefaultVariables(): array
    {
        return [
            'customer_name' => 'Nome do cliente',
            'customer_phone' => 'Telefone do cliente',
            'customer_email' => 'E-mail do cliente',
            'order_number' => 'Número do pedido',
            'order_total' => 'Valor total do pedido',
            'status' => 'Status do pedido',
            'tracking_code' => 'Código de rastreamento',
            'company_name' => 'Nome da empresa',
            'product_name' => 'Nome do produto',
            'quantity' => 'Quantidade',
            'date' => 'Data atual',
            'time' => 'Hora atual',
            'shipping_address' => 'Endereço de entrega',
        ];
    }
}
