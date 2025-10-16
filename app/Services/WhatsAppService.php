<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Setting;

class WhatsAppService
{
    /**
     * Gera URL do WhatsApp com mensagem personalizada
     */
    public function generateOrderUrl(array $orderData): string
    {
        $whatsappNumber = Setting::get('whatsapp_number', '5511999999999');
        $baseMessage = Setting::get('whatsapp_message', 'Olá! Gostaria de fazer um pedido.');
        
        $message = $this->buildOrderMessage($baseMessage, $orderData);
        $encodedMessage = urlencode($message);
        
        return "https://wa.me/{$whatsappNumber}?text={$encodedMessage}";
    }

    /**
     * Constrói mensagem personalizada com dados do pedido
     */
    private function buildOrderMessage(string $baseMessage, array $orderData): string
    {
        $message = $baseMessage . "\n\n";
        
        // Dados do cliente
        if (isset($orderData['customer_name'])) {
            $message .= "👤 *Cliente:* " . $orderData['customer_name'] . "\n";
        }
        
        if (isset($orderData['customer_phone'])) {
            $message .= "📞 *Telefone:* " . $orderData['customer_phone'] . "\n";
        }
        
        if (isset($orderData['customer_email'])) {
            $message .= "📧 *E-mail:* " . $orderData['customer_email'] . "\n";
        }

        // Endereço de entrega
        if (isset($orderData['shipping_address'])) {
            $message .= "\n📍 *Endereço de Entrega:*\n";
            $message .= $orderData['shipping_address'];
            
            if (isset($orderData['shipping_neighborhood'])) {
                $message .= "\nBairro: " . $orderData['shipping_neighborhood'];
            }
            
            if (isset($orderData['shipping_city'])) {
                $message .= "\nCidade: " . $orderData['shipping_city'];
            }
            
            if (isset($orderData['shipping_state'])) {
                $message .= " - " . $orderData['shipping_state'];
            }
            
            if (isset($orderData['shipping_zip'])) {
                $message .= "\nCEP: " . $orderData['shipping_zip'];
            }
            
            if (isset($orderData['shipping_complement']) && !empty($orderData['shipping_complement'])) {
                $message .= "\nComplemento: " . $orderData['shipping_complement'];
            }
        }

        // Itens do pedido
        if (isset($orderData['items']) && !empty($orderData['items'])) {
            $message .= "\n\n🛒 *Itens do Pedido:*\n";
            
            foreach ($orderData['items'] as $index => $item) {
                $itemNumber = $index + 1;
                $productName = is_array($item) && isset($item['product_name']) ? $item['product_name'] : 'Produto';
                $message .= "\n*{$itemNumber}.* {$productName}\n";
                
                if (isset($item['measurement_description']) && is_string($item['measurement_description'])) {
                    $message .= "   📏 {$item['measurement_description']}\n";
                }
                
                if (isset($item['configuration']) && is_array($item['configuration']) && !empty($item['configuration'])) {
                    $message .= "   ⚙️ *Configurações:*\n";
                    foreach ($item['configuration'] as $key => $value) {
                        $keyStr = is_string($key) ? $key : 'Configuração';
                        $valueStr = is_string($value) || is_numeric($value) ? $value : 'N/A';
                        $message .= "      • {$keyStr}: {$valueStr}\n";
                    }
                }
                
                if (isset($item['quantity']) && is_numeric($item['quantity'])) {
                    $message .= "   📦 Quantidade: {$item['quantity']}\n";
                }
                
                if (isset($item['unit_price']) && is_numeric($item['unit_price'])) {
                    $unitPrice = (float)$item['unit_price'];
                    $message .= "   💰 Preço unitário: R$ " . number_format($unitPrice, 2, ',', '.') . "\n";
                }
                
                if (isset($item['total_price']) && is_numeric($item['total_price'])) {
                    $totalPrice = (float)$item['total_price'];
                    $message .= "   💵 Total: R$ " . number_format($totalPrice, 2, ',', '.') . "\n";
                }
            }
        }

        // Resumo do pedido
        if (isset($orderData['total_amount'])) {
            $totalAmount = is_numeric($orderData['total_amount']) ? (float)$orderData['total_amount'] : 0.0;
            $message .= "\n💰 *Valor Total:* R$ " . number_format($totalAmount, 2, ',', '.');
        }

        // Observações
        if (isset($orderData['notes']) && !empty($orderData['notes'])) {
            $message .= "\n\n📝 *Observações:*\n" . $orderData['notes'];
        }

        // Data do pedido
        $message .= "\n\n📅 *Data do Pedido:* " . now()->format('d/m/Y H:i');

        return $message;
    }

