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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('product_type'); // or separate categories
            $table->string('pricing_type'); // Requirement #2
            $table->decimal('fixed_price', 12, 2)->nullable(); // for fixed price items
            $table->decimal('blanket_markup_percentage', 5, 2)->default(3.00); // Requirement #1
            $table->boolean('use_override_markup')->default(false); // Requirement #1
            $table->decimal('override_markup_percentage', 5, 2)->nullable(); // Requirement #1
            $table->string('inventory_type'); // Requirement #3
            $table->integer('quantity_available')->nullable(); // for limited items
            $table->integer('low_inventory_threshold')->default(5); // Requirement #4
            $table->boolean('is_active')->default(true);
            $table->string('image_path');
            $table->softDeletes(); // Add this line
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
