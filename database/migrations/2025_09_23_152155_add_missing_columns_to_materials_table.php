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
            // Adicionar colunas que estão faltando
            if (!Schema::hasColumn('materials', 'available_thicknesses')) {
                $table->json('available_thicknesses')->nullable()->after('density');
            }
            if (!Schema::hasColumn('materials', 'weight_per_m2_by_thickness')) {
                $table->json('weight_per_m2_by_thickness')->nullable()->after('available_thicknesses');
            }
            if (!Schema::hasColumn('materials', 'config_extras')) {
                $table->json('config_extras')->nullable()->after('weight_per_m2_by_thickness');
            }
            if (!Schema::hasColumn('materials', 'density_g_cm3')) {
                $table->decimal('density_g_cm3', 8, 3)->nullable()->after('density');
            }
            if (!Schema::hasColumn('materials', 'technical_specs')) {
                $table->text('technical_specs')->nullable()->after('config_extras');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->dropColumn([
                'available_thicknesses',
                'weight_per_m2_by_thickness', 
                'config_extras',
                'density_g_cm3',
                'technical_specs'
            ]);
        });
    }
};