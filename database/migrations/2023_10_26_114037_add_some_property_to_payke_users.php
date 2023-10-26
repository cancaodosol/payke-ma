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
            $table->text('memo')->after('user_app_name');
            $table->string('email_address')->after('user_app_name');
            $table->string('user_name')->after('user_app_name');
            $table->boolean('enable_affiliate')->after('user_app_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payke_users', function (Blueprint $table) {
            $table->dropColumn('memo');
            $table->dropColumn('email_address');
            $table->dropColumn('user_name');
            $table->dropColumn('enable_affiliate');
        });
    }
};
