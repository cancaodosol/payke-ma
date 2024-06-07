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
            $table->integer('deploy_setting_no')->nullable()->after('payke_resource_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payke_users', function (Blueprint $table) {
            $table->dropColumn('deploy_setting_no');
        });
    }
};
