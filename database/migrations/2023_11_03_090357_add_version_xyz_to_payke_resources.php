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
            $table->double('version_z', 4, 2)->after('version');
            $table->integer('version_y')->after('version');
            $table->integer('version_x')->after('version');
            $table->text('memo')->nullable()->after('payke_zip_file_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payke_resources', function (Blueprint $table) {
            $table->dropColumn('version_x');
            $table->dropColumn('version_y');
            $table->dropColumn('version_z');
            $table->dropColumn('memo');
        });
    }
};
