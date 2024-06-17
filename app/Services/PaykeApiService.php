<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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

    function get_order(int $order_id)
    {
        $url = "{$this->api_url}/api/orders/{$order_id}.json";
        Log::info("[API] get_order: {$url}");
        $responce = Http::withHeaders([
            "Accept" => "application/json",
            "Authorization" => "{$this->api_token}",
        ])->get($url);
        $data = json_decode($responce->getBody()->getContents());
        
        return new Order($data);
    }

    function cancel_order(int $order_id, bool $retry = true)
    {
        try
        {
            $url = "{$this->api_url}/api/orders/updateStatus/{$order_id}.json";
            Log::info("[API] cancel_order: {$url}");
            $responce = Http::withHeaders([
                "Accept" => "application/json",
                "Content-Type" => "application/json",
                "Authorization" => "{$this->api_token}",
            ])->post($url,[
                "Order" => [
                    "status" => "canceled"
                ]
            ]);
            $data = json_decode($responce->getBody()->getContents());
            $is_success = $data->success == true;
            return $is_success;
        }
        catch (\Exception $e)
        {
            Log::info("[API] cancel_order: 失敗");
            sleep(1);
            Log::info("[API] cancel_order: リトライ");
            $order = $this->get_order($order_id);
            if($order->is_status_cancel()) return true;
            if($retry) return $this->cancel_order($order_id, false);
            throw $e;
        }
    }
    }
}