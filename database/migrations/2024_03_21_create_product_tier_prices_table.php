<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_tier_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('price_tier_range_id')->references('id')->on('price_tier_range')->onDelete('cascade');
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->boolean('use_tier_pricing')->default(false);
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_tier_prices');
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('use_tier_pricing');
        });
    }
}; 