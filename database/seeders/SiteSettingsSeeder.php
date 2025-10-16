<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SiteSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Configurações básicas do site
            [
                'key' => 'site_name',
                'value' => 'Laser Link',
                'type' => 'string',
                'description' => 'Nome do site'
            ],
            [
                'key' => 'site_description',
                'value' => 'Especialistas em comunicação visual e produtos personalizados',
                'type' => 'string',
                'description' => 'Descrição do site'
            ],
            [
                'key' => 'site_email',
                'value' => 'contato@laserlink.com.br',
                'type' => 'string',
                'description' => 'E-mail de contato'
            ],
            [
                'key' => 'site_phone',
                'value' => '(11) 99999-9999',
                'type' => 'string',
                'description' => 'Telefone de contato'
            ],
            [
                'key' => 'site_address',
                'value' => 'Rua das Comunicações, 123 - Centro - São Paulo/SP',
                'type' => 'string',
                'description' => 'Endereço da empresa'
            ],
            
            // Configurações de aparência
            [
                'key' => 'site_logo_path',
                'value' => null,
                'type' => 'string',
                'description' => 'Caminho do logo do site público'
            ],
            [
                'key' => 'primary_color',
                'value' => '#EE0000',
                'type' => 'string',
                'description' => 'Cor primária do site'
            ],
            [
                'key' => 'secondary_color',
                'value' => '#f8f9fa',
                'type' => 'string',
                'description' => 'Cor secundária do site'
            ],
            [
                'key' => 'accent_color',
                'value' => '#ffc107',
                'type' => 'string',
                'description' => 'Cor de destaque do site'
            ],
            
            // Configurações de WhatsApp
            [
                'key' => 'whatsapp_number',
                'value' => '5511999999999',
                'type' => 'string',
                'description' => 'Número do WhatsApp (com código do país)'
            ],
            [
                'key' => 'whatsapp_message',
                'value' => 'Olá! Gostaria de saber mais sobre seus produtos.',
                'type' => 'string',
                'description' => 'Mensagem padrão do WhatsApp'
            ],
            
            // Configurações de SEO
            [
                'key' => 'meta_title',
                'value' => 'Laser Link - Comunicação Visual e Produtos Personalizados',
                'type' => 'string',
                'description' => 'Título meta para SEO'
            ],
            [
                'key' => 'meta_description',
                'value' => 'Especialistas em acrílicos, troféus, medalhas, placas, letreiros e produtos personalizados. Qualidade e inovação em comunicação visual.',
                'type' => 'string',
                'description' => 'Descrição meta para SEO'
            ],
            [
                'key' => 'meta_keywords',
                'value' => 'comunicação visual, acrílicos, troféus, medalhas, placas, letreiros, personalizados, laser link',
                'type' => 'string',
                'description' => 'Palavras-chave para SEO'
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'type' => $setting['type'],
                    'description' => $setting['description']
                ]
            );
        }

        $this->command->info('Configurações do site criadas com sucesso!');
    }
}
