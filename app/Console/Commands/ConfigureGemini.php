<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Setting;

class ConfigureGemini extends Command
{
    protected $signature = 'gemini:configure';
    protected $description = 'Configure Gemini AI settings';

    public function handle()
    {
        $this->info('Configurando Gemini AI...');
        
        // Configurar Gemini AI
        Setting::updateOrCreate(['key' => 'gemini_enabled'], [
            'value' => '1',
            'type' => 'boolean',
            'description' => 'Gemini AI habilitado'
        ]);
        
        Setting::updateOrCreate(['key' => 'gemini_model'], [
            'value' => 'gemini-2.0-flash-exp',
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
        
        $this->info('✅ Gemini AI configurado!');
        $this->warn('⚠️  IMPORTANTE: Configure sua chave da API em /admin/configuracoes/gemini');
        $this->info('📝 Para obter uma chave da API:');
        $this->line('   1. Acesse: https://aistudio.google.com/app/apikey');
        $this->line('   2. Crie uma nova chave da API');
        $this->line('   3. Configure em: /admin/configuracoes/gemini');
    }
}

