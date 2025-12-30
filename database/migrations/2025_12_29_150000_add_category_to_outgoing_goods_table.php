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
        Schema::table('outgoing_goods', function (Blueprint $table) {
            $table->enum('category', [
                'Device',
                'Liquid',
                'Coil & Cartridge',
                'Battery & Charger',
                'Accessories',
                'Atomizer',
                'Tools & Spare Part'
            ])->after('product');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('outgoing_goods', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};

