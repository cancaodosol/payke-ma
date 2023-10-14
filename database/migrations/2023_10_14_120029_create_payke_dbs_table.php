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
        Schema::create('payke_dbs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payke_host_id');
            $table->string('db_host');
            $table->string('db_username');
            $table->string('db_password');
            $table->string('db_database');
            $table->timestamps();

            // payke_hostsテーブルとの関連付け
            $table->foreign('payke_host_id')->references('id')->on('payke_hosts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payke_dbs');
    }
};
