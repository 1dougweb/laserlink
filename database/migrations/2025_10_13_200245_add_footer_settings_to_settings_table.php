<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Adicionar novos campos de configuração do rodapé
        $settings = [
            ['key' => 'site_address', 'value' => '', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'footer_extra_text', 'value' => '', 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($settings as $setting) {
            \DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                $setting
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remover os campos adicionados
        \DB::table('settings')->whereIn('key', ['site_address', 'footer_extra_text'])->delete();
    }
};
