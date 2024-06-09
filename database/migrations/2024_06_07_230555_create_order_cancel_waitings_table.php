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
        Schema::create('order_cancel_waitings', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->nullable();
            $table->string('new_order_id');
            $table->bigInteger('user_id');
            $table->bigInteger('payke_user_id');
            $table->string('payke_user_uuid');
            $table->string('email_address');
            $table->string('user_name');
            $table->string('app_url');
            $table->boolean('is_active');
            $table->boolean('has_canceled');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_cancel_waitings');
    }
};
