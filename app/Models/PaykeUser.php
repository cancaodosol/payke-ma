<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaykeUser extends Model
{
    use HasFactory;

    const STATUS__ACTIVE = 1;
    const STATUS__BEFORE_SETTING = -1;
    const STATUS__DISABLE_ADMIN = 2;
    const STATUS__DISABLE_ADMIN_AND_SALES = 3;
    const STATUS__DELETE = 4;
    const STATUS__HAS_ERROR = 9;

    public function PaykeHost()
    {
        return $this->belongsTo('App\Models\PaykeHost');
    }

    public function PaykeDb()
    {
        return $this->belongsTo('App\Models\PaykeDb');
    }

    public function PaykeResource()
    {
        return $this->belongsTo('App\Models\PaykeResource');
    }

    public function is_active() { return $this->status == PaykeUser::STATUS__ACTIVE; }
    public function is_before_setting() { return $this->status == PaykeUser::STATUS__BEFORE_SETTING; }
    public function is_disable_admin() { return $this->status == PaykeUser::STATUS__DISABLE_ADMIN; }
    public function is_disable_admin_and_sales() { return $this->status == PaykeUser::STATUS__DISABLE_ADMIN_AND_SALES; }
    public function is_delete() { return $this->status == PaykeUser::STATUS__DELETE; }
    public function has_error() { return $this->status == PaykeUser::STATUS__HAS_ERROR; }
}
