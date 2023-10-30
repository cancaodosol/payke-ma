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
}
