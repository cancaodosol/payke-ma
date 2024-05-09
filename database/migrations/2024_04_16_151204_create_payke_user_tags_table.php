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
        Schema::create('payke_user_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color')->nullable();
            $table->integer('order_no')->nullable();
            $table->boolean('is_hidden');
            $table->timestamps();
        });

        Schema::table('payke_users', function (Blueprint $table) {
            $table->unsignedBigInteger('tag_id')->nullable()->after('user_id');

            // tagsテーブルとの関連付け
            $table->foreign('tag_id')->references('id')->on('payke_user_tags');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payke_users', function (Blueprint $table) {
            $table->dropForeign('payke_users_tag_id_foreign');
            $table->dropColumn('tag_id');
        });

        Schema::dropIfExists('payke_user_tags');
    }
};
