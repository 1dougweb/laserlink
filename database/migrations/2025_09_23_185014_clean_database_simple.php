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
        // Limpar apenas as tabelas que não têm foreign keys
        Schema::dropIfExists('product_dynamic_fields');
        Schema::dropIfExists('dynamic_field_options');
        Schema::dropIfExists('dynamic_fields');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Não implementar rollback para simplificar
    }
};