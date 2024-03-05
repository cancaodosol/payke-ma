<?php

namespace App\Http\Requests\Payke;

use DateTime;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\PaykeEcOrder;

class OrderRequest extends FormRequest
{
    public function raw(): string
    {
        return $this->getContent();
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
        return $this->get("type");
    }

    public function is_type_placed(): string
    {
        return $this->type() == "order.placed";
    }

    public function order_id(): string
    {
        if($this->type() == 'order.placed') return $this->data()["id"];
        if($this->type() == 'payment.succeeded') return $this->data()["order_id"];
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
