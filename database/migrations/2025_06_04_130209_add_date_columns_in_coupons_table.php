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
        Schema::table('coupons', function (Blueprint $table) {
            $table->boolean('free_shipping')->nullable()->default(false);
            $table->boolean('free_service_fee')->nullable()->default(false);
            $table->date('valid_from')->nullable()->default(null);
            $table->date('valid_to')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn('free_shipping');
            $table->dropColumn('free_service_fee');
            $table->dropColumn('valid_from');
            $table->dropColumn('valid_to');
        });
    }
};
