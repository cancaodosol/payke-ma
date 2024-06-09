<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\PaykeUser;

class OrderCancelWaiting extends Model
{
    protected $fillable = [
        'order_id',
        'new_order_id',
        'user_id',
        'payke_user_id',
        'payke_user_uuid',
        'email_address',
        'user_name',
        'app_url',
        'is_active',
        'has_canceled'
    ];

    public function set_order(PaykeUser $pUser, string $new_order_id): OrderCancelWaiting
    {
        $this->order_id = $pUser->payke_order_id;
        $this->new_order_id = $new_order_id;
        $this->user_id = $pUser->User->id;
        $this->payke_user_id = $pUser->id;
        $this->payke_user_uuid = $pUser->uuid;
        $this->email_address = $pUser->User->email;
        $this->user_name = $pUser->user_name;
        $this->app_url = $pUser->app_url;
        $this->is_active = true;
        $this->has_canceled = false;
        return $this;
    }
}
