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
        Schema::create('deploy_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('status');
            $table->unsignedBigInteger('user_id');
            $table->string('user_name');
            $table->string('user_app_name');
            $table->string('payke_version');
            $table->string('message');
            $table->text('deploy_params');
            $table->text('deployer_log');
            $table->timestamps();

            // payke_usersテーブルとの関連付け
            $table->foreign('user_id')->references('id')->on('payke_users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deploy_logs');
    }
};
