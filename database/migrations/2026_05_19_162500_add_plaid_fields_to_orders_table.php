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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('plaid_bank_name')->nullable()->after('payment_method');
            $table->string('plaid_account_mask')->nullable()->after('plaid_bank_name');
            $table->string('plaid_account_id')->nullable()->after('plaid_account_mask');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['plaid_bank_name', 'plaid_account_mask', 'plaid_account_id']);
        });
    }
};
