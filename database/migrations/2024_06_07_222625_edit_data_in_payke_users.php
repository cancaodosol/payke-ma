<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

use App\Models\PaykeUser;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payke_users', function (Blueprint $table) {
            $users = PaykeUser::all();
            foreach ($users as $user) {
                $user->uuid = (string)Str::uuid();
                $user->save();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payke_users', function (Blueprint $table) {
            //
        });
    }
};
