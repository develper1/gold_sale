<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('stripe_bank_name')->nullable()->after('plaid_account_id');
            $table->string('stripe_account_mask')->nullable()->after('stripe_bank_name');
            $table->string('stripe_payment_method_id')->nullable()->after('stripe_account_mask');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['stripe_bank_name', 'stripe_account_mask', 'stripe_payment_method_id']);
        });
    }
};
