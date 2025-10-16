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
        Schema::table('materials', function (Blueprint $table) {
            $table->decimal('cutting_speed', 8, 1)->nullable()->after('weight_per_m2_by_thickness')->comment('Velocidade de corte em mm/min');
            $table->decimal('setup_time', 8, 1)->nullable()->after('cutting_speed')->comment('Tempo de setup em minutos');
            $table->decimal('machine_cost_per_hour', 8, 2)->nullable()->after('setup_time')->comment('Custo da máquina por hora em R$');
            
            // Remove o campo config_extras se existir
            $table->dropColumn('config_extras');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->dropColumn(['cutting_speed', 'setup_time', 'machine_cost_per_hour']);
            
            // Restaura o campo config_extras
            $table->json('config_extras')->nullable()->after('weight_per_m2_by_thickness');
        });
    }
};
