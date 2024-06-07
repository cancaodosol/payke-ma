<?php

namespace App\Http\Responces\PaykeApi;

class Order
{
    public function __construct($data)
    {
        $this->id = $data->data->id;
        $this->uuid = $data->data->uuid;
        $this->customer_name = $data->data->customer_name;
        $this->customer_email = $data->data->customer_email;
        $this->arg1 = $data->data->arg1;
        $this->arg2 = $data->data->arg2;
    }
}