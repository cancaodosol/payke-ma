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
        Schema::table('deploy_logs', function (Blueprint $table) {
            $table->renameColumn('payke_version', 'title');
            $table->unsignedBigInteger('payke_resource_id')->after('user_app_name')->nullable();
            $table->renameColumn('status', 'type');

            // payke_resourcesテーブルとの関連付け
            $table->foreign('payke_resource_id')->references('id')->on('payke_resources');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deploy_logs', function (Blueprint $table) {
            $table->renameColumn('title', 'payke_version');
            $table->renameColumn('type', 'status');
            $table->dropForeign('deploy_logs_payke_resource_id_foreign');
            $table->dropColumn('payke_resource_id');
        });
    }
};