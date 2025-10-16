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
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('rating_average', 3, 2)->default(0)->after('price')->comment('Média de avaliações (0-5)');
            $table->unsignedInteger('rating_count')->default(0)->after('rating_average')->comment('Número de avaliações');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['rating_average', 'rating_count']);
        });
    }
};
