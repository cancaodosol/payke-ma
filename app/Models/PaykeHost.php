<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaykeHost extends Model
{
    use HasFactory;

    const STATUS__READY = 0;
    const STATUS__IN_USE = 1;
    const STATUS__DELETE = 2;

    public function PaykeDbs()
    {
        return $this->hasMany('App\Models\PaykeDb');
    }
}
