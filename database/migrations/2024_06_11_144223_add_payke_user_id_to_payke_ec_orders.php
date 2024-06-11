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
        Schema::table('payke_ec_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('payke_user_id')->nullable()->after('order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payke_ec_orders', function (Blueprint $table) {
            $table->dropColumn('payke_user_id');
        });
    }
};
