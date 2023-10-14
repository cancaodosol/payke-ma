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
        Schema::create('payke_users', function (Blueprint $table) {
            $table->id();
            $table->integer('status');
            $table->unsignedBigInteger('payke_host_id');
            $table->unsignedBigInteger('payke_db_id');
            $table->unsignedBigInteger('payke_resource_id');
            $table->string('user_folder_id');
            $table->string('user_app_name');
            $table->timestamps();

            // paykeの各テーブルとの関連付け
            $table->foreign('payke_host_id')->references('id')->on('payke_hosts');
            $table->foreign('payke_db_id')->references('id')->on('payke_dbs');
            $table->foreign('payke_resource_id')->references('id')->on('payke_resources');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payke_users');
    }
};
