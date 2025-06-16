<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('price_tier_range', function (Blueprint $table) {
            $table->integer('tier_end')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('price_tier_range', function (Blueprint $table) {
            $table->integer('tier_end')->nullable(false)->change();
        });
    }
}; 