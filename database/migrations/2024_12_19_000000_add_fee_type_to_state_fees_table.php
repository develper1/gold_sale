<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Add the fee_type column
        Schema::table('state_fees', function (Blueprint $table) {
            $table->enum('fee_type', ['flat', 'percentage'])->default('flat')->after('code');
        });

        // Update existing records to have 'flat' as fee_type
        DB::table('state_fees')->whereNull('fee_type')->orWhere('fee_type', '')->update(['fee_type' => 'flat']);
    }

    public function down()
    {
        Schema::table('state_fees', function (Blueprint $table) {
            $table->dropColumn('fee_type');
        });
    }
};
