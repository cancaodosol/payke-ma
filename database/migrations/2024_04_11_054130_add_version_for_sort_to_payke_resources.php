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
        Schema::table('payke_resources', function (Blueprint $table) {
            $table->double('version_for_sort')->after('version_z');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payke_resources', function (Blueprint $table) {
            $table->dropColumn('version_for_sort');
        });
    }
};
