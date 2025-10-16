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
        Schema::table('settings', function (Blueprint $table) {
            // Adicionar configurações do WhatsApp se a tabela settings existir
            if (Schema::hasTable('settings')) {
                // Verificar se as colunas já existem antes de adicionar
                if (!Schema::hasColumn('settings', 'whatsapp_enabled')) {
                    $table->boolean('whatsapp_enabled')->default(true);
                }
                if (!Schema::hasColumn('settings', 'whatsapp_notifications_enabled')) {
                    $table->boolean('whatsapp_notifications_enabled')->default(true);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasTable('settings')) {
                if (Schema::hasColumn('settings', 'whatsapp_enabled')) {
                    $table->dropColumn('whatsapp_enabled');
                }
                if (Schema::hasColumn('settings', 'whatsapp_notifications_enabled')) {
                    $table->dropColumn('whatsapp_notifications_enabled');
                }
            }
        });
    }
};

