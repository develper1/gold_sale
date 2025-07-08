<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('home_sliders', function (Blueprint $table) {
            $table->id();
            $table->string('image_path');
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('home_sliders');
    }
}; 