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
        Schema::create('whatsapp_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('whatsapp_instance_id')->constrained()->onDelete('cascade');
            $table->string('recipient_phone'); // Telefone do destinatário
            $table->string('recipient_name')->nullable(); // Nome do destinatário
            $table->enum('notification_type', ['order_status', 'promotion', 'cart_abandonment', 'custom']);
            $table->string('related_type')->nullable(); // Tipo do modelo relacionado (Order, Product, etc)
            $table->unsignedBigInteger('related_id')->nullable(); // ID do modelo relacionado
            $table->text('message'); // Mensagem enviada
            $table->enum('status', ['pending', 'sent', 'failed', 'delivered', 'read'])->default('pending');
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->text('error_message')->nullable(); // Mensagem de erro em caso de falha
            $table->timestamps();

            $table->index(['related_type', 'related_id']);
            $table->index(['notification_type', 'status']);
            $table->index('recipient_phone');
            $table->index('sent_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_notifications');
    }
};

