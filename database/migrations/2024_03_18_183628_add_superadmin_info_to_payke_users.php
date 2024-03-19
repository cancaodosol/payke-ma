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
            $table->string('superadmin_password')->nullable()->after('email_address');
            $table->string('superadmin_username')->nullable()->after('email_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payke_users', function (Blueprint $table) {
            $table->dropColumn('superadmin_password');
            $table->dropColumn('superadmin_username');
        });
    }
};
