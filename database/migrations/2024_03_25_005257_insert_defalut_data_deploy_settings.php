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
        // 初期データの投入
        Artisan::call('db:seed', ['--class' => 'DeploySettingSeeder']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
