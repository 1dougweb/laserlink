<?php

namespace Database\Seeders;

use App\Models\WhatsAppMessageTemplate;
use Illuminate\Database\Seeder;

class WhatsAppTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Pedido Pendente',
                'template_type' => 'order_pending',
                'message_template' => "Olá {customer_name}! 👋\n\nRecebemos seu pedido #{order_number} com sucesso!\n\n📋 *Resumo do pedido:*\n💰 Valor total: {order_total}\n📍 Endereço de entrega: {shipping_address}\n\nEstamos processando seu pedido e em breve você receberá mais atualizações.\n\nObrigado por escolher a {company_name}! 🙏\n\n📅 Data do pedido: {date} às {time}",
                'variables' => ['customer_name', 'order_number', 'order_total', 'shipping_address', 'company_name', 'date', 'time'],
                'is_active' => true,
            ],
            [
                'name' => 'Pedido Confirmado',
                'template_type' => 'order_confirmed',
                'message_template' => "Ótimas notícias, {customer_name}! 🎉\n\nSeu pedido #{order_number} foi *CONFIRMADO* e está sendo preparado!\n\n📋 *Detalhes do pedido:*\n💰 Valor: {order_total}\n📍 Entrega: {shipping_address}\n\nEm breve começaremos a produção dos seus itens. Você receberá atualizações sobre o progresso.\n\nObrigado pela preferência! 😊\n\n📅 Confirmado em: {date} às {time}",
                'variables' => ['customer_name', 'order_number', 'order_total', 'shipping_address', 'date', 'time'],
                'is_active' => true,
            ],
            [
                'name' => 'Pedido Processando',
                'template_type' => 'order_processing',
                'message_template' => "Olá {customer_name}! 🔨\n\nSeu pedido #{order_number} está sendo *PROCESSADO*!\n\n👷 Nossa equipe está trabalhando na produção dos seus itens com muito cuidado e atenção aos detalhes.\n\n📋 *Resumo:*\n💰 Valor: {order_total}\n📍 Entrega: {shipping_address}\n\nEm breve você receberá informações sobre o envio.\n\nAguarde mais algumas informações! 📦",
                'variables' => ['customer_name', 'order_number', 'order_total', 'shipping_address'],
                'is_active' => true,
            ],
            [
                'name' => 'Pedido Enviado',
                'template_type' => 'order_shipped',
                'message_template' => "🎉 {customer_name}, seu pedido #{order_number} foi *ENVIADO*!\n\n📦 Seus itens estão a caminho e devem chegar em breve!\n\n🚚 *Informações do envio:*\n💰 Valor: {order_total}\n📍 Destino: {shipping_address}\n📋 Código de rastreamento: {tracking_code}\n\nVocê pode acompanhar sua entrega usando o código acima.\n\nObrigado por escolher a {company_name}! 🙏",
                'variables' => ['customer_name', 'order_number', 'order_total', 'shipping_address', 'tracking_code', 'company_name'],
                'is_active' => true,
            ],
            [
                'name' => 'Pedido Entregue',
                'template_type' => 'order_delivered',
                'message_template' => "🎊 {customer_name}, seu pedido #{order_number} foi *ENTREGUE*!\n\n📦 Esperamos que esteja satisfeito com sua compra!\n\n💰 Valor pago: {order_total}\n📍 Endereço: {shipping_address}\n\nSe precisar de qualquer coisa ou tiver dúvidas, estamos aqui para ajudar!\n\n🌟 *Avalie sua experiência conosco!*\n\nObrigado por escolher a {company_name}! 😊\n\n📅 Entregue em: {date} às {time}",
                'variables' => ['customer_name', 'order_number', 'order_total', 'shipping_address', 'company_name', 'date', 'time'],
                'is_active' => true,
            ],
            [
                'name' => 'Pedido Cancelado',
                'template_type' => 'order_cancelled',
                'message_template' => "Olá {customer_name}.\n\nInfelizmente seu pedido #{order_number} foi *CANCELADO*.\n\n📋 *Detalhes:*\n💰 Valor: {order_total}\n📍 Endereço: {shipping_address}\n\nPor favor, entre em contato conosco para mais informações sobre o cancelamento.\n\nEstamos à disposição para esclarecer qualquer dúvida.\n\nAtenciosamente,\nEquipe {company_name}",
                'variables' => ['customer_name', 'order_number', 'order_total', 'shipping_address', 'company_name'],
                'is_active' => true,
            ],
            [
                'name' => 'Promoção Geral',
                'template_type' => 'promotion_general',
                'message_template' => "🎉 *PROMOÇÃO ESPECIAL!* 🎉\n\nOlá {customer_name}!\n\nTemos uma oferta incrível especialmente para você!\n\n🔥 *Oferta válida por tempo limitado*\n\n📞 Entre em contato conosco para saber mais detalhes!\n\nNão perca esta oportunidade! 😊\n\n{company_name}",
                'variables' => ['customer_name', 'company_name'],
                'is_active' => true,
            ],
            [
                'name' => 'Carrinho Abandonado',
                'template_type' => 'cart_abandonment',
                'message_template' => "Oi {customer_name}! 👋\n\nVocê deixou {cart_items_count} item(ns) no seu carrinho!\n\n🛒 Que tal finalizar sua compra?\n\nOferecemos:\n✅ Produtos de alta qualidade\n✅ Entrega rápida\n✅ Atendimento especializado\n\n📞 Precisa de ajuda? Estamos aqui!\n\n{company_name}",
                'variables' => ['customer_name', 'cart_items_count', 'company_name'],
                'is_active' => true,
            ],
            [
                'name' => 'Boas-vindas Cliente Novo',
                'template_type' => 'welcome_new_customer',
                'message_template' => "Bem-vindo(a) à {company_name}! 🎉\n\nOlá {customer_name}!\n\nFicamos muito felizes em tê-lo(a) como nosso cliente!\n\n🌟 *O que oferecemos:*\n• Produtos personalizados\n• Qualidade garantida\n• Atendimento especializado\n• Entrega rápida\n\n📞 Entre em contato conosco para conhecer nossos produtos!\n\nSeja muito bem-vindo(a)! 😊",
                'variables' => ['customer_name', 'company_name'],
                'is_active' => true,
            ],
            [
                'name' => 'Template Personalizado',
                'template_type' => 'custom',
                'message_template' => "Olá {customer_name}!\n\nMensagem personalizada da {company_name}.\n\n📅 {date} às {time}\n\nEntre em contato conosco para mais informações!\n\nObrigado! 😊",
                'variables' => ['customer_name', 'company_name', 'date', 'time'],
                'is_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            WhatsAppMessageTemplate::updateOrCreate(
                ['template_type' => $template['template_type']],
                $template
            );
        }

        $this->command->info('Templates WhatsApp criados com sucesso!');
    }
}

