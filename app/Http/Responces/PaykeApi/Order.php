<?php

namespace App\Http\Responces\PaykeApi;

class Order
{
    public function __construct($data)
    {
        $this->id = $data->data->id;
        $this->uuid = $data->data->uuid;
        $this->status = $data->data->status;
        $this->customer_name = $data->data->customer_name;
        $this->customer_email = $data->data->customer_email;
        $this->arg1 = $data->data->arg1;
        $this->arg2 = $data->data->arg2;

        $this->product_id = $data->data->product_id;
        $this->product_name = $data->data->name;
    }

    public function is_status_cancel(){
        return $this->status == 2;
        // null: 保存済（Payke EC画面上では空白と表現）
        // 0: 未決済
        // 1: 決済完
        // 2: キャンセル
        // 3: 登録
        // 4: 支払中
        // 5: 予約
    }

    public function is_change_plan_request(){
        return $this->arg1 == "mode_cplan";
    }

    public function get_uuid(){
        return str_replace("puuid_", "", $this->arg2);
    }

    public function to_array_for_log()
    {
        return [
            "id: {$this->id}",
            "uuid: {$this->uuid}",
            "status: {$this->status}",
            "customer_name: {$this->customer_name}",
            "customer_email: {$this->customer_email}",
            "arg1: {$this->arg1}",
            "arg2: {$this->arg2}",
            "product_id: {$this->product_id}",
            "product_name: {$this->product_name}"
        ];
    }
}