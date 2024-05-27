<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\DeploySetting;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $settings = DeploySetting::all();
        foreach ($settings as $setting) {
            $setting->no = 1;
            $setting->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
