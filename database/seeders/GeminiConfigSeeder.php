<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class GeminiConfigSeeder extends Seeder
{
    public function run(): void
    {
        // Configurações Gemini AI
        Setting::updateOrCreate(['key' => 'gemini_api_key'], [
            'value' => '',
            'type' => 'string',
            'description' => 'Chave da API Gemini'
        ]);
        
        Setting::updateOrCreate(['key' => 'gemini_model'], [
            'value' => 'gemini-2.5-flash',
            'type' => 'string',
            'description' => 'Modelo Gemini'
        ]);
        
        Setting::updateOrCreate(['key' => 'gemini_temperature'], [
            'value' => '0.7',
            'type' => 'string',
            'description' => 'Temperatura Gemini'
        ]);
        
        Setting::updateOrCreate(['key' => 'gemini_max_tokens'], [
            'value' => '1024',
            'type' => 'string',
            'description' => 'Máximo de tokens Gemini'
        ]);
        
        Setting::updateOrCreate(['key' => 'gemini_enabled'], [
            'value' => '0',
            'type' => 'boolean',
            'description' => 'Gemini AI habilitado'
        ]);
        
        $this->command->info('Configurações do Gemini AI criadas/atualizadas!');
    }
}

