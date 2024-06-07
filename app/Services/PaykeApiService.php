<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Http\Responces\PaykeApi\Order;

use App\Services\DeploySettingService;

class PaykeApiService
{
    public string $api_url;
    public string $api_token;

    public function __construct()
    {
        $ser = new DeploySettingService();
        $bases = $ser->find_base();
        foreach ($bases as $base) {
            if($base->key == "payke_api_url") $this->api_url = $base->value;
            if($base->key == "payke_api_token") $this->api_token = $base->value;
        }
    }

    function get_order(int $order_id){
        $responce = Http::withHeaders([
            "Accept" => "application/json",
            "Authorization" => "{$this->api_token}",
        ])->get("{$this->api_url}/api/orders/{$order_id}.json");

        $data = json_decode($responce->getBody()->getContents());
        
        return new Order($data);
    }

}