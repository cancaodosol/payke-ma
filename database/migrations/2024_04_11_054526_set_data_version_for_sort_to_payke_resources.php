<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\PaykeResource;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payke_resources', function (Blueprint $table) {
            $resources = PaykeResource::all();
            foreach ($resources as $resource) {
                $version_for_sort = 1000000 * $resource->version_x + 1000 * $resource->version_y + $resource->version_z;
                $resource->version_for_sort = $version_for_sort;
                $resource->save();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
