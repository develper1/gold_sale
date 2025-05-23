<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('subscriber_details', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('city');
            $table->string('email');
            $table->json('investment_type');
            $table->string('investment_criteria');
            $table->enum('contact_preference', ['yes', 'no']);
            $table->string('mobile_number')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('subscriber_details');
    }
}; 