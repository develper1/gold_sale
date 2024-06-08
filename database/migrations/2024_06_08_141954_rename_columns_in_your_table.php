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
        Schema::table('users', function (Blueprint $table) {
            
            // Step 1: Add new column
            $table->string('firstName')->nullable();
        });

        // Step 2: Copy data from old column to new column
        DB::statement('UPDATE users SET firstName = name');

        Schema::table('users', function (Blueprint $table) {
            // Step 3: Drop the old column
            $table->dropColumn('name');

            // Step 4: Make the new column non-nullable
            $table->string('firstName')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Step 1: Add the old column back
            $table->string('name')->nullable();
        });

        // Step 2: Copy data back to the old column
        DB::statement('UPDATE users SET name = firstName');

        Schema::table('users', function (Blueprint $table) {
            // Step 3: Drop the new column
            $table->dropColumn('firstName');

            // Step 4: Make the old column non-nullable
            $table->string('name')->nullable(false)->change();
        });

    }
};
