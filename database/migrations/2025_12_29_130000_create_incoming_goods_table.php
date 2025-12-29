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
        Schema::create('incoming_goods', function (Blueprint $table) {
            $table->id();
            $table->string('product');
            $table->integer('incoming');
            $table->enum('category', [
                'Device',
                'Liquid',
                'Coil & Cartridge',
                'Battery & Charger',
                'Accessories',
                'Atomizer',
                'Tools & Spare Part'
            ]);
            $table->string('supplier');
            $table->date('date');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incoming_goods');
    }
};

