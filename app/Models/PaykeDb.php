<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaykeDb extends Model
{
    use HasFactory;

    const STATUS__READY = 0;
    const STATUS__IN_USE = 1;
    const STATUS__DELETE = 2;

    public function PaykeUser()
    {
        return $this->hasOne('App\Models\PaykeUser');
    }
}
