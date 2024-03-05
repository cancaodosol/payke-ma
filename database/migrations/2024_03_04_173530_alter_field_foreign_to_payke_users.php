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
        Schema::table('payke_users', function (Blueprint $table) {
            $table->unsignedBigInteger('payke_host_id')->nullable()->change();
            $table->unsignedBigInteger('payke_db_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payke_users', function (Blueprint $table) {
            $table->unsignedBigInteger('payke_host_id')->change();
            $table->unsignedBigInteger('payke_db_id')->change();
        });
    }
};
