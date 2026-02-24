<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sub_categories', function (Blueprint $table) {
            $table->unsignedInteger('sort_order')->default(0)->after('category_id');
        });

        // Set existing rows to their id so relative order is preserved
        DB::statement('UPDATE sub_categories SET sort_order = id');
    }

    public function down(): void
    {
        Schema::table('sub_categories', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
};
