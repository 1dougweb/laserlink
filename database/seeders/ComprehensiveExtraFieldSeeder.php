<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExtraField;
use App\Models\ExtraFieldOption;
use Illuminate\Support\Str;

class ComprehensiveExtraFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Campo: Acabamento - Gravação a Laser
        $gravacaoLaser = ExtraField::create([
            'name' => 'Gravação a Laser',
            'slug' => 'gravacao-laser',
            'description' => 'Adicione gravação personalizada a laser no seu produto',
            'type' => 'radio',
            'is_required' => false,
            'is_active' => true,
            'sort_order' => 1,
            'settings' => [
                'show_price' => true,
                'layout' => 'vertical',
            ],
            'validation_rules' => [],
            'pricing_rules' => [
                'calculation_type' => 'fixed_or_percentage',
            ],
        ]);

        ExtraFieldOption::create([
            'extra_field_id' => $gravacaoLaser->id,
            'value' => 'sem-gravacao',
            'label' => 'Sem gravação',
            'description' => 'Produto sem gravação',
            'price' => 0.00,
            'price_type' => 'fixed',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        ExtraFieldOption::create([
            'extra_field_id' => $gravacaoLaser->id,
            'value' => 'gravacao-simples',
            'label' => 'Gravação Simples',
            'description' => 'Texto ou logo simples',
            'price' => 15.00,
            'price_type' => 'fixed',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        ExtraFieldOption::create([
            'extra_field_id' => $gravacaoLaser->id,
            'value' => 'gravacao-complexa',
            'label' => 'Gravação Complexa',
            'description' => 'Design detalhado ou múltiplos elementos',
            'price' => 35.00,
            'price_type' => 'fixed',
            'is_active' => true,
            'sort_order' => 3,
        ]);

        // 2. Campo: Cor do Acrílico
        $corAcrilico = ExtraField::create([
            'name' => 'Cor do Acrílico',
            'slug' => 'cor-acrilico',
            'description' => 'Escolha a cor do acrílico para seu produto',
            'type' => 'select',
            'is_required' => true,
            'is_active' => true,
            'sort_order' => 2,
            'settings' => [
                'show_price' => true,
                'placeholder' => 'Selecione a cor',
            ],
        ]);

        $cores = [
            ['value' => 'transparente', 'label' => 'Transparente (Cristal)', 'price' => 0.00],
            ['value' => 'branco-leitoso', 'label' => 'Branco Leitoso', 'price' => 5.00],
            ['value' => 'preto', 'label' => 'Preto', 'price' => 8.00],
            ['value' => 'vermelho', 'label' => 'Vermelho', 'price' => 10.00],
            ['value' => 'azul', 'label' => 'Azul', 'price' => 10.00],
            ['value' => 'verde', 'label' => 'Verde', 'price' => 10.00],
            ['value' => 'amarelo', 'label' => 'Amarelo', 'price' => 10.00],
            ['value' => 'espelhado', 'label' => 'Espelhado', 'price' => 25.00],
        ];

        foreach ($cores as $index => $cor) {
            ExtraFieldOption::create([
                'extra_field_id' => $corAcrilico->id,
                'value' => $cor['value'],
                'label' => $cor['label'],
                'price' => $cor['price'],
                'price_type' => 'percentage',
                'is_active' => true,
                'sort_order' => $index + 1,
            ]);
        }

        // 3. Campo: Tipo de Corte
        $tipoCorte = ExtraField::create([
            'name' => 'Tipo de Corte',
            'slug' => 'tipo-corte',
            'description' => 'Escolha o tipo de acabamento do corte',
            'type' => 'radio',
            'is_required' => false,
            'is_active' => true,
            'sort_order' => 3,
            'settings' => [
                'show_price' => true,
                'layout' => 'horizontal',
            ],
        ]);

        ExtraFieldOption::create([
            'extra_field_id' => $tipoCorte->id,
            'value' => 'corte-reto',
            'label' => 'Corte Reto',
            'description' => 'Corte padrão reto',
            'price' => 0.00,
            'price_type' => 'fixed',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        ExtraFieldOption::create([
            'extra_field_id' => $tipoCorte->id,
            'value' => 'corte-personalizado',
            'label' => 'Corte Personalizado',
            'description' => 'Formato customizado',
            'price' => 20.00,
            'price_type' => 'fixed',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        ExtraFieldOption::create([
            'extra_field_id' => $tipoCorte->id,
            'value' => 'corte-arredondado',
            'label' => 'Cantos Arredondados',
            'description' => 'Acabamento com cantos arredondados',
            'price' => 12.00,
            'price_type' => 'fixed',
            'is_active' => true,
            'sort_order' => 3,
        ]);

        // 4. Campo: Instalação
        $instalacao = ExtraField::create([
            'name' => 'Instalação',
            'slug' => 'instalacao',
            'description' => 'Serviço de instalação do produto',
            'type' => 'radio',
            'is_required' => false,
            'is_active' => true,
            'sort_order' => 4,
            'settings' => [
                'show_price' => true,
            ],
        ]);

        ExtraFieldOption::create([
            'extra_field_id' => $instalacao->id,
            'value' => 'sem-instalacao',
            'label' => 'Sem Instalação (Retirada)',
            'price' => 0.00,
            'price_type' => 'fixed',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        ExtraFieldOption::create([
            'extra_field_id' => $instalacao->id,
            'value' => 'instalacao-basica',
            'label' => 'Instalação Básica',
            'description' => 'Instalação em superfície plana',
            'price' => 80.00,
            'price_type' => 'fixed',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        ExtraFieldOption::create([
            'extra_field_id' => $instalacao->id,
            'value' => 'instalacao-complexa',
            'label' => 'Instalação Complexa',
            'description' => 'Instalação em altura ou local de difícil acesso',
            'price' => 150.00,
            'price_type' => 'fixed',
            'is_active' => true,
            'sort_order' => 3,
        ]);

        // 5. Campo: Iluminação (para letreiros e luminosos)
        $iluminacao = ExtraField::create([
            'name' => 'Iluminação LED',
            'slug' => 'iluminacao-led',
            'description' => 'Adicione iluminação LED ao seu produto',
            'type' => 'select',
            'is_required' => false,
            'is_active' => true,
            'sort_order' => 5,
            'settings' => [
                'show_price' => true,
                'placeholder' => 'Selecione o tipo de LED',
            ],
        ]);

        ExtraFieldOption::create([
            'extra_field_id' => $iluminacao->id,
            'value' => 'sem-led',
            'label' => 'Sem Iluminação',
            'price' => 0.00,
            'price_type' => 'fixed',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        ExtraFieldOption::create([
            'extra_field_id' => $iluminacao->id,
            'value' => 'led-branco',
            'label' => 'LED Branco',
            'price' => 50.00,
            'price_type' => 'per_area',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        ExtraFieldOption::create([
            'extra_field_id' => $iluminacao->id,
            'value' => 'led-colorido',
            'label' => 'LED Colorido (RGB)',
            'price' => 80.00,
            'price_type' => 'per_area',
            'is_active' => true,
            'sort_order' => 3,
        ]);

        // 6. Campo: Texto para Personalização
        $textoPersonalizado = ExtraField::create([
            'name' => 'Texto Personalizado',
            'slug' => 'texto-personalizado',
            'description' => 'Digite o texto que deseja gravar ou imprimir',
            'type' => 'textarea',
            'is_required' => false,
            'is_active' => true,
            'sort_order' => 6,
            'settings' => [
                'placeholder' => 'Digite seu texto aqui...',
                'rows' => 4,
                'max_length' => 500,
            ],
            'validation_rules' => [
                'max_length' => 500,
            ],
        ]);

        // 7. Campo: Tipo de Fonte
        $tipoFonte = ExtraField::create([
            'name' => 'Tipo de Fonte',
            'slug' => 'tipo-fonte',
            'description' => 'Escolha a fonte para o texto',
            'type' => 'select',
            'is_required' => false,
            'is_active' => true,
            'sort_order' => 7,
            'settings' => [
                'show_price' => false,
                'placeholder' => 'Selecione a fonte',
            ],
        ]);

        $fontes = [
            'arial' => 'Arial',
            'times-new-roman' => 'Times New Roman',
            'helvetica' => 'Helvetica',
            'impact' => 'Impact',
            'script' => 'Script Personalizada (+R$ 25)',
        ];

        $sortOrder = 1;
        foreach ($fontes as $value => $label) {
            ExtraFieldOption::create([
                'extra_field_id' => $tipoFonte->id,
                'value' => $value,
                'label' => $label,
                'price' => $value === 'script' ? 25.00 : 0.00,
                'price_type' => 'fixed',
                'is_active' => true,
                'sort_order' => $sortOrder++,
            ]);
        }

        // 8. Campo: Acabamento Superfície
        $acabamentoSuperficie = ExtraField::create([
            'name' => 'Acabamento da Superfície',
            'slug' => 'acabamento-superficie',
            'description' => 'Escolha o acabamento da superfície',
            'type' => 'radio',
            'is_required' => false,
            'is_active' => true,
            'sort_order' => 8,
        ]);

        ExtraFieldOption::create([
            'extra_field_id' => $acabamentoSuperficie->id,
            'value' => 'fosco',
            'label' => 'Fosco',
            'price' => 0.00,
            'price_type' => 'fixed',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        ExtraFieldOption::create([
            'extra_field_id' => $acabamentoSuperficie->id,
            'value' => 'brilhante',
            'label' => 'Brilhante',
            'price' => 5.00,
            'price_type' => 'percentage',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        ExtraFieldOption::create([
            'extra_field_id' => $acabamentoSuperficie->id,
            'value' => 'texturizado',
            'label' => 'Texturizado',
            'price' => 10.00,
            'price_type' => 'percentage',
            'is_active' => true,
            'sort_order' => 3,
        ]);

        // 9. Campo: Proteção UV
        $protecaoUV = ExtraField::create([
            'name' => 'Proteção UV',
            'slug' => 'protecao-uv',
            'description' => 'Adicione proteção contra raios UV para uso externo',
            'type' => 'checkbox',
            'is_required' => false,
            'is_active' => true,
            'sort_order' => 9,
        ]);

        ExtraFieldOption::create([
            'extra_field_id' => $protecaoUV->id,
            'value' => '1',
            'label' => 'Sim, adicionar proteção UV',
            'description' => 'Recomendado para produtos expostos ao sol',
            'price' => 15.00,
            'price_type' => 'percentage',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        // 10. Campo: Prazo de Entrega
        $prazoEntrega = ExtraField::create([
            'name' => 'Prazo de Entrega',
            'slug' => 'prazo-entrega',
            'description' => 'Escolha o prazo de produção e entrega',
            'type' => 'radio',
            'is_required' => true,
            'is_active' => true,
            'sort_order' => 10,
        ]);

        ExtraFieldOption::create([
            'extra_field_id' => $prazoEntrega->id,
            'value' => 'normal',
            'label' => 'Normal (5-7 dias úteis)',
            'price' => 0.00,
            'price_type' => 'fixed',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        ExtraFieldOption::create([
            'extra_field_id' => $prazoEntrega->id,
            'value' => 'rapido',
            'label' => 'Rápido (2-3 dias úteis)',
            'price' => 20.00,
            'price_type' => 'percentage',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        ExtraFieldOption::create([
            'extra_field_id' => $prazoEntrega->id,
            'value' => 'expresso',
            'label' => 'Expresso (24 horas)',
            'price' => 50.00,
            'price_type' => 'percentage',
            'is_active' => true,
            'sort_order' => 3,
        ]);

        $this->command->info('✅ Campos extras criados com sucesso!');
        $this->command->info('Total de campos: ' . ExtraField::count());
        $this->command->info('Total de opções: ' . ExtraFieldOption::count());
    }
}

