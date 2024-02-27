<?php

namespace App\Http\Requests\Payke;

use Illuminate\Foundation\Http\FormRequest;

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

    public function type(): string
    {
        // # タイプ一覧
        // - order.placed
        // - payment.succeeded
        return $this->get("type");
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

    public function created_at(): string
    {
        return $this->get("created_at");
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
}
