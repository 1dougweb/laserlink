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
        Schema::create('api_settings', function (Blueprint $table) {
            $table->id();
            $table->string('provider'); // 'gemini' ou 'chatgpt'
            $table->string('api_key')->nullable();
            $table->string('model')->nullable(); // modelo específico
            $table->boolean('is_active')->default(false);
            $table->json('config')->nullable(); // configurações adicionais
            $table->timestamps();
            
            $table->unique('provider');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_settings');
    }
};