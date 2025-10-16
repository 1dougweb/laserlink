<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Changelog;
use App\Models\User;

class ChangelogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@admin.com')->first() ?? User::first();
        
        $changelogs = [
            [
                'version' => '1.2.0',
                'title' => 'Sistema de Subcategorias e Melhorias na Loja',
                'description' => 'Implementação completa do sistema de subcategorias, filtros com checkboxes e padronização da página de contato.',
                'release_date' => now()->subDays(0),
                'is_published' => true,
                'user_id' => $admin?->id,
                'features' => [
                    'Sistema de categorias e subcategorias hierárquico',
                    'Filtros de produtos com checkboxes para múltipla seleção',
                    'Busca de produtos com AJAX e debounce',
                    'Redes sociais dinâmicas no rodapé e página de contato',
                    'Mapa do Google Maps configurável na página de contato',
                    'Sistema de FAQ (Perguntas Frequentes) com accordion',
                    'Gerenciador de cache no painel admin',
                ],
                'improvements' => [
                    'Filtros colapsáveis com animação suave',
                    'Página de contato totalmente padronizada com cores da marca',
                    'Cards de informação com ícones modernos',
                    'Campos de formulário com melhor usabilidade (padding aumentado)',
                    'Link "Ver Loja" na topbar do admin',
                    'Repeater fields com drag-and-drop usando SortableJS',
                ],
                'fixes' => [
                    'Correção do erro htmlspecialchars em filtros com array',
                    'Correção da lógica de filtro por categorias e subcategorias',
                    'Resolução de problemas com cache persistente',
                    'Correção da submissão do formulário de ordenação',
                ],
            ],
            [
                'version' => '1.1.0',
                'title' => 'Melhorias de Performance e UX',
                'description' => 'Otimizações gerais de performance e melhorias na experiência do usuário.',
                'release_date' => now()->subDays(30),
                'is_published' => true,
                'user_id' => $admin?->id,
                'features' => [
                    'Sistema de notificações em tempo real',
                    'Gerenciador de arquivos integrado',
                    'Upload de múltiplos banners para a home',
                    'Sistema de avaliações de produtos',
                ],
                'improvements' => [
                    'Otimização do carregamento de imagens',
                    'Melhoria na responsividade mobile',
                    'Interface do admin mais intuitiva',
                    'Performance de queries do banco de dados',
                ],
                'fixes' => [
                    'Correção de bug no carrinho de compras',
                    'Fix no cálculo de preços com campos extras',
                    'Correção de links quebrados',
                    'Ajuste no envio de emails',
                ],
            ],
            [
                'version' => '1.0.0',
                'title' => 'Lançamento Inicial',
                'description' => 'Primeira versão do sistema completo de e-commerce Laser Link.',
                'release_date' => now()->subDays(90),
                'is_published' => true,
                'user_id' => $admin?->id,
                'features' => [
                    'Sistema completo de e-commerce',
                    'Catálogo de produtos com categorias',
                    'Carrinho de compras',
                    'Sistema de checkout',
                    'Painel administrativo completo',
                    'Gerenciamento de pedidos',
                    'Sistema de usuários e permissões',
                    'Integração com WhatsApp',
                ],
                'improvements' => [],
                'fixes' => [],
            ],
        ];

        foreach ($changelogs as $changelogData) {
            Changelog::create($changelogData);
        }
        
        $this->command->info('✓ Changelogs criados com sucesso!');
        $this->command->info('  Total de versões: ' . count($changelogs));
    }
}

