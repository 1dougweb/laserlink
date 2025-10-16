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
        Schema::table('users', function (Blueprint $table) {
            $table->string('cpf')->nullable()->after('phone');
            $table->string('address')->nullable()->after('cpf');
            $table->string('address_number')->nullable()->after('address');
            $table->string('address_complement')->nullable()->after('address_number');
            $table->string('neighborhood')->nullable()->after('address_complement');
            $table->string('city')->nullable()->after('neighborhood');
            $table->string('state', 2)->nullable()->after('city');
            $table->string('zip_code')->nullable()->after('state');
            $table->timestamp('last_login_at')->nullable()->after('zip_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'cpf',
                'address',
                'address_number',
                'address_complement',
                'neighborhood',
                'city',
                'state',
                'zip_code',
                'last_login_at'
            ]);
        });
    }
};
