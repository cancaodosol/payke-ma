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
        Schema::create('payke_ec_orders', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->string('order_id');
            $table->string('type');
            $table->json('raw');
            $table->dateTime('raw_created_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payke_ec_orders');
    }
};
