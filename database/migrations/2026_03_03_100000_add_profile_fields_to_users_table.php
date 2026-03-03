<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->timestamp('profile_completed_at')->nullable()->after('shipping_postcode');
            $table->timestamp('policies_agreed_at')->nullable()->after('profile_completed_at');
        });

        // Backfill: existing users with address data are considered profile-complete
        \DB::table('users')
            ->whereNotNull('shipping_address_1')
            ->whereNotNull('shipping_city')
            ->whereNull('profile_completed_at')
            ->update([
                'profile_completed_at' => now(),
                'policies_agreed_at' => now(),
            ]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'profile_completed_at', 'policies_agreed_at']);
        });
    }
};
