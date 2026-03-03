<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('shipping_country')->nullable()->after('allow_different_shipping');
            $table->string('shipping_address_1')->nullable()->after('shipping_country');
            $table->string('shipping_address_2')->nullable()->after('shipping_address_1');
            $table->string('shipping_city')->nullable()->after('shipping_address_2');
            $table->string('shipping_state')->nullable()->after('shipping_city');
            $table->string('shipping_postcode')->nullable()->after('shipping_state');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_country',
                'shipping_address_1',
                'shipping_address_2',
                'shipping_city',
                'shipping_state',
                'shipping_postcode',
            ]);
        });
    }
};

