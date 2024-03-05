<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaykeEcOrder extends Model
{
    use HasFactory;

    const TYPE_NAMES = [
        "order.placed" => "注文完了",
        "payment.succeeded" => "支払い完了"
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
