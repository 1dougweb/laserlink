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
        Schema::create('whatsapp_message_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nome do template
            $table->string('template_type'); // Tipo: order_pending, order_confirmed, etc
            $table->text('message_template'); // Template da mensagem com variáveis
            $table->json('variables')->nullable(); // Variáveis disponíveis no template
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique('template_type');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_message_templates');
    }
};

