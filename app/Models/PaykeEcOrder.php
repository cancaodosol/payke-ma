<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaykeEcOrder extends Model
{
    use HasFactory;

    const TYPE_NAMES = [
        "order.paid_or_registered" => "注文時",
        "payment.succeeded" => "支払い完了",
        "order.payment_stopped" => "未払い",
        "order.payment_restarted" => "支払い再開",
        "order.canceled" => "キャンセル",
        "order.placed" => "期間満了"
    ];

    protected $fillable = [
        'uuid'
        ,'order_id'
        ,'type'
        ,'raw'
        ,'raw_created_at'
    ];

    public function type_name(): string
    {
        return $this->type ? $this::TYPE_NAMES[$this->type] : '';
    }
}
