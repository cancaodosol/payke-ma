<?php

namespace App\Http\Requests\Payke;

use DateTime;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\PaykeEcOrder;

class OrderRequest extends FormRequest
{
    public function raw(): string
    {
        $raw = [
            "x-auth-token" => $this->header("X-AUTH-TOKEN", "none"),
            "content" => json_decode($this->getContent())
        ];
        return json_encode($raw);
    }

    public function data(): array
    {
        return $this->get("data")["object"];
    }

    public function id(): string
    {
        return $this->get("id");
    }

    public function request_url(): string
    {
        return $this->httpHost();
    }

    public function type(): string
    {
        // # タイプ一覧
        // - order.placed
        // - payment.succeeded
        // - order.payment_stopped
        // - order.payment_restarted
        // - order.canceled
        return $this->get("type");
    }

    // [1]PaykeECの新規購入時
    public function is_type_placed(): bool
    {
        return $this->type() == "order.placed";
    }

    // [2]決済成功時
    public function is_type_payment_succeeded(): bool
    {
        return $this->type() == "payment.succeeded";
    }

    // [3]継続決済の支払停止時
    public function is_type_payment_stopped(): bool
    {
        return $this->type() == "order.payment_stopped";
    }

    // [4]継続決済の支払再開時
    public function is_type_payment_restarted(): bool
    {
        return $this->type() == "order.payment_restarted";
    }

    // [5]キャンセル時
    public function is_type_order_canceled(): bool
    {
        return $this->type() == "order.canceled";
    }

    public function order_id(): string
    {
        if($this->is_type_placed()) return $this->data()["id"];
        if($this->is_type_payment_succeeded()) return $this->data()["order_id"];
        if($this->is_type_payment_stopped()) return $this->data()["id"];
        if($this->is_type_payment_restarted()) return $this->data()["id"];
        if($this->is_type_order_canceled()) return $this->data()["id"];
        return "";
    }

    public function customer_email(): string
    {
        return $this->data()["customer_email"];
    }

    public function customer_full_name(): string
    {
        return $this->data()["customer_last_name"].$this->data()["customer_first_name"];
    }

    public function created_at()
    {
        $date = new DateTime();
        $date->setTimestamp($this->get("created_at"));
        return $date;
    }

    public function to_array(): array
    {
        return [
            "id" => $this->id(),
            "type" => $this->type(),
            "data" => $this->get("data"),
            "created_at" => $this->created_at(),
        ];
    }

    public function to_payke_ec_order(): PaykeEcOrder
    {
        $order = new PaykeEcOrder();
        $order->uuid = $this->id();
        $order->type = $this->type();
        $order->order_id = $this->order_id();
        $order->raw = $this->raw();
        $order->raw_created_at = $this->created_at();
        return $order;
    }
}
