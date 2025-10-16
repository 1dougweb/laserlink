<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class FooterSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'site_address',
                'value' => 'Rua das Flores, 123 - Centro - São Paulo/SP - CEP: 01234-567',
                'description' => 'Endereço completo da empresa para exibição no rodapé'
            ],
            [
                'key' => 'footer_extra_text',
                'value' => 'Especialistas em comunicação visual com mais de 10 anos de experiência. Oferecemos soluções personalizadas em acrílicos, troféus, medalhas, placas e letreiros para sua empresa.',
                'description' => 'Texto adicional que aparece abaixo do copyright no rodapé'
            ]
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'description' => $setting['description'] ?? null
                ]
            );
        }
    }
}