    /**
     * Gera URL para orçamento rápido
     */
    public function generateQuickQuoteUrl(array $quoteData): string
    {
        $whatsappNumber = Setting::get('whatsapp_number', '5511999999999');
        $baseMessage = Setting::get('whatsapp_message', 'Olá! Gostaria de solicitar um orçamento.');
        
        $message = $this->buildQuickQuoteMessage($baseMessage, $quoteData);
        $encodedMessage = urlencode($message);
        
        return "https://wa.me/{$whatsappNumber}?text={$encodedMessage}";
    }

    /**
     * Constrói mensagem para orçamento rápido
     */
    private function buildQuickQuoteMessage(string $baseMessage, array $quoteData): string
    {
        $message = $baseMessage . "\n\n";
        
        // Dados do cliente
        if (isset($quoteData['customer_name'])) {
            $message .= "👤 *Cliente:* " . $quoteData['customer_name'] . "\n";
        }
        
        if (isset($quoteData['customer_phone'])) {
            $message .= "📞 *Telefone:* " . $quoteData['customer_phone'] . "\n";
        }
        
        if (isset($quoteData['customer_email'])) {
            $message .= "📧 *E-mail:* " . $quoteData['customer_email'] . "\n";
        }

        // Detalhes do produto
        if (isset($quoteData['product_type_name'])) {
            $message .= "\n🏷️ *Produto:* " . $quoteData['product_type_name'] . "\n";
        }
        
        if (isset($quoteData['material_name'])) {
            $message .= "🔧 *Material:* " . $quoteData['material_name'] . "\n";
        }

        // Dimensões
        if (isset($quoteData['dimensions'])) {
            $message .= "\n📏 *Dimensões:*\n";
            $message .= "   • Largura: " . $quoteData['dimensions']['width'] . " cm\n";
            $message .= "   • Altura: " . $quoteData['dimensions']['height'] . " cm\n";
            $message .= "   • Espessura: " . $quoteData['dimensions']['thickness'] . " mm\n";
            $area = is_numeric($quoteData['dimensions']['area_m2']) ? (float)$quoteData['dimensions']['area_m2'] : 0.0;
            $message .= "   • Área: " . number_format($area, 4, ',', '.') . " m²\n";
        }

        // Quantidade
        if (isset($quoteData['quantity'])) {
            $message .= "\n📦 *Quantidade:* " . $quoteData['quantity'] . "\n";
        }

        // Preços
        if (isset($quoteData['unit_price'])) {
            $unitPrice = is_numeric($quoteData['unit_price']) ? (float)$quoteData['unit_price'] : 0.0;
            $message .= "\n💰 *Preço Unitário:* R$ " . number_format($unitPrice, 2, ',', '.') . "\n";
        }
        
        if (isset($quoteData['total_price'])) {
            $totalPrice = is_numeric($quoteData['total_price']) ? (float)$quoteData['total_price'] : 0.0;
            $message .= "💵 *Total:* R$ " . number_format($totalPrice, 2, ',', '.') . "\n";
        }

        // Peso
        if (isset($quoteData['total_weight'])) {
            $totalWeight = is_numeric($quoteData['total_weight']) ? (float)$quoteData['total_weight'] : 0.0;
            $message .= "⚖️ *Peso Total:* " . number_format($totalWeight, 2, ',', '.') . " kg\n";
        }

        // Validade
        if (isset($quoteData['valid_until'])) {
            $message .= "\n⏰ *Validade:* " . $quoteData['valid_until'] . "\n";
        }

        // Observações
        if (isset($quoteData['notes']) && !empty($quoteData['notes'])) {
            $message .= "\n📝 *Observações:*\n" . $quoteData['notes'];
        }

        // Data da solicitação
        $message .= "\n\n📅 *Data da Solicitação:* " . now()->format('d/m/Y H:i');

        return $message;
    }

    /**
     * Verifica se o WhatsApp está habilitado
     */
    public function isEnabled(): bool
    {
        return (bool) Setting::get('whatsapp_enabled', true);
    }

    /**
     * Obtém número do WhatsApp configurado
     */
    public function getWhatsAppNumber(): string
    {
        return Setting::get('whatsapp_number', '5511999999999');
    }
}
