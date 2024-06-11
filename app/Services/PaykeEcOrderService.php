<?php

namespace App\Services;

use App\Models\PaykeEcOrder;
use App\Models\PaykeUser;

class PaykeEcOrderService
{
    public function __construct()
    {
    }

    function save(PaykeEcOrder $order){
        $order->save();
        $user = PaykeUser::where('payke_order_id', $order->order_id)->first();
        if($user) $this->update_payke_user_id($order, $user);
    }

    function update_payke_user_id(PaykeEcOrder $order, PaykeUser $user){
        $orders = PaykeEcOrder::where([['payke_user_id', '=', null], ['order_id', '=', $order->order_id]])->get();
        foreach ($orders as $o) {
            $o->payke_user_id = $user->id;
            $o->save();
        }
    }
}