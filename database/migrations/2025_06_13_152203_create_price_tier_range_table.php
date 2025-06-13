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
        Schema::create('price_tier_range', function (Blueprint $table) {
            $table->id();
            $table->integer('tier_start')->nullable(); 
            $table->integer('tier_end')->nullable(); 
            $table->decimal('tier_price', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_tier_range');
    }
};
