<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class ContactFaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'Qual o prazo de entrega dos produtos?',
                'answer' => 'O prazo de entrega varia de acordo com o tipo de produto e a complexidade do projeto. Em média, produtos padrão levam de 5 a 7 dias úteis. Para produtos personalizados, o prazo pode variar de 7 a 15 dias úteis. Entre em contato conosco para confirmar o prazo específico do seu pedido.'
            ],
            [
                'question' => 'Vocês fazem orçamento sem compromisso?',
                'answer' => 'Sim! Fazemos orçamentos gratuitos e sem compromisso. Você pode solicitar através do nosso formulário de contato, WhatsApp ou visitando nossa loja. Nossa equipe está sempre pronta para ajudar você a encontrar a melhor solução para suas necessidades.'
            ],
            [
                'question' => 'Quais tipos de materiais vocês trabalham?',
                'answer' => 'Trabalhamos com diversos materiais de alta qualidade, incluindo: acrílico, MDF, madeira, metal, vidro, entre outros. Utilizamos tecnologia de corte a laser de precisão para criar produtos personalizados com acabamento impecável. Consulte-nos para saber o melhor material para seu projeto.'
            ],
            [
                'question' => 'É possível personalizar os produtos?',
                'answer' => 'Sim! A personalização é nossa especialidade. Você pode escolher cores, tamanhos, textos, logos e muito mais. Nossa equipe trabalha com você para criar produtos únicos que atendam exatamente suas necessidades. Entre em contato para discutir suas ideias!'
            ],
            [
                'question' => 'Como funciona o processo de pagamento?',
                'answer' => 'Aceitamos diversas formas de pagamento para sua comodidade: cartão de crédito, débito, PIX, transferência bancária e boleto. Para projetos corporativos, também trabalhamos com faturamento. Entre em contato para mais detalhes sobre as opções disponíveis.'
            ],
            [
                'question' => 'Vocês atendem pedidos em grandes quantidades?',
                'answer' => 'Sim! Atendemos tanto pedidos pequenos quanto grandes volumes para empresas e eventos. Oferecemos condições especiais para compras em quantidade. Entre em contato com nossa equipe comercial para receber um orçamento personalizado.'
            ],
            [
                'question' => 'Fazem entrega? Qual a região de atendimento?',
                'answer' => 'Sim, fazemos entregas! Atendemos toda a região metropolitana e também enviamos para todo o Brasil via transportadora. O frete é calculado de acordo com o destino e peso do pedido. Consulte-nos para saber os valores e prazos específicos para sua localização.'
            ],
            [
                'question' => 'Posso visitar a loja para ver os produtos?',
                'answer' => 'Claro! Convidamos você a visitar nossa loja para conhecer nossos produtos e conversar pessoalmente com nossa equipe. Temos diversos produtos em exposição e amostras de materiais. Confira nosso endereço e horário de funcionamento nesta página.'
            ],
            [
                'question' => 'Vocês fazem projetos especiais ou sob medida?',
                'answer' => 'Sim! Somos especializados em projetos personalizados e sob medida. Nossa equipe de designers e profissionais experientes trabalha junto com você para criar soluções únicas. Desde troféus personalizados até projetos de sinalização completa, transformamos suas ideias em realidade.'
            ],
            [
                'question' => 'Como posso acompanhar meu pedido?',
                'answer' => 'Após fazer seu pedido, você receberá atualizações por e-mail e WhatsApp sobre o andamento da produção. Você também pode entrar em contato conosco a qualquer momento para verificar o status. Para pedidos online, você pode acompanhar através da área "Meus Pedidos" no site.'
            ]
        ];

        Setting::updateOrCreate(
            ['key' => 'contact_faq'],
            ['value' => json_encode($faqs)]
        );
        
        $this->command->info('✓ FAQs de contato criadas com sucesso!');
        $this->command->info('  Total de perguntas: ' . count($faqs));
    }
}

