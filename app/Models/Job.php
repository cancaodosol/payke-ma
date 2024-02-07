<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    public function is_waiting()
    {
        return $this->attempts == 0;
    }

    public function is_running()
    {
        // "reserved_at" => 1707308884
        // "attempts" => 1
        return $this->attempts == 1;
    }

    public function get_message()
    {
        $json = json_decode($this->payload, true);
        $deployParams = unserialize($json["data"]["command"]);
        if($this->is_waiting())
        {
            return  "「 {$deployParams->user->user_name} 」Paykeアップデート待機中（ → {$deployParams->payke->version} ）";
        }
        if($this->is_running())
        {
            return  "「 {$deployParams->user->user_name} 」Paykeアップデート実施中（ → {$deployParams->payke->version} ）";
        }
        return "";
    }

    public function to_array()
    {
        return [
            "is_running" => $this->is_running(),
            "is_waiting" => $this->is_waiting(),
            "message" => $this->get_message(),
            "available_at" => date('Y-m-d H:i:s',$this->available_at),
            "reserved_at" => $this->reserved_at
        ];
    }
}
