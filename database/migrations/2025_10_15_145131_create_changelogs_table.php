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
        Schema::create('changelogs', function (Blueprint $table) {
            $table->id();
            $table->string('version', 20);
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('features')->nullable(); // Novas funcionalidades
            $table->json('improvements')->nullable(); // Melhorias
            $table->json('fixes')->nullable(); // Correções de bugs
            $table->date('release_date');
            $table->boolean('is_published')->default(true);
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('changelogs');
    }
};
