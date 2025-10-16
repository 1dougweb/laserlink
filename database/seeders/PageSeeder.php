<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            [
                'title' => 'Política de Privacidade',
                'slug' => 'politica-de-privacidade',
                'content' => $this->getPoliticaPrivacidade(),
                'is_active' => true,
                'meta_title' => 'Política de Privacidade - Laser Link',
                'meta_description' => 'Conheça nossa política de privacidade e como protegemos seus dados pessoais.',
            ],
            [
                'title' => 'Termos de Uso',
                'slug' => 'termos-de-uso',
                'content' => $this->getTermosUso(),
                'is_active' => true,
                'meta_title' => 'Termos de Uso - Laser Link',
                'meta_description' => 'Leia nossos termos e condições de uso da plataforma.',
            ],
            [
                'title' => 'Política de Troca e Devolução',
                'slug' => 'politica-de-troca-e-devolucao',
                'content' => $this->getPoliticaTrocaDevolucao(),
                'is_active' => true,
                'meta_title' => 'Política de Troca e Devolução - Laser Link',
                'meta_description' => 'Saiba como realizar trocas e devoluções de produtos.',
            ],
            [
                'title' => 'Política de Entrega',
                'slug' => 'politica-de-entrega',
                'content' => $this->getPoliticaEntrega(),
                'is_active' => true,
                'meta_title' => 'Política de Entrega - Laser Link',
                'meta_description' => 'Informações sobre prazos, custos e formas de entrega.',
            ],
            [
                'title' => 'Sobre Nós',
                'slug' => 'sobre-nos',
                'content' => $this->getSobreNos(),
                'is_active' => true,
                'meta_title' => 'Sobre Nós - Laser Link',
                'meta_description' => 'Conheça a história e os valores da Laser Link.',
            ],
            [
                'title' => 'Perguntas Frequentes',
                'slug' => 'perguntas-frequentes',
                'content' => $this->getFaq(),
                'is_active' => true,
                'meta_title' => 'Perguntas Frequentes (FAQ) - Laser Link',
                'meta_description' => 'Tire suas dúvidas sobre nossos produtos e serviços.',
            ],
        ];

        foreach ($pages as $pageData) {
            Page::updateOrCreate(
                ['slug' => $pageData['slug']],
                $pageData
            );
        }

        $this->command->info('Páginas criadas com sucesso!');
    }

    private function getPoliticaPrivacidade(): string
    {
        return <<<'HTML'
<h1>Política de Privacidade</h1>

<p><strong>Última atualização:</strong> Outubro de 2024</p>

<p>A <strong>Laser Link</strong> (CNPJ: 50.671.659/0001-48) está comprometida em proteger a privacidade e os dados pessoais de seus clientes. Esta Política de Privacidade descreve como coletamos, usamos, armazenamos e protegemos suas informações.</p>

<h2>1. Informações que Coletamos</h2>

<h3>1.1 Informações Fornecidas por Você</h3>
<ul>
    <li>Nome completo</li>
    <li>CPF ou CNPJ</li>
    <li>Endereço de e-mail</li>
    <li>Telefone e WhatsApp</li>
    <li>Endereço de entrega e cobrança</li>
    <li>Informações de pagamento (processadas por gateway seguro)</li>
</ul>

<h3>1.2 Informações Coletadas Automaticamente</h3>
<ul>
    <li>Endereço IP</li>
    <li>Tipo de navegador</li>
    <li>Páginas visitadas</li>
    <li>Data e hora de acesso</li>
    <li>Cookies e tecnologias similares</li>
</ul>

<h2>2. Como Usamos Suas Informações</h2>

<p>Utilizamos suas informações pessoais para:</p>
<ul>
    <li>Processar e gerenciar seus pedidos</li>
    <li>Comunicar sobre o status dos pedidos</li>
    <li>Enviar notificações importantes</li>
    <li>Melhorar nossos produtos e serviços</li>
    <li>Personalizar sua experiência de compra</li>
    <li>Cumprir obrigações legais e regulatórias</li>
    <li>Prevenir fraudes e garantir a segurança</li>
</ul>

<h2>3. Compartilhamento de Informações</h2>

<p>Não vendemos suas informações pessoais a terceiros. Compartilhamos dados apenas quando necessário:</p>
<ul>
    <li><strong>Transportadoras:</strong> Para entrega dos produtos</li>
    <li><strong>Gateways de pagamento:</strong> Para processar transações</li>
    <li><strong>Autoridades legais:</strong> Quando exigido por lei</li>
    <li><strong>Prestadores de serviços:</strong> Que nos auxiliam nas operações (sob contrato de confidencialidade)</li>
</ul>

<h2>4. Segurança dos Dados</h2>

<p>Implementamos medidas de segurança técnicas e organizacionais para proteger suas informações:</p>
<ul>
    <li>Criptografia SSL/TLS</li>
    <li>Armazenamento seguro em servidores protegidos</li>
    <li>Acesso restrito aos dados pessoais</li>
    <li>Monitoramento regular de segurança</li>
</ul>

<h2>5. Seus Direitos (LGPD)</h2>

<p>De acordo com a Lei Geral de Proteção de Dados (LGPD), você tem direito a:</p>
<ul>
    <li>Confirmar a existência de tratamento de dados</li>
    <li>Acessar seus dados pessoais</li>
    <li>Corrigir dados incompletos ou desatualizados</li>
    <li>Solicitar anonimização ou exclusão de dados</li>
    <li>Revogar consentimento</li>
    <li>Portabilidade dos dados</li>
</ul>

<p>Para exercer seus direitos, entre em contato conosco através do e-mail: <strong>privacidade@laserlink.com.br</strong></p>

<h2>6. Cookies</h2>

<p>Utilizamos cookies para melhorar sua experiência de navegação. Você pode configurar seu navegador para recusar cookies, mas isso pode afetar algumas funcionalidades do site.</p>

<h2>7. Retenção de Dados</h2>

<p>Mantemos seus dados pessoais pelo tempo necessário para cumprir as finalidades descritas nesta política, exceto quando um período de retenção mais longo for exigido por lei.</p>

<h2>8. Alterações nesta Política</h2>

<p>Podemos atualizar esta Política de Privacidade periodicamente. Notificaremos sobre alterações significativas através do site ou por e-mail.</p>

<h2>9. Contato</h2>

<p>Para dúvidas sobre esta Política de Privacidade:</p>
<p>
    <strong>Laser Link</strong><br>
    CNPJ: 50.671.659/0001-48<br>
    E-mail: privacidade@laserlink.com.br
</p>
HTML;
    }

    private function getTermosUso(): string
    {
        return <<<'HTML'
<h1>Termos de Uso</h1>

<p><strong>Última atualização:</strong> Outubro de 2024</p>

<p>Bem-vindo à <strong>Laser Link</strong> (CNPJ: 50.671.659/0001-48). Ao acessar e usar nosso site, você concorda com os seguintes termos e condições.</p>

<h2>1. Aceitação dos Termos</h2>

<p>Ao acessar e utilizar este site, você aceita estar vinculado a estes Termos de Uso e à nossa Política de Privacidade. Se você não concordar com qualquer parte destes termos, não deverá usar nosso site.</p>

<h2>2. Uso do Site</h2>

<h3>2.1 Elegibilidade</h3>
<p>Você deve ter pelo menos 18 anos ou ter a permissão de seus pais ou responsáveis para usar este site.</p>

<h3>2.2 Conta de Usuário</h3>
<p>Para realizar compras, você precisará criar uma conta. Você é responsável por:</p>
<ul>
    <li>Manter a confidencialidade de sua senha</li>
    <li>Todas as atividades realizadas em sua conta</li>
    <li>Notificar-nos imediatamente sobre uso não autorizado</li>
    <li>Fornecer informações verdadeiras e atualizadas</li>
</ul>

<h3>2.3 Uso Proibido</h3>
<p>Você concorda em NÃO:</p>
<ul>
    <li>Usar o site para fins ilegais</li>
    <li>Tentar obter acesso não autorizado</li>
    <li>Interferir no funcionamento do site</li>
    <li>Enviar vírus ou código malicioso</li>
    <li>Coletar dados de outros usuários</li>
    <li>Reproduzir ou copiar conteúdo sem autorização</li>
</ul>

<h2>3. Produtos e Serviços</h2>

<h3>3.1 Descrição</h3>
<p>Fazemos o possível para descrever nossos produtos com precisão. No entanto, não garantimos que descrições, preços ou outros conteúdos estejam completos, atuais ou livres de erros.</p>

<h3>3.2 Preços</h3>
<ul>
    <li>Preços estão sujeitos a alteração sem aviso prévio</li>
    <li>Preços podem variar de acordo com personalização</li>
    <li>Impostos e frete são calculados no checkout</li>
    <li>Reservamos o direito de corrigir erros de precificação</li>
</ul>

<h3>3.3 Disponibilidade</h3>
<p>A disponibilidade de produtos está sujeita a estoque. Reservamos o direito de limitar quantidades ou descontinuar produtos.</p>

<h2>4. Pedidos e Pagamentos</h2>

<h3>4.1 Processamento de Pedidos</h3>
<p>Todos os pedidos estão sujeitos a aceitação. Podemos recusar ou cancelar pedidos por:</p>
<ul>
    <li>Indisponibilidade de produtos</li>
    <li>Erros no preço ou descrição</li>
    <li>Suspeita de fraude</li>
    <li>Problemas com informações de pagamento ou entrega</li>
</ul>

<h3>4.2 Formas de Pagamento</h3>
<p>Aceitamos cartões de crédito, PIX e outras formas de pagamento indicadas no site. O pagamento é processado por gateways seguros.</p>

<h2>5. Entrega</h2>

<p>As condições de entrega estão descritas em nossa <a href="/politica-de-entrega">Política de Entrega</a>.</p>

<h2>6. Trocas e Devoluções</h2>

<p>Consulte nossa <a href="/politica-de-troca-e-devolucao">Política de Troca e Devolução</a> para informações detalhadas.</p>

<h2>7. Propriedade Intelectual</h2>

<p>Todo o conteúdo deste site (textos, imagens, logos, gráficos) é propriedade da Laser Link e está protegido por leis de direitos autorais. É proibida a reprodução sem autorização expressa.</p>

<h2>8. Limitação de Responsabilidade</h2>

<p>A Laser Link não será responsável por:</p>
<ul>
    <li>Danos indiretos ou consequentes</li>
    <li>Perda de lucros ou dados</li>
    <li>Interrupções no serviço</li>
    <li>Erros ou omissões no conteúdo</li>
</ul>

<h2>9. Modificações dos Termos</h2>

<p>Reservamos o direito de modificar estes termos a qualquer momento. As alterações entrarão em vigor imediatamente após a publicação no site.</p>

<h2>10. Lei Aplicável</h2>

<p>Estes termos são regidos pelas leis brasileiras. Disputas serão resolvidas no foro da comarca de nossa sede.</p>

<h2>11. Contato</h2>

<p>Para dúvidas sobre estes Termos de Uso:</p>
<p>
    <strong>Laser Link</strong><br>
    CNPJ: 50.671.659/0001-48<br>
    E-mail: contato@laserlink.com.br
</p>
HTML;
    }

    private function getPoliticaTrocaDevolucao(): string
    {
        return <<<'HTML'
<h1>Política de Troca e Devolução</h1>

<p><strong>Última atualização:</strong> Outubro de 2024</p>

<p>Na <strong>Laser Link</strong> (CNPJ: 50.671.659/0001-48), queremos que você fique satisfeito com sua compra. Esta política descreve as condições para trocas e devoluções.</p>

<h2>1. Direito de Arrependimento (CDC - Código de Defesa do Consumidor)</h2>

<p>De acordo com o Art. 49 do Código de Defesa do Consumidor, você tem o direito de desistir da compra em até <strong>7 (sete) dias corridos</strong> após o recebimento do produto, sem necessidade de justificativa.</p>

<h3>Condições:</h3>
<ul>
    <li>O produto deve estar em sua embalagem original</li>
    <li>Sem sinais de uso</li>
    <li>Com todos os acessórios e manuais</li>
    <li>Nota fiscal anexada</li>
</ul>

<h2>2. Produtos com Defeito ou Avaria</h2>

<h3>2.1 Produtos com Defeito de Fabricação</h3>
<p>Se o produto apresentar defeito de fabricação, você pode:</p>
<ul>
    <li>Solicitar troca por produto igual</li>
    <li>Solicitar troca por outro produto de valor equivalente</li>
    <li>Solicitar reembolso total</li>
</ul>

<p><strong>Prazo:</strong> Até 30 dias após o recebimento</p>

<h3>2.2 Produtos Danificados no Transporte</h3>
<p>Se o produto chegar danificado:</p>
<ul>
    <li>Não aceite a entrega ou registre a avaria com o entregador</li>
    <li>Entre em contato conosco em até 24 horas</li>
    <li>Envie fotos do produto e da embalagem</li>
    <li>Providenciaremos a troca sem custo adicional</li>
</ul>

<h2>3. Produtos Personalizados</h2>

<p><strong>IMPORTANTE:</strong> Produtos personalizados (com gravações, cortes especiais, cores customizadas, etc.) <strong>NÃO</strong> podem ser trocados ou devolvidos, exceto em caso de:</p>
<ul>
    <li>Defeito de fabricação</li>
    <li>Erro na personalização por parte da Laser Link</li>
    <li>Divergência com o pedido aprovado</li>
</ul>

<h2>4. Como Solicitar Troca ou Devolução</h2>

<h3>Passo 1: Entre em Contato</h3>
<p>Envie um e-mail para <strong>trocas@laserlink.com.br</strong> com:</p>
<ul>
    <li>Número do pedido</li>
    <li>Motivo da troca/devolução</li>
    <li>Fotos do produto (se aplicável)</li>
</ul>

<h3>Passo 2: Aguarde Aprovação</h3>
<p>Nossa equipe analisará sua solicitação em até 2 dias úteis e enviará as instruções.</p>

<h3>Passo 3: Envie o Produto</h3>
<p>Após aprovação, envie o produto conforme orientações:</p>
<ul>
    <li>Embalagem original ou equivalente</li>
    <li>Produto protegido adequadamente</li>
    <li>Código de postagem que forneceremos (se a troca for por nossa responsabilidade)</li>
</ul>

<h3>Passo 4: Recebimento e Processamento</h3>
<p>Ao recebermos o produto:</p>
<ul>
    <li>Verificaremos as condições</li>
    <li>Processaremos a troca ou reembolso em até 5 dias úteis</li>
</ul>

<h2>5. Reembolso</h2>

<h3>5.1 Prazo de Reembolso</h3>
<ul>
    <li><strong>Cartão de crédito:</strong> Estorno em até 2 faturas</li>
    <li><strong>PIX/Transferência:</strong> Até 5 dias úteis após aprovação</li>
    <li><strong>Boleto:</strong> Até 10 dias úteis (informe dados bancários)</li>
</ul>

<h3>5.2 Valor do Reembolso</h3>
<p>Inclui:</p>
<ul>
    <li>Valor do produto</li>
    <li>Frete pago (se a devolução for por nossa responsabilidade)</li>
</ul>

<h2>6. Custos de Envio</h2>

<table>
    <thead>
        <tr>
            <th>Situação</th>
            <th>Responsável pelo Frete</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Defeito de fabricação</td>
            <td>Laser Link</td>
        </tr>
        <tr>
            <td>Produto errado enviado</td>
            <td>Laser Link</td>
        </tr>
        <tr>
            <td>Avaria no transporte</td>
            <td>Laser Link</td>
        </tr>
        <tr>
            <td>Arrependimento (CDC)</td>
            <td>Cliente</td>
        </tr>
        <tr>
            <td>Desistência sem justificativa</td>
            <td>Cliente</td>
        </tr>
    </tbody>
</table>

<h2>7. Produtos Não Elegíveis para Troca/Devolução</h2>

<p>Não aceitamos devolução de:</p>
<ul>
    <li>Produtos personalizados (exceto defeito)</li>
    <li>Produtos com embalagem violada</li>
    <li>Produtos com sinais de uso</li>
    <li>Produtos higienizáveis que foram abertos</li>
    <li>Produtos fora do prazo estabelecido</li>
</ul>

<h2>8. Garantia</h2>

<p>Todos os produtos possuem:</p>
<ul>
    <li><strong>Garantia legal:</strong> 90 dias (CDC)</li>
    <li><strong>Garantia do fabricante:</strong> Quando aplicável, conforme especificado</li>
</ul>

<h2>9. Contato</h2>

<p>Para solicitar troca ou devolução:</p>
<p>
    <strong>Laser Link</strong><br>
    CNPJ: 50.671.659/0001-48<br>
    E-mail: trocas@laserlink.com.br<br>
    WhatsApp: [Insira número]
</p>

<p><em>Estamos à disposição para garantir sua satisfação!</em></p>
HTML;
    }

    private function getPoliticaEntrega(): string
    {
        return <<<'HTML'
<h1>Política de Entrega</h1>

<p><strong>Última atualização:</strong> Outubro de 2024</p>

<p>A <strong>Laser Link</strong> (CNPJ: 50.671.659/0001-48) trabalha para entregar seus pedidos com rapidez e segurança. Confira abaixo as informações sobre prazos, custos e formas de entrega.</p>

<h2>1. Prazo de Processamento</h2>

<p>Antes do envio, seu pedido passa por:</p>

<h3>1.1 Produtos em Estoque</h3>
<ul>
    <li>Processamento: 1 a 2 dias úteis</li>
    <li>Separação e embalagem</li>
    <li>Emissão de nota fiscal</li>
</ul>

<h3>1.2 Produtos Personalizados</h3>
<ul>
    <li>Processamento: 3 a 10 dias úteis (varia conforme complexidade)</li>
    <li>Produção sob demanda</li>
    <li>Aprovação de arte (quando necessário)</li>
</ul>

<p><strong>Importante:</strong> O prazo de entrega começa a contar APÓS o processamento e aprovação do pagamento.</p>

<h2>2. Formas de Entrega</h2>

<h3>2.1 Correios (PAC)</h3>
<ul>
    <li>Prazo: 8 a 15 dias úteis (após processamento)</li>
    <li>Rastreamento incluso</li>
    <li>Entrega em todo Brasil</li>
    <li>Frete calculado por CEP</li>
</ul>

<h3>2.2 Correios (SEDEX)</h3>
<ul>
    <li>Prazo: 3 a 7 dias úteis (após processamento)</li>
    <li>Rastreamento incluso</li>
    <li>Entrega em todo Brasil</li>
    <li>Frete calculado por CEP</li>
</ul>

<h3>2.3 Transportadora</h3>
<ul>
    <li>Prazo: Varia por região (5 a 15 dias úteis)</li>
    <li>Para produtos grandes ou pedidos volumosos</li>
    <li>Rastreamento disponível</li>
    <li>Necessário CPF/CNPJ para emissão de nota</li>
</ul>

<h3>2.4 Retirada na Loja</h3>
<ul>
    <li>Prazo: Disponível após processamento</li>
    <li><strong>SEM CUSTO DE FRETE</strong></li>
    <li>Notificação por e-mail/WhatsApp quando pronto</li>
    <li>Retirada em horário comercial</li>
</ul>

<h2>3. Cálculo do Frete</h2>

<p>O valor do frete é calculado automaticamente no carrinho com base em:</p>
<ul>
    <li>CEP de destino</li>
    <li>Peso e dimensões do produto</li>
    <li>Modalidade de envio escolhida</li>
</ul>

<h3>3.1 Frete Grátis</h3>
<p>Oferecemos frete grátis para:</p>
<ul>
    <li>Pedidos acima de R$ 500,00 (válido para algumas regiões)</li>
    <li>Promoções específicas (consulte o site)</li>
    <li>Retirada na loja (sempre gratuito)</li>
</ul>

<h2>4. Rastreamento do Pedido</h2>

<p>Após o envio, você receberá:</p>
<ul>
    <li>E-mail com código de rastreamento</li>
    <li>Link para acompanhar o status</li>
    <li>Atualizações via WhatsApp (se autorizado)</li>
</ul>

<p>Você também pode rastrear pelo site em: <strong>Minha Conta > Meus Pedidos</strong></p>

<h2>5. Áreas de Entrega</h2>

<h3>5.1 Atendemos</h3>
<ul>
    <li>Todo o território nacional</li>
    <li>Áreas urbanas e rurais (conforme alcance dos Correios)</li>
</ul>

<h3>5.2 Regiões Remotas</h3>
<p>Para áreas de difícil acesso:</p>
<ul>
    <li>Prazo de entrega pode ser estendido</li>
    <li>Taxas adicionais podem ser aplicadas</li>
    <li>Consulte disponibilidade no checkout</li>
</ul>

<h2>6. Problemas na Entrega</h2>

<h3>6.1 Ausência no Recebimento</h3>
<p>Se não houver ninguém no endereço:</p>
<ul>
    <li>Correios/transportadora deixará aviso</li>
    <li>Você pode agendar nova entrega</li>
    <li>Ou retirar no posto dos Correios/filial da transportadora</li>
    <li>Prazo para retirada: 7 dias (após retorna ao remetente)</li>
</ul>

<h3>6.2 Endereço Incorreto</h3>
<ul>
    <li>Verifique cuidadosamente os dados no checkout</li>
    <li>Após o envio, alterações têm custo adicional</li>
    <li>Entre em contato imediatamente se houver erro</li>
</ul>

<h3>6.3 Extravio ou Atraso</h3>
<p>Se o pedido não chegar no prazo:</p>
<ul>
    <li>Aguarde 3 dias úteis além do prazo estimado</li>
    <li>Entre em contato: entrega@laserlink.com.br</li>
    <li>Abriremos chamado junto à transportadora</li>
    <li>Reenviaremos ou reembolsaremos se confirmado extravio</li>
</ul>

<h2>7. Recebimento do Pedido</h2>

<h3>Ao Receber, Verifique:</h3>
<ul>
    <li>Estado da embalagem (sem avarias)</li>
    <li>Conteúdo conforme pedido</li>
    <li>Qualidade dos produtos</li>
</ul>

<h3>Produto Avariado:</h3>
<ul>
    <li>Não aceite a entrega ou registre ocorrência com entregador</li>
    <li>Tire fotos da embalagem e produto</li>
    <li>Entre em contato em até 24 horas</li>
    <li>Providenciaremos substituição sem custo</li>
</ul>

<h2>8. Nota Fiscal</h2>

<p>Todos os pedidos incluem:</p>
<ul>
    <li>Nota Fiscal Eletrônica (NF-e)</li>
    <li>Enviada por e-mail após emissão</li>
    <li>Via impressa dentro da embalagem</li>
    <li>DANFE para acompanhar o produto</li>
</ul>

<h2>9. Horário de Entrega</h2>

<ul>
    <li><strong>Correios:</strong> Seg a Sex, horário comercial</li>
    <li><strong>Transportadoras:</strong> Seg a Sex, 8h às 18h (agendamento pode ser necessário)</li>
    <li><strong>Retirada na loja:</strong> Seg a Sex, 8h às 18h | Sáb, 8h às 12h</li>
</ul>

<h2>10. Produtos Grandes ou Pesados</h2>

<p>Para itens volumosos:</p>
<ul>
    <li>Entrega por transportadora especializada</li>
    <li>Agendamento prévio obrigatório</li>
    <li>Entrega no térreo (descarregamento)</li>
    <li>Içamento ou transporte interno não inclusos</li>
</ul>

<h2>11. Contato</h2>

<p>Dúvidas sobre entrega?</p>
<p>
    <strong>Laser Link</strong><br>
    CNPJ: 50.671.659/0001-48<br>
    E-mail: entrega@laserlink.com.br<br>
    WhatsApp: [Insira número]
</p>

<p><em>Trabalhamos para que seu pedido chegue com rapidez e segurança!</em></p>
HTML;
    }

    private function getSobreNos(): string
    {
        return <<<'HTML'
<h1>Sobre a Laser Link</h1>

<p>Bem-vindo à <strong>Laser Link</strong>, sua parceira em soluções de comunicação visual e personalização!</p>

<h2>Nossa História</h2>

<p>Fundada com o propósito de transformar ideias em realidade, a <strong>Laser Link</strong> (CNPJ: 50.671.659/0001-48) é especializada em comunicação visual, corte e gravação a laser, troféus, placas, brindes personalizados e muito mais.</p>

<p>Ao longo dos anos, consolidamos nossa posição no mercado através da qualidade de nossos produtos, atendimento personalizado e compromisso com a satisfação de nossos clientes.</p>

<h2>Nossa Missão</h2>

<p>Proporcionar soluções criativas e de alta qualidade em comunicação visual e personalização, superando as expectativas de nossos clientes através da inovação, tecnologia e atendimento diferenciado.</p>

<h2>Nossa Visão</h2>

<p>Ser referência nacional em comunicação visual e personalização, reconhecida pela excelência em produtos e serviços, inovação constante e compromisso com a satisfação do cliente.</p>

<h2>Nossos Valores</h2>

<ul>
    <li><strong>Qualidade:</strong> Produtos e serviços que superam expectativas</li>
    <li><strong>Inovação:</strong> Tecnologia de ponta e soluções criativas</li>
    <li><strong>Compromisso:</strong> Prazos e promessas cumpridas</li>
    <li><strong>Atendimento:</strong> Personalizado e focado no cliente</li>
    <li><strong>Sustentabilidade:</strong> Responsabilidade ambiental e social</li>
    <li><strong>Ética:</strong> Transparência e honestidade em todas as relações</li>
</ul>

<h2>O Que Fazemos</h2>

<h3>Corte e Gravação a Laser</h3>
<p>Utilizamos tecnologia laser de última geração para cortar e gravar em diversos materiais como acrílico, MDF, madeira, metal, tecido e muito mais. Precisão e qualidade em cada detalhe.</p>

<h3>Comunicação Visual</h3>
<p>Placas, letreiros, painéis, fachadas e sinalização. Soluções completas para destacar sua marca e negócio.</p>

<h3>Troféus e Medalhas</h3>
<p>Reconhecimento e premiação com produtos personalizados de alta qualidade para eventos corporativos, esportivos e acadêmicos.</p>

<h3>Brindes Personalizados</h3>
<p>Amplo catálogo de brindes corporativos, chaveiros, canetas, canecas e itens promocionais com personalização exclusiva.</p>

<h3>Presentes Personalizados</h3>
<p>Crie presentes únicos e especiais para datas comemorativas, casamentos, aniversários e outras ocasiões especiais.</p>

<h2>Nossa Estrutura</h2>

<p>Contamos com:</p>
<ul>
    <li>Equipamentos de corte e gravação laser de alta precisão</li>
    <li>Equipe técnica especializada e qualificada</li>
    <li>Designers para criação e desenvolvimento de artes</li>
    <li>Showroom com produtos para demonstração</li>
    <li>Atendimento online e presencial</li>
    <li>Logística eficiente para entregas em todo Brasil</li>
</ul>

<h2>Diferenciais Laser Link</h2>

<ul>
    <li>✓ Atendimento personalizado e consultivo</li>
    <li>✓ Orçamentos rápidos e sem compromisso</li>
    <li>✓ Aprovação de arte antes da produção</li>
    <li>✓ Prazo de entrega cumprido</li>
    <li>✓ Produtos com garantia de qualidade</li>
    <li>✓ Ampla variedade de materiais e acabamentos</li>
    <li>✓ Preços competitivos</li>
    <li>✓ Entrega em todo território nacional</li>
</ul>

<h2>Nossos Clientes</h2>

<p>Atendemos desde pessoas físicas até grandes empresas, proporcionando soluções personalizadas para:</p>
<ul>
    <li>Empresas (todos os segmentos)</li>
    <li>Eventos corporativos</li>
    <li>Escolas e universidades</li>
    <li>Clubes e associações esportivas</li>
    <li>Organizadores de eventos</li>
    <li>Arquitetos e designers</li>
    <li>Pessoas físicas para presentes e projetos pessoais</li>
</ul>

<h2>Sustentabilidade</h2>

<p>Na Laser Link, nos preocupamos com o meio ambiente. Por isso:</p>
<ul>
    <li>Utilizamos materiais de fornecedores certificados</li>
    <li>Otimizamos o uso de matéria-prima para reduzir desperdícios</li>
    <li>Destinamos corretamente os resíduos</li>
    <li>Buscamos constantemente alternativas sustentáveis</li>
</ul>

<h2>Onde Estamos</h2>

<p>
    <strong>Laser Link</strong><br>
    CNPJ: 50.671.659/0001-48<br>
    <br>
    <strong>Atendimento Online:</strong><br>
    E-mail: contato@laserlink.com.br<br>
    WhatsApp: [Insira número]<br>
    <br>
    <strong>Horário de Atendimento:</strong><br>
    Segunda a Sexta: 8h às 18h<br>
    Sábado: 8h às 12h
</p>

<h2>Trabalhe Conosco</h2>

<p>Está em busca de novos desafios? Envie seu currículo para: <strong>rh@laserlink.com.br</strong></p>

<h2>Entre em Contato</h2>

<p>Tem um projeto em mente? Quer conhecer melhor nossos produtos e serviços? Estamos à disposição!</p>

<p>
    <a href="/contato" class="bg-red-600 text-white px-6 py-3 rounded-lg inline-block hover:bg-red-700 transition-colors">Entre em Contato</a>
</p>

<p><em>Laser Link - Transformando suas ideias em realidade!</em></p>
HTML;
    }

    private function getFaq(): string
    {
        return <<<'HTML'
<h1>Perguntas Frequentes (FAQ)</h1>

<p>Tire suas dúvidas sobre nossos produtos, serviços e processos. Se não encontrar a resposta que procura, <a href="/contato">entre em contato</a> conosco!</p>

<h2>Sobre Pedidos</h2>

<h3>Como faço um pedido?</h3>
<p>É simples! Navegue pelo site, escolha os produtos, personalize conforme necessário, adicione ao carrinho e finalize a compra. Você pode pagar com cartão de crédito, PIX ou outras formas disponíveis.</p>

<h3>Posso alterar meu pedido após finalizar a compra?</h3>
<p>Sim, entre em contato imediatamente através do e-mail ou WhatsApp. Se o pedido ainda não entrou em produção, podemos fazer alterações.</p>

<h3>Como acompanho meu pedido?</h3>
<p>Acesse "Minha Conta" no site e clique em "Meus Pedidos". Lá você verá o status atualizado e código de rastreamento quando enviado.</p>

<h3>Posso cancelar meu pedido?</h3>
<p>Sim, antes do início da produção. Entre em contato o quanto antes. Após iniciada a produção, produtos personalizados não podem ser cancelados.</p>

<h2>Sobre Produtos</h2>

<h3>Vocês trabalham com quais materiais?</h3>
<p>Trabalhamos com acrílico, MDF, madeira, metal, vidro, tecido, couro, entre outros. Cada produto especifica os materiais disponíveis.</p>

<h3>Posso personalizar qualquer produto?</h3>
<p>A maioria dos nossos produtos aceita personalização. Consulte a página do produto ou entre em contato para saber as opções disponíveis.</p>

<h3>Como envio a arte para personalização?</h3>
<p>Você pode fazer upload durante o pedido ou enviar por e-mail após a compra. Aceitamos formatos como JPG, PNG, PDF, AI, CDR, entre outros.</p>

<h3>Vocês criam a arte para mim?</h3>
<p>Sim! Oferecemos serviço de criação de arte por um custo adicional. Nossa equipe de design transformará sua ideia em realidade.</p>

<h3>Preciso aprovar a arte antes da produção?</h3>
<p>Sim! Para produtos personalizados, sempre enviamos um preview para sua aprovação antes de iniciar a produção.</p>

<h2>Sobre Pagamento</h2>

<h3>Quais formas de pagamento vocês aceitam?</h3>
<p>Aceitamos:</p>
<ul>
    <li>Cartão de crédito (parcelamento disponível)</li>
    <li>PIX (desconto de 5%)</li>
    <li>Boleto bancário</li>
    <li>Transferência bancária</li>
</ul>

<h3>O pagamento é seguro?</h3>
<p>Sim! Utilizamos gateways de pagamento certificados e criptografia SSL para proteger seus dados.</p>

<h3>Quando é cobrado o pagamento?</h3>
<p>No cartão de crédito, a cobrança é imediata. Para PIX e boleto, o pedido é processado após confirmação do pagamento.</p>

<h3>Emitem nota fiscal?</h3>
<p>Sim, todos os pedidos incluem Nota Fiscal Eletrônica (NF-e), enviada por e-mail e impressa junto ao produto.</p>

<h2>Sobre Entrega</h2>

<h3>Quanto tempo leva para receber meu pedido?</h3>
<p>Depende do tipo de produto:</p>
<ul>
    <li><strong>Produtos em estoque:</strong> 1 a 2 dias úteis para processar + prazo de entrega</li>
    <li><strong>Produtos personalizados:</strong> 3 a 10 dias úteis para produzir + prazo de entrega</li>
</ul>
<p>O prazo de entrega varia conforme região e modalidade escolhida (PAC, SEDEX, transportadora).</p>

<h3>Vocês entregam em todo Brasil?</h3>
<p>Sim! Enviamos para todo o território nacional pelos Correios e transportadoras parceiras.</p>

<h3>Quanto custa o frete?</h3>
<p>O frete é calculado automaticamente no carrinho com base no CEP, peso e dimensões do produto. Oferecemos frete grátis para pedidos acima de R$ 500 em algumas regiões.</p>

<h3>Posso retirar na loja?</h3>
<p>Sim! A retirada na loja é gratuita. Você será notificado quando o pedido estiver pronto.</p>

<h2>Sobre Troca e Devolução</h2>

<h3>Posso trocar ou devolver um produto?</h3>
<p>Sim, conforme nossa <a href="/politica-de-troca-e-devolucao">Política de Troca e Devolução</a>. Você tem 7 dias para arrependimento (produtos não personalizados) e 30 dias em caso de defeito.</p>

<h3>Produtos personalizados podem ser devolvidos?</h3>
<p>Apenas em caso de defeito de fabricação ou erro da Laser Link. Produtos personalizados não são elegíveis para devolução por arrependimento.</p>

<h3>Quem paga o frete da devolução?</h3>
<p>Se a devolução for por defeito ou erro nosso, nós arcamos com o frete. Em caso de arrependimento, o custo é do cliente.</p>

<h2>Sobre Orçamentos</h2>

<h3>Como solicito um orçamento?</h3>
<p>Você pode:</p>
<ul>
    <li>Usar o formulário de contato no site</li>
    <li>Enviar e-mail para orcamento@laserlink.com.br</li>
    <li>Entrar em contato via WhatsApp</li>
</ul>

<h3>Quanto tempo leva para receber o orçamento?</h3>
<p>Em até 1 dia útil você receberá nosso orçamento detalhado.</p>

<h3>O orçamento é grátis?</h3>
<p>Sim! Orçamentos são totalmente gratuitos e sem compromisso.</p>

<h3>Vocês atendem pessoa jurídica?</h3>
<p>Sim! Atendemos tanto pessoa física quanto jurídica. Para empresas, oferecemos condições especiais e faturamento.</p>

<h2>Sobre Atendimento</h2>

<h3>Qual o horário de atendimento?</h3>
<p>
    <strong>Online:</strong> Segunda a Sexta: 8h às 18h | Sábado: 8h às 12h<br>
    <strong>Presencial:</strong> Segunda a Sexta: 8h às 18h | Sábado: 8h às 12h
</p>

<h3>Como entro em contato?</h3>
<p>
    E-mail: contato@laserlink.com.br<br>
    WhatsApp: [Insira número]<br>
    Telefone: [Insira número]<br>
    Ou pelo <a href="/contato">formulário de contato</a>
</p>

<h2>Sobre Tecnologia</h2>

<h3>O que é corte a laser?</h3>
<p>É uma tecnologia que usa um feixe de laser de alta precisão para cortar materiais. Garante cortes limpos, precisos e detalhados em diversos tipos de materiais.</p>

<h3>O que é gravação a laser?</h3>
<p>É o processo de marcar/gravar permanentemente superfícies usando laser. Permite gravações detalhadas em madeira, acrílico, metal, couro e outros materiais.</p>

<h3>Quais as vantagens do laser?</h3>
<ul>
    <li>Precisão milimétrica</li>
    <li>Acabamento perfeito sem rebarbas</li>
    <li>Detalhes complexos</li>
    <li>Versatilidade de materiais</li>
    <li>Produção rápida</li>
</ul>

<h2>Outras Dúvidas</h2>

<h3>Preciso ter cadastro para comprar?</h3>
<p>Sim, é necessário criar uma conta para finalizar compras e acompanhar pedidos. O cadastro é rápido e gratuito.</p>

<h3>Meus dados estão seguros?</h3>
<p>Sim! Seguimos a LGPD (Lei Geral de Proteção de Dados) e usamos tecnologia de criptografia. Consulte nossa <a href="/politica-de-privacidade">Política de Privacidade</a>.</p>

<h3>Vocês trabalham com grandes quantidades?</h3>
<p>Sim! Atendemos desde pequenas até grandes quantidades. Para pedidos corporativos, solicite um orçamento personalizado.</p>

<h3>Fazem projetos sob medida?</h3>
<p>Sim! Nossa equipe desenvolve projetos customizados. Entre em contato com sua ideia e faremos um orçamento.</p>

<hr>

<h2>Não encontrou sua resposta?</h2>

<p>Nossa equipe está pronta para ajudar!</p>

<p>
    <a href="/contato" class="bg-red-600 text-white px-6 py-3 rounded-lg inline-block hover:bg-red-700 transition-colors">Fale Conosco</a>
</p>

<p><strong>Laser Link</strong> - CNPJ: 50.671.659/0001-48</p>
HTML;
    }
}
