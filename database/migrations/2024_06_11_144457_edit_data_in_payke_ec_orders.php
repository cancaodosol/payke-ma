<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\PaykeUser;
use App\Models\PaykeEcOrder;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $orders = PaykeEcOrder::all();
        foreach ($orders as $order) {
            $user = PaykeUser::where("payke_order_id", $order->order_id)->first();
            if($user){
                $order->payke_user_id = $user->id;
                $order->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
