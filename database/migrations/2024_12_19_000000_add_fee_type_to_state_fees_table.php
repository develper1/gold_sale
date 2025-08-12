<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('state_fees', function (Blueprint $table) {
            $table->enum('fee_type', ['flat', 'percentage'])->default('flat')->after('code');
            $table->decimal('amount', 10, 4)->change(); // Increase precision for percentages
        });
    }

    public function down()
    {
        Schema::table('state_fees', function (Blueprint $table) {
            $table->dropColumn('fee_type');
            $table->decimal('amount', 10, 2)->change();
        });
    }
};
