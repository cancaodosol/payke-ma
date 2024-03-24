<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaykeEcOrder extends Model
{
    use HasFactory;

    const TYPE_NAMES = [
        "order.placed" => "注文完了",
        "payment.succeeded" => "支払い完了",
        "order.payment_stopped" => "未払い",
        "order.payment_restarted" => "支払い再開",
        "order.canceled" => "キャンセル"
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
