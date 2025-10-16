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
        Schema::create('whatsapp_instances', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nome amigável da instância
            $table->string('instance_name')->unique(); // Nome da instância na Evolution API
            $table->enum('purpose', ['orders', 'promotions', 'support']); // Finalidade da instância
            $table->string('api_key'); // Chave da API Evolution
            $table->string('base_url'); // URL base da Evolution API
            $table->text('qr_code')->nullable(); // QR Code para conexão
            $table->enum('status', ['disconnected', 'connecting', 'connected'])->default('disconnected');
            $table->boolean('is_active')->default(true);
            $table->timestamp('connected_at')->nullable();
            $table->timestamp('disconnected_at')->nullable();
            $table->timestamps();

            $table->index(['purpose', 'is_active']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_instances');
    }
};